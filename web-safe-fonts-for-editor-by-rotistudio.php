<?php
/**
 * Plugin Name: Web-Safe Fonts for Editor by RotiStudio
 * Plugin URI: https://rotistudio.com/plugins/web-safe-fonts-plugin-wordpress/
 * Description: Add the option to choose web safe fonts in the WordPress block editor (Gutenberg), so your website can be faster and more consistent appearance.
 * Version: 1.2.0
 * Requires at least: 6.5
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

require_once RSWSFG_PATH . 'font-definitions.php';

/**
 * Returns the active font slugs saved in options.
 *
 * No font is active until the option has been saved.
 *
 * @return array<int, string> Active font slugs.
 */
function rswsfg_get_active_font_slugs() {
	$rswsfg_saved = get_option( 'rswsfg_active_fonts', array() );

	if ( ! is_array( $rswsfg_saved ) ) {
		return array();
	}

	return array_values( array_filter( $rswsfg_saved, 'is_string' ) );
}

/**
 * Returns the definitions of the enabled fonts, in definition order.
 *
 * @return array<string, array<string, mixed>> Active font definitions keyed by slug.
 */
function rswsfg_get_active_fonts() {
	return array_intersect_key(
		rswsfg_get_fonts(),
		array_flip( rswsfg_get_active_font_slugs() )
	);
}

/**
 * Adds the active web safe font families to the theme's own font families.
 *
 * Only the family stacks are registered. No fontFace is passed to theme.json,
 * because WordPress would print it under the first name of the stack (for
 * example "Arial") and so replace the font installed on the visitor's device.
 * The @font-face rules are printed by rswsfg_enqueue_font_faces() instead.
 *
 * @param WP_Theme_JSON_Data $theme_json Theme JSON data object.
 * @return WP_Theme_JSON_Data Filtered theme JSON data.
 */
function rswsfg_filter_theme_json_fonts( $theme_json ) {
	$rswsfg_fonts = rswsfg_get_active_fonts();

	if ( empty( $rswsfg_fonts ) ) {
		return $theme_json;
	}

	$rswsfg_data     = $theme_json->get_data();
	$rswsfg_families = array();

	// update_with() replaces the font family list, so start from the theme's own families.
	if ( isset( $rswsfg_data['settings']['typography']['fontFamilies'] ) && is_array( $rswsfg_data['settings']['typography']['fontFamilies'] ) ) {
		$rswsfg_families_root = $rswsfg_data['settings']['typography']['fontFamilies'];

		if ( isset( $rswsfg_families_root['theme'] ) && is_array( $rswsfg_families_root['theme'] ) ) {
			$rswsfg_families = $rswsfg_families_root['theme'];
		} elseif ( isset( $rswsfg_families_root[0] ) ) {
			$rswsfg_families = $rswsfg_families_root;
		}
	}

	$rswsfg_existing_slugs = array_column( $rswsfg_families, 'slug' );

	foreach ( $rswsfg_fonts as $rswsfg_slug => $rswsfg_font ) {
		if ( in_array( $rswsfg_slug, $rswsfg_existing_slugs, true ) ) {
			continue;
		}

		$rswsfg_families[] = array(
			'fontFamily' => $rswsfg_font['fontFamily'],
			'name'       => $rswsfg_font['name'],
			'slug'       => $rswsfg_slug,
		);
	}

	return $theme_json->update_with(
		array(
			'version'  => isset( $rswsfg_data['version'] ) ? $rswsfg_data['version'] : 2,
			'settings' => array(
				'typography' => array(
					'fontFamilies' => $rswsfg_families,
				),
			),
		)
	);
}
add_filter( 'wp_theme_json_data_theme', 'rswsfg_filter_theme_json_fonts', 20 );

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
 * Checks whether a fontFace entry loads a file from this plugin.
 *
 * @param array $rswsfg_face Font face definition (theme.json shape).
 * @return bool True when src points at this plugin's fonts.
 */
