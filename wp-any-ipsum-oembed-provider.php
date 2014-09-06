<?php
/*
Plugin Name: Bacon Ipsum - oEmbed provider
Description: Adds an oEmbed provider for Bacon Ipsum URLs
Plugin URI: https://github.com/petenelson/bacon-ipsum
Version: 2.1.4
Author: Pete Nelson (@GunGeekATX)
Author URI: http://petenelson.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action('init', 'gga_baconipsum_wp_oembed_add_provider');

function gga_baconipsum_wp_oembed_add_provider() {
	wp_oembed_add_provider( '#https?://(www\.)?baconipsum\.com/*#i', 'http://baconipsum.com/oembed', true );
}
