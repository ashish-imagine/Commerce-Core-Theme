<?php
defined('ABSPATH') || exit;


/**
 * Core directories to load
 */

$autoload_dirs = [
    'core',
    'api',
    'modules'
];

foreach($autolaod_dirs as $dir){
    $path = PARENT_PATH . '/' . $dir;

    if(!is_dir($path)){
        continue;
    }

    foreach(glob($path . '/**/*.php') as $file){
        require_once $file;
    }
}