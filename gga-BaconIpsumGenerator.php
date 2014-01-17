<?php
/*
Class: Bacon Ipsum Generator
Author: Pete Nelson (@GunGeekATX)
Version: 2.1.5

Revision History

= v2.1.1 October 18, 2012 =
* Added doner (http://en.wikipedia.org/wiki/Doner_kebab)

= v2.1 July 21, 2012 =
* Changed the Make_Some_Meaty_Filler() function to return an array of paragraphs, added default parameters
* Added support for individual sentences
* Added more randomness to commas in a sentence.  Instead of always adding a
  comma to a sentence with seven or more words, it will add one about 2/3rds of the time.

= v2.0 July 14, 2012 =
* First public release

*/

class BaconIpsumGenerator {

	function GetWords($type) {


		$meat = array(
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

		$filler = array(
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
				'laborum');


		if ($type == 'meat-and-filler')
			$words = array_merge($meat, $filler);
		else
			$words = $meat;


		shuffle($words);

		return $words;

	}


	function Make_a_Sentence($type)	{
		// A sentence should be bewteen 4 and 15 words.
		$sentence = '';
		$length = rand(4, 15);

		// Add a little more randomness to commas, about 2/3rds of the time
		$includeComma = $length >= 7 && rand(0,2) > 0;

		$words = $this->GetWords($type);

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

	public function Make_a_Paragraph($type)	{
		// A paragraph should be bewteen 4 and 7 sentences.

		$para = '';
		$length = rand(4, 7);

		for ($i = 0; $i < $length; $i++)
			$para .= $this->Make_a_Sentence($type) . ' ';

		return rtrim($para);

	}

	public function Make_Some_Meaty_Filler(
		$type = 'meat-and-filler',
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
					$words .= $this->Make_a_Sentence($type);
			}
			else
				$words = $this->Make_a_Paragraph($type);

			if ($i == 0 && $start_with_lorem && count($words) > 0) {
				$words[0] = strtolower($words[0]);
				$words = 'Bacon ipsum dolor sit amet ' . $words;
			}

			$paragraphs[]  = rtrim($words);

		}

		return $paragraphs;

	}


}
