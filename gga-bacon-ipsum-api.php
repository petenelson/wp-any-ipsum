<?php
/*
Plugin Name: Bacon Ipsum - API
Description: Handles incoming API requests
Plugin URI: https://github.com/petenelson/bacon-ipsum
Version: 2.0
Author: Pete Nelson (@GunGeekATX)
Author URI: http://petenelson.com
*/

add_action('posts_selection', 'gga_bacon_ipsum_api');
function gga_bacon_ipsum_api() {

	if (is_page('api') && isset($_REQUEST['type'])) { 
		
		require_once 'gga-BaconIpsumGenerator.php';
		
		$paras = Array();
		
		$generator = new BaconIpsumGenerator();
		$paragraphs = 5;
		if (isset($_REQUEST["paras"]))
			$paragraphs = intval($_REQUEST["paras"]);

		$output = '';
					
		if ($paragraphs < 1)
			$paragraphs = 1;

		if ($paragraphs > 100)
			$paragraphs = 100;


		$start_with_lorem = isset($_REQUEST["start-with-lorem"]) && $_REQUEST["start-with-lorem"] == "1";
		$meat_and_filler = isset($_REQUEST["type"]) && $_REQUEST["type"] == "meat-and-filler";

		for ($i = 0; $i < $paragraphs; $i++) {
		
			$words = $generator->Make_a_Paragraph($_REQUEST["type"]);

			if ($i == 0 && $start_with_lorem && count($words) > 0) { 	
				$words[0] = strtolower($words[0]);
				$words = 'Bacon ipsum dolor sit amet ' . $words;
			}
					
			array_push($paras, $words);
			
		}

		
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