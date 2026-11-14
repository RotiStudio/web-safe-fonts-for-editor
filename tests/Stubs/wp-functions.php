<?php
/**
 * WordPress functions the main plugin file calls while it loads.
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

/**
 * Stub of plugin_dir_path().
 *
 * @param string $file Plugin file.
 * @return string Directory path with a trailing slash.
 */
function plugin_dir_path( $file ) {
	return dirname( $file ) . '/';
}

/**
 * Stub of plugin_dir_url().
 *
 * @param string $file Plugin file.
 * @return string Directory URL with a trailing slash.
 */
function plugin_dir_url( $file ) {
	return RSWSFG_TESTS_PLUGIN_URL;
}

/**
 * Stub of plugin_basename().
 *
 * @param string $file Plugin file.
 * @return string Folder and file name.
 */
function plugin_basename( $file ) {
	return basename( dirname( $file ) ) . '/' . basename( $file );
}