function rswsfg_font_face_src_is_plugin( $rswsfg_face ) {
	if ( empty( $rswsfg_face['src'] ) ) {
		return false;
	}

	$rswsfg_marker = basename( __DIR__ ) . '/assets/fonts/';

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
 * Earlier versions registered their faces in theme.json, and activating such
 * a font in the Site Editor Font Manager copied the faces into the user
 * theme.json. WordPress prints those copies under the real font name, which
 * replaces the installed font. The faces come from
 * rswsfg_enqueue_font_faces() instead.
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
	} elseif ( isset( $rswsfg_families_root[0] ) ) {
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
 * Builds the @font-face rules of the active fonts.
 *
 * Every rule uses the font's own "-safe" family name, which the font-family
 * stack lists after the real font name. The browser therefore uses the
 * installed web-safe font first, then a local() match of the same font or its
 * alternative, and downloads the woff2 file only when none of them exists.
 *
 * @return string CSS, empty when no font is active.
 */
function rswsfg_get_font_face_css() {
	$rswsfg_fonts = rswsfg_get_active_fonts();

	if ( empty( $rswsfg_fonts ) ) {
		return '';
	}

	$rswsfg_base_url  = RSWSFG_URL . 'assets/fonts/';
	$rswsfg_display   = rswsfg_get_font_display();
	$rswsfg_css_parts = array();

	foreach ( $rswsfg_fonts as $rswsfg_font ) {
		foreach ( $rswsfg_font['faces'] as $rswsfg_face ) {
			$rswsfg_sources = array();

			foreach ( $rswsfg_face['local'] as $rswsfg_local_name ) {
				$rswsfg_sources[] = sprintf( 'local("%s")', $rswsfg_local_name );
			}

			$rswsfg_sources[] = sprintf( 'url("%s") format("woff2")', esc_url_raw( $rswsfg_base_url . $rswsfg_face['file'] ) );

			$rswsfg_css_parts[] = sprintf(
				'@font-face{font-family:"%1$s";font-style:%2$s;font-weight:%3$s;font-display:%4$s;src:%5$s;}',
				$rswsfg_font['safeFamily'],
				$rswsfg_face['style'],
				$rswsfg_face['weight'],
				$rswsfg_display,
				implode( ',', $rswsfg_sources )
			);
		}
	}

	return implode( "\n", $rswsfg_css_parts );
}

/**
 * Enqueues the @font-face rules on the front end and in the block editor.
 *
 * The enqueue_block_assets hook also runs for the iframed editor canvas.
 *
 * @return void
 */
function rswsfg_enqueue_font_faces() {
	$rswsfg_css = rswsfg_get_font_face_css();

	if ( '' === $rswsfg_css ) {
		return;
	}

	wp_register_style( 'rswsfg-fonts', false, array(), RSWSFG_VERSION );
	wp_enqueue_style( 'rswsfg-fonts' );
	wp_add_inline_style( 'rswsfg-fonts', $rswsfg_css );
}
add_action( 'enqueue_block_assets', 'rswsfg_enqueue_font_faces', 20 );

/**
 * Outputs <link rel="preload"> tags for the regular-weight fallback woff2 files
 * of each active font, but only on mobile devices (via wp_is_mobile()).
 *
 * Mobile browsers (Android in particular) typically lack web-safe system fonts,
 * so preloading the fallback woff2 reduces the time-to-first-render of styled text.
 * Desktop systems (Windows, macOS) usually have these fonts installed locally,
 * making preload unnecessary and wasteful there.
 *
 * Only the 400/normal variant is preloaded - it covers the most common rendering
 * path. Duplicate woff2 files shared by multiple font families are output once.
 *
 * @return void
 */
function rswsfg_output_font_preloads() {
	if ( ! wp_is_mobile() ) {
		return;
	}

	$rswsfg_base_url   = RSWSFG_URL . 'assets/fonts/';
	$rswsfg_seen_files = array();

	foreach ( rswsfg_get_active_fonts() as $rswsfg_font ) {
		foreach ( $rswsfg_font['faces'] as $rswsfg_face ) {
			if ( '400' !== $rswsfg_face['weight'] || 'normal' !== $rswsfg_face['style'] ) {
				continue;
			}

			$rswsfg_file = $rswsfg_face['file'];

			if ( in_array( $rswsfg_file, $rswsfg_seen_files, true ) ) {
				continue;
			}

			$rswsfg_seen_files[] = $rswsfg_file;

			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( $rswsfg_base_url . $rswsfg_file )
			);
		}
	}
}
add_action( 'wp_head', 'rswsfg_output_font_preloads', 2 );

/**
 * Enqueue @font-face preview CSS on the Settings > Writing admin page.
 *
 * One rule per alternative font, so its name can be shown in its own
 * typeface. local() tries the installed alternative first; the regular woff2
 * file is downloaded only when it is missing.
 *
 * @return void
 */
