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
		 * : The number of paragraphs in each post, defaults to 5, can be a number of a
		 * range (ex: 2-7 for two to seven paragraphs)
		 *
		 * [--type=<all-custom>]
		 * : all-custom - uses just your custom words (default)
		 * filler-and-custom - uses your custom words and lorem ipsum filler
		 * Note: This value will vary based on how you have your Any Ipsum configured
		 *
		 * [--start-with-lorem]
		 * : Adds your 'Stars With' text at the beginning (Bacon ipsum dolor amet)
		 *
		 * [--excerpt]
		 * : Takes the first sentence from the post and makes it the post excerpt
		 *
		 * [--[no-]titles]
		 * : Flag to set using filler content to create post titles of varying
		 * lengths, no-titles will use generic 'Post #'
		 *
		 * [--post_type=<post>]
		 * : post type to create, defaults to 'post', but can also be a custom post type
		 *
		 * [--post_status=<publish>]
		 * : post status, defaults to 'publish'
		 *
		 * [--category=<category>]
		 * : category ID, slug, or name.  If a slug or name is passed
		 * and it does not exist, it will be created automatically.
		 *
		 * [--[no-]progress-bar]
		 * : Show a progress bar (default) or show each individual post as it's generated
		 *
		 * ## EXAMPLES
		 *
		 *     wp any-ipsum generate-posts 25
		 *
		 *     wp any-ipsum generate-posts --paras=10 --type=filler-and-custom --start-with-lorem --excerpt --category="Any Ipsum Sample"
		 *
		 * @subcommand generate-posts
		 *
		 * @synopsis [<posts>] [--paras=<paragraphs>] [--type=<filler-and-custom>] [--start-with-lorem] [--excerpt] [--[no-]titles] [--post_type=<post>] [--post_status=<publish>] [--category=<category>] [--[no-]progress-bar]
		 */
		public function generate_posts( $positional_args, $assoc_args ) {

			$generated   = 0;
			$start_time  = current_time( 'timestamp' );

			// default to 10 posts
			if ( empty( $positional_args ) ) {
				$positional_args = array( 10 );
			}
			list( $number_of_posts ) = $positional_args;

			// parse some of our command-line args
			$number_of_posts  = absint( $number_of_posts );
			if ( empty( $number_of_posts ) ) {
				$number_of_posts = 10;
			}

			// WP CLI Utils has get_flag_value() which we maybe can use if we can support namespaces
			$filler_titles        = \WP_CLI\Utils\get_flag_value( $assoc_args, 'titles', true );
			$show_progress_bar    = \WP_CLI\Utils\get_flag_value( $assoc_args, 'progress-bar', true );
			$excerpt              = ! empty( $assoc_args['excerpt'] );
			$post_status          = ! empty( $assoc_args['post_status'] ) ? $assoc_args['post_status'] : 'publish';
			$post_type            = ! empty( $assoc_args['post_type'] ) ? $assoc_args['post_type'] : 'post';

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

			// check for a category
			if ( ! empty( $assoc_args['category'] ) ) {

				$category = $assoc_args['category'];

				if ( absint( $category ) > 0 ) {
					$category_by_id = get_category( $category );
					if ( empty( $category_by_id ) ) {
						WP_CLI::error( sprintf( 'Invalid category ID %d', $category ) );
					} else {
						$category_id = absint( $category );
					}
				} else {

					// try to get the category
					foreach ( array( 'slug', 'name' ) as $field ) {
						$existing_category = get_term_by( $field, $category, 'category' );
						if ( ! empty( $existing_category ) ) {
							$category_id = $existing_category->term_id;
							break;
						}
					}

					// create the category if it doesn't exist
					if ( empty( $category_id ) ) {
						$category_id = wp_insert_category( array( 'cat_name' => $category ) );
						if ( empty( $category_id ) ) {
							WP_CLI::error( sprintf( 'Error creating category %s', $category ) );
						} else {
							WP_CLI::line( sprintf( 'Category "%s" created, ID %d', $category, $category_id ) );
						}
					}

				}

			}

			if ( $show_progress_bar ) {
				$progress_bar = \WP_CLI\Utils\make_progress_bar( 'Generating Posts:', $number_of_posts );
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

				// set the excerpt
				if ( ! empty( $excerpt ) && ! empty( $paras ) ) {
					$content_sentences = explode('.', $paras[0], 2 );
					if ( ! empty( $content_sentences ) ) {
						$post_args['post_excerpt'] = $content_sentences[0] . '.';
					}
				}

				// set the category
				if ( ! empty( $category_id ) ) {
					$post_args['post_category'] = array( $category_id );
				}

				// allow filtering of the args
				$post_args = apply_filters( 'anyipsum-filler-wp-cli-insert-post-args', $post_args );

				// create a post
				$post_id = wp_insert_post( $post_args );

				// output results to console
				if ( empty( $post_id ) ) {
					WP_CLI::warning( 'Unable to generate post' );
				} else {
					if ( $show_progress_bar ) {
						$progress_bar->tick();
					} else {
						WP_CLI::line( sprintf( 'Post ID %d generated: %s', $post_id, $post_args['post_title'] ) );
					}

					$generated++;

					// send notification for anything else that's hooked in

					do_action( 'anyipsum-filler-wp-cli-post-inserted', $post_id );

					$assoc_args['source'] = 'api';
					$assoc_args['output'] = $post_args['post_content'];

					do_action( 'anyipsum-filler-generated', $assoc_args );

				}

			}

			if ( $show_progress_bar ) {
				$progress_bar->finish();
			}

			$end_time = current_time( 'timestamp' );

			WP_CLI::success(
				sprintf( 'Done! %d posts generated in %d seconds',
					$generated,
					$end_time - $start_time
					)
				);

		}


	}

}

