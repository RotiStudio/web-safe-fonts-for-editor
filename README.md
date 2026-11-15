<!-- Generated from readme.txt by ~/rotistudio-wp/bin/readme-md. Edit readme.txt, not this file. -->

![Web-Safe Fonts for Editor by RotiStudio](https://ps.w.org/web-safe-fonts-for-editor-by-rotistudio/assets/banner-1544x500.png)

# Web-Safe Fonts for Editor by RotiStudio

> Adds web-safe fonts to the WordPress block editor (Gutenberg), so your website loads faster and looks more consistent.

| | |
|---|---|
| Contributors | rtomo, rotistudio |
| Tags | fonts, typography, gutenberg, block editor, editor |
| Requires at least | 6.5 |
| Tested up to | 7.1 |
| Requires PHP | 7.4 |
| Stable tag | 1.3.0 |
| License | GPLv2 or later |
| License URI | [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html) |
| Donate link | [https://rotistudio.com/contact/](https://rotistudio.com/contact/) |

[Plugin page on wordpress.org](https://wordpress.org/plugins/web-safe-fonts-for-editor-by-rotistudio/)

## Description

Hungarian: [Magyar nyelvű bővítmény leírás](https://rotistudio.hu/bovitmenyek/web-safe-betutipusok-wordpress-szerkesztohoz/)

This plugin adds 15 web-safe font families to the WordPress block editor's typography panel. No Google Fonts, no requests to external servers, no JavaScript font loaders, no CSS imports: just the fonts already installed on your visitors' devices.

Web-safe fonts come pre-installed on common operating systems such as Windows and macOS, so they render instantly without a network request. Your website loads faster, looks consistent across devices and avoids layout shifts caused by font loading.

Plan B included: when a visitor's device does not have a font, the plugin loads a close open-source alternative from your own site, so the design does not change.

**Available fonts:**

*Sans-serif*
- Arial (Alternative: Liberation Sans)
- Verdana (Alternative: DejaVu Sans)
- Tahoma (Alternative: DejaVu Sans)
- Trebuchet MS (Alternative: Cabin Condensed)
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
- No external font service and no requests to other servers
- Fonts render instantly when they are already available on the user's device
- Uses standard system fonts first; the bundled alternative is served from your own site
- Works with every block that supports typography (paragraph, heading, button, etc.)
- No additional privacy, GDPR, or cookie measures required
- Fully compatible with Full Site Editing (FSE) and block themes
- Core Web Vitals friendly - eliminates font-related render blocking and layout shifts
- Lightweight, with no unnecessary features
- Downloads a font file only when the device does not have the font

**How it works**
The plugin registers the font families in the theme.json data layer via the `wp_theme_json_data_theme` filter. This makes them appear alongside your theme's own fonts in the Typography panel of every block that supports font selection. The plugin prints its own `@font-face` rules for the selected fonts: the browser uses the font installed on the device first and downloads the alternative only when it is missing.

Looking for more plugins? See [rotistudio.com](https://rotistudio.com/).
Want to learn more about my work? Visit my personal website: [rottenbacher.hu](https://rottenbacher.hu/).
Development / source code: [GitHub repository](https://github.com/rotisoft/web-safe-fonts-for-editor)

**Bundled fonts and their licenses**
The alternative fonts are bundled as woff2 files in `assets/fonts/`; the full license texts are in `assets/licences/`.

- Liberation Sans, Liberation Serif, Liberation Mono: SIL Open Font License 1.1, [liberationfonts/liberation-fonts](https://github.com/liberationfonts/liberation-fonts)
- DejaVu Sans, DejaVu Sans Mono: Bitstream Vera license, DejaVu changes are in the public domain, [dejavu-fonts.github.io](https://dejavu-fonts.github.io/)
- Cabin Condensed: SIL Open Font License 1.1, [impallari/Cabin](https://github.com/impallari/Cabin)
- Archivo Black: SIL Open Font License 1.1, [Omnibus-Type/ArchivoBlack](https://github.com/Omnibus-Type/ArchivoBlack)
- Oswald: SIL Open Font License 1.1, [googlefonts/OswaldFont](https://github.com/googlefonts/OswaldFont)
- Comic Neue: SIL Open Font License 1.1, [crozynski/comicneue](https://github.com/crozynski/comicneue)
- TeX Gyre Pagella: GUST Font License, [GUST e-foundry](https://www.gust.org.pl/projects/e-foundry/tex-gyre)

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/web-safe-fonts-for-editor-by-rotistudio` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings > Writing (`/wp-admin/options-writing.php`) and select the fonts you want.
4. Open any block in the block editor, go to the Typography panel on the right sidebar, and select a font family from the list.

## Frequently Asked Questions

### Which blocks support font family selection?

Any block that exposes the Typography panel in the block editor supports font selection. This includes Paragraph, Heading, List, Button, and many more - both core blocks and third-party blocks that follow WordPress block standards.

### Will this conflict with my theme's fonts?

No. The plugin adds its fonts alongside your theme's existing font families, not instead of them. Your theme fonts remain fully available.

### Does it work with classic themes and Classic Editor?

No. The font families are registered in the theme.json layer, which classic themes may not fully support. The plugin is primarily designed for block themes and Full Site Editing.

### Is it compatible with Site Editor (Full Site Editing - FSE)?

Yes. The plugin uses the standard `wp_theme_json_data_theme` filter, which is the recommended way to extend typography settings in FSE block themes.

### Why can't I see the fonts?

Go to Settings > Writing, select the desired font checkbox, and save. The font then appears in the Typography panel font list; there is nothing else to activate.
If a font is still missing, open Appearance > Editor > Styles > Typography > Fonts and check that it has not been deactivated there.

### What do the Preload checkboxes do?

The font file of a selected font is requested early, in the page head, so the text is styled sooner. You can turn it on separately for mobile and for desktop devices. Turn it on only for fonts used on most pages; desktop devices usually have these fonts installed, so a preload is rarely needed there. Preloading applies only to fonts that are also selected.

### Can I try the fonts with my own text?

Yes. Type up to 60 characters into the Preview text field on Settings > Writing and every font row shows it. The text is not saved.

### Can I change the font-display value?

Yes. By default the plugin uses `font-display: swap`. Developers can override it with the `rswsfg_font_display` filter (`auto`, `block`, `swap`, `fallback`, or `optional`).

### What does the plugin store in the database?

Only three small options in the `wp_options` table (the table prefix may differ on your site), each a list of font slugs such as `arial`:

* `rswsfg_active_fonts` - the fonts enabled on Settings > Writing.
* `rswsfg_preload_mobile_fonts` - the fonts preloaded on mobile devices.
* `rswsfg_preload_desktop_fonts` - the fonts preloaded on desktop devices.

The plugin creates no tables, post meta or user meta. These options stay in the database when you delete the plugin, so your choices come back if you install it again. To remove them, delete the three options, for example with WP-CLI: `wp option delete rswsfg_active_fonts rswsfg_preload_mobile_fonts rswsfg_preload_desktop_fonts`.

## Screenshots

1. Web-safe fonts listed in the block editor Typography panel.

   ![Web-safe fonts listed in the block editor Typography panel.](https://ps.w.org/web-safe-fonts-for-editor-by-rotistudio/assets/screenshot-1.png)

2. A heading block with a web-safe font applied in the editor.

   ![A heading block with a web-safe font applied in the editor.](https://ps.w.org/web-safe-fonts-for-editor-by-rotistudio/assets/screenshot-2.png)

3. The options to enable the fonts.

   ![The options to enable the fonts.](https://ps.w.org/web-safe-fonts-for-editor-by-rotistudio/assets/screenshot-3.png)

## Upgrade Notice

### 1.3.0

* Fixes theme fonts disappearing and installed fonts being replaced. Preloading is now set per font and is off by default. Requires WordPress 6.5.

### 1.2.0

* font-display swap, developer filter, duplicate font-face fix.
* Tested up to WordPress 7.1.

### 1.1.0

* Minor bug fix.
* Replace FreeSans with Cabin Condensed as the Trebuchet MS alternative.

### 1.0.0

* Initial release.

## Changelog

### 1.3.0

* Fix: the theme's own font families are no longer removed.
* Fix: fonts installed on the device are no longer replaced by downloaded files.
* Fix: Arial Black, Tahoma and MS Sans Serif load the alternative shown on the settings page.
* Add Preload on mobile and Preload on desktop checkboxes per font; preloading is off by default.
* Add a Preview text field to try the fonts with your own text.
* Add a Web-Safe Fonts link to the Appearance > Fonts screen (WordPress 7.0+).
* Show the fonts as a table, with the alternative font and its license link in their own column.
* Security hardening: the settings are sanitized on every save path and translatable texts no longer carry HTML.
* Load the bundled translations on WordPress versions before 6.8.
* Improve the English and Hungarian texts.
* List the bundled fonts and their licenses in the readme.
* Remove unused font files.
* Requires WordPress 6.5.

### 1.2.0

* Use font-display: swap by default.
* Add rswsfg_font_display filter.
* Fix duplicate @font-face output.
* Fix Site Editor font copies replacing installed fonts.
* Tested up to WordPress 7.1.

### 1.1.0

* Minor bug fix.
* Replace FreeSans with Cabin Condensed as the Trebuchet MS alternative.

### 1.0.0

* Initial release. 15 web-safe font families added to the WordPress block editor.

## Support

- RotiStudio.com/support - Official
- WordPress.org Plugin Support forum
