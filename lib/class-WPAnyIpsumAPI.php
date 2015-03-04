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
			add_action( 'parse_request', array( $this, 'sniff_requests' ), 0 );
		}


		function sniff_requests() {

			if ( apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-api', 'api-enabled' ) ) {

				global $wp;

				$pagename = '';
				if ( !empty( $wp->query_vars['name'] ) )
					$pagename = $wp->query_vars['name'];

				if ( !empty( $wp->query_vars['pagename'] ) )
					$pagename = $wp->query_vars['pagename'];

				if ( strtolower( $pagename ) === strtolower( apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-api', 'api-endpoint' ) ) ) {
					$this->handle_api_request();
				}

			}

		}


		function handle_api_request() {

			$type = WPAnyIpsumCore::get_request( 'type' );

			if ( ! empty ( $type ) ) {

				header( 'Access-Control-Allow-Origin: *' );

				$args = apply_filters( 'anyipsum-parse-request-args', $_SERVER['QUERY_STRING'] );
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
