<?php
/*
Class: WPAnyIpsumGenerator
Author: Pete Nelson (@GunGeekATX)
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

	/**
	 * Returns a random list of filler words, or custom and filler words,
	 * depending on type.
	 *
	 * @param  string $type The type to return.
	 * @return array
	 */
	public function get_words( $type ) {

		$custom_words = empty( $this->custom_words ) ? $this->default_custom() : $this->custom_words;
		$filler_words = empty( $this->filler ) ? $this->default_filler() : $this->filler;

		if ( $type == $this->custom_and_filler ) {
			$words = array_merge( $custom_words, $filler_words );
		} else {
			$words = $custom_words;
		}

		shuffle( $words );

		return apply_filters( 'wp_any_ipsum_get_words', $words, $type );
	}

	public function make_a_sentence( $type ) {

		if ( $this->sentence_mode ) {
			// grab the first random sentence
			$words = $this->get_words( $type );
			if ( is_array( $words ) && count( $words ) > 0 )
				return trim( $words[0] );
			else
				return '';
		}


		$words = $this->get_words( $type );

		// A sentence should be bewteen 4 and 15 words.
		$sentence = '';
		$length = rand( 4, 15 );

		if ( is_array( $words ) && $length > count( $words ) ) {
			$length = count($words);
		}

		// Add a little more randomness to commas, about 2/3rds of the time
		$includeComma = $length >= 7 && rand( 0, 2 ) > 0;

		if ( is_array( $words ) && count( $words ) > 0 ) {
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

	public function make_a_paragraph( $type ) {
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
			// Generate a random amount of paragraphs.
			$number_of_paragraphs = rand( $number_of_paragraphs, $max_paragraphs );
		}

		$words = '';

		for ( $i = 0; $i < $number_of_paragraphs; $i++ ) {

			if ( $number_of_sentences > 0 ) {
				for ( $s = 0; $s < $number_of_sentences; $s++ )
					$words .= $this->make_a_sentence( $type );
			}
			else {
				$words = $this->make_a_paragraph( $type );
			}

			if ( $i == 0 && $start_with_lorem && strlen( $words ) > 0 ) {
				$words = strtolower( substr( $words , 0, 1 ) ) . substr( $words, 1 );
				$words = ( empty( $this->start_with ) ? 'Lorem ipsum dolor amet' : $this->start_with ) . ' ' . $words;
			}

			$paragraphs[]  = rtrim( $words );

		}

		return $paragraphs;
	}

	/**
	 * Returns the default list of custom words.
	 *
	 * @return array
	 */
	public function default_custom() {
		$filename = apply_filters( 'wp_any_ipsum_default_custom_filename', WP_ANY_IPSUM_ROOT . 'lib/default-custom.txt' );
		if ( file_exists( $filename ) ) {
			return explode( PHP_EOL, file_get_contents( $filename ) );
		} else {
			return array();
		}
	}

	/**
	 * Returns the default list of filler words.
	 *
	 * @return array
	 */
	public function default_filler() {
		$filename = apply_filters( 'wp_any_ipsum_default_filler_filename', WP_ANY_IPSUM_ROOT . 'lib/default-filler.txt' );
		if ( file_exists( $filename ) ) {
			return explode( PHP_EOL, file_get_contents( $filename ) );
		} else {
			return array();
		}
	}
}
