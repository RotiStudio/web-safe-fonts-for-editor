<?php
/**
 * Plugin Name: Web-Safe Fonts for Editor by RotiStudio
 * Plugin URI: https://rotistudio.com/plugins/web-safe-fonts-plugin-wordpress/
 * Description: Adds web-safe fonts to the WordPress block editor (Gutenberg), so your website loads faster and looks more consistent.
 * Version: 1.3.1
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: RotiStudio - Tamas Rottenbacher
 * Author URI: https://rotistudio.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: web-safe-fonts-for-editor-by-rotistudio
 * Domain Path: /languages
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RSWSFG_VERSION', '1.3.1' );
define( 'RSWSFG_PREVIEW_MAX_LENGTH', 60 );
define( 'RSWSFG_PATH', plugin_dir_path( __FILE__ ) );
define( 'RSWSFG_URL', plugin_dir_url( __FILE__ ) );

require_once RSWSFG_PATH . 'font-definitions.php';

/**
 * Loads the bundled translations on WordPress versions before 6.8.
 *
 * Since 6.8 core loads the translations from the Domain Path by itself.
 *
 * @return void
 */
function rswsfg_load_textdomain() {
	if ( version_compare( get_bloginfo( 'version' ), '6.8', '<' ) ) {
		// phpcs:ignore PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound -- Needed below WordPress 6.8, which does not load the bundled translations by itself.
		load_plugin_textdomain( 'web-safe-fonts-for-editor-by-rotistudio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}
add_action( 'init', 'rswsfg_load_textdomain', 10 );

/**
 * Returns the font slugs saved in one of the plugin's options.
 *
 * @param string $rswsfg_option Option name.
 * @return array<int, string> Saved font slugs, empty until the option is saved.
 */
function rswsfg_get_saved_font_slugs( $rswsfg_option ) {
	$rswsfg_saved = get_option( $rswsfg_option, array() );

	if ( ! is_array( $rswsfg_saved ) ) {
		return array();
	}

	return array_values( array_filter( $rswsfg_saved, 'is_string' ) );
}

/**
 * Returns the active font slugs saved in options.
 *
 * @return array<int, string> Active font slugs.
 */
function rswsfg_get_active_font_slugs() {
	return rswsfg_get_saved_font_slugs( 'rswsfg_active_fonts' );
}

/**
 * Returns the slugs of the fonts marked for preloading on a device type.
 *
 * @param string $rswsfg_device Device type: mobile or desktop.
 * @return array<int, string> Font slugs to preload.
 */
function rswsfg_get_preload_font_slugs( $rswsfg_device ) {
	return rswsfg_get_saved_font_slugs( 'mobile' === $rswsfg_device ? 'rswsfg_preload_mobile_fonts' : 'rswsfg_preload_desktop_fonts' );
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
 * Adds the active web-safe font families to the theme's own font families.
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
 * Removes the font faces hosted by this plugin from a list of font families.
 *
 * @param array $rswsfg_families      Font families (theme.json shape).
 * @param bool  $rswsfg_keep_families Whether a family left without faces is kept.
 * @param bool  $rswsfg_changed       Set to true when a face was removed.
 * @return array Font families without the plugin-hosted faces.
 */
function rswsfg_remove_plugin_font_faces( $rswsfg_families, $rswsfg_keep_families, &$rswsfg_changed ) {
	$rswsfg_new_families = array();

	foreach ( $rswsfg_families as $rswsfg_family ) {
		if ( empty( $rswsfg_family['fontFace'] ) || ! is_array( $rswsfg_family['fontFace'] ) ) {
			$rswsfg_new_families[] = $rswsfg_family;
			continue;
		}

		$rswsfg_new_faces = array();

		foreach ( $rswsfg_family['fontFace'] as $rswsfg_face ) {
			if ( ! rswsfg_font_face_src_is_plugin( $rswsfg_face ) ) {
				$rswsfg_new_faces[] = $rswsfg_face;
			}
		}

		if ( count( $rswsfg_new_faces ) === count( $rswsfg_family['fontFace'] ) ) {
			$rswsfg_new_families[] = $rswsfg_family;
			continue;
		}

		$rswsfg_changed = true;

		if ( ! empty( $rswsfg_new_faces ) ) {
			$rswsfg_family['fontFace'] = $rswsfg_new_faces;
		} elseif ( $rswsfg_keep_families ) {
			unset( $rswsfg_family['fontFace'] );
		} else {
			continue;
		}

		$rswsfg_new_families[] = $rswsfg_family;
	}

	return $rswsfg_new_families;
}

/**
 * Removes plugin-hosted font faces from user global styles at runtime.
 *
 * Earlier versions registered their faces in theme.json, and activating such
 * a font in the Site Editor Font Library copied the faces into the user
 * theme.json. WordPress prints those copies under the real font name, which
 * replaces the installed font. The faces come from
 * rswsfg_enqueue_font_faces() instead.
 *
 * The user data keeps the activated theme fonts under the "theme" key and the
 * installed fonts under "custom". A family under "theme" stays in the list
 * without its faces, because removing it there would deactivate the font.
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
	$rswsfg_changed       = false;

	if ( isset( $rswsfg_families_root[0] ) ) {
		$rswsfg_new_root = rswsfg_remove_plugin_font_faces( $rswsfg_families_root, false, $rswsfg_changed );
	} else {
		$rswsfg_new_root = array();

		foreach ( array( 'theme', 'custom' ) as $rswsfg_origin ) {
			if ( isset( $rswsfg_families_root[ $rswsfg_origin ] ) && is_array( $rswsfg_families_root[ $rswsfg_origin ] ) ) {
				$rswsfg_new_root[ $rswsfg_origin ] = rswsfg_remove_plugin_font_faces(
					$rswsfg_families_root[ $rswsfg_origin ],
					'theme' === $rswsfg_origin,
					$rswsfg_changed
				);
			}
		}
	}

	if ( ! $rswsfg_changed ) {
		return $theme_json;
	}

	return $theme_json->update_with(
		array(
			'version'  => isset( $rswsfg_data['version'] ) ? $rswsfg_data['version'] : 2,
			'settings' => array(
				'typography' => array(
					'fontFamilies' => $rswsfg_new_root,
				),
			),
		)
	);
}
add_filter( 'wp_theme_json_data_user', 'rswsfg_filter_user_theme_json_fonts', 20 );

/**
 * Builds the @font-face rules of the given fonts.
 *
 * Every rule uses the font's own "-safe" family name, which the font-family
 * stack lists after the real font name. The browser therefore uses the
 * installed web-safe font first, then a local() match of the same font or its
 * alternative, and downloads the woff2 file only when none of them exists.
 *
 * @param array<string, array<string, mixed>> $rswsfg_fonts Font definitions.
 * @return string CSS, empty when the list is empty.
 */
function rswsfg_get_font_face_css( $rswsfg_fonts ) {
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
	$rswsfg_css = rswsfg_get_font_face_css( rswsfg_get_active_fonts() );

	if ( '' === $rswsfg_css ) {
		return;
	}

	wp_register_style( 'rswsfg-fonts', false, array(), RSWSFG_VERSION );
	wp_enqueue_style( 'rswsfg-fonts' );
	wp_add_inline_style( 'rswsfg-fonts', $rswsfg_css );
}
add_action( 'enqueue_block_assets', 'rswsfg_enqueue_font_faces', 20 );

/**
 * Returns the woff2 file to preload for a font.
 *
 * @param array<string, mixed> $rswsfg_font Font definition.
 * @return string File name of the regular face, or of the first face when
 *                the font has no regular one (Arial Black, Impact).
 */
function rswsfg_get_preload_file( $rswsfg_font ) {
	foreach ( $rswsfg_font['faces'] as $rswsfg_face ) {
		if ( '400' === $rswsfg_face['weight'] && 'normal' === $rswsfg_face['style'] ) {
			return $rswsfg_face['file'];
		}
	}

	return $rswsfg_font['faces'][0]['file'];
}

/**
 * Outputs <link rel="preload"> tags for the fonts that are both active and
 * marked for preloading on the visitor's device type on the settings page.
 *
 * The device type comes from wp_is_mobile(). Mobile browsers (Android in
 * particular) typically lack web-safe system fonts, so preloading the fallback
 * woff2 reduces the time-to-first-render of styled text. Desktop systems
 * (Windows, macOS) usually have these fonts installed, so a preload there
 * downloads a file the browser may not need.
 *
 * One file per font is preloaded, see rswsfg_get_preload_file(). A woff2 file
 * shared by several fonts is output once.
 *
 * @return void
 */
function rswsfg_output_font_preloads() {
	$rswsfg_fonts = array_intersect_key(
		rswsfg_get_active_fonts(),
		array_flip( rswsfg_get_preload_font_slugs( wp_is_mobile() ? 'mobile' : 'desktop' ) )
	);

	$rswsfg_base_url = RSWSFG_URL . 'assets/fonts/';
	$rswsfg_files    = array_unique( array_map( 'rswsfg_get_preload_file', $rswsfg_fonts ) );

	foreach ( $rswsfg_files as $rswsfg_file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $rswsfg_base_url . $rswsfg_file )
		);
	}
}
add_action( 'wp_head', 'rswsfg_output_font_preloads', 2 );

/**
 * Enqueues the preview CSS and script on the Settings > Writing admin page.
 *
 * The CSS holds the @font-face rules of every font, so each row is shown as
 * it will render for visitors, one rule per alternative font, so its name can
 * be shown in its own typeface, and the layout of the font table.
 *
 * @return void
 */
function rswsfg_enqueue_admin_assets() {
	$rswsfg_screen = get_current_screen();

	if ( ! $rswsfg_screen || 'options-writing' !== $rswsfg_screen->id ) {
		return;
	}

	$rswsfg_fonts     = rswsfg_get_fonts();
	$rswsfg_base_url  = RSWSFG_URL . 'assets/fonts/';
	$rswsfg_display   = rswsfg_get_font_display();
	$rswsfg_alt_rules = array();

	foreach ( $rswsfg_fonts as $rswsfg_font ) {
		// Several fonts share an alternative; the key keeps one rule per alternative.
		$rswsfg_alt_rules[ $rswsfg_font['altName'] ] = sprintf(
			'@font-face{font-family:"%1$s";font-style:normal;font-weight:400;font-display:%3$s;src:local("%1$s"),url("%2$s") format("woff2");}',
			$rswsfg_font['altName'],
			esc_url_raw( $rswsfg_base_url . $rswsfg_font['altFile'] ),
			$rswsfg_display
		);
	}

	$rswsfg_css_parts = array(
		rswsfg_get_font_face_css( $rswsfg_fonts ),
		implode( "\n", $rswsfg_alt_rules ),
		'.rswsfg-fonts{max-width:1100px;margin-top:8px;}',
		'.rswsfg-fonts td,.rswsfg-fonts th{vertical-align:middle;}',
		'.rswsfg-fonts .rswsfg-font-name{font-size:16px;white-space:nowrap;}',
		'.rswsfg-fonts .rswsfg-font-name span{padding-left:6px;}',
		'.rswsfg-fonts .rswsfg-font-preview{font-size:16px;overflow-wrap:anywhere;}',
		'.rswsfg-fonts .rswsfg-font-preload{text-align:center;}',
	);

	wp_add_inline_style( 'wp-admin', implode( "\n", $rswsfg_css_parts ) );

	// Copies the preview text into every font row while the user types.
	$rswsfg_script = '( function () {'
		. 'var input = document.getElementById( "rswsfg-preview-text" );'
		. 'if ( ! input ) { return; }'
		. 'var targets = document.querySelectorAll( ".rswsfg-font-preview" );'
		. 'input.addEventListener( "input", function () {'
		. 'var text = input.value.slice( 0, input.maxLength );'
		. 'targets.forEach( function ( target ) { target.textContent = text; } );'
		. '} );'
		. '} )();';

	wp_register_script( 'rswsfg-admin', false, array(), RSWSFG_VERSION, true );
	wp_enqueue_script( 'rswsfg-admin' );
	wp_add_inline_script( 'rswsfg-admin', $rswsfg_script );
}
add_action( 'admin_enqueue_scripts', 'rswsfg_enqueue_admin_assets', 20 );

/**
 * Adds a "Web-Safe Fonts" link after the tabs of the Appearance > Fonts screen.
 *
 * The screen (WordPress 7.0+) builds its tabs in JavaScript and has no
 * extension point, so a small script appends the link to the rendered tab
 * list. When the markup of the screen changes, the link is simply not added.
 * The script loads in the head, right after its translated label.
 *
 * @param string $hook_suffix Current admin page.
 * @return void
 */
function rswsfg_enqueue_font_library_link( $hook_suffix ) {
	if ( 'font-library.php' !== $hook_suffix || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$rswsfg_data = array(
		'url'   => admin_url( 'options-writing.php#rswsfg-fonts-section' ),
		'label' => __( 'Web-Safe Fonts', 'web-safe-fonts-for-editor-by-rotistudio' ),
	);

	$rswsfg_script = '( function () {'
		. 'var data = window.rswsfgFontLibrary;'
		. 'function addLink() {'
		. 'if ( document.getElementById( "rswsfg-font-library-link" ) ) { return; }'
		. 'var list = document.querySelector( ".font-library-page__tablist [role=\'tablist\']" );'
		. 'if ( ! list ) { return; }'
		. 'var tab = list.querySelector( "[role=\'tab\']:not([aria-selected=\'true\'])" ) || list.querySelector( "[role=\'tab\']" );'
		. 'var link = document.createElement( "a" );'
		. 'link.id = "rswsfg-font-library-link";'
		. 'link.href = data.url;'
		. 'link.textContent = data.label;'
		. 'if ( tab ) { link.className = tab.className; }'
		. 'link.style.textDecoration = "none";'
		. 'list.appendChild( link );'
		. '}'
		. 'addLink();'
		. 'new MutationObserver( addLink ).observe( document.documentElement, { childList: true, subtree: true } );'
		. '} )();';

	wp_register_script( 'rswsfg-font-library', false, array(), RSWSFG_VERSION, false );
	wp_enqueue_script( 'rswsfg-font-library' );
	wp_add_inline_script( 'rswsfg-font-library', 'var rswsfgFontLibrary = ' . wp_json_encode( $rswsfg_data, JSON_HEX_TAG | JSON_HEX_AMP ) . ';', 'before' );
	wp_add_inline_script( 'rswsfg-font-library', $rswsfg_script );
}
add_action( 'admin_enqueue_scripts', 'rswsfg_enqueue_font_library_link', 20 );

/**
 * Registers the settings of the Writing settings page.
 *
 * Runs on init, not only in the admin, so the sanitize callback also applies
 * to values saved outside the settings page (WP-CLI, other code).
 *
 * @return void
 */
function rswsfg_register_settings() {
	foreach ( array( 'rswsfg_active_fonts', 'rswsfg_preload_mobile_fonts', 'rswsfg_preload_desktop_fonts' ) as $rswsfg_option ) {
		register_setting(
			'writing',
			$rswsfg_option,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'rswsfg_sanitize_font_slugs',
				'default'           => array(),
			)
		);
	}
}
add_action( 'init', 'rswsfg_register_settings', 10 );

/**
 * Adds the plugin's section to the Writing settings page.
 *
 * @return void
 */
function rswsfg_add_settings_section() {
	add_settings_section(
		'rswsfg_fonts_section',
		__( 'Web-Safe Fonts', 'web-safe-fonts-for-editor-by-rotistudio' ),
		'rswsfg_section_callback',
		'writing'
	);
}
add_action( 'admin_init', 'rswsfg_add_settings_section', 10 );

/**
 * Renders the section description, the preview text field and the font rows.
 *
 * The preview field has no name attribute, so its value is not submitted.
 *
 * @return void
 */
function rswsfg_section_callback() {
	printf(
		'<p id="rswsfg-fonts-section">%s</p>',
		esc_html__( 'Select which web-safe fonts should be available in the WordPress block editor.', 'web-safe-fonts-for-editor-by-rotistudio' )
	);

	printf(
		'<p class="description">%s</p>',
		esc_html__( 'Preload: the font file of a selected font is requested early, in the page head, on the chosen device type. Turn it on only for fonts used on most pages. Desktop devices usually have these fonts installed, so a preload is rarely needed there.', 'web-safe-fonts-for-editor-by-rotistudio' )
	);

	printf(
		'<p><label for="rswsfg-preview-text"><strong>%1$s</strong></label><br>'
		. '<input type="text" id="rswsfg-preview-text" class="regular-text" maxlength="%2$d" autocomplete="off" aria-describedby="rswsfg-preview-text-description"> '
		. '<span class="description" id="rswsfg-preview-text-description">%3$s</span></p>',
		esc_html__( 'Preview text', 'web-safe-fonts-for-editor-by-rotistudio' ),
		(int) RSWSFG_PREVIEW_MAX_LENGTH,
		esc_html(
			sprintf(
				/* translators: %d: maximum number of characters. */
				__( 'Type up to %d characters to see them in every font below. The text is not saved.', 'web-safe-fonts-for-editor-by-rotistudio' ),
				(int) RSWSFG_PREVIEW_MAX_LENGTH
			)
		)
	);

	rswsfg_render_font_rows();
}

/**
 * Renders the font table. One row per font: the font checkbox, the preview
 * text, the alternative font with its license and the two preload checkboxes.
 *
 * @return void
 */
function rswsfg_render_font_rows() {
	$rswsfg_active_slugs  = rswsfg_get_active_font_slugs();
	$rswsfg_mobile_slugs  = rswsfg_get_preload_font_slugs( 'mobile' );
	$rswsfg_desktop_slugs = rswsfg_get_preload_font_slugs( 'desktop' );
	$rswsfg_licenses_url  = RSWSFG_URL . 'assets/licences/';
	$rswsfg_fonts         = rswsfg_get_fonts();
	$rswsfg_mobile_label  = __( 'Preload on mobile', 'web-safe-fonts-for-editor-by-rotistudio' );
	$rswsfg_desktop_label = __( 'Preload on desktop', 'web-safe-fonts-for-editor-by-rotistudio' );

	uasort(
		$rswsfg_fonts,
		function ( $rswsfg_a, $rswsfg_b ) {
			return strcasecmp( $rswsfg_a['name'], $rswsfg_b['name'] );
		}
	);

	printf(
		'<table class="widefat striped rswsfg-fonts"><thead><tr>'
		. '<th scope="col">%1$s</th><th scope="col">%2$s</th><th scope="col">%3$s</th>'
		. '<th scope="col" class="rswsfg-font-preload">%4$s</th><th scope="col" class="rswsfg-font-preload">%5$s</th>'
		. '</tr></thead><tbody>',
		esc_html__( 'Font', 'web-safe-fonts-for-editor-by-rotistudio' ),
		esc_html__( 'Preview', 'web-safe-fonts-for-editor-by-rotistudio' ),
		esc_html__( 'Alternative font', 'web-safe-fonts-for-editor-by-rotistudio' ),
		esc_html( $rswsfg_mobile_label ),
		esc_html( $rswsfg_desktop_label )
	);

	foreach ( $rswsfg_fonts as $rswsfg_slug => $rswsfg_font ) {
		printf(
			'<tr>'
			. '<td><label class="rswsfg-font-name"><input type="checkbox" name="rswsfg_active_fonts[]" value="%1$s"%2$s><span style="font-family:%3$s">%4$s</span></label></td>'
			. '<td class="rswsfg-font-preview" style="font-family:%3$s"></td>'
			. '<td><span style="font-family:\'%5$s\'">%6$s</span> &ndash; <a href="%7$s" target="_blank" rel="noopener noreferrer">%8$s</a></td>'
			. '<td class="rswsfg-font-preload"><input type="checkbox" name="rswsfg_preload_mobile_fonts[]" value="%1$s" aria-label="%9$s"%10$s></td>'
			. '<td class="rswsfg-font-preload"><input type="checkbox" name="rswsfg_preload_desktop_fonts[]" value="%1$s" aria-label="%11$s"%12$s></td>'
			. '</tr>',
			esc_attr( $rswsfg_slug ),
			checked( in_array( $rswsfg_slug, $rswsfg_active_slugs, true ), true, false ),
			esc_attr( $rswsfg_font['fontFamily'] ),
			esc_html( $rswsfg_font['name'] ),
			esc_attr( $rswsfg_font['altName'] ),
			esc_html( $rswsfg_font['altName'] ),
			esc_url( $rswsfg_licenses_url . $rswsfg_font['licenseFile'] ),
			esc_html_x( 'license', 'link to the license text of the alternative font', 'web-safe-fonts-for-editor-by-rotistudio' ),
			/* translators: 1: preload column label, 2: font name. */
			esc_attr( sprintf( _x( '%1$s: %2$s', 'preload checkbox label', 'web-safe-fonts-for-editor-by-rotistudio' ), $rswsfg_mobile_label, $rswsfg_font['name'] ) ),
			checked( in_array( $rswsfg_slug, $rswsfg_mobile_slugs, true ), true, false ),
			/* translators: 1: preload column label, 2: font name. */
			esc_attr( sprintf( _x( '%1$s: %2$s', 'preload checkbox label', 'web-safe-fonts-for-editor-by-rotistudio' ), $rswsfg_desktop_label, $rswsfg_font['name'] ) ),
			checked( in_array( $rswsfg_slug, $rswsfg_desktop_slugs, true ), true, false )
		);
	}

	echo '</tbody></table>';
}

/**
 * Sanitizes the submitted font slugs array.
 *
 * @param mixed $rswsfg_input Raw submitted value.
 * @return array<int, string> Validated font slugs.
 */
function rswsfg_sanitize_font_slugs( $rswsfg_input ) {
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
