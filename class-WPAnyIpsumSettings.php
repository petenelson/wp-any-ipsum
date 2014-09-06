<?php

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumSettings')) {

	class WPAnyIpsumSettings {

		public function plugins_loaded() {
			add_action('init', array($this, 'init') );
		}

		function init() {
			// WordPress init hook

		}

	}

}
