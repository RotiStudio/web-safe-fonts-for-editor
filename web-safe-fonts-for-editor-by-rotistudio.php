<?php
/**
 * Plugin Name: Web-Safe Fonts for Editor by RotiStudio
 * Plugin URI: https://rotistudio.com/plugins/web-safe-fonts-plugin-wordpress/
 * Description: Add the option to choose web safe fonts in the WordPress block editor (Gutenberg), so your website can be faster and more consistent appearance.
 * Version: 1.2.0
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Author: RotiStudio - Tamas Rottenbacher
 * Author URI: https://rotistudio.com
 * License: GPLv2 or later
 * Text Domain: web-safe-fonts-for-editor-by-rotistudio
 * Domain Path: /languages
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RSWSFG_VERSION', '1.2.0' );
define( 'RSWSFG_PATH', plugin_dir_path( __FILE__ ) );
define( 'RSWSFG_URL', plugin_dir_url( __FILE__ ) );

require_once RSWSFG_PATH . 'font-families.php';
require_once RSWSFG_PATH . 'font-definitions.php';

/**
 * Escapes a string for safe use inside a CSS double-quoted string.
 *
 * Prevents breaking out of CSS context (e.g. injection via " or \).
 *
 * @param string $value The raw string.
 * @return string The escaped string safe for CSS "..." context.
 */
function rswsfg_esc_css_string( $value ) {
	$value = str_replace( '\\', '\\\\', $value );
	$value = str_replace( '"', '\\"', $value );

	return $value;
}

/**
 * Returns the active font slugs saved in options.
 * Defaults to all fonts when the option has never been saved.
 *
 * @return array<int, string> Active font slugs.
 */
function rswsfg_get_active_font_slugs() {
	$saved = get_option( 'rswsfg_active_fonts', null );

	if ( null === $saved ) {
		return array();
	}

	return is_array( $saved ) ? $saved : array();
}

/**
 * Returns only the enabled font families based on saved settings.
 *
 * @return array<int, array<string, string>> Active font family definitions.
 */
function rswsfg_get_active_font_families() {
	$active_slugs = rswsfg_get_active_font_slugs();

	return array_values(
		array_filter(
			rswsfg_get_font_families(),
			function ( $font ) use ( $active_slugs ) {
				return in_array( $font['slug'], $active_slugs, true );
			}
		)
	);
}

/**
 * Builds font families with fontFace entries for theme.json registration.
 *
 * Merges the active font families with their face variants so the Site Editor
 * (Appearance › Editor › Styles › Typography › Fonts) can display each
 * weight/style as a separate variant.
 *
 * @return array<int, array<string, mixed>> Font families including fontFace data.
 */
function rswsfg_build_theme_json_font_families() {
	$families    = rswsfg_get_active_font_families();
	$definitions = rswsfg_get_font_face_definitions();
	$base_url    = RSWSFG_URL . 'assets/fonts/';

	foreach ( $families as &$family ) {
		$slug = $family['slug'];

		if ( ! isset( $definitions[ $slug ] ) ) {
			continue;
		}

		$def        = $definitions[ $slug ];
		$font_faces = array();

		foreach ( $def['faces'] as $face ) {
			$font_faces[] = array(
				'fontFamily'  => $def['family'],
				'fontWeight'  => $face['weight'],
				'fontStyle'   => $face['style'],
				'fontDisplay' => rswsfg_get_font_display(),
				'src'         => array( esc_url_raw( $base_url . $face['file'] ) ),
			);
		}

		$family['fontFace'] = $font_faces;
	}

	unset( $family );

	return $families;
}

/**
 * Register web safe font families in the block editor via theme.json filter.
 */
add_filter(
	'wp_theme_json_data_theme',
	function ( $theme_json ) {
		$custom_fonts = array(
			'version'  => 2,
			'settings' => array(
				'typography' => array(
					'fontFamilies' => rswsfg_build_theme_json_font_families(),
				),
			),
		);

		return $theme_json->update_with( $custom_fonts );
	},
	20
);

/**
 * Returns the CSS format string for a given font filename.
 *
 * @param string $filename Font filename.
 * @return string CSS format value (e.g. 'woff2', 'truetype').
 */
function rswsfg_get_font_format( $filename ) {
	$formats = array(
		'woff2' => 'woff2',
		'woff'  => 'woff',
		'ttf'   => 'truetype',
		'otf'   => 'opentype',
	);
	$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	return isset( $formats[ $ext ] ) ? $formats[ $ext ] : 'woff2';
}

