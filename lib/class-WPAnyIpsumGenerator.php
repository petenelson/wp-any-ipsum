<?php
/*
Class: WPAnyIpsumGenerator
Author: Pete Nelson (@GunGeekATX)

Revision History

= March 27, 2015 =
* Trim word before adding to sentence

= October 22, 2014 =
* Added sentence_mode

= September 7, 2014 =
* Made parameters more generic and not meat-specific

= September 6, 2014 =
* Initial fork of Bacon Ipsum

*/

class WPAnyIpsumGenerator {

	public $custom_words;
	public $filler;
	public $start_with;
	public $custom_and_filler;
	public $sentence_mode;

	public function __constructor() {
		$this->custom_words = array();
		$this->filler = array();
		$this->start_with = '';
		$this->sentence_mode = false;
	}


	private function get_words( $type ) {

		if ( $type == $this->custom_and_filler ) {
			$words = array_merge( empty( $this->custom_words ) ? $this->default_filler() : $this->custom_words, ( empty( $this->filler ) ? $this->default_filler() : $this->filler ) );
		} else {
			$words = empty( $this->custom_words ) ? $this->default_filler() : $this->custom_words;
		}

		shuffle( $words );

		return $words;

	}


	private function make_a_sentence( $type ) {

		if ( $this->sentence_mode ) {
			// grab the first random sentence
			$words = $this->get_words( $type );
			if ( count( $words ) > 0 )
				return trim( $words[0] );
			else
				return '';
		}


		// A sentence should be bewteen 4 and 15 words.
		$sentence = '';
		$length = rand( 4, 15 );

		// Add a little more randomness to commas, about 2/3rds of the time
		$includeComma = $length >= 7 && rand( 0, 2 ) > 0;

		$words = $this->get_words( $type );

		if ( count( $words ) > 0 ) {
			// Capitalize the first word.
			$words[0] =  trim( ucfirst( $words[0] ) );

			for ( $i = 0; $i < $length; $i++ ) {

				if ( $i > 0 ) {
					if ( $i >= 3 && $i != $length - 1 && $includeComma ) {

						if ( rand( 0, 1 ) == 1 ) {
							$sentence = rtrim( $sentence ) . ', ';
							$includeComma = false;
						}
						else
							$sentence .= ' ';
					}
					else
						$sentence .= ' ';

				}

				$sentence .= trim( $words[$i] );
			}

			$sentence = rtrim( $sentence ) . '. ';

		}

		return $sentence;

	}


	private function make_a_paragraph( $type ) {
		// A paragraph should be bewteen 4 and 7 sentences.

		$para = '';
		$length = rand( 4, 7 );

		for ( $i = 0; $i < $length; $i++ ) {
			$para .= $this->make_a_sentence( $type ) . ' ';
		}

		return rtrim( $para );

	}


	public function make_some_custom_filler(
			$type = '',
			$number_of_paragraphs = 5,
			$start_with_lorem = true,
			$number_of_sentences = 0,
			$max_paragraphs = 5
		) {

		if ( empty( $type ) ) {
			$type = $this->custom_and_filler;
		}

		$paragraphs = array();
		if ( $number_of_sentences > 0 ) {
			$number_of_paragraphs = 1;
		}

		if ( $number_of_paragraphs > $max_paragraphs || $number_of_sentences > 0 ) {
			$max_paragraphs = $number_of_paragraphs;
		}

		if ( $max_paragraphs !== $number_of_paragraphs ) {
			// generate a random amount of paragraphs
			$number_of_paragraphs = rand( $number_of_paragraphs, $max_paragraphs );
		}

		$words = '';

		for ( $i = 0; $i < $number_of_paragraphs; $i++ ) {

			if ( $number_of_sentences > 0 ) {
				for ( $s = 0; $s < $number_of_sentences; $s++ )
					$words .= $this->make_a_sentence( $type );
			}
			else {
				$words = $this->Make_a_Paragraph( $type );
			}

			if ( $i == 0 && $start_with_lorem && count( $words ) > 0 ) {
				$words[0] = trim( strtolower( $words[0] ) );
				$words = ( empty( $this->start_with ) ? 'Lorem ipsum dolor amet' : $this->start_with ) . ' ' . $words;
			}

			$paragraphs[]  = rtrim( $words );

		}

		return $paragraphs;

	}


	public function default_custom() {
		return explode( "\n", file_get_contents( plugin_dir_url( __FILE__ ) . 'default-custom.txt' ) );
	}


	public function default_filler() {
		return explode( "\n", file_get_contents( plugin_dir_url( __FILE__ ) .  'default-filler.txt' ) );
	}


}
