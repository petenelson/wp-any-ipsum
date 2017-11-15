<?php
/*
Class: WPAnyIpsumCore
Author: Pete Nelson (@GunGeekATX)

Exposes filters used by other classes

*/

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WPAnyIpsumCore' ) ) {

	class WPAnyIpsumCore {

		const PLUGIN_NAME       = 'any-ipsum';
		const PLUGIN_VERSION    = '1.9.1';

		public function plugins_loaded() {
			add_filter( 'anyipsum-generate-filler',       array( $this, 'generate_filler' ) );
			add_filter( 'anyipsum-parse-request-args',    array( $this, 'parse_request_args' ) );
			add_action( 'admin_init',                     array( $this, 'store_plugin_version' ) );
		}


		static public function get_request( $key, $default = '', $filter = FILTER_SANITIZE_STRING ) {
			foreach ( array( INPUT_GET, INPUT_POST ) as $input ) {
				$value = filter_input( $input, $key, $filter );
				if ( ! empty( $value ) ) {
					return $value;
				}
			}
			return $default;
		}


		/**
		 * Stores the plugin version in the options table if it doesn't exist (autoload no)
		 * @return void
		 */
		public function store_plugin_version() {
			// putting this here in case we need to use it for admin upgrade notices
			$option = get_option( WPAnyIpsumCore::PLUGIN_NAME . '-version' );
			if ( empty( $option ) ) {
				add_option( WPAnyIpsumCore::PLUGIN_NAME . '-version', WPAnyIpsumCore::PLUGIN_VERSION, '', 'no' );
			}
		}


		private function default_generator_args() {
			return array(
				'type'                => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' ),
				'start-with-lorem'    => false,
				'sentences'           => '',
				'paras'               => 5,
				'max-paras'           => 5,
				'callback'            => '',
			);
		}


		public function parse_request_args( $args ) {

			// if this is a URL, grab the query string parameters
			if ( ! empty( $args ) && is_string( $args ) && strpos( $args, '?' ) ) {
				$args = substr( $args, strpos( $args, '?' ) + 1 );
			}

			$args = wp_parse_args( $args, $this->default_generator_args() );

			if ( false !== strpos( $args['paras'], '-') ) {
				$parts = explode( '-', $args['paras'] );
				$args['paras'] = absint( $parts[0] );
				$args['max-paras'] = absint( $parts[1] );
			} else {
				$args['paras'] = absint( $args['paras'] );
				$args['max-paras'] = $args['paras'];
			}

			$args['start-with-lorem'] = ! empty( $args['start-with-lorem'] ) && ( $args['start-with-lorem'] === '1' || $args['start-with-lorem'] === 1 || $args['start-with-lorem'] );

			$args = $this->sanitize_args( $args );

			if ( empty( $args['number-of-paragraphs'] ) ) {
				$args['number-of-paragraphs'] = $args['paras'];
				$args['max-number-of-paragraphs'] = $args['paras'];
			}

			$args['max-number-of-paragraphs'] = $args['max-paras'];

			if ( empty( $args['number-of-sentences'] ) ) {
				$args['number-of-sentences'] = $args['sentences'];
			}

			return $args;

		}


		private function sanitize_args( $args ) {

			if ( $args['paras'] < 1 ) {
				$args['paras'] = 1;
			}

			if ( $args['paras'] > 100 ) {
				$args['paras'] = 100;
			}

			if ( $args['max-paras'] < 1 ) {
				$args['max-paras'] = 1;
			}

			if ( $args['max-paras'] > 100 ) {
				$args['max-paras'] = 100;
			}

			if ( ! empty( $args['sentences'] ) ) {

				$args['sentences'] = intval( $args['sentences'] );

				if ( $args['sentences'] < 1 ) {
					$args['sentences'] = 1;
				}

				if ( $args['sentences'] > 100 ) {
					$args['sentences'] = 100;
				}

			}

			return $args;
		}


		public function generate_filler( $args ) {

			$args = wp_parse_args( $args, $this->default_generator_args() );

			if ( class_exists( 'WPAnyIpsumGenerator' ) ) {

				$generator = new WPAnyIpsumGenerator();
				$generator->custom_words         = $this->get_words( 'custom-words' );
				$generator->filler               = $this->get_words( 'filler-words' );
				$generator->start_with           = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' );
				$generator->custom_and_filler    = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' );
				$generator->sentence_mode        = apply_filters( 'anyipsum-setting-get', false, 'anyipsum-settings-custom-filler', 'sentence-mode' );

				return $generator->Make_Some_Custom_Filler(
					$args['type'],
					$args['number-of-paragraphs'],
					$args['start-with-lorem'],
					$args['number-of-sentences'],
					$args['max-number-of-paragraphs']
				);

			}
			else
				return array();

		}


		private function get_words( $type ) {
			$words = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-custom-filler', $type );
			if ( !empty( $words ) ) {
				$words = explode( "\n", $words );
			}
			return $words;
		}


	}

}
