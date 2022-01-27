<?php

function mf_load_styles() {
    wp_register_script( 'load-exchange-company-data', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0', true );

    wp_enqueue_script( 'load-exchange-company-data' );
}
add_action('wp_enqueue_scripts', 'mf_load_styles');


/**
 * Register Custom Post Types
 * 
 * Post types: Stock Recommendations
 *
 * @since 1.0.0
 */
function mfsa_register_post_types() {
    // Recommendations
    $labels = array(
        'name' => 'Stocks',
        'singular_name' => 'Stock',
        'add_new_item' => 'Add New Stock',
        'new_item' => 'New Stock',
        'edit_item' => 'Edit Stock',
        'view_item' => 'View Stock',
        'all_items' => 'All Stocks',
        'search_items' => 'Search Stocks',
        'not_found' => 'No Stocks found.',
        'not_found_in_trash' => 'No Stocks found in Trash.'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Manage Stock Stocks articles',
        'public' => true,
        'publicly_queryable' => true,
        'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'Stocks' ),
        'capability_type' => 'post',
        'menu_position' => 5,
        'menu_icon' => 'dashicons-saved',
        'exclude_from_search' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('category'),
        'has_archive' => true
    );
    register_post_type( 'Stocks', $args );

}
add_action( 'init', 'mfsa_register_post_types' );
