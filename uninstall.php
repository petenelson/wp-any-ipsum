<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die( 'restricted access' );

$keys = array(
	'anyipsum-settings-general',
	'anyipsum-settings-custom-filler',
	'anyipsum-settings-api',
	'anyipsum-settings-oembed',
);

// remove options
foreach ($keys as $key)
	delete_option( $key );
