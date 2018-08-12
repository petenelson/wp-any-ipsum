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

		return apply_filters( 'wp_any_ipsum_get_words', array_values( $words ), $type );
	}

	/**
	 * Returns a sentence based on randomized custom words and filler, or
	 * a single line from the custom words if the generator is in sentence
	 * mode.
	 *
	 * @param  string $type The type, either custom or custom and filler,
	 *                      based on matching the custom_and_filler setting.
	 * @return string
	 */
	public function make_a_sentence( $type ) {

		if ( $this->sentence_mode ) {
			// Grab the first random sentence.
			$words = $this->get_words( $type );
			if ( is_array( $words ) && count( $words ) > 0 )
				return trim( $words[0] );
			else
				return '';
		}

		$words = $this->get_words( $type );

		$sentence = '';
		$length = $this->get_sentence_length();

		if ( is_array( $words ) && $length > count( $words ) ) {
			$length = count( $words );
		}

		$words = array_values( array_slice( $words, 0, $length ) );

		if ( is_array( $words ) && count( $words ) > 0 ) {

			// Add a little more randomness to commas, about 2/3rds of the time
			$includeComma = count( $words ) >= 7 && rand( 0, 2 ) > 0;

			// Capitalize the first word.
			$words[0] =  trim( ucfirst( $words[0] ) );

			// Add a comma, maybe.
			for ( $i = 0; $i < count( $words ); $i++ ) {
				if ( $i > 0 ) {
					if ( $i >= 3 && $i != count( $words ) - 1 && $includeComma ) {
						if ( rand( 0, 1 ) == 1 ) {
							$words[ $i ] = $words[ $i ] . ',';
							$includeComma = false;
						}
					}
				}
			}

			$words = array_map( 'trim', $words );

			// Create a sentence.
			$sentence = implode( ' ', $words ) . '.';
		}

		return $sentence;
	}

	/**
	 * Returns a random number for the length of a sentence in words.
	 *
	 * @return int
	 */
	public function get_sentence_length() {
		// A sentence should be between 4 and 15 words.
		return apply_filters( 'wp_any_ipsum_random_sentence_length', rand( 4, 15 ) );
	}

	/**
	 * Returns a random number for the length of a paragraph in sentences.
	 *
	 * @return int
	 */
	public function get_paragraph_length() {
		// A paragraph should be bewteen 4 and 7 sentences.
		return apply_filters( 'wp_any_ipsum_random_paragraph_length', rand( 4, 7 ) );
	}

	public function make_a_paragraph( $type ) {

		$para = '';
		$length = $this->get_paragraph_length();

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
		if ( is_string( $filename ) && file_exists( $filename ) ) {
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
		if ( is_string( $filename ) && file_exists( $filename ) ) {
			return explode( PHP_EOL, file_get_contents( $filename ) );
		} else {
			return array();
		}
	}
}
