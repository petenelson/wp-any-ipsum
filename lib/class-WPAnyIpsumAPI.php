<?php
/*
Class: WPAnyIpsumAPI
Author: Pete Nelson (@GunGeekATX)

Exposes a JSON API endpoint

*/

if ( !defined( 'ABSPATH' ) ) wp_die( 'restricted access' );

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

			$type = WPAnyIpsumCore::get_request( 'type' );
			$callback = WPAnyIpsumCore::get_request( 'callback' );

			if ( ! empty ( $type ) || ! empty ( $callback ) ) {

				$args = apply_filters( 'anyipsum-parse-request-args', $_SERVER['REQUEST_METHOD'] === 'POST' ? $_REQUEST : $_SERVER['QUERY_STRING'] );
				$paras = apply_filters( 'anyipsum-generate-filler', $args );

				if ( ! empty( $args['callback'] ) ) {
					header( "Content-Type: application/javascript" );
					echo $args['callback'] . '(' . json_encode( $paras ) . ');';
				}
				else {
					header( "Content-Type: application/json; charset=utf-8" );
					echo json_encode( $paras );
				}

				exit;

			}


		}

	}

}
