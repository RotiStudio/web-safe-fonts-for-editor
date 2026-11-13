<?php
/**
 * Web-safe font definitions.
 *
 * The single source of the font data. Each entry is keyed by the font slug:
 * - name: display name of the web-safe font.
 * - fontFamily: CSS font-family stack; it contains the safeFamily name.
 * - safeFamily: family name of the plugin's own @font-face rules.
 * - altName: open-source alternative used when the font is not installed.
 * - altFile: regular woff2 file of the alternative, for the admin preview.
 * - licenseFile: licence file of the alternative in assets/licences/.
 * - faces: face variants (weight, style, local font names, woff2 file).
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the web-safe font definitions.
 *
 * @return array<string, array<string, mixed>> Font definitions keyed by slug.
 */
function rswsfg_get_fonts() {
	return array(
		'arial'               => array(
			'name'        => 'Arial',
			'fontFamily'  => 'Arial, "Arial-safe", Helvetica, sans-serif',
			'safeFamily'  => 'Arial-safe',
			'altName'     => 'Liberation Sans',
			'altFile'     => 'liberationsans-regular.woff2',
			'licenseFile' => 'liberation-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Arial', 'ArialMT', 'Liberation Sans' ),
					'file'   => 'liberationsans-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Arial Bold', 'Arial-BoldMT', 'Liberation Sans Bold' ),
					'file'   => 'liberationsans-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Arial Italic', 'Arial-ItalicMT', 'Liberation Sans Italic' ),
					'file'   => 'liberationsans-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Arial Bold Italic', 'Arial-BoldItalicMT', 'Liberation Sans Bold Italic' ),
					'file'   => 'liberationsans-bolditalic.woff2',
				),
			),
		),
		'verdana'             => array(
			'name'        => 'Verdana',
			'fontFamily'  => 'Verdana, "Verdana-safe", Geneva, Tahoma, sans-serif',
			'safeFamily'  => 'Verdana-safe',
			'altName'     => 'DejaVu Sans',
			'altFile'     => 'dejavusans-regular.woff2',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Verdana', 'DejaVu Sans' ),
					'file'   => 'dejavusans-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Verdana Bold', 'DejaVu Sans Bold' ),
					'file'   => 'dejavusans-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Verdana Italic', 'DejaVu Sans Italic' ),
					'file'   => 'dejavusans-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Verdana Bold Italic', 'DejaVu Sans Bold Italic' ),
					'file'   => 'dejavusans-bolditalic.woff2',
				),
			),
		),
		'tahoma'              => array(
			'name'        => 'Tahoma',
			'fontFamily'  => 'Tahoma, "Tahoma-safe", "Segoe UI", Verdana, Arial, sans-serif',
			'safeFamily'  => 'Tahoma-safe',
			'altName'     => 'DejaVu Sans',
			'altFile'     => 'dejavusans-regular.woff2',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Tahoma', 'DejaVu Sans' ),
					'file'   => 'dejavusans-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Tahoma Bold', 'DejaVu Sans Bold' ),
					'file'   => 'dejavusans-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Tahoma Italic', 'DejaVu Sans Italic' ),
					'file'   => 'dejavusans-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Tahoma Bold Italic', 'DejaVu Sans Bold Italic' ),
					'file'   => 'dejavusans-bolditalic.woff2',
				),
			),
		),
		'trebuchet-ms'        => array(
			'name'        => 'Trebuchet MS',
			'fontFamily'  => '"Trebuchet MS", "Trebuchet-safe", Helvetica, sans-serif',
			'safeFamily'  => 'Trebuchet-safe',
			'altName'     => 'Cabin Condensed',
			'altFile'     => 'CabinCondensed-Regular.woff2',
			'licenseFile' => 'cabin-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Cabin Condensed', 'CabinCondensed', 'CabinCondensed-Regular' ),
					'file'   => 'CabinCondensed-Regular.woff2',
				),
				array(
					'weight' => '500',
					'style'  => 'normal',
					'local'  => array( 'Cabin Condensed Medium', 'CabinCondensed-Medium' ),
					'file'   => 'CabinCondensed-Medium.woff2',
				),
				array(
					'weight' => '600',
					'style'  => 'normal',
					'local'  => array( 'Cabin Condensed SemiBold', 'CabinCondensed-SemiBold' ),
					'file'   => 'CabinCondensed-SemiBold.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Cabin Condensed Bold', 'CabinCondensed-Bold' ),
					'file'   => 'CabinCondensed-Bold.woff2',
				),
			),
		),
		'arial-black'         => array(
			'name'        => 'Arial Black',
			'fontFamily'  => '"Arial Black", "Arial-Black-safe", Gadget, sans-serif',
			'safeFamily'  => 'Arial-Black-safe',
			'altName'     => 'Archivo Black',
			'altFile'     => 'ArchivoBlack-Regular.woff2',
			'licenseFile' => 'archivo-black-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '900',
					'style'  => 'normal',
					'local'  => array( 'Arial Black', 'Arial-Black', 'Archivo Black', 'ArchivoBlack-Regular' ),
					'file'   => 'ArchivoBlack-Regular.woff2',
				),
			),
		),
		'lucida-sans-unicode' => array(
			'name'        => 'Lucida Sans Unicode',
			'fontFamily'  => '"Lucida Sans Unicode", "Lucida Grande", "Lucida-Sans-safe", sans-serif',
			'safeFamily'  => 'Lucida-Sans-safe',
			'altName'     => 'DejaVu Sans',
			'altFile'     => 'dejavusans-regular.woff2',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Lucida Sans Unicode', 'Lucida Grande', 'DejaVu Sans' ),
					'file'   => 'dejavusans-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Lucida Sans Unicode Bold', 'Lucida Grande Bold', 'DejaVu Sans Bold' ),
					'file'   => 'dejavusans-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Lucida Sans Unicode Italic', 'Lucida Grande Italic', 'DejaVu Sans Italic' ),
					'file'   => 'dejavusans-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Lucida Sans Unicode Bold Italic', 'Lucida Grande Bold Italic', 'DejaVu Sans Bold Italic' ),
					'file'   => 'dejavusans-bolditalic.woff2',
				),
			),
		),
		'ms-sans-serif'       => array(
			'name'        => 'MS Sans Serif',
			'fontFamily'  => '"MS Sans Serif", "Microsoft Sans Serif", Geneva, "MS-Sans-safe", sans-serif',
			'safeFamily'  => 'MS-Sans-safe',
			'altName'     => 'DejaVu Sans',
			'altFile'     => 'dejavusans-regular.woff2',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'MS Sans Serif', 'Microsoft Sans Serif', 'DejaVu Sans' ),
					'file'   => 'dejavusans-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'MS Sans Serif Bold', 'Microsoft Sans Serif Bold', 'DejaVu Sans Bold' ),
					'file'   => 'dejavusans-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'MS Sans Serif Italic', 'DejaVu Sans Italic' ),
					'file'   => 'dejavusans-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'MS Sans Serif Bold Italic', 'DejaVu Sans Bold Italic' ),
					'file'   => 'dejavusans-bolditalic.woff2',
				),
			),
		),
		'times-new-roman'     => array(
			'name'        => 'Times New Roman',
			'fontFamily'  => '"Times New Roman", Times, "Times-safe", serif',
			'safeFamily'  => 'Times-safe',
			'altName'     => 'Liberation Serif',
			'altFile'     => 'liberationserif-regular.woff2',
			'licenseFile' => 'liberation-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Times New Roman', 'TimesNewRomanPSMT', 'Liberation Serif' ),
					'file'   => 'liberationserif-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Times New Roman Bold', 'TimesNewRomanPS-BoldMT', 'Liberation Serif Bold' ),
					'file'   => 'liberationserif-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Times New Roman Italic', 'TimesNewRomanPS-ItalicMT', 'Liberation Serif Italic' ),
					'file'   => 'liberationserif-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Times New Roman Bold Italic', 'TimesNewRomanPS-BoldItalicMT', 'Liberation Serif Bold Italic' ),
					'file'   => 'liberationserif-bolditalic.woff2',
				),
			),
		),
		'georgia'             => array(
			'name'        => 'Georgia',
			'fontFamily'  => 'Georgia, "Georgia-safe", "Times New Roman", Times, serif',
			'safeFamily'  => 'Georgia-safe',
			'altName'     => 'Liberation Serif',
			'altFile'     => 'liberationserif-regular.woff2',
			'licenseFile' => 'liberation-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Georgia', 'Georgia-Regular', 'Liberation Serif' ),
					'file'   => 'liberationserif-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Georgia Bold', 'Georgia-Bold', 'Liberation Serif Bold' ),
					'file'   => 'liberationserif-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Georgia Italic', 'Georgia-Italic', 'Liberation Serif Italic' ),
					'file'   => 'liberationserif-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Georgia Bold Italic', 'Georgia-BoldItalic', 'Liberation Serif Bold Italic' ),
					'file'   => 'liberationserif-bolditalic.woff2',
				),
			),
		),
		'palatino-linotype'   => array(
			'name'        => 'Palatino Linotype',
			'fontFamily'  => '"Palatino Linotype", "Book Antiqua", Palatino, "Palatino-safe", serif',
			'safeFamily'  => 'Palatino-safe',
			'altName'     => 'TeX Gyre Pagella',
			'altFile'     => 'texgyrepagella-regular.woff2',
			'licenseFile' => 'texgyrepagella-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Palatino Linotype', 'Palatino-Roman', 'TeX Gyre Pagella' ),
					'file'   => 'texgyrepagella-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Palatino Linotype Bold', 'Palatino-Bold', 'TeX Gyre Pagella Bold' ),
					'file'   => 'texgyrepagella-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Palatino Linotype Italic', 'Palatino-Italic', 'TeX Gyre Pagella Italic' ),
					'file'   => 'texgyrepagella-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Palatino Linotype Bold Italic', 'Palatino-BoldItalic', 'TeX Gyre Pagella Bold Italic' ),
					'file'   => 'texgyrepagella-bolditalic.woff2',
				),
			),
		),
		'book-antiqua'        => array(
			'name'        => 'Book Antiqua',
			'fontFamily'  => '"Book Antiqua", Palatino, "Palatino Linotype", "Book-Antiqua-safe", serif',
			'safeFamily'  => 'Book-Antiqua-safe',
			'altName'     => 'TeX Gyre Pagella',
			'altFile'     => 'texgyrepagella-regular.woff2',
			'licenseFile' => 'texgyrepagella-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Book Antiqua', 'BookAntiqua', 'Palatino', 'TeX Gyre Pagella' ),
					'file'   => 'texgyrepagella-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Book Antiqua Bold', 'BookAntiqua-Bold', 'Palatino Bold', 'TeX Gyre Pagella Bold' ),
					'file'   => 'texgyrepagella-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Book Antiqua Italic', 'BookAntiqua-Italic', 'Palatino Italic', 'TeX Gyre Pagella Italic' ),
					'file'   => 'texgyrepagella-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Book Antiqua Bold Italic', 'BookAntiqua-BoldItalic', 'Palatino Bold Italic', 'TeX Gyre Pagella Bold Italic' ),
					'file'   => 'texgyrepagella-bolditalic.woff2',
				),
			),
		),
		'courier-new'         => array(
			'name'        => 'Courier New',
			'fontFamily'  => '"Courier New", Courier, "Courier-safe", monospace',
			'safeFamily'  => 'Courier-safe',
			'altName'     => 'Liberation Mono',
			'altFile'     => 'liberationmono-regular.woff2',
			'licenseFile' => 'liberation-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Courier New', 'CourierNewPSMT', 'Liberation Mono' ),
					'file'   => 'liberationmono-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Courier New Bold', 'CourierNewPS-BoldMT', 'Liberation Mono Bold' ),
					'file'   => 'liberationmono-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Courier New Italic', 'CourierNewPS-ItalicMT', 'Liberation Mono Italic' ),
					'file'   => 'liberationmono-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Courier New Bold Italic', 'CourierNewPS-BoldItalicMT', 'Liberation Mono Bold Italic' ),
					'file'   => 'liberationmono-bolditalic.woff2',
				),
			),
		),
		'lucida-console'      => array(
			'name'        => 'Lucida Console',
			'fontFamily'  => '"Lucida Console", "Lucida-Console-safe", Monaco, monospace',
			'safeFamily'  => 'Lucida-Console-safe',
			'altName'     => 'DejaVu Sans Mono',
			'altFile'     => 'dejavusansmono-regular.woff2',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Lucida Console', 'Monaco', 'DejaVu Sans Mono' ),
					'file'   => 'dejavusansmono-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Lucida Console Bold', 'DejaVu Sans Mono Bold' ),
					'file'   => 'dejavusansmono-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Lucida Console Italic', 'DejaVu Sans Mono Oblique' ),
					'file'   => 'dejavusansmono-oblique.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Lucida Console Bold Italic', 'DejaVu Sans Mono Bold Oblique' ),
					'file'   => 'dejavusansmono-boldoblique.woff2',
				),
			),
		),
		'comic-sans-ms'       => array(
			'name'        => 'Comic Sans MS',
			'fontFamily'  => '"Comic Sans MS", "Comic Sans", "Comic-Sans-safe", cursive',
			'safeFamily'  => 'Comic-Sans-safe',
			'altName'     => 'Comic Neue',
			'altFile'     => 'comicneue-regular.woff2',
			'licenseFile' => 'comicneue-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '400',
					'style'  => 'normal',
					'local'  => array( 'Comic Sans MS', 'ComicSansMS', 'Comic Neue' ),
					'file'   => 'comicneue-regular.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Comic Sans MS Bold', 'ComicSansMS-Bold', 'Comic Neue Bold' ),
					'file'   => 'comicneue-bold.woff2',
				),
				array(
					'weight' => '400',
					'style'  => 'italic',
					'local'  => array( 'Comic Sans MS Italic', 'Comic Neue Italic' ),
					'file'   => 'comicneue-italic.woff2',
				),
				array(
					'weight' => '700',
					'style'  => 'italic',
					'local'  => array( 'Comic Sans MS Bold Italic', 'Comic Neue Bold Italic' ),
					'file'   => 'comicneue-bolditalic.woff2',
				),
			),
		),
		'impact'              => array(
			'name'        => 'Impact',
			'fontFamily'  => 'Impact, "Impact-safe", Charcoal, sans-serif',
			'safeFamily'  => 'Impact-safe',
			'altName'     => 'Oswald',
			'altFile'     => 'oswald-regular.woff2',
			'licenseFile' => 'oswald-LICENSE.txt',
			'faces'       => array(
				array(
					'weight' => '700',
					'style'  => 'normal',
					'local'  => array( 'Impact', 'Impact Bold', 'Oswald Bold', 'Oswald' ),
					'file'   => 'oswald-bold.woff2',
				),
			),
		),
	);
}
