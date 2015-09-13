<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WPAnyIpsumOEmbed' ) ) {

	class WPAnyIpsumOEmbed {

		public function plugins_loaded() {
			add_action( 'init', array( $this, 'register_rewrites' ) );
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		}


		public function register_rewrites() {
			$enabled = apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-oembed', 'oembed-enabled' );
			$endpoint = sanitize_key( apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-oembed', 'oembed-endpoint' ) );
			if ( $enabled && ! empty( $endpoint ) ) {
				add_rewrite_tag( '%any-ipsum-oembed-request%', '1' );
				add_rewrite_rule( $endpoint . '?', 'index.php?any-ipsum-oembed-request=1', 'top' );
			}
		}


		public function template_redirect() {
			global $wp_query;
			if ( $wp_query->get( 'any-ipsum-oembed-request' ) === '1' ) {
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
