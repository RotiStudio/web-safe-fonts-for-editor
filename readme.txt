=== Web-Safe Fonts for Editor by RotiStudio ===
Contributors: rtomo, rotistudio
Tags: fonts, typography, gutenberg, block editor, editor
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://rotistudio.com/contact/

Add the option to choose web safe fonts in the WordPress block editor (Gutenberg), so your website can be faster and more consistent appearance.

== Description ==

Hungarian: [Magyar nyelvű bővítmény leírás](https://rotistudio.hu/bovitmenyek/web-safe-betutipusok-wordpress-szerkesztohoz/)

This plugin adds 15 web safe font families to the WordPress block editor's typography panel. No Google Fonts, no external requests, no JavaScript font loaders, CSS import - just the fonts that are already installed on your visitors' devices.

Web safe fonts are pre-installed on Windows, Linux operating systems, which means they render instantly without any network request. Your website loads faster, looks consistent across devices, and avoids layout shifts caused by font loading.

We have a Plan B! When a visitor uses a different operating system or device, the plugin loads the same or an alternative font, so the design won’t change.

**Available fonts:**

*Sans-serif*
- Arial (Alternative: Liberation Sans)
- Verdana (Alternative: DejaVu Sans)
- Tahoma (Alternative: DejaVu Sans)
- Trebuchet MS (Alternative: CabinCondensed)
- Arial Black (Alternative: Archivo Black)
- Lucida Sans Unicode (Alternative: DejaVu Sans)
- MS Sans Serif (Alternative: DejaVu Sans)
- Impact (Alternative: Oswald)

*Serif*
- Times New Roman (Alternative: Liberation Serif)
- Georgia (Alternative: Liberation Serif)
- Palatino Linotype (Alternative: TeX Gyre Pagella)
- Book Antiqua (Alternative: TeX Gyre Pagella)

*Monospace*
- Courier New (Alternative: Liberation Mono)
- Lucida Console (Alternative: DejaVu Sans Mono)

*Cursive*
- Comic Sans MS (Alternative: Comic Neue)

**Why is this plugin beneficial?**
- No external font files or network requests
- Fonts load instantly - already available on the user’s device
- Uses standard, pre-installed system fonts (no Android, iOS, or macOS dependencies)
- Works with every block that supports typography (paragraph, heading, button, etc.)
- No additional privacy, GDPR, or cookie measures required
- Fully compatible with Full Site Editing (FSE) and block themes
- Core Web Vitals friendly - eliminates font-related render blocking and layout shifts
- Lightweight, with no overengineered or unnecessary features
- Downloads a font only when necessary

**How it works**
The plugin registers the font families in the theme.json data layer via the `wp_theme_json_data_theme` filter. This makes them appear alongside your theme's own fonts in the Typography panel of every block that supports font selection. The plugin prints its own `@font-face` rules for the selected fonts: the browser uses the font installed on the device first and downloads the alternative only when it is missing.

Do you have other plugins? Yes, check my plugins website: [rotistudio.com](https://rotistudio.com/)
Where can we learn more about your work? Check my personal website there: [rottenbacher.hu](https://rottenbacher.hu/)
Development / source code: [GitHub repository](https://github.com/rotisoft/web-safe-fonts-for-editor)

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/web-safe-fonts-for-editor-by-rotistudio` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Dashboard/Settings/Writing `/wp-admin/options-writing.php`
4. Open any block in the block editor, go to the Typography panel on the right sidebar, and select a font family from the list.

== Frequently Asked Questions ==

= Which blocks support font family selection? =

Any block that exposes the Typography panel in the block editor supports font selection. This includes Paragraph, Heading, List, Button, and many more - both core blocks and third-party blocks that follow WordPress block standards.

= Will this conflict with my theme's fonts? =

No. The plugin adds its fonts alongside your theme's existing font families, not instead of them. Your theme fonts remain fully available.

= Does it work with classic themes and Classic Editor? =

Sad, but no. The font families are registered in the theme.json layer, which classic themes may not fully support. The plugin is primarily designed for block themes and Full Site Editing.

= Is it compatible with Site Editor (Full Site Editing - FSE)? =

Yes. The plugin uses the standard `wp_theme_json_data_theme` filter, which is the recommended way to extend typography settings in FSE block themes.

= Why can’t I see the fonts? =

Go to Dashboard / Settings / Writing, select the desired font checkbox, and save. The font then appears in the Typography panel font list, there is nothing else to activate.
If a font is still missing, open Dashboard / Appearance / Editor / Styles / Typography / Fonts and check that it has not been deactivated there.

= What do the Preload checkboxes do? =

The font file of a selected font is requested early, in the page head, so the text is styled sooner. You can turn it on separately for mobile and for desktop devices. Turn it on only for fonts used on most pages; desktop devices usually have these fonts installed, so a preload is rarely needed there. It works only for fonts that are selected too.

= Can I try the fonts with my own text? =

Yes. Type up to 60 characters into the Preview text field on Dashboard / Settings / Writing and every font row shows it. The text is not saved.

= Can I change the font-display value? =

Yes. By default the plugin uses `font-display: swap`. Developers can override it with the `rswsfg_font_display` filter (`auto`, `block`, `swap`, `fallback`, or `optional`).

== Screenshots ==

1. Web safe fonts listed in the block editor Typography panel.
2. A header block with a web safe font applied in the editor.
3. The options to enable the fonts.

== Upgrade Notice ==

= 1.3.0 =
* Fixes the theme's own fonts disappearing and installed fonts being replaced. Requires WordPress 6.5.

= 1.2.0 =
* font-display swap, developer filter, duplicate font-face fix.
* Tested up to WordPress 7.1.

= 1.1.0 =
* Minor bug fix.
* Replace FreeSans to CabinCondensed for Trebuchet MS alternative.

= 1.0.0 =
* Initial release.

== Changelog ==

= 1.3.0 =
* Fix: the theme's own font families are no longer removed.
* Fix: fonts installed on the device are no longer replaced by downloaded files.
* Fix: Arial Black, Tahoma and MS Sans Serif load the alternative shown on the settings page.
* Add Preload on mobile and Preload on desktop checkboxes per font; preloading is off by default.
* Add a Web Safe Fonts link to the Appearance / Fonts screen (WordPress 7.0+).
* Add a Preview text field to try the fonts with your own text.
* Remove unused font files.
* Requires WordPress 6.5.

= 1.2.0 =
* Use font-display: swap by default.
* Add rswsfg_font_display filter.
* Fix duplicate @font-face output.
* Fix Site Editor copies forcing fallback.
* Tested up to WordPress 7.1.

= 1.1.0 =
* Minor bug fix.
* Replace FreeSans to CabinCondensed for Trebuchet MS alternative.

= 1.0.0 =
* Initial release. 15 web safe font families added to the WordPress block editor.
