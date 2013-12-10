<?php
/*
Plugin Name: Bacon Ipsum - Generator Form
Description: Generates the input form for generating meaty bacon ipsum
Plugin URI: https://github.com/petenelson/bacon-ipsum
Version: 2.1.4
Author: Pete Nelson (@GunGeekATX)
Author URI: http://petenelson.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_shortcode('gga-bacon-ipsum-form', 'gga_bacon_ipsum_form');

function gga_bacon_ipsum_form($atts) {
	$output = '';

	$form = '
		<p>Does your lorem ipsum text long for something a little meatier? Give our generator a try… it’s tasty!</p>

		<form id="make-it-meaty" action="' . site_url('/') . '" method="get">
			<table>
				<tbody>
				<tr>
					<td>Paragraphs:</td>
					<td><input style="width: 40px;" type="text" name="paras" value="5" maxlength="2" /></td>
				</tr>
				<tr>
					<td>Type:</td>
					<td><input id="all-meat" type="radio" name="type" value="all-meat" checked="checked" /><label for="all-meat">All Meat</label> <input id="meat-and-filler" type="radio" name="type" value="meat-and-filler" /><label for="meat-and-filler">Meat and Filler</label></td>
				</tr>
				<tr>
					<td></td>
					<td><input id="start-with-lorem" type="checkbox" name="start-with-lorem" value="1" checked="checked" /> <label for="start-with-lorem">Start with \'Bacon ipsum dolor sit amet...\'</label></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Give me bacon" /></td>
				</tr>
				</tbody>
				</table>
		</form>
	';


	if (isset($_REQUEST["type"])) {

		require_once 'gga-BaconIpsumGenerator.php';

		$generator = new BaconIpsumGenerator();
		$number_of_paragraphs = 5;
		if (isset($_REQUEST["paras"]))
			$number_of_paragraphs = intval($_REQUEST["paras"]);

		$output = '';
					
		if ($number_of_paragraphs < 1)
			$number_of_paragraphs = 1;

		if ($number_of_paragraphs > 100)
			$number_of_paragraphs = 100;

		$paragraphs = $generator->Make_Some_Meaty_Filler(
			$_REQUEST["type"], 
			$number_of_paragraphs,
			isset($_REQUEST["start-with-lorem"]) && $_REQUEST["start-with-lorem"] == "1");


		$output = '<div>';
		foreach($paragraphs as $paragraph)
			$output .= '<p>' . $paragraph . '</p>';
		 
		$output .= '</div>';
	}



	return $output . $form;

}

