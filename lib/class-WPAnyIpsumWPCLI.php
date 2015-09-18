<?php
/**
 * Interact with the Any Ipsum plugin
 */

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( defined('WP_CLI') && WP_CLI && ! class_exists( 'WPAnyIpsumWPCLI' ) ) {

	class WPAnyIpsumWPCLI extends WP_CLI_Command {


		/**
		 * Creates posts using Any Ipsum filler content (such as Bacon Ipsum).
		 *
		 * ## OPTIONS
		 *
		 * [<posts>]
		 * : The number of posts to generate, defaults to 10
		 *
		 * [--paras=<paragraphs>]
		 * : The number of paragraphs in each post, defaults to 5, can be a number of a range (ex: 2-7 for two to seven paragraphs)
		 *
		 * [--type=<all-custom>]
		 * : all-custom - uses just your custom words (default)
		 * filler-and-custom - uses your custom words and lorem ipsum filler
		 * Note: This value will vary based on how you have your Any Ipsum configured
		 *
		 * [--start-with-lorem]
		 * : Adds your 'Stars With' text at the beginning (Bacon ipsum dolor amet)
		 *
		 * [--[no-]titles]
		 * : Flag to set using filler content to create post titles of varying lengths, no-titles will use generic 'Post #'
		 *
		 * [--post_type=<post>]
		 * : post type to create, defaults to 'post', but can also be a custom post type
		 *
		 * [--post_status=<publish>]
		 * : post status, defaults to 'publish'
		 *
		 * ## EXAMPLES
		 *
		 *     wp any-ispum generate-posts 25
		 *
		 *     wp any-ispum generate-posts --paras=10 --type=filler-and-custom --start-with-lorem
		 *
		 * @subcommand generate-posts
		 *
		 * @synopsis [<posts>] [--paras=<paragraphs>] [--type=<filler-and-custom>] [--start-with-lorem] [--[no-]titles] [--post_type=<post>] [--post_status=<publish>]
		 */
		public function generate_posts( $positional_args, $assoc_args ) {

			$generated   = 0;
			$start_time  = current_time( 'timestamp' );

			list( $number_of_posts ) = $positional_args;

			// parse some of our command-line args
			$number_of_posts  = absint( $number_of_posts );
			if ( empty( $number_of_posts ) ) {
				$number_of_posts = 10;
			}

			// WP CLI Utils has get_flag_value() which we maybe can use if we can support namespaces
			$filler_titles    = \WP_CLI\Utils\get_flag_value( $assoc_args, 'titles', true );
			$post_status      = ! empty( $assoc_args['post_status'] ) ? $assoc_args['post_status'] : 'publish';
			$post_type        = ! empty( $assoc_args['post_type'] ) ? $assoc_args['post_type'] : 'post';

			if ( ! in_array( $post_type, get_post_types() ) ) {
				WP_CLI::error( sprintf( 'Invalid post_type %s', $post_type ) );
			}

			if ( ! array_key_exists( $post_status, get_post_statuses() ) ) {
				WP_CLI::error( sprintf( 'Invalid post_status %s', $post_status ) );
			}

			$assoc_args       = apply_filters( 'anyipsum-parse-request-args', $assoc_args );
			if ( $filler_titles ) {
				$sentence_args = $assoc_args;
				$sentence_args['start-with-lorem'] = false;
				$sentence_args['number-of-sentences'] = 1;
			}

			for ( $i = 0; $i < $number_of_posts; $i++ ) {

				// generate post content filler
				$paras  = apply_filters( 'anyipsum-generate-filler', $assoc_args );

				// create the post title
				if ( $filler_titles ) {
					$sentences = apply_filters( 'anyipsum-generate-filler', $sentence_args );
				} else {
					$sentences = array( sprintf( 'Post %d', ( $i + 1 ) ) );
				}

				// build arguments to create posts
				$post_args = array(
					'post_type'       => $post_type,
					'post_content'    => implode( "\n\n", $paras ),
					'post_status'     => $post_status,
					'post_title'      => $sentences[0],
					);

				// create a post
				$post_id = wp_insert_post( $post_args );

				// output results to console
				if ( empty( $post_id ) ) {
					WP_CLI::warning( 'Unable to generate post' );
				} else {
					WP_CLI::line( sprintf( 'Post ID %d generated: %s', $post_id, $post_args['post_title'] ) );
					$generated++;

					// send notification for anything else that's hooked in
					$assoc_args['source'] = 'api';
					$assoc_args['output'] = $post_args['post_content'];

					do_action( 'anyipsum-filler-generated', $assoc_args );

				}

			}

			$end_time = current_time( 'timestamp' );

			WP_CLI::success(
				sprintf( 'Done! %s posts generated in %s seconds',
					number_format( $generated ),
					number_format( $end_time - $start_time )
					)
				);

		}


	}

}