/**
 * Returns the font-display value used in @font-face rules.
 *
 * Defaults to swap. Developers can override via the rswsfg_font_display filter.
 *
 * @return string One of: auto, block, swap, fallback, optional.
 */
function rswsfg_get_font_display() {
	$rswsfg_allowed = array( 'auto', 'block', 'swap', 'fallback', 'optional' );
	$rswsfg_display = apply_filters( 'rswsfg_font_display', 'swap' );

	if ( ! is_string( $rswsfg_display ) ) {
		return 'swap';
	}

	$rswsfg_display = strtolower( sanitize_key( $rswsfg_display ) );

	if ( ! in_array( $rswsfg_display, $rswsfg_allowed, true ) ) {
		return 'swap';
	}

	return $rswsfg_display;
}

/**
 * Returns a path marker used to detect plugin-hosted font files.
 *
 * @return string Relative path fragment inside font src URLs.
 */
function rswsfg_get_plugin_font_src_marker() {
	return 'web-safe-fonts-for-editor-by-rotistudio/assets/fonts/';
}

/**
 * Checks whether a fontFace entry loads a file from this plugin.
 *
 * @param array $rswsfg_face Font face definition (theme.json shape).
 * @return bool True when src points at this plugin's fonts.
 */
function rswsfg_font_face_src_is_plugin( $rswsfg_face ) {
	if ( empty( $rswsfg_face['src'] ) ) {
		return false;
	}

	$rswsfg_marker = rswsfg_get_plugin_font_src_marker();

	foreach ( (array) $rswsfg_face['src'] as $rswsfg_src ) {
		if ( is_string( $rswsfg_src ) && false !== strpos( $rswsfg_src, $rswsfg_marker ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Removes plugin-hosted font faces from user global styles at runtime.
 *
 * Activating a theme font in the Site Editor Font Manager copies faces into
 * the user theme.json without fontDisplay (WordPress then prints fallback).
 * Those copies also duplicate the faces already registered via the theme
 * filter. Active fonts are provided by wp_theme_json_data_theme instead.
 *
 * @param WP_Theme_JSON_Data $theme_json Theme JSON data object.
 * @return WP_Theme_JSON_Data Filtered theme JSON data.
 */
function rswsfg_filter_user_theme_json_fonts( $theme_json ) {
	$rswsfg_data = $theme_json->get_data();

	if ( empty( $rswsfg_data['settings']['typography']['fontFamilies'] ) || ! is_array( $rswsfg_data['settings']['typography']['fontFamilies'] ) ) {
		return $theme_json;
	}

	$rswsfg_families_root = $rswsfg_data['settings']['typography']['fontFamilies'];

	if ( isset( $rswsfg_families_root['custom'] ) && is_array( $rswsfg_families_root['custom'] ) ) {
		$rswsfg_families = $rswsfg_families_root['custom'];
	} elseif ( isset( $rswsfg_families_root[0] ) || empty( $rswsfg_families_root ) ) {
		$rswsfg_families = $rswsfg_families_root;
	} else {
		return $theme_json;
	}

	$rswsfg_changed      = false;
	$rswsfg_new_families = array();

	foreach ( $rswsfg_families as $rswsfg_family ) {
		if ( empty( $rswsfg_family['fontFace'] ) || ! is_array( $rswsfg_family['fontFace'] ) ) {
			$rswsfg_new_families[] = $rswsfg_family;
			continue;
		}

		$rswsfg_has_plugin_face = false;
		$rswsfg_new_faces       = array();

		foreach ( $rswsfg_family['fontFace'] as $rswsfg_face ) {
			if ( rswsfg_font_face_src_is_plugin( $rswsfg_face ) ) {
				$rswsfg_has_plugin_face = true;
				$rswsfg_changed         = true;
				continue;
			}

			$rswsfg_new_faces[] = $rswsfg_face;
		}

		if ( $rswsfg_has_plugin_face && empty( $rswsfg_new_faces ) ) {
			continue;
		}

		if ( $rswsfg_has_plugin_face ) {
			$rswsfg_family['fontFace'] = $rswsfg_new_faces;
		}

		$rswsfg_new_families[] = $rswsfg_family;
	}

	if ( ! $rswsfg_changed ) {
		return $theme_json;
	}

	return $theme_json->update_with(
		array(
			'version'  => isset( $rswsfg_data['version'] ) ? $rswsfg_data['version'] : 2,
			'settings' => array(
				'typography' => array(
					'fontFamilies' => $rswsfg_new_families,
				),
			),
		)
	);
}
add_filter( 'wp_theme_json_data_user', 'rswsfg_filter_user_theme_json_fonts', 20 );

/**
 * Enqueue web safe font family utility CSS for the front end and the block editor.
 *
 * @font-face rules are printed by WordPress into wp-fonts-local from theme.json.
 * This callback only adds the .has-{slug}-font-family utility classes.
 *
 * @return void
 */
function rswsfg_enqueue_fonts_css() {
	$css_parts = array();

	foreach ( rswsfg_get_active_font_families() as $font ) {
		$css_parts[] = sprintf(
			'.has-%s-font-family { font-family: %s !important; }',
			esc_attr( $font['slug'] ),
			$font['fontFamily']
		);
	}

	if ( ! empty( $css_parts ) ) {
		$css = implode( "\n", $css_parts );

		if ( wp_style_is( 'global-styles', 'registered' ) ) {
			wp_add_inline_style( 'global-styles', $css );
		} else {
			wp_register_style( 'rswsfg-fonts', false, array(), RSWSFG_VERSION );
			wp_enqueue_style( 'rswsfg-fonts' );
			wp_add_inline_style( 'rswsfg-fonts', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rswsfg_enqueue_fonts_css', 20 );
add_action( 'enqueue_block_editor_assets', 'rswsfg_enqueue_fonts_css', 20 );

/**
 * Outputs <link rel="preload"> tags for the regular-weight fallback woff2 files
 * of each active font, but only on mobile devices (via wp_is_mobile()).
 *
 * Mobile browsers (Android in particular) typically lack web-safe system fonts,
 * so preloading the fallback woff2 reduces the time-to-first-render of styled text.
 * Desktop systems (Windows, macOS) usually have these fonts installed locally,
 * making preload unnecessary and wasteful there.
 *
 * Only the 400/normal variant is preloaded — it covers the most common rendering
 * path. Duplicate woff2 files shared by multiple font families are output once.
 *
 * @return void
 */
function rswsfg_output_font_preloads() {
	if ( ! wp_is_mobile() ) {
		return;
	}

	$definitions = rswsfg_get_font_face_definitions();
	$active_slugs = rswsfg_get_active_font_slugs();
	$base_url    = RSWSFG_URL . 'assets/fonts/';
	$seen_files  = array();

	foreach ( $active_slugs as $slug ) {
		if ( ! isset( $definitions[ $slug ] ) ) {
			continue;
		}

		foreach ( $definitions[ $slug ]['faces'] as $face ) {
			if ( '400' !== $face['weight'] || 'normal' !== $face['style'] ) {
				continue;
			}

			$file = $face['file'];

			if ( in_array( $file, $seen_files, true ) ) {
				continue;
			}

			$seen_files[] = $file;

			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( $base_url . $file )
			);
		}
	}
}
add_action( 'wp_head', 'rswsfg_output_font_preloads', 2 );

/**
 * Returns unique alternative font @font-face definitions for the admin preview.
 *
 * Keyed by the alternative font name. Each entry maps to the woff2 file for
 * the regular weight. local() tries the alternative font first; if not
 * installed the woff2 is downloaded.
 *
 * @return array<string, string> Alt font name => woff2 filename.
 */
function rswsfg_get_alt_font_files() {
	return array(
		'Liberation Sans'  => 'liberationsans-regular.woff2',
		'Liberation Serif' => 'liberationserif-regular.woff2',
		'Liberation Mono'  => 'liberationmono-regular.woff2',
		'DejaVu Sans'      => 'dejavusans-regular.woff2',

		'DejaVu Sans Mono' => 'dejavusansmono-regular.woff2',
		'Cabin Condensed'  => 'CabinCondensed-Regular.woff2',
		'TeX Gyre Pagella' => 'texgyrepagella-regular.woff2',
		'Archivo Black'    => 'ArchivoBlack-Regular.woff2',
		'Oswald'           => 'oswald-regular.woff2',
		'Comic Neue'       => 'comicneue-regular.woff2',
	);
}

/**
 * Enqueue @font-face preview CSS on the Settings > Writing admin page.
 *
 * @return void
 */
function rswsfg_enqueue_admin_fonts_css() {
	$screen = get_current_screen();

	if ( ! $screen || 'options-writing' !== $screen->id ) {
		return;
	}

	$base_url     = RSWSFG_URL . 'assets/fonts/';
	$font_display = rswsfg_get_font_display();
	$css_parts    = array();

	foreach ( rswsfg_get_alt_font_files() as $alt_name => $file ) {
		$escaped_name = rswsfg_esc_css_string( $alt_name );
		$css_parts[]  = sprintf(
			'@font-face { font-family: "%s"; src: local("%s"), url("%s") format("%s"); font-weight: 400; font-style: normal; font-display: %s; }',
			$escaped_name,
			$escaped_name,
			esc_url( $base_url . $file ),
			rswsfg_get_font_format( $file ),
			$font_display
		);
	}

	wp_add_inline_style( 'wp-admin', implode( "\n", $css_parts ) );
}
add_action( 'admin_enqueue_scripts', 'rswsfg_enqueue_admin_fonts_css', 20 );

// --- Admin settings ---

/**
 * Register the settings, section and field on the Writing settings page.
 *
 * @return void
 */
function rswsfg_register_settings() {
	register_setting(
		'writing',
		'rswsfg_active_fonts',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'rswsfg_sanitize_active_fonts',
			'default'           => array(),
		)
	);

	add_settings_section(
		'rswsfg_fonts_section',
		__( 'Web Safe Fonts', 'web-safe-fonts-for-editor-by-rotistudio' ),
		'rswsfg_section_callback',
		'writing'
	);
}
add_action( 'admin_init', 'rswsfg_register_settings', 10 );

/**
 * Renders the section description.
 *
 * @return void
 */
function rswsfg_section_callback() {
	printf(
		'<p id="rswsfg-fonts-section">%s</p>',
		esc_html__( 'Select which web safe fonts should be available in the WordPress block editor.', 'web-safe-fonts-for-editor-by-rotistudio' )
	);
	rswsfg_fonts_field_callback();
}

/**
 * Renders the font checkboxes.
 *
 * @return void
 */
function rswsfg_fonts_field_callback() {
	$active_slugs = rswsfg_get_active_font_slugs();
	$licences_url = RSWSFG_URL . 'assets/licences/';
	$fonts        = rswsfg_get_font_families();

	usort(
		$fonts,
		function ( $a, $b ) {
			return strcasecmp( $a['name'], $b['name'] );
		}
	);

	foreach ( $fonts as $font ) {
		$checked     = in_array( $font['slug'], $active_slugs, true );
		$license_url = esc_url( $licences_url . $font['licenseFile'] );

		$alt_span = sprintf(
			'<span style="font-family:\'%1$s\'">%2$s</span>',
			esc_attr( $font['altName'] ),
			esc_html( $font['altName'] )
		);

		$license_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			$license_url,
			esc_html__( 'license', 'web-safe-fonts-for-editor-by-rotistudio' )
		);

		/* translators: 1: alternative font name with inline style (HTML), 2: license link (HTML) */
		$alt_info = wp_kses(
			sprintf(
				_x( '(Alternative: %1$s &ndash; %2$s)', 'Alternative font info. 1: font name with style, 2: license link', 'web-safe-fonts-for-editor-by-rotistudio' ),
				$alt_span,
				$license_link
			),
			array(
				'span' => array( 'style' => true ),
				'a'    => array(
					'href'   => true,
					'target' => true,
					'rel'    => true,
				),
			)
		);

		printf(
			'<label style="padding: 3px 0; font-size: 16px;">'
			. '<input type="checkbox" name="rswsfg_active_fonts[]" value="%1$s"%2$s> '
			. '<span style="min-width: 170px; display: inline-block; padding-left: 10px; font-family:%3$s">%4$s</span>'
			. ' <span>%5$s</span>'
			. '</label><hr>',
			esc_attr( $font['slug'] ),
			checked( $checked, true, false ),
			esc_attr( $font['fontFamily'] ),
			esc_html( $font['name'] ),
			$alt_info // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized via wp_kses().
		);
	}
}

/**
 * Sanitizes the submitted font slugs array.
 *
 * @param mixed $input Raw submitted value.
 * @return array<int, string> Validated font slugs.
 */
function rswsfg_sanitize_active_fonts( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	$valid_slugs = array_column( rswsfg_get_font_families(), 'slug' );

	return array_values( array_intersect( $input, $valid_slugs ) );
}

/**
 * Add Settings link to the plugin action links.
 */
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links ) {
		array_unshift(
			$links,
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'options-writing.php#rswsfg-fonts-section' ) ),
				esc_html__( 'Settings', 'web-safe-fonts-for-editor-by-rotistudio' )
			)
		);

		return $links;
	},
	10
);
