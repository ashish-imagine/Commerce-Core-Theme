<?php

namespace Commerce\services;

use WP_Query;
use WC_Product;

class ProductQueryService{

    protected string $cache_group = 'commerce_products';
    protected int $cache_ttl = 300; //5min
/**
 * Get the Latest products
 */
public function latest(array $args = []): array{

    $defaults = [
        'limit' => 8,
        'paged' => 1,
    ];
    
    $args = wp_parse_args($args, $defaults);

    $cache_key = cacheKey('latest', $args);

    return $this->getCachedProducts($cache_key, function() use($args) {
        
        return $this->runQuery([
           'post_per_page' => $args['limit'],
           'paged' => $args['paged'],
        ]);
    });
}


/**
 * Get Products by category
 */

public function byCategory(string $slug, array $args = [] ): array{

    $defaults = [
        'limit' => 8,
        'paged' => 1,
    ];

    $args = wp_parse_args($args, $defaults);

    $cache_key = cacheKey('category_' . $slug, $args);

     return $this->getCachedProducts($cache_key, function() use($args, $slug){

         return $this->runQuery([
           'post_per_page' => $args['limit'],
           'paged' => $args['paged'],
           'tax_query' => [
             [
                 'taxonomy' => 'product_cat',
                 'field' => 'slug',
                 'terms' => $slug,
             ],
            ],
         ]);

     });

}


/**
 * Get Featured Products
 */
public function featured(array $args = []): array{

    $defaults = [
        'limit' => 8,
    ];

    $args = wp_parse_args($args, $defaults);

    $cache_key = cacheKey('featured', $args);


    return $this->getCachedProducts($cache_key, function() use ($args){

        return $this->runQuery([
            'post_per_page' => $args['limit'],
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => ['featured'],
                ]
            ]
        ]);
    });
}

/**
 * Core Query Runner
 */

protected function runQuery(array $queryArgs ): array{

    $query = WP_Query(array_merge([
       'post_type' => 'product',
       'post_status' => 'publish',
       'no_found_rows' => true,
    ], $queryArgs));

    if(!$query->have_post()){
        return [];
    }

    return array_map(
        fn($post)=> wc_get_product($post->ID), $query->posts
    );
}

/**
 * Cache Wrapper
 */
protected function getCachedProducts(string $key, callable $callback ): array{

       $cached = wp_cache_get($key, $this->cache_group);

       if($cached !== false){
        return $this->hydrateProducts($cached);
       }

       $product_ids = $callback;

       wp_cache_set(
        $key,
        $product_ids,
        $this->cache_group,
        $this->cache_ttl
       );

      return $this->hydrateProducts($product_ids);

}


/**
 * Convert IDs to WC_Product objects
 */
protected function hydrateProducts(array $ids): array{
    return array_values(array_filter(array_map('wp_get_product', $ids)));
}

/**
 * Generate Cache Key
 */

protected function cacheKey(string $prefix, array $args): string{
    return 'pq_' . $prefix . '_' . md5(wp_json_encode($args));
}


}