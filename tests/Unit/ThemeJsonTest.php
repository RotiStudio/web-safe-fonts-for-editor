<?php
/**
 * Tests of the theme.json filters.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

namespace RotiStudio\WebSafeFonts\Tests\Unit;

use WP_Theme_JSON_Data;

/**
 * The active fonts join the theme's families, and plugin-hosted faces are
 * removed from the user's global styles.
 */
final class ThemeJsonTest extends TestCase {

	/**
	 * Builds theme.json data with the given font families.
	 *
	 * @param array $families Font families (list or origin-keyed).
	 * @return WP_Theme_JSON_Data
	 */
	private function theme_json( array $families ) {
		return new WP_Theme_JSON_Data(
			array(
				'version'  => 3,
				'settings' => array( 'typography' => array( 'fontFamilies' => $families ) ),
			)
		);
	}

	/**
	 * Font face whose file is hosted by this plugin.
	 *
	 * @return array
	 */
	private function plugin_face() {
		return array( 'src' => array( RSWSFG_TESTS_PLUGIN_URL . 'assets/fonts/liberationsans-regular.woff2' ) );
	}

	/**
	 * The family list update_with() received.
	 *
	 * @param WP_Theme_JSON_Data $theme_json Filtered data.
	 * @return array
	 */
	private function updated_families( WP_Theme_JSON_Data $theme_json ) {
		$this->assertNotNull( $theme_json->update, 'update_with() was not called.' );
		return $theme_json->update['settings']['typography']['fontFamilies'];
	}

	public function test_theme_json_is_untouched_without_active_fonts() {
		$this->set_options( array() );
		$theme_json = $this->theme_json( array( 'theme' => array() ) );

		$this->assertSame( $theme_json, rswsfg_filter_theme_json_fonts( $theme_json ) );
		$this->assertNull( $theme_json->update );
	}

	public function test_active_fonts_are_added_after_the_theme_families() {
		$this->set_options( array( 'rswsfg_active_fonts' => array( 'arial' ) ) );
		$theme_family = array(
			'slug'       => 'body',
			'name'       => 'Body',
			'fontFamily' => 'Inter, sans-serif',
		);

		$theme_json = rswsfg_filter_theme_json_fonts( $this->theme_json( array( 'theme' => array( $theme_family ) ) ) );
		$families   = $this->updated_families( $theme_json );

		$this->assertSame( array( 'body', 'arial' ), array_column( $families, 'slug' ) );
		$this->assertArrayNotHasKey( 'fontFace', $families[1], 'Faces are printed by the plugin, never passed to theme.json.' );
		$this->assertSame( 3, $theme_json->update['version'] );
	}

	public function test_a_list_shaped_family_root_is_supported() {
		$this->set_options( array( 'rswsfg_active_fonts' => array( 'arial' ) ) );

		$families = $this->updated_families(
			rswsfg_filter_theme_json_fonts( $this->theme_json( array( array( 'slug' => 'body' ) ) ) )
		);

		$this->assertSame( array( 'body', 'arial' ), array_column( $families, 'slug' ) );
	}

	public function test_a_theme_family_with_the_same_slug_wins() {
		$this->set_options( array( 'rswsfg_active_fonts' => array( 'arial', 'verdana' ) ) );
		$theme_arial = array(
			'slug'       => 'arial',
			'name'       => 'Theme Arial',
			'fontFamily' => 'Arial',
		);

		$families = $this->updated_families(
			rswsfg_filter_theme_json_fonts( $this->theme_json( array( 'theme' => array( $theme_arial ) ) ) )
		);

		$this->assertSame( array( 'arial', 'verdana' ), array_column( $families, 'slug' ) );
		$this->assertSame( 'Theme Arial', $families[0]['name'] );
	}

	public function test_user_styles_without_plugin_faces_are_untouched() {
		$theme_json = $this->theme_json(
			array(
				'custom' => array(
					array(
						'slug'     => 'inter',
						'fontFace' => array( array( 'src' => array( 'https://example.test/uploads/fonts/inter.woff2' ) ) ),
					),
				),
			)
		);

		$this->assertSame( $theme_json, rswsfg_filter_user_theme_json_fonts( $theme_json ) );
		$this->assertNull( $theme_json->update );
	}

	public function test_plugin_faces_are_removed_from_user_styles() {
		$own_face   = array( 'src' => array( 'https://example.test/uploads/fonts/arial-own.woff2' ) );
		$theme_json = $this->theme_json(
			array(
				'theme'  => array(
					array(
						'slug'     => 'arial',
						'fontFace' => array( $this->plugin_face() ),
					),
				),
				'custom' => array(
					array(
						'slug'     => 'verdana',
						'fontFace' => array( $this->plugin_face() ),
					),
					array(
						'slug'     => 'mixed',
						'fontFace' => array( $this->plugin_face(), $own_face ),
					),
				),
			)
		);

		$families = $this->updated_families( rswsfg_filter_user_theme_json_fonts( $theme_json ) );

		$this->assertSame( array( array( 'slug' => 'arial' ) ), $families['theme'], 'A theme family stays active without its faces.' );
		$this->assertSame(
			array(
				array(
					'slug'     => 'mixed',
					'fontFace' => array( $own_face ),
				),
			),
			$families['custom'],
			'An installed family left without faces is removed, other faces stay.'
		);
	}
}
