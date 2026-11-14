<?php
/**
 * Base test case with the Brain Monkey lifecycle.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

namespace RotiStudio\WebSafeFonts\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Sets up and tears down Brain Monkey around every test, so WordPress
 * functions can be stubbed without loading WordPress.
 */
abstract class TestCase extends PHPUnitTestCase {

	/**
	 * Sets up Brain Monkey.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tears down Brain Monkey.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Makes get_option() return the given values, and the default for any other option.
	 *
	 * @param array<string, mixed> $options Option values keyed by name.
	 * @return void
	 */
	protected function set_options( array $options ) {
		Monkey\Functions\when( 'get_option' )->alias(
			static function ( $name, $fallback = false ) use ( $options ) {
				return array_key_exists( $name, $options ) ? $options[ $name ] : $fallback;
			}
		);
	}
}
