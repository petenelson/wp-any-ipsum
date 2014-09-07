<?php
// TODO
if (false) {
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action('init', 'gga_baconipsum_wp_oembed_add_provider');

function gga_baconipsum_wp_oembed_add_provider() {
	wp_oembed_add_provider( '#https?://(www\.)?baconipsum\.com/*#i', 'http://baconipsum.com/oembed', true );
}
}
