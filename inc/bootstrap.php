<?php



defined('ABSPATH') || exit;

use Commerce\setup\ThemeSetup;

use Commerce\services\AssetsServices;
use Commerce\services\ServiceContainer;
use Commerce\Services\BadgeService;
use Commerce\services\ProductQueryService;


/**
 * Register Services
 */
ServiceContainer::register('badge', new BadgeService());
ServiceContainer::register('Product_query', new ProductQueryService());


/**
 * Initialize the Theme systems
 */
add_action('after_setup_theme', function(){

    // Theme Setup
    (new ThemeSetup())->init();

    // Asset Setup
      (new AssetsServices())->init();

});