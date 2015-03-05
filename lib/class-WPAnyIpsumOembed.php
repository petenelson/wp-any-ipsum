<?php

if ( ! defined( 'ABSPATH' ) ) wp_die( 'restricted access' );

if ( ! class_exists( 'WPAnyIpsumOEmbed' ) ) {

	class WPAnyIpsumOEmbed {

		public function plugins_loaded() {
		}

		public function register_rewrites() {
			$enabled = apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-oembed', 'oembed-enabled' );
			$endpoint = sanitize_key( apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-oembed', 'oembed-endpoint' ) );
			if ( $enabled && ! empty( $endpoint ) ) {
				add_rewrite_tag( '%any-ipsum-api-request%', '1' );
				//add_rewrite_rule( 'hello-world'. '?', 'index.php?any-ipsum-api-request=1', 'top' );
			}
		}

		public function template_redirect() {

				global $wp_query;
				$action = $wp_query->get( 'gga-image-api-action' );

				var_dump($action);

				die();


		}


		function sniff_requests() {

			if ( apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-oembed', 'oembed-enabled' ) ) {

				global $wp;

				$pagename = '';
				if ( !empty( $wp->query_vars['name'] ) )
					$pagename = $wp->query_vars['name'];

				if ( !empty( $wp->query_vars['pagename'] ) )
					$pagename = $wp->query_vars['pagename'];

				if ( strtolower( $pagename ) === strtolower( apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-oembed', 'oembed-endpoint' ) ) )
					$this->handle_oembed_request();
			}

		}


		private function handle_oembed_request() {

			$url = WPAnyIpsumCore::get_request( 'url' );

			if ( ! empty( $url ) ) {

				$html = $this->build_html( apply_filters( 'anyipsum-generate-filler', apply_filters( 'anyipsum-parse-request-args', $url ) ) );

				$oembed = new stdClass();
				$oembed->type = 'rich';
				$oembed->html = $html;
				$oembed->provider_name = 'Bacon Ipsum';
				$oembed->version = '1.0';
				$oembed->provider_url = 'http://baconipsum.com';

				wp_send_json( $oembed );

			}

		}

		private function build_html( $paras ) {
			$html = '';
			for ( $i=0; $i < count( $paras ); $i++ )
				$html .= '<p>' . $paras[$i] . '</p>';
			return $html;
		}

	}

}
