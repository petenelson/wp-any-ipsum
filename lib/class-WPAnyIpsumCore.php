<?php
/*
Class: WPAnyIpsumCore
Author: Pete Nelson (@GunGeekATX)

Exposes filters used by other classes

*/

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumCore')) {

	class WPAnyIpsumCore {

		public function plugins_loaded() {
			add_filter( 'anyipsum-generate-filler', array($this, 'generate_filler') );
			add_filter( 'anyipsum-parse-request-args', array($this, 'parse_request_args') );
		}


		function parse_request_args($args) {

			$args['type'] = filter_var($_REQUEST["type"], FILTER_SANITIZE_STRING);

			$number_of_paragraphs = 5;
			if (isset($_REQUEST["paras"]))
				$number_of_paragraphs = intval($_REQUEST["paras"]);

			if ($number_of_paragraphs < 1)
				$number_of_paragraphs = 1;

			if ($number_of_paragraphs > 100)
				$number_of_paragraphs = 100;

			$args['number-of-paragraphs'] = $number_of_paragraphs;
			$args['start-with-lorem'] = !empty($_REQUEST["start-with-lorem"]) && '1' === $_REQUEST["start-with-lorem"];

			return $args;

		}


		function generate_filler($args) {

			$args = wp_parse_args( $args, array(
					'type' => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' ),
					'number-of-paragraphs' => 5,
					'start-with-lorem' => true,
					'number-of-sentences' => 0,
				)
			);

			if (class_exists('WPAnyIpsumGenerator')) {

				$generator = new WPAnyIpsumGenerator();
				$generator->custom_words = $this->get_words('custom-words');
				$generator->filler = $this->get_words('filler-words');
				$generator->start_with = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' );
				$generator->type_all_custom = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-all-custom' );
				$generator->type_custom_and_filler = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' );

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


		function get_words($type) {
			$words = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-custom-filler', $type );
			if (!empty($words))
				$words = explode("\n", $words);
			return $words;
		}


	}

}
