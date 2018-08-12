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

}
