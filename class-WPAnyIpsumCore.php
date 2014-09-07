<?php

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumCore')) {

	class WPAnyIpsumCore {

		public function plugins_loaded() {
			add_filter( 'anyipsum-generate-filler', array($this, 'generate_filler'), 1);
		}

		function generate_filler($args) {

			$args = wp_parse_args( $args, array(
				'type' => 'custom-and-filler',
				'number-of-paragraphs' => 5,
				'start-with-lorem' => true,
				'number-of-sentences' => 0,
				));

			if (class_exists('WPAnyIpsumGenerator')) {

				$generator = new WPAnyIpsumGenerator();
				$generator->custom_words = $this->get_words('custom-words');
				$generator->filler = $this->get_words('filler-words');
				$generator->start_with = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-general', 'start-with' );

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
