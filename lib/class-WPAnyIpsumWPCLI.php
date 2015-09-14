<?php
/**
 * Implements example command.
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
		 * : The number of paragraphs in each post, defaults to 5
		 *
		 * [--type=<all-custom>]
		 * : all-custom - uses just your custom words (default)
		 * filler-and-custom - uses your custom words and lorem ipsum filler
		 * Note: This value will vary based on how you have your Any Ipsum configured
		 *
		 * [--start-with-lorem]
		 * : Adds your 'Stars With' text at the beginning (Bacon ipsum dolor amet)
		 *
		 * [--no-titles]
		 * : Disables using filler content to create post titles of varying lengths
		 *
		 * [--post_type=<post>]
		 * : post type to create, defaults to 'post' but can also be a custom post type
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
		 * @synopsis [<posts>] [--paras=<paragraphs>] [--type=<filler-and-custom>] [--start-with-lorem] [--titles] [--post_type=<post>]
		 */
		public function generate_posts( $positional_args, $assoc_args ) {

			list( $number_of_posts ) = $positional_args;

			$number_of_posts  = absint( $number_of_posts );

			$assoc_args       = apply_filters( 'anyipsum-parse-request-args', $assoc_args );
			if ( ! empty( $assoc_args['no-titles'] ) ) {
				$sentence_args = $assoc_args;
				$sentence_args['start-with-lorem'] = false;
				$sentence_args['number-of-sentences'] = 1;
			}

			for ($i=0; $i < $number_of_posts ; $i++) {


				$paras  = apply_filters( 'anyipsum-generate-filler', $assoc_args );

				if ( ! empty( $assoc_args['no-titles'] ) ) {
					$sentences = apply_filters( 'anyipsum-generate-filler', $sentence_args );
				} else {
					$sentences = array( 'Post ' . ( $i + 1 ) );
				}


				$post_args = array(
					'post_type' => 'post',
					'post_content' => implode( "\n\n", $paras ),
					'post_status' => 'publish',
					'post_title' => $sentences[0],
					);

				$post_id = wp_insert_post( $post_args );

				WP_CLI::line( 'Post ID ' . $post_id . ' generated: ' . $post_args['post_title'] );

			}






			WP_CLI::success( 'done' );

		}


	}

}

