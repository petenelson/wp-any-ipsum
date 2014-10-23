<?php

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumOEmbed')) {

	class WPAnyIpsumOEmbed {

		public function plugins_loaded() {
			add_action('parse_request', array($this, 'sniff_requests'), 0);
		}


		function sniff_requests() {

			if (apply_filters( 'anyipsum-setting-is-enabled', false, 'anyipsum-settings-oembed', 'oembed-enabled' )) {

				global $wp;

				$pagename = '';
				if (!empty($wp->query_vars['name']))
					$pagename = $wp->query_vars['name'];

				if (!empty($wp->query_vars['pagename']))
					$pagename = $wp->query_vars['pagename'];

				if( strtolower($pagename) === strtolower(apply_filters( 'anyipsum-setting-get', 'api', 'anyipsum-settings-oembed', 'oembed-endpoint' )) )
					$this->handle_oembed_reques();
			}

		}


		function handle_oembed_reques() {

			$url = $_REQUEST['url'];

			if (!empty($url)) {

				$parsed_url = parse_url($url);
				if (is_array($parsed_url) && isset($parsed_url['query'])) {

					parse_str($parsed_url['query'], $parts);

					$number_of_sentences = 0;
					$number_of_paragraphs = 5;

					if (isset($parts["paras"]))
						$number_of_paragraphs = intval($parts["paras"]);

					if (isset($parts["sentences"]))
						$number_of_sentences = intval($parts["sentences"]);

					if ($number_of_paragraphs < 1)
						$number_of_paragraphs = 1;

					if ($number_of_paragraphs > 100)
						$number_of_paragraphs = 100;

					if ($number_of_sentences > 100)
						$number_of_sentences = 100;

					$start_with_lorem = !empty($parts["start-with-lorem"]) && $parts["start-with-lorem"] == "1";

					$type = !empty($parts["type"]) ? filter_var($parts["type"], FILTER_SANITIZE_STRING) : '';

					$paras = apply_filters( 'anyipsum-generate-filler', array(
						'type' => $type,
						'paras' => $number_of_paragraphs,
						'sentences' => $number_of_sentences,
						'start-with-lorem' => $start_with_lorem,
					));

					$html = '';

					for ($i=0; $i < count($paras); $i++)
						$html .= '<p>' . $paras[$i] . '</p>';


					$oembed = new stdClass();
					$oembed->type = 'rich';
					$oembed->html = $html;
					$oembed->provider_name = 'Bacon Ipsum';
					$oembed->version = '1.0';
					$oembed->provider_url = 'http://baconipsum.com';


					header('Content-type: application/json');
					echo json_encode($oembed);
					die();

				}

			}



		}

	}

}
