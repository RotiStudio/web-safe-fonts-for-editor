<?php
/**
 * Web-safe font family definitions.
 *
 * Each entry contains the CSS font-family stack, the display name, the slug,
 * the open-source alternative name (altName) and the licence filename (licenseFile)
 * located in assets/licences/.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the list of web-safe font families.
 *
 * @return array<int, array<string, string>> Font family definitions.
 */
function rswsfg_get_font_families() {
	return array(
		array(
			'fontFamily'  => 'Arial, "Arial-safe", Helvetica, sans-serif',
			'name'        => 'Arial',
			'slug'        => 'arial',
			'altName'     => 'Liberation Sans',
			'licenseFile' => 'liberation-LICENSE.txt',
		),
		array(
			'fontFamily'  => 'Verdana, "Verdana-safe", Geneva, Tahoma, sans-serif',
			'name'        => 'Verdana',
			'slug'        => 'verdana',
			'altName'     => 'DejaVu Sans',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
		),
		array(
			'fontFamily'  => 'Tahoma, "Tahoma-safe", "Segoe UI", Verdana, Arial, sans-serif',
			'name'        => 'Tahoma',
			'slug'        => 'tahoma',
			'altName'     => 'DejaVu Sans',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Trebuchet MS", "Trebuchet-safe", Helvetica, sans-serif',
			'name'        => 'Trebuchet MS',
			'slug'        => 'trebuchet-ms',
			'altName'     => 'Cabin Condensed',
			'licenseFile' => 'cabin-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Arial Black", "Arial-Black-safe", Gadget, sans-serif',
			'name'        => 'Arial Black',
			'slug'        => 'arial-black',
			'altName'     => 'Archivo Black',
			'licenseFile' => 'archivo-black-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Lucida Sans Unicode", "Lucida Grande", "Lucida-Sans-safe", sans-serif',
			'name'        => 'Lucida Sans Unicode',
			'slug'        => 'lucida-sans-unicode',
			'altName'     => 'DejaVu Sans',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"MS Sans Serif", "Microsoft Sans Serif", Geneva, "MS-Sans-safe", sans-serif',
			'name'        => 'MS Sans Serif',
			'slug'        => 'ms-sans-serif',
			'altName'     => 'DejaVu Sans',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Times New Roman", Times, "Times-safe", serif',
			'name'        => 'Times New Roman',
			'slug'        => 'times-new-roman',
			'altName'     => 'Liberation Serif',
			'licenseFile' => 'liberation-LICENSE.txt',
		),
		array(
			'fontFamily'  => 'Georgia, "Georgia-safe", "Times New Roman", Times, serif',
			'name'        => 'Georgia',
			'slug'        => 'georgia',
			'altName'     => 'Liberation Serif',
			'licenseFile' => 'liberation-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Palatino Linotype", "Book Antiqua", Palatino, "Palatino-safe", serif',
			'name'        => 'Palatino Linotype',
			'slug'        => 'palatino-linotype',
			'altName'     => 'TeX Gyre Pagella',
			'licenseFile' => 'texgyrepagella-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Book Antiqua", Palatino, "Palatino Linotype", "Book-Antiqua-safe", serif',
			'name'        => 'Book Antiqua',
			'slug'        => 'book-antiqua',
			'altName'     => 'TeX Gyre Pagella',
			'licenseFile' => 'texgyrepagella-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Courier New", Courier, "Courier-safe", monospace',
			'name'        => 'Courier New',
			'slug'        => 'courier-new',
			'altName'     => 'Liberation Mono',
			'licenseFile' => 'liberation-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Lucida Console", "Lucida-Console-safe", Monaco, monospace',
			'name'        => 'Lucida Console',
			'slug'        => 'lucida-console',
			'altName'     => 'DejaVu Sans Mono',
			'licenseFile' => 'dejavu-sans-LICENSE.txt',
		),
		array(
			'fontFamily'  => '"Comic Sans MS", "Comic Sans", "Comic-Sans-safe", cursive',
			'name'        => 'Comic Sans MS',
			'slug'        => 'comic-sans-ms',
			'altName'     => 'Comic Neue',
			'licenseFile' => 'comicneue-LICENSE.txt',
		),
		array(
			'fontFamily'  => 'Impact, "Impact-safe", Charcoal, sans-serif',
			'name'        => 'Impact',
			'slug'        => 'impact',
			'altName'     => 'Oswald',
			'licenseFile' => 'oswald-LICENSE.txt',
		),
	);
}
