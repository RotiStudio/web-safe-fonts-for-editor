<?php
/**
 * Tests of the saved settings: sanitizing and reading the font slugs.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

namespace RotiStudio\WebSafeFonts\Tests\Unit;

/**
 * The settings only ever hold known font slugs.
 */
final class SettingsTest extends TestCase {

	/**
	 * Submitted values and the expected sanitized slugs.
	 *
	 * @return array<string, array{0: mixed, 1: array<int, string>}>
	 */
	public function sanitize_provider() {
		return array(
			'known slugs are kept in order'  => array( array( 'verdana', 'arial' ), array( 'verdana', 'arial' ) ),
			'unknown slugs are dropped'      => array( array( 'arial', 'comic-sans-pro', '../arial' ), array( 'arial' ) ),
			'non-string values are dropped'  => array( array( 'arial', 1, null, array( 'verdana' ) ), array( 'arial' ) ),
			'keys are reindexed'             => array( array( 5 => 'arial', 'x' => 'verdana' ), array( 'arial', 'verdana' ) ),
			'empty array'                    => array( array(), array() ),
			'a string instead of an array'   => array( 'arial', array() ),
			'null when nothing was selected' => array( null, array() ),
		);
	}

	/**
	 * @dataProvider sanitize_provider
	 *
	 * @param mixed              $input    Submitted value.
	 * @param array<int, string> $expected Sanitized slugs.
	 */
	public function test_sanitize_font_slugs( $input, array $expected ) {
		$this->assertSame( $expected, rswsfg_sanitize_font_slugs( $input ) );
	}

	public function test_saved_slugs_ignore_a_corrupted_option() {
		$this->set_options( array( 'rswsfg_active_fonts' => 'arial' ) );

		$this->assertSame( array(), rswsfg_get_active_font_slugs() );
	}

	public function test_saved_slugs_skip_non_string_values() {
		$this->set_options( array( 'rswsfg_active_fonts' => array( 'arial', 7, 'verdana' ) ) );

		$this->assertSame( array( 'arial', 'verdana' ), rswsfg_get_active_font_slugs() );
	}

	public function test_preload_slugs_are_read_per_device() {
		$this->set_options(
			array(
				'rswsfg_preload_mobile_fonts'  => array( 'arial' ),
				'rswsfg_preload_desktop_fonts' => array( 'verdana' ),
			)
		);

		$this->assertSame( array( 'arial' ), rswsfg_get_preload_font_slugs( 'mobile' ) );
		$this->assertSame( array( 'verdana' ), rswsfg_get_preload_font_slugs( 'desktop' ) );
	}

	public function test_active_fonts_follow_definition_order_and_skip_unknown_slugs() {
		$this->set_options( array( 'rswsfg_active_fonts' => array( 'verdana', 'unknown', 'arial' ) ) );

		$this->assertSame( array( 'arial', 'verdana' ), array_keys( rswsfg_get_active_fonts() ) );
	}
}
