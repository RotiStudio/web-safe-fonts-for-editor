<?php
/**
 * Tests of the font definitions and the bundled files.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

namespace RotiStudio\WebSafeFonts\Tests\Unit;

/**
 * Every font definition is complete and points at files that exist.
 */
final class FontDefinitionsTest extends TestCase {

	/**
	 * One case per font.
	 *
	 * @return array<string, array{0: string, 1: array<string, mixed>}>
	 */
	public function font_provider() {
		$cases = array();

		foreach ( rswsfg_get_fonts() as $slug => $font ) {
			$cases[ $slug ] = array( $slug, $font );
		}

		return $cases;
	}

	/**
	 * @dataProvider font_provider
	 *
	 * @param string               $slug Font slug.
	 * @param array<string, mixed> $font Font definition.
	 */
	public function test_font_definition_is_complete( $slug, array $font ) {
		$this->assertMatchesRegularExpression( '/^[a-z0-9-]+$/', $slug );

		foreach ( array( 'name', 'fontFamily', 'safeFamily', 'altName', 'altFile', 'licenseFile' ) as $key ) {
			$this->assertIsString( $font[ $key ] ?? null, "Missing {$key}." );
			$this->assertNotSame( '', $font[ $key ], "Empty {$key}." );
		}

		$this->assertStringContainsString( '"' . $font['safeFamily'] . '"', $font['fontFamily'], 'The family stack must list the safe family.' );
		$this->assertNotEmpty( $font['faces'] );
	}

	/**
	 * @dataProvider font_provider
	 *
	 * @param string               $slug Font slug.
	 * @param array<string, mixed> $font Font definition.
	 */
	public function test_font_files_exist( $slug, array $font ) {
		$this->assertFileExists( RSWSFG_TESTS_PLUGIN_DIR . 'assets/fonts/' . $font['altFile'] );
		$this->assertFileExists( RSWSFG_TESTS_PLUGIN_DIR . 'assets/licences/' . $font['licenseFile'] );

		foreach ( $font['faces'] as $face ) {
			$this->assertMatchesRegularExpression( '/^[1-9]00$/', $face['weight'] );
			$this->assertContains( $face['style'], array( 'normal', 'italic' ) );
			$this->assertNotEmpty( $face['local'] );
			$this->assertFileExists( RSWSFG_TESTS_PLUGIN_DIR . 'assets/fonts/' . $face['file'] );
		}
	}

	public function test_every_bundled_font_file_is_used() {
		$used = array();

		foreach ( rswsfg_get_fonts() as $font ) {
			$used[] = $font['altFile'];
			foreach ( $font['faces'] as $face ) {
				$used[] = $face['file'];
			}
		}

		$bundled = array_map( 'basename', glob( RSWSFG_TESTS_PLUGIN_DIR . 'assets/fonts/*.woff2' ) );

		$this->assertSame( array(), array_values( array_diff( $bundled, $used ) ), 'Font files no font definition uses.' );
	}

	public function test_every_bundled_license_is_used() {
		$used    = array_column( rswsfg_get_fonts(), 'licenseFile' );
		$bundled = array_map( 'basename', glob( RSWSFG_TESTS_PLUGIN_DIR . 'assets/licences/*.txt' ) );

		$this->assertSame( array(), array_values( array_diff( $bundled, $used ) ), 'License files no font definition uses.' );
	}
}
