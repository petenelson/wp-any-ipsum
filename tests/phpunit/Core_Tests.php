<?php

class Core_Tests extends WP_UnitTestCase {

	public function test_default_generator_args() {

		$core = new WPAnyIpsumCore();

		$args = $core->default_generator_args();

		$this->assertTrue( is_array( $args ) );
		$this->assertNotEmpty( $args );
	}

}
