<?php

class Generator_Tests extends WP_UnitTestCase {

				// $generator = new WPAnyIpsumGenerator();
				// $generator->custom_words         = $this->get_words( 'custom-words' );
				// $generator->filler               = $this->get_words( 'filler-words' );
				// $generator->start_with           = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' );
				// $generator->custom_and_filler    = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' );
				// $generator->sentence_mode        = apply_filters( 'anyipsum-setting-get', false, 'anyipsum-settings-custom-filler', 'sentence-mode' );

				// return $generator->Make_Some_Custom_Filler(
				// 	$args['type'],
				// 	$args['number-of-paragraphs'],
				// 	$args['start-with-lorem'],
				// 	$args['number-of-sentences'],
				// 	$args['max-number-of-paragraphs']
				// );


	public function test_default_custom_filler() {

		$generator = new WPAnyIpsumGenerator();

		$custom = $generator->default_custom();
		$filler = $generator->default_filler();

		$this->assertTrue( is_array( $custom ) );
		$this->assertNotEmpty( $custom );

		$this->assertTrue( is_array( $filler ) );
		$this->assertNotEmpty( $filler );

		$this->assertContains( 'bacon', $custom );
		$this->assertCount( 67, $custom );

		$this->assertContains( 'lorem', $filler );
		$this->assertContains( 'ipsum', $filler );
		$this->assertCount( 66, $filler );

		add_filter( 'wp_any_ipsum_default_custom_filename', '__return_empty_string' );
		add_filter( 'wp_any_ipsum_default_filler_filename', '__return_empty_string' );

		$custom = $generator->default_custom();
		$filler = $generator->default_filler();

		$this->assertTrue( is_array( $custom ) );
		$this->assertEmpty( $custom );

		$this->assertTrue( is_array( $filler ) );
		$this->assertEmpty( $filler );

		remove_filter( 'wp_any_ipsum_default_custom_filename', '__return_empty_array' );
		remove_filter( 'wp_any_ipsum_default_filler_filename', '__return_empty_array' );
	}

	public function test_get_words() {

		$generator = new WPAnyIpsumGenerator();
		$generator->custom_and_filler = 'meat-and-filler';

		$words = $generator->get_words( 'meat-and-filler' );

		$this->assertTrue( is_array( $words ) );
		$this->assertNotEmpty( $words );

		$this->assertContains( 'bacon', $words );
		$this->assertContains( 'ipsum', $words );

		$words = $generator->get_words( 'all-meat' );

		$this->assertTrue( is_array( $words ) );
		$this->assertNotEmpty( $words );

		$this->assertContains( 'bacon', $words );
		$this->assertNotContains( 'ipsum', $words );
	}

	public function test_make_a_sentence() {

		$generator = new WPAnyIpsumGenerator();
		$generator->custom_and_filler = 'meat-and-filler';
		$generator->custom_words = [
			'beef',
			'chicken',
			'pork',
			'bacon',
			'chuck',
			'sirloin',
			'shank',
			'flank',
			'sausage',
			'shoulder',
			'cow',
			'pig',
			't-bone',
			'ribeye',
			'shankle',
			'tongue',
			'tail',
			'pastrami',
			'jerky',
			'ham',
			'fatback',
			'pancetta',
		];

		$sentence = trim( $generator->make_a_sentence( 'meat-and-filler' ) );

		// First letter is capital.
		$this->assertRegExp( '/^[A-Z]{1}/', $sentence );

		// Ends with a period.
		$this->assertStringEndsWith( '.', $sentence );

		$sentences = 100;
		$has_meat = false;
		$has_filler = false;
		$has_comma = false;

		for ( $i = 0; $i < $sentences; $i++ ) {

			// A sentence should be bewteen 4 and 15 words.
			$length = $generator->get_sentence_length();
			$this->assertGreaterThanOrEqual( 4, $length );
			$this->assertLessThanOrEqual( 15, $length );

			$sentence = $generator->make_a_sentence( 'meat-and-filler' );

			$words = explode( ' ', $sentence );
			$this->assertGreaterThanOrEqual( 4, count( $words ) );
			$this->assertLessThanOrEqual( 15, count( $words ) );

			if ( false !== stripos( $sentence, 'bacon' ) ) {
				$has_meat = true;
			}

			if ( false !== stripos( $sentence, 'ipsum' ) ) {
				$has_filler = true;
			}

			if ( false !== stripos( $sentence, ',' ) ) {
				$has_comma = true;
			}
		}

		$this->assertTrue( $has_filler, 'filler' );
		$this->assertTrue( $has_meat, 'meat' );
		$this->assertTrue( $has_comma, 'comma' );
	}
}
