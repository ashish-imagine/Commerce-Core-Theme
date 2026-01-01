<?php

namespace Commerce\services;

use WP_Query;
use WC_Product;

class ProductQueryService{

/**
 * Get the Latest products
 */
public function latest(array $args = []): array{

    $defaults = [
        'limit' => 8,
        'paged' => 1,
    ];
    
    $args = wp_parse_args($args, $defaults);

    return $this->runQuery([
       'post_per_page' => $args['limit'],
       'paged' => $args['paged'],
    ]);

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

    return $this->runQuery([
      'post_per_page' => $args['limit'],
      'paged' => $args['paged'],
      'tax_query' => [
        [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $slug,
        ]
      ]
    ]);
}


/**
 * Get Featured Products
 */
public function featured(array $args = []): array{

    $defaults = [
        'limit' => 8,
    ];

    $args = wp_parse_args($args, $defaults);

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


}