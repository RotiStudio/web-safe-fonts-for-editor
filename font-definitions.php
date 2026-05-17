<?php
/**
 * Web-safe font @font-face definitions.
 *
 * Each entry is keyed by the font slug and contains the internal CSS family
 * name and an array of face variants (weight, style, local names, woff2 file).
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the @font-face definitions for each font slug.
 *
 * Each entry contains the internal CSS family name and an array of face
 * variants (weight, style, local names, woff2 filename).
 *
 * @return array<string, array<string, mixed>> Font face definitions keyed by slug.
 */
function rswsfg_get_font_face_definitions() {
	return array(
		'arial'               => array(
			'family' => 'Arial-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Arial', 'ArialMT', 'Liberation Sans' ), 'file' => 'liberationsans-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Arial Bold', 'Arial-BoldMT', 'Liberation Sans Bold' ), 'file' => 'liberationsans-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Arial Italic', 'Arial-ItalicMT', 'Liberation Sans Italic' ), 'file' => 'liberationsans-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Arial Bold Italic', 'Arial-BoldItalicMT', 'Liberation Sans Bold Italic' ), 'file' => 'liberationsans-bolditalic.woff2' ),
			),
		),
		'verdana'             => array(
			'family' => 'Verdana-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Verdana', 'DejaVu Sans' ), 'file' => 'dejavusans-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Verdana Bold', 'DejaVu Sans Bold' ), 'file' => 'dejavusans-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Verdana Italic', 'DejaVu Sans Italic' ), 'file' => 'dejavusans-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Verdana Bold Italic', 'DejaVu Sans Bold Italic' ), 'file' => 'dejavusans-bolditalic.woff2' ),
			),
		),
		'tahoma'              => array(
			'family' => 'Tahoma-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Tahoma', 'Liberation Sans', 'DejaVu Sans' ), 'file' => 'liberationsans-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Tahoma Bold', 'Liberation Sans Bold', 'DejaVu Sans Bold' ), 'file' => 'liberationsans-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Tahoma Italic', 'Liberation Sans Italic', 'DejaVu Sans Italic' ), 'file' => 'liberationsans-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Tahoma Bold Italic', 'Liberation Sans Bold Italic', 'DejaVu Sans Bold Italic' ), 'file' => 'liberationsans-bolditalic.woff2' ),
			),
		),
		'trebuchet-ms'        => array(
			'family' => 'Trebuchet-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Cabin Condensed', 'CabinCondensed', 'CabinCondensed-Regular' ), 'file' => 'CabinCondensed-Regular.woff2' ),
				array( 'weight' => '500', 'style' => 'normal', 'local' => array( 'Cabin Condensed Medium', 'CabinCondensed-Medium' ), 'file' => 'CabinCondensed-Medium.woff2' ),
				array( 'weight' => '600', 'style' => 'normal', 'local' => array( 'Cabin Condensed SemiBold', 'CabinCondensed-SemiBold' ), 'file' => 'CabinCondensed-SemiBold.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Cabin Condensed Bold', 'CabinCondensed-Bold' ), 'file' => 'CabinCondensed-Bold.woff2' ),
			),
		),
		'arial-black'         => array(
			'family' => 'Arial-Black-safe',
			'faces'  => array(
				array( 'weight' => '900', 'style' => 'normal', 'local' => array( 'Arial Black', 'Arial-Black', 'Liberation Sans Bold', 'Archivo Black' ), 'file' => 'liberationsans-bold.woff2' ),
			),
		),
		'lucida-sans-unicode' => array(
			'family' => 'Lucida-Sans-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Lucida Sans Unicode', 'Lucida Grande', 'DejaVu Sans' ), 'file' => 'dejavusans-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Lucida Sans Unicode Bold', 'Lucida Grande Bold', 'DejaVu Sans Bold' ), 'file' => 'dejavusans-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Lucida Sans Unicode Italic', 'Lucida Grande Italic', 'DejaVu Sans Italic' ), 'file' => 'dejavusans-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Lucida Sans Unicode Bold Italic', 'Lucida Grande Bold Italic', 'DejaVu Sans Bold Italic' ), 'file' => 'dejavusans-bolditalic.woff2' ),
			),
		),
		'ms-sans-serif'       => array(
			'family' => 'MS-Sans-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'MS Sans Serif', 'Microsoft Sans Serif', 'Liberation Sans', 'DejaVu Sans' ), 'file' => 'liberationsans-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'MS Sans Serif Bold', 'Microsoft Sans Serif Bold', 'Liberation Sans Bold', 'DejaVu Sans Bold' ), 'file' => 'liberationsans-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'MS Sans Serif Italic', 'Liberation Sans Italic', 'DejaVu Sans Italic' ), 'file' => 'liberationsans-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'MS Sans Serif Bold Italic', 'Liberation Sans Bold Italic', 'DejaVu Sans Bold Italic' ), 'file' => 'liberationsans-bolditalic.woff2' ),
			),
		),
		'times-new-roman'     => array(
			'family' => 'Times-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Times New Roman', 'TimesNewRomanPSMT', 'Liberation Serif' ), 'file' => 'liberationserif-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Times New Roman Bold', 'TimesNewRomanPS-BoldMT', 'Liberation Serif Bold' ), 'file' => 'liberationserif-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Times New Roman Italic', 'TimesNewRomanPS-ItalicMT', 'Liberation Serif Italic' ), 'file' => 'liberationserif-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Times New Roman Bold Italic', 'TimesNewRomanPS-BoldItalicMT', 'Liberation Serif Bold Italic' ), 'file' => 'liberationserif-bolditalic.woff2' ),
			),
		),
		'georgia'             => array(
			'family' => 'Georgia-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Georgia', 'Georgia-Regular', 'Liberation Serif' ), 'file' => 'liberationserif-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Georgia Bold', 'Georgia-Bold', 'Liberation Serif Bold' ), 'file' => 'liberationserif-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Georgia Italic', 'Georgia-Italic', 'Liberation Serif Italic' ), 'file' => 'liberationserif-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Georgia Bold Italic', 'Georgia-BoldItalic', 'Liberation Serif Bold Italic' ), 'file' => 'liberationserif-bolditalic.woff2' ),
			),
		),
		'palatino-linotype'   => array(
			'family' => 'Palatino-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Palatino Linotype', 'Palatino-Roman', 'TeX Gyre Pagella' ), 'file' => 'texgyrepagella-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Palatino Linotype Bold', 'Palatino-Bold', 'TeX Gyre Pagella Bold' ), 'file' => 'texgyrepagella-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Palatino Linotype Italic', 'Palatino-Italic', 'TeX Gyre Pagella Italic' ), 'file' => 'texgyrepagella-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Palatino Linotype Bold Italic', 'Palatino-BoldItalic', 'TeX Gyre Pagella Bold Italic' ), 'file' => 'texgyrepagella-bolditalic.woff2' ),
			),
		),
		'book-antiqua'        => array(
			'family' => 'Book-Antiqua-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Book Antiqua', 'BookAntiqua', 'Palatino', 'TeX Gyre Pagella' ), 'file' => 'texgyrepagella-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Book Antiqua Bold', 'BookAntiqua-Bold', 'Palatino Bold', 'TeX Gyre Pagella Bold' ), 'file' => 'texgyrepagella-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Book Antiqua Italic', 'BookAntiqua-Italic', 'Palatino Italic', 'TeX Gyre Pagella Italic' ), 'file' => 'texgyrepagella-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Book Antiqua Bold Italic', 'BookAntiqua-BoldItalic', 'Palatino Bold Italic', 'TeX Gyre Pagella Bold Italic' ), 'file' => 'texgyrepagella-bolditalic.woff2' ),
			),
		),
		'courier-new'         => array(
			'family' => 'Courier-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Courier New', 'CourierNewPSMT', 'Liberation Mono' ), 'file' => 'liberationmono-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Courier New Bold', 'CourierNewPS-BoldMT', 'Liberation Mono Bold' ), 'file' => 'liberationmono-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Courier New Italic', 'CourierNewPS-ItalicMT', 'Liberation Mono Italic' ), 'file' => 'liberationmono-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Courier New Bold Italic', 'CourierNewPS-BoldItalicMT', 'Liberation Mono Bold Italic' ), 'file' => 'liberationmono-bolditalic.woff2' ),
			),
		),
		'lucida-console'      => array(
			'family' => 'Lucida-Console-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Lucida Console', 'Monaco', 'DejaVu Sans Mono' ), 'file' => 'dejavusansmono-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Lucida Console Bold', 'DejaVu Sans Mono Bold' ), 'file' => 'dejavusansmono-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Lucida Console Italic', 'DejaVu Sans Mono Oblique' ), 'file' => 'dejavusansmono-oblique.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Lucida Console Bold Italic', 'DejaVu Sans Mono Bold Oblique' ), 'file' => 'dejavusansmono-boldoblique.woff2' ),
			),
		),
		'comic-sans-ms'       => array(
			'family' => 'Comic-Sans-safe',
			'faces'  => array(
				array( 'weight' => '400', 'style' => 'normal', 'local' => array( 'Comic Sans MS', 'ComicSansMS', 'Comic Neue' ), 'file' => 'comicneue-regular.woff2' ),
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Comic Sans MS Bold', 'ComicSansMS-Bold', 'Comic Neue Bold' ), 'file' => 'comicneue-bold.woff2' ),
				array( 'weight' => '400', 'style' => 'italic', 'local' => array( 'Comic Sans MS Italic', 'Comic Neue Italic' ), 'file' => 'comicneue-italic.woff2' ),
				array( 'weight' => '700', 'style' => 'italic', 'local' => array( 'Comic Sans MS Bold Italic', 'Comic Neue Bold Italic' ), 'file' => 'comicneue-bolditalic.woff2' ),
			),
		),
		'impact'              => array(
			'family' => 'Impact-safe',
			'faces'  => array(
				array( 'weight' => '700', 'style' => 'normal', 'local' => array( 'Impact', 'Impact Bold', 'Oswald Bold', 'Oswald' ), 'file' => 'oswald-bold.woff2' ),
			),
		),
	);
}
