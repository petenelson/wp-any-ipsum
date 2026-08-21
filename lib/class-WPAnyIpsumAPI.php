<?php
/**
 * Class WPAnyIpsumAPI
 */

if ( !defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( !class_exists( 'WPAnyIpsumAPI' ) ) {

	class WPAnyIpsumAPI {

		public function plugins_loaded() {
			add_action( 'init', array( $this, 'register_rewrites' ) );
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		}

		public function register_rewrites() {
			$enabled = apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-api', 'api-enabled' );
			$endpoint = sanitize_key( apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-api', 'api-endpoint' ) );
			if ( $enabled && ! empty( $endpoint ) ) {
				add_rewrite_tag( '%any-ipsum-api-request%', '1' );
				add_rewrite_rule( $endpoint . '?', 'index.php?any-ipsum-api-request=1', 'top' );
			}
		}


		public function template_redirect() {
			global $wp_query;
			if ( $wp_query->get( 'any-ipsum-api-request' ) === '1' ) {
				$this->handle_api_request();
			}
		}


	    function handle_api_request() {

			header( 'Access-Control-Allow-Origin: *' );
			$type      = WPAnyIpsumCore::get_request( 'type' );
			$callback  = WPAnyIpsumCore::get_request( 'callback' );
			$format    = WPAnyIpsumCore::get_request( 'format' );

			// no need to keep going if there's nothing to do
			if ( empty( $type ) && empty( $callback ) ) {
				return;
			}

			if ( !in_array( $format, array( 'json', 'text', 'html' ) ) ) {
				$format = 'json';
			}

			// The callback is echoed back into a JavaScript response, so only allow
			// a valid JS function name (optionally namespaced) through.
			if ( ! empty( $callback ) && ! preg_match( '/^[a-zA-Z_$][a-zA-Z0-9_$]*(\.[a-zA-Z_$][a-zA-Z0-9_$]*)*$/', $callback ) ) {
				$callback = '';
			}

			$args = apply_filters( 'anyipsum-parse-request-args', $_SERVER['REQUEST_METHOD'] === 'POST' ? $_REQUEST : $_SERVER['QUERY_STRING'] );
			$paras = apply_filters( 'anyipsum-generate-filler', $args );

			// Allow filtering of the generated filler.
			$paras = apply_filters( 'anyipsum-generated-filler', $paras );

			$content_type = '';
			$output = '';

			if ( ! empty( $callback ) ) {
				$output_prefix = $callback . '(';
				$output_suffix = ');';
			} else {
				$output_prefix = '';
				$output_suffix = '';
			}

			switch ( $format ) {
				case 'html':
					$content_type = 'text/html';
					foreach ( $paras as $para ) {
						$output .= wpautop( wp_kses_post( $para ) );
					}
				break;

				case 'text':
					$content_type = 'text/plain';
					$output = implode( "\n\n", $paras );
				break;

				default: // JSON
					$content_type = 'application/json';
					$output = json_encode( $paras );
				break;
			}

			if ( ! empty( $callback ) ) {
				$content_type = 'application/javascript';
			}

			$output = $output_prefix . $output . $output_suffix;

			header( 'Content-Type: ' . $content_type . '; charset=' . get_bloginfo( 'charset' ) );
			header( 'Content-Length: ' . strlen( $output ) );
			// Keep browsers from sniffing a non-HTML response as HTML.
			header( 'X-Content-Type-Options: nosniff' );

			echo $output;

			// send notification for anything else that's hooked in
			$args['source'] = 'api';
			$args['format'] = $format;
			$args['output'] = $output;

			do_action( 'anyipsum-filler-generated', $args );

			exit;


	    } // end handle_api_request


	}

}