function rswsfg_enqueue_admin_fonts_css() {
	$rswsfg_screen = get_current_screen();

	if ( ! $rswsfg_screen || 'options-writing' !== $rswsfg_screen->id ) {
		return;
	}

	$rswsfg_base_url  = RSWSFG_URL . 'assets/fonts/';
	$rswsfg_display   = rswsfg_get_font_display();
	$rswsfg_css_parts = array();

	foreach ( rswsfg_get_fonts() as $rswsfg_font ) {
		// Several fonts share an alternative; the key keeps one rule per alternative.
		$rswsfg_css_parts[ $rswsfg_font['altName'] ] = sprintf(
			'@font-face { font-family: "%1$s"; src: local("%1$s"), url("%2$s") format("woff2"); font-weight: 400; font-style: normal; font-display: %3$s; }',
			$rswsfg_font['altName'],
			esc_url_raw( $rswsfg_base_url . $rswsfg_font['altFile'] ),
			$rswsfg_display
		);
	}

	wp_add_inline_style( 'wp-admin', implode( "\n", $rswsfg_css_parts ) );
}
add_action( 'admin_enqueue_scripts', 'rswsfg_enqueue_admin_fonts_css', 20 );

/**
 * Register the setting and its section on the Writing settings page.
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
 * Renders the section description and the font checkboxes.
 *
 * @return void
 */
function rswsfg_section_callback() {
	printf(
		'<p id="rswsfg-fonts-section">%s</p>',
		esc_html__( 'Select which web safe fonts should be available in the WordPress block editor.', 'web-safe-fonts-for-editor-by-rotistudio' )
	);
	rswsfg_render_font_checkboxes();
}

/**
 * Renders the font checkboxes.
 *
 * @return void
 */
function rswsfg_render_font_checkboxes() {
	$rswsfg_active_slugs = rswsfg_get_active_font_slugs();
	$rswsfg_licences_url = RSWSFG_URL . 'assets/licences/';
	$rswsfg_fonts        = rswsfg_get_fonts();

	uasort(
		$rswsfg_fonts,
		function ( $rswsfg_a, $rswsfg_b ) {
			return strcasecmp( $rswsfg_a['name'], $rswsfg_b['name'] );
		}
	);

	foreach ( $rswsfg_fonts as $rswsfg_slug => $rswsfg_font ) {
		$rswsfg_checked = in_array( $rswsfg_slug, $rswsfg_active_slugs, true );

		$rswsfg_alt_span = sprintf(
			'<span style="font-family:\'%1$s\'">%2$s</span>',
			esc_attr( $rswsfg_font['altName'] ),
			esc_html( $rswsfg_font['altName'] )
		);

		$rswsfg_license_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( $rswsfg_licences_url . $rswsfg_font['licenseFile'] ),
			esc_html__( 'license', 'web-safe-fonts-for-editor-by-rotistudio' )
		);

		$rswsfg_alt_info = wp_kses(
			sprintf(
				/* translators: 1: alternative font name with inline style (HTML), 2: license link (HTML) */
				_x( '(Alternative: %1$s &ndash; %2$s)', 'Alternative font info. 1: font name with style, 2: license link', 'web-safe-fonts-for-editor-by-rotistudio' ),
				$rswsfg_alt_span,
				$rswsfg_license_link
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
			esc_attr( $rswsfg_slug ),
			checked( $rswsfg_checked, true, false ),
			esc_attr( $rswsfg_font['fontFamily'] ),
			esc_html( $rswsfg_font['name'] ),
			$rswsfg_alt_info // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized via wp_kses().
		);
	}
}

/**
 * Sanitizes the submitted font slugs array.
 *
 * @param mixed $rswsfg_input Raw submitted value.
 * @return array<int, string> Validated font slugs.
 */
function rswsfg_sanitize_active_fonts( $rswsfg_input ) {
	if ( ! is_array( $rswsfg_input ) ) {
		return array();
	}

	$rswsfg_valid_slugs = array_keys( rswsfg_get_fonts() );

	return array_values( array_intersect( array_filter( $rswsfg_input, 'is_string' ), $rswsfg_valid_slugs ) );
}

/**
 * Adds the Settings link to the plugin action links.
 *
 * @param array<int|string, string> $links Plugin action links.
 * @return array<int|string, string> Plugin action links with the Settings link first.
 */
function rswsfg_add_settings_link( $links ) {
	array_unshift(
		$links,
		sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-writing.php#rswsfg-fonts-section' ) ),
			esc_html__( 'Settings', 'web-safe-fonts-for-editor-by-rotistudio' )
		)
	);

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rswsfg_add_settings_link', 10 );
