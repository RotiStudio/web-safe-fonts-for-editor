<?php
/**
 * Tests of the @font-face rules and the preload links.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

namespace RotiStudio\WebSafeFonts\Tests\Unit;

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;

/**
 * The plugin prints correct @font-face rules and preloads one file per font.
 */
final class FontLoadingTest extends TestCase {

	/**
	 * Stubs the WordPress formatting functions the output uses.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		Functions\stubEscapeFunctions();
		Functions\when( 'esc_url_raw' )->returnArg();
		Functions\when( 'sanitize_key' )->alias(
			static function ( $key ) {
				return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( $key ) );
			}
		);
	}

	/**
	 * Values of the rswsfg_font_display filter and the expected font-display.
	 *
	 * @return array<string, array{0: mixed, 1: string}>
	 */
	public function font_display_provider() {
		return array(
			'allowed value'         => array( 'optional', 'optional' ),
			'upper case'            => array( 'FALLBACK', 'fallback' ),
			'unknown value'         => array( 'instant', 'swap' ),
			'css injection attempt' => array( 'swap;}body{display:none', 'swap' ),
			'not a string'          => array( 1, 'swap' ),
		);
	}

	public function test_font_display_defaults_to_swap() {
		$this->assertSame( 'swap', rswsfg_get_font_display() );
	}

	/**
	 * @dataProvider font_display_provider
	 *
	 * @param mixed  $filtered Value returned by the filter.
	 * @param string $expected Expected font-display value.
	 */
	public function test_font_display_filter_is_validated( $filtered, $expected ) {
		Filters\expectApplied( 'rswsfg_font_display' )->once()->with( 'swap' )->andReturn( $filtered );

		$this->assertSame( $expected, rswsfg_get_font_display() );
	}

	public function test_font_face_css_is_empty_without_fonts() {
		$this->assertSame( '', rswsfg_get_font_face_css( array() ) );
	}

	public function test_font_face_css_lists_local_sources_before_the_bundled_file() {
		$fonts = array_intersect_key( rswsfg_get_fonts(), array( 'arial' => true ) );
		$rules = explode( "\n", rswsfg_get_font_face_css( $fonts ) );

		$this->assertCount( count( $fonts['arial']['faces'] ), $rules );
		$this->assertSame(
			'@font-face{font-family:"Arial-safe";font-style:normal;font-weight:400;font-display:swap;'
			. 'src:local("Arial"),local("ArialMT"),local("Liberation Sans"),'
			. 'url("' . RSWSFG_TESTS_PLUGIN_URL . 'assets/fonts/liberationsans-regular.woff2") format("woff2");}',
			$rules[0]
		);
	}

	public function test_preload_file_is_the_regular_face() {
		$this->assertSame( 'liberationsans-regular.woff2', rswsfg_get_preload_file( rswsfg_get_fonts()['arial'] ) );
	}

	public function test_preload_file_falls_back_to_the_first_face() {
		$font = array(
			'faces' => array(
				array(
					'weight' => '900',
					'style'  => 'normal',
					'file'   => 'black.woff2',
				),
				array(
					'weight' => '900',
					'style'  => 'italic',
					'file'   => 'black-italic.woff2',
				),
			),
		);

		$this->assertSame( 'black.woff2', rswsfg_get_preload_file( $font ) );
	}

	public function test_preloads_only_active_fonts_marked_for_the_device() {
		Functions\when( 'wp_is_mobile' )->justReturn( true );
		$this->set_options(
			array(
				'rswsfg_active_fonts'          => array( 'arial', 'georgia' ),
				'rswsfg_preload_mobile_fonts'  => array( 'arial', 'verdana' ),
				'rswsfg_preload_desktop_fonts' => array( 'georgia' ),
			)
		);

		$this->expectOutputString(
			'<link rel="preload" href="' . RSWSFG_TESTS_PLUGIN_URL . 'assets/fonts/liberationsans-regular.woff2" as="font" type="font/woff2" crossorigin>' . "\n"
		);
		rswsfg_output_font_preloads();
	}

	public function test_a_file_shared_by_several_fonts_is_preloaded_once() {
		$fonts  = rswsfg_get_fonts();
		$shared = rswsfg_get_preload_file( $fonts['verdana'] );
		$this->assertSame( $shared, rswsfg_get_preload_file( $fonts['tahoma'] ), 'Test premise: Verdana and Tahoma share their alternative.' );

		Functions\when( 'wp_is_mobile' )->justReturn( false );
		$this->set_options(
			array(
				'rswsfg_active_fonts'          => array( 'verdana', 'tahoma' ),
				'rswsfg_preload_desktop_fonts' => array( 'verdana', 'tahoma' ),
			)
		);

		$this->expectOutputString(
			'<link rel="preload" href="' . RSWSFG_TESTS_PLUGIN_URL . 'assets/fonts/' . $shared . '" as="font" type="font/woff2" crossorigin>' . "\n"
		);
		rswsfg_output_font_preloads();
	}
}
