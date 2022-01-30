<?php

require('ticker-meta-box.php');


function mfsa_load_styles() {
    wp_enqueue_style( 'mfsa-reset', get_template_directory_uri() . '/assets/css/reset.css', array(), '1.0' );
    wp_enqueue_style( 'mfsa-theme-css', get_template_directory_uri() . '/assets/css/app.css', array('mfsa-reset'), '1.0' );
    wp_register_script( 'load-exchange-company-data', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0', true );

    wp_enqueue_script( 'load-exchange-company-data' );
}
add_action('wp_enqueue_scripts', 'mfsa_load_styles');


function mfsa_theme_setup() {
    // Add required theme supports
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'mfsa_theme_setup' );


function mfsa_get_theme_info($request) {
    $themeInfo = wp_get_theme();

    return $themeInfo->display($request);
}


function mfsa_truncate_content( $content, $num_words = 30 ) {
    $trimPost = preg_replace( '#\[[^\]]+\]#', '', $content );
    $truncatedPost = wp_trim_words( $trimPost, $num_words, '...' );
    $filteredPost = apply_filters( 'mq_filter_content', $truncatedPost );

    return $filteredPost;
}

function mfsa_filter_excerpt_more_link( $more ) {
    return ' <a href="'.get_the_permalink().'" rel="nofollow">Continue Reading...</a>';
}
add_filter( 'excerpt_more', 'mfsa_filter_excerpt_more_link' );


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
        'rewrite' => array( 'slug' => 'stocks' ),
        'capability_type' => 'post',
        'menu_position' => 5,
        'menu_icon' => 'dashicons-saved',
        'exclude_from_search' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('category'),
        'has_archive' => true
    );
    register_post_type( 'stocks', $args );
    
    // Company
    $labels = array(
        'name' => 'Companies',
        'singular_name' => 'Company',
        'add_new_item' => 'Add New Company',
        'new_item' => 'New Company',
        'edit_item' => 'Edit Company',
        'view_item' => 'View Company',
        'all_items' => 'All Companies',
        'search_items' => 'Search Companies',
        'not_found' => 'No Companies found.',
        'not_found_in_trash' => 'No Companies found in Trash.'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Manage Company articles',
        'public' => true,
        'publicly_queryable' => true,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'menu_position' => 5,
        'menu_icon' => 'dashicons-saved',
        'exclude_from_search' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('category'),
        'has_archive' => true
    );
    register_post_type( 'companies', $args );

}
add_action( 'init', 'mfsa_register_post_types' );

/**
 * Include Stock Recommendations post type in main query on homepage
 * This allows CPTs to share template files (index, single, etc) with main posts
 *
 * @since 1.0.0
 */
function mfsa_include_stock_cpt_on_homepage( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_home() ) {
            $query->set( 'post_type', array( 'post', 'stocks' ) );
        }
    }
    return $query;
}
add_action( 'pre_get_posts', 'mfsa_include_stock_cpt_on_homepage' );



/**
 * Register Menus
 *
 * @since 1.0.0
 */
register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu' ),
    )
);


/**
 * Get Company Financial Quote
 * Data provided by call to https://financialmodelingprep.com/ API
 *
 * @since 1.0.0
 */
function mfsa_get_company_quote($companySymbol) {
    set_time_limit(0);

    $endpoint = "https://financialmodelingprep.com/api/v3/quote/{$companySymbol}?apikey=e2471ff0e15771593da05997639bf01d";

    $channel = curl_init();

    curl_setopt($channel, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($channel, CURLOPT_HEADER, 0);
    curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($channel, CURLOPT_URL, $endpoint);
    curl_setopt($channel, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($channel, CURLOPT_TIMEOUT, 0);
    curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);

    $output = curl_exec($channel);

    if (curl_error($channel)) {
        return 'error:' . curl_error($channel);
    } else {
        $jsonResults = json_decode($output);
        return $jsonResults;
    }
}


/**
 * Get Company Key Stats
 * Data provided by call to https://financialmodelingprep.com/ API
 *
 * @since 1.0.0
 */
function mfsa_get_company_stats($companySymbol) {
    set_time_limit(0);

    $endpoint = "https://financialmodelingprep.com/api/v3/profile/{$companySymbol}?apikey=e2471ff0e15771593da05997639bf01d";

    $channel = curl_init();

    curl_setopt($channel, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($channel, CURLOPT_HEADER, 0);
    curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($channel, CURLOPT_URL, $endpoint);
    curl_setopt($channel, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($channel, CURLOPT_TIMEOUT, 0);
    curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);

    $output = curl_exec($channel);

    if (curl_error($channel)) {
        return 'error:' . curl_error($channel);
    } else {
        $jsonResults = json_decode($output);
        return $jsonResults;
    }
}

function mfsa_get_company_callout_box($postID) {
    // Get the symbol assigned to post
    $symbol = get_post_meta($postID, '_mfsa_symbol', true);
    
    // If symbol doesn't exist, bail out
    if (! $symbol) return false;
    
    // Get company stats from API
    $keyStats = mfsa_get_company_stats($symbol);

    $logoURL = $keyStats[0]->image;
    $companyName = $keyStats[0]->companyName;
    $exchangeShortName = $keyStats[0]->exchangeShortName;
    $description = $keyStats[0]->description;
    $sector = $keyStats[0]->sector;
    $website = $keyStats[0]->website;
    
    // Build our markup
    $markup = '<aside class="company-stats">';
        $markup .= '<div class="stats-title">';
            $markup .= '<img src="' .$logoURL. '" />';
            $markup .= '<span class="heading-size-5">' .$companyName. '</span>';
        $markup .= '</div>';
        $markup .= '<p class="stats-description">';
            $markup .= mfsa_truncate_content($description);
        $markup .= '</p>';
        $markup .= '<div class="stats-meta">';
            $markup .= '<span><strong>Exchange: </strong>' .$exchangeShortName. '</span>';
            $markup .= '<span><strong>Industry: </strong>' .$sector. '</span>';
            $markup .= '<span><strong>Website: </strong><a href="' .$website. '">' .$companyName. '</a></span>';
        $markup .= '</div>';
    $markup .= '</aside>';

    return $markup;
}

function mfsa_inject_company_stats_into_post($postContent) {
    if ( is_single() && get_post_type() === 'stocks' ) {
        // Get the 2nd instance of a closing paragraph tag
        $getPos = strpos($postContent, '</p>', strpos($postContent, '</p>') + 1);

        // Get the callout box markup
        $getCalloutMarkup = mfsa_get_company_callout_box( get_the_ID() );

        // If our markup function fails, return content as normal
        if (! $getCalloutMarkup ) return $postContent;

        $newContent = substr_replace($postContent, $getCalloutMarkup, $getPos, 0);
        $postContent = $newContent;
    }
    return $postContent;
}
add_filter( 'the_content', 'mfsa_inject_company_stats_into_post' );