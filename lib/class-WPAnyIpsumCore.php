<?php
/*
Class: WPAnyIpsumCore
Author: Pete Nelson (@GunGeekATX)

Exposes filters used by other classes

*/

if ( ! defined( 'ABSPATH' ) ) wp_die( 'restricted access' );

if ( ! class_exists( 'WPAnyIpsumCore' ) ) {

	class WPAnyIpsumCore {

		public function plugins_loaded() {
			add_filter( 'anyipsum-generate-filler', array( $this, 'generate_filler' ) );
			add_filter( 'anyipsum-parse-request-args', array( $this, 'parse_request_args' ) );
		}


		static public function get_request( $key, $default = '', $filter = FILTER_SANITIZE_STRING ) {
			foreach (array( INPUT_GET, INPUT_POST ) as $input) {
				$value = filter_input( $input, $key, $filter );
				if ( ! empty( $value ) ) {
					return $value;
				}
			}
			return $default;
		}


		function parse_request_args( $args ) {

			$args['type'] = WPAnyIpsumCore::get_request( 'type' );

			$number_of_paragraphs = intval( WPAnyIpsumCore::get_request( 'paras', 5 ) );

			if ( $number_of_paragraphs < 1 )
				$number_of_paragraphs = 1;

			if ( $number_of_paragraphs > 100 )
				$number_of_paragraphs = 100;

			$args['number-of-paragraphs'] = $number_of_paragraphs;
			$args['start-with-lorem'] = WPAnyIpsumCore::get_request( 'start-with-lorem' ) === '1';


			$number_of_sentences = WPAnyIpsumCore::get_request( 'sentences' );
			if ( ! empty( $number_of_sentences ) ) {

				$number_of_sentences = intval( $number_of_sentences );

				if ( $number_of_sentences < 1 )
					$number_of_sentences = 1;

				if ( $number_of_sentences > 100 )
					$number_of_sentences = 100;

				$args['number-of-sentences'] = $number_of_sentences;

			}


			return $args;

		}


		function generate_filler( $args ) {

			$args = wp_parse_args( $args, array(
					'type' => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' ),
					'number-of-paragraphs' => 5,
					'start-with-lorem' => true,
					'number-of-sentences' => 0,
				)
			);

			if ( class_exists( 'WPAnyIpsumGenerator' ) ) {

				$generator = new WPAnyIpsumGenerator();
				$generator->custom_words    = $this->get_words( 'custom-words' );
				$generator->filler      = $this->get_words( 'filler-words' );
				$generator->start_with     = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' );
				$generator->type_all_custom   = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-all-custom' );
				$generator->type_custom_and_filler  = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' );
				$generator->sentence_mode    = apply_filters( 'anyipsum-setting-get', false, 'anyipsum-settings-custom-filler', 'sentence-mode' );

				return $generator->Make_Some_Custom_Filler(
					$args['type'],
					$args['number-of-paragraphs'],
					$args['start-with-lorem'],
					$args['number-of-sentences']
				);

			}
			else
				return array();

		}


		private function get_words( $type ) {
			$words = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-custom-filler', $type );
			if ( !empty( $words ) )
				$words = explode( "\n", $words );
			return $words;
		}


	}

}
