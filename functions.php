<?php

defined('ABSPATH') || exit;

/**
 * Theme Constants
 */
define('THEME_VERSION', '1.0');
define('PARENT_PATH', get_template_directory());
define('PARENT_URL', get_template_directory_uri());

define('THEME_PATH', get_stylesheet_directory());
define('THEME_URL', get_stylesheet_directory_uri());

// Auto load core files
$core_files = [
    'inc/helpers.php',
    'inc/assets.php',
    'inc/autoload.php',
    'inc/setup.php',
    ];

foreach ($core_files as $file) {
    locate_template($file, true);
}
