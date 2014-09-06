<?php
/*
Plugin Name: Any Ipsum
Description: Roll your own custom lorem ipsum generator
Plugin URI: https://github.com/petenelson/wp-any-ipsum
Version: 0.0.1
Author: Pete Nelson (@GunGeekATX)
Author URI: http://petenelson.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// include required files
$includes = array('Settings', 'Core');
foreach ($includes as $include)
	require_once 'class-WPAnyIpsum' . $include . '.php';

// load our classes, hooks them to WordPress
if (class_exists('WPAnyIpsumCore')) {
	$WPAnyIpsumCore = new WPAnyIpsumCore();
	add_action( 'plugins_loaded', array($WPAnyIpsumCore, 'plugins_loaded') );
}

if (class_exists('WPAnyIpsumSettings')) {
	$WPAnyIpsumSettings = new WPAnyIpsumSettings();
	add_action( 'plugins_loaded', array($WPAnyIpsumSettings, 'plugins_loaded') );
}
