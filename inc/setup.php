<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function(){
  
    load_theme_domain('commerce-core', THEME_PATH . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);

    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'commerce-core'),
        'footer' => __('Footer Menu', 'commerce-core'),
    ]);
});