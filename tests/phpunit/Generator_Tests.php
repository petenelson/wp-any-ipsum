<?php

class Generator_Tests extends WP_UnitTestCase {

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
}
