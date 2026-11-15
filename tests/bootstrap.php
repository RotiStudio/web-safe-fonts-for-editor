<?php
/**
 * PHPUnit bootstrap.
 *
 * The unit tests run without WordPress: Brain Monkey provides the hook API and
 * each test stubs the WordPress functions it needs (see tests/Unit/TestCase.php).
 *
 * @package Web_Safe_Fonts_for_Editor_by_RotiStudio
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'RSWSFG_TESTS_PLUGIN_DIR', dirname( __DIR__ ) . '/' );
define( 'RSWSFG_TESTS_PLUGIN_URL', 'https://example.test/wp-content/plugins/' . basename( dirname( __DIR__ ) ) . '/' );

require_once RSWSFG_TESTS_PLUGIN_DIR . 'vendor/autoload.php';
require_once RSWSFG_TESTS_PLUGIN_DIR . 'vendor/brain/monkey/inc/patchwork-loader.php';
require_once RSWSFG_TESTS_PLUGIN_DIR . 'vendor/brain/monkey/inc/wp-hook-functions.php';
require_once RSWSFG_TESTS_PLUGIN_DIR . 'vendor/brain/monkey/inc/wp-helper-functions.php';
require_once __DIR__ . '/Stubs/wp-functions.php';
require_once __DIR__ . '/Stubs/class-wp-theme-json-data.php';

require_once RSWSFG_TESTS_PLUGIN_DIR . 'web-safe-fonts-for-editor-by-rotistudio.php';
