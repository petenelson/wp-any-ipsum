<?php
/*
Class: Any Ipsum Generator
Author: Pete Nelson (@GunGeekATX)

Revision History

= September 6, 2014 =
* Initial fork of Bacon Ipsum

*/

class WPAnyIpsumGenerator {

	public $custom_words;
	public $filler;
	public $start_with;


	function __constructor() {
		$this->custom_words = array();
		$this->filler = array();
		$this->start_with = '';
	}


	function get_words($type) {

		if ($type == 'custom-and-filler')
			$words = array_merge(empty($this->custom_words) ? $this->default_filler() : $this->custom_words, (empty($this->filler) ? $this->default_filler() : $this->filler));
		else
			$words = empty($this->custom_words) ? $this->default_filler() : $this->custom_words;

		shuffle($words);

		return $words;

	}


	function make_a_sentence($type)	{
		// A sentence should be bewteen 4 and 15 words.
		$sentence = '';
		$length = rand(4, 15);

		// Add a little more randomness to commas, about 2/3rds of the time
		$includeComma = $length >= 7 && rand(0,2) > 0;

		$words = $this->get_words($type);

		if (count($words) > 0)
		{
			// Capitalize the first word.
			$words[0] =  ucfirst($words[0]);

			for ($i = 0; $i < $length; $i++) {

				if ($i > 0) {
					if ($i >= 3 && $i != $length - 1 && $includeComma) {

						if (rand(0,1) == 1) {
							$sentence = rtrim($sentence) . ', ';
							$includeComma = false;
						}
						else
							$sentence .= ' ';
					}
					else
						$sentence .= ' ';

				}

				$sentence .= $words[$i];
			}


			$sentence = rtrim($sentence) . '. ';
		}

		return $sentence;

	}

	function make_a_paragraph($type)	{
		// A paragraph should be bewteen 4 and 7 sentences.

		$para = '';
		$length = rand(4, 7);

		for ($i = 0; $i < $length; $i++)
			$para .= $this->make_a_sentence($type) . ' ';

		return rtrim($para);

	}


	public function make_some_custom_filler(
		$type = 'custom-and-filler',
		$number_of_paragraphs = 5,
		$start_with_lorem = true,
		$number_of_sentences = 0) {

		$paragraphs = array();
		if ($number_of_sentences > 0)
			$number_of_paragraphs = 1;

		$words = '';

		for ($i = 0; $i < $number_of_paragraphs; $i++) {

			if ($number_of_sentences > 0) {
				for ($s = 0; $s < $number_of_sentences; $s++)
					$words .= $this->make_a_sentence($type);
			}
			else
				$words = $this->Make_a_Paragraph($type);

			if ($i == 0 && $start_with_lorem && count($words) > 0) {
				$words[0] = strtolower($words[0]);
				$words = (empty($this->start_with) ? 'Lorem ipsum dolor amet' : $this->start_with) . ' ' . $words;
			}

			$paragraphs[]  = rtrim($words);

		}

		return $paragraphs;

	}


	public function default_custom () {

		return array(
			'beef',
			'chicken',
			'pork',
			'bacon',
			'chuck',
			'short loin',
			'sirloin',
			'shank',
			'flank',
			'sausage',
			'pork belly',
			'shoulder',
			'cow',
			'pig',
			'ground round',
			'hamburger',
			'meatball',
			'tenderloin',
			'strip steak',
			't-bone',
			'ribeye',
			'shankle',
			'tongue',
			'tail',
			'pork chop',
			'pastrami',
			'corned beef',
			'jerky',
			'ham',
			'fatback',
			'ham hock',
			'pancetta',
			'pork loin',
			'short ribs',
			'spare ribs',
			'beef ribs',
			'drumstick',
			'tri-tip',
			'ball tip',
			'venison',
			'turkey',
			'biltong',
			'rump',
			'jowl',
			'salami',
			'bresaola',
			'meatloaf',
			'brisket',
			'boudin',
			'andouille',
			'capicola',
			'swine',
			'kielbasa',
			'frankfurter',
			'prosciutto',
			'filet mignon',
			'leberkas',
			'turducken',
			'doner',
			'kevin',
			'landjaeger',
			'porchetta'
			);

	}


	function default_filler() {

		return array(
				'lorem',
				'ipsum',
				'consectetur',
				'adipisicing',
				'elit',
				'sed',
				'do',
				'eiusmod',
				'tempor',
				'incididunt',
				'ut',
				'labore',
				'et',
				'dolore',
				'magna',
				'aliqua',
				'ut',
				'enim',
				'ad',
				'minim',
				'veniam',
				'quis',
				'nostrud',
				'exercitation',
				'ullamco',
				'laboris',
				'nisi',
				'ut',
				'aliquip',
				'ex',
				'ea',
				'commodo',
				'consequat',
				'duis',
				'aute',
				'irure',
				'dolor',
				'in',
				'reprehenderit',
				'in',
				'voluptate',
				'velit',
				'esse',
				'cillum',
				'dolore',
				'eu',
				'fugiat',
				'nulla',
				'pariatur',
				'excepteur',
				'sint',
				'occaecat',
				'cupidatat',
				'non',
				'proident',
				'sunt',
				'in',
				'culpa',
				'qui',
				'officia',
				'deserunt',
				'mollit',
				'anim',
				'id',
				'est',
				'laborum'
			);

	}


}
