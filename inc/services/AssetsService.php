<?php

namespace Commerce\services;

class AssetsServices{

public function init ():void{
    add_action('wp_enqueue_scripts', [$this, 'registerAssets'] );

}

public function registerAssets():void{
    wp_enqueue_style(
        'commerce-core',
        PARENT_URL . '/assets/css/main.css',
        [],
        THEME_VERSION
    );

    if(PARENT_PATH !== THEME_PATH){
        wp_enqueue_style(
            'commerce-child',
            THEME_URL . '/style.css',
            ['commerce-core'],
            THEME_VERSION

        );
    }
}

}