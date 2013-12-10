<?php
/*
Plugin Name: Bacon Ipsum - API
Description: Handles incoming API requests
Plugin URI: https://github.com/petenelson/bacon-ipsum
Version: 2.1.4
Author: Pete Nelson (@GunGeekATX)
Author URI: http://petenelson.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class GGA_Bacon_Ipsum_API {
	
	function __construct() {
		add_action('posts_selection', array(&$this, 'api'));
	}
	
	function api() {

		if (is_page('api') && isset($_REQUEST['type'])) { 
		
			require_once 'gga-BaconIpsumGenerator.php';
			
			$generator = new BaconIpsumGenerator();
			$number_of_sentences = 0;
			$number_of_paragraphs = 5;
	
			if (isset($_REQUEST["paras"]))
				$number_of_paragraphs = intval($_REQUEST["paras"]);
	
			if (isset($_REQUEST["sentences"]))
				$number_of_sentences = intval($_REQUEST["sentences"]);
	
			$output = '';
						
			if ($number_of_paragraphs < 1)
				$number_of_paragraphs = 1;
	
			if ($number_of_paragraphs > 100)
				$number_of_paragraphs = 100;
	
			if ($number_of_sentences > 100)
				$number_of_sentences = 100;
	
			$start_with_lorem = isset($_REQUEST["start-with-lorem"]) && $_REQUEST["start-with-lorem"] == "1";
	
			$paras = $generator->Make_Some_Meaty_Filler(
				filter_var($_REQUEST["type"], FILTER_SANITIZE_STRING), 
				$number_of_paragraphs, 
				$start_with_lorem, 
				$number_of_sentences);
	
			header('Access-Control-Allow-Origin: *');
	
			if (isset($_REQUEST["callback"])) {
				header("Content-Type: application/javascript");
				echo $_GET['callback'] . '(' . json_encode($paras) . ')';
			}
			else {
				header("Content-Type: application/json; charset=utf-8");
				echo json_encode($paras);
			}		
	
			exit;
	
		}
	
	}

}


new GGA_Bacon_Ipsum_API();
