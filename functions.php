<?php

// Load ticker symbol meta box functions
require('includes/ticker-meta-box.php');

// Load custom WP REST API functions
require('includes/custom-rest-endpoints.php');


/**
 * Load Theme CSS and JS files
 *
 * @since 1.0.0
 */
function mfsa_load_styles() {
    wp_enqueue_style( 'mfsa-reset', get_template_directory_uri() . '/assets/css/reset.css', array(), '1.0' );
    wp_enqueue_style( 'mfsa-theme-css', get_template_directory_uri() . '/assets/css/app.css', array('mfsa-reset'), '1.0' );
    wp_register_script( 'mfsa-stock-api', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0', true );

    if (! is_admin() && is_single() && get_post_type(get_the_id()) === 'companies') {
        wp_enqueue_script( 'mfsa-stock-api' );
        wp_add_inline_script( 'mfsa-stock-api', 'const MFSA_DATA = ' . json_encode( 
            array( 
                'site_url' => site_url(),
                'symbol' => get_post_meta(get_the_ID(), '_mfsa_symbol', true),
            )
        ), 'before' );
    }
}
add_action('wp_enqueue_scripts', 'mfsa_load_styles');


/**
 * Adds required theme supports
 *
 * @since 1.0.0
 */
function mfsa_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'mfsa_theme_setup' );


/**
 * Gets theme info from style.css doc block
 *  
 * @param string $request "Ex: Theme Name | Author | Author URI | Version | Etc"
 * 
 * @since 1.0.0
 */
function mfsa_get_theme_info($request) {
    $themeInfo = wp_get_theme();

    return $themeInfo->display($request);
}


/**
 * Trims a paragraph to max number of words
 *  
 * @param string $content The paragraph you want to trim
 * @param int $num_words Max number of words you want to allow. Default: 30
 * 
 * @return string the trimmed paragraph
 * 
 * @since 1.0.0
 */
function mfsa_truncate_content( $content, $num_words = 30 ) {
    $trimPost = preg_replace( '#\[[^\]]+\]#', '', $content );
    $truncatedPost = wp_trim_words( $trimPost, $num_words, '...' );
    $filteredPost = apply_filters( 'mq_filter_content', $truncatedPost );

    return $filteredPost;
}


/**
 * Register Custom Post Types
 * 
 * Post types: Stock Recommendations, Companies
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
 * Gets the permalink for a company profile page
 *  
 * @param string $symbol the company's stock exchange symbol. Ex: SBUX (Starbucks)
 * 
 * @return mixed false | the url of the company page
 * 
 * @since 1.0.0
 */
function get_link_to_company_profile($symbol) {
    $getPosts = get_posts(array(
        'post_type' => 'companies',
        'meta_query' => array(
            'relation' => 'AND',
            'symbol_clause' => array(
                'key' => '_mfsa_symbol',
                'value' => $symbol,
                'compare' => '='
            )
        )
    ));

    if (! $getPosts) return false;

    $companyPageID = $getPosts ? $getPosts[0]->ID : 0;
    $companyPageLink = $companyPageID > 0 ? get_permalink($companyPageID) : '#';

    return $companyPageLink;
}


/**
 * Gets Company Key Stats by their symbol
 * Data provided by call to https://financialmodelingprep.com/ API
 * 
 * IMPORTANT: API KEY must be defined in wp-config.php as MFSA_API_KEY
 *
 * @param string $symbol the company's stock exchange symbol. Ex: SBUX (Starbucks)
 * 
 * @return array
 * 
 * @since 1.0.0
 */
function mfsa_get_company_profile($companySymbol) {
    // Make sure an API key is defined
    if (! defined('MFSA_API_KEY') ) {
        return array(
            'status' => 0, 
            'response' => 'Error: Missing API KEY. You must define an API key in the wp-config file to use this feature. See https://github.com/cuppajoey/motley-fool-challenge for details.'
        );
    };
    
    set_time_limit(0);

    $APIKEY = MFSA_API_KEY;
    $companySymbol = strtoupper($companySymbol);
    $endpoint = "https://financialmodelingprep.com/api/v3/profile/{$companySymbol}?apikey={$APIKEY}";

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
        $response = array(
            'status' => 0,
            'response' => 'Error:' . curl_error($channel)
        );
    } else {
        $response = array(
            'status' => 1,
            'response' => json_decode($output)
        );
    }
    return $response;
}


/**
 * Creates the HTML markup for a company callout box
 *
 * @param int $postID the id of the associated post
 * 
 * @return string the html markup
 * 
 * @since 1.0.0
 */
function mfsa_get_company_callout_box($postID) {
    // Get the symbol assigned to post
    $symbol = get_post_meta($postID, '_mfsa_symbol', true);
    
    // If symbol doesn't exist, bail out
    if (! $symbol) return false;
    
    // Get company stats from API
    $keyStats = mfsa_get_company_profile($symbol);

    if ($keyStats['status'] === 0) {
        error_log( print_r($keyStats['response'], true) );
        return false;
    }

    $keyStats = $keyStats['response'];

    $logoURL = $keyStats[0]->image;
    $companyName = $keyStats[0]->companyName;
    $exchangeShortName = $keyStats[0]->exchangeShortName;
    $description = $keyStats[0]->description;
    $sector = $keyStats[0]->sector;
    $ceo = $keyStats[0]->ceo;
    $website = $keyStats[0]->website;
    $permalink = get_link_to_company_profile($keyStats[0]->symbol);
    
    // Build our markup
    $markup = '<aside class="company-stats">';
        $markup .= '<div class="stats-title">';
            $markup .= '<img src="' .$logoURL. '" />';
            $markup .= '<span class="heading-size-5">' .$companyName. '</span>';
        $markup .= '</div>';
        $markup .= '<p class="stats-description">';
            $markup .= mfsa_truncate_content($description);
            if ($permalink) {
                $markup .= ' <a href="'.$permalink.'">Learn more about ' . $companyName . '</a>';
            }
        $markup .= '</p>';
        $markup .= '<div class="stats-meta">';
            $markup .= '<span><strong>Exchange: </strong>' .$exchangeShortName. '</span>';
            $markup .= '<span><strong>Industry: </strong>' .$sector. '</span>';
            $markup .= '<span><strong>CEO: </strong>' .$ceo. '</span>';
            $markup .= '<span><strong>Website: </strong><a href="' .$website. '">' .$companyName. '</a></span>';
        $markup .= '</div>';
    $markup .= '</aside>';

    return $markup;
}

/**
 * Injects a company stats callout box after the 2nd paragraph of
 * a Stock Recommendation post
 *
 * @param string $postContent the post body
 * 
 * @return string the filtered post body
 * 
 * @since 1.0.0
 */
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


/**
 * Fix pagination issue on a custom post type
 *
 * @link https://core.trac.wordpress.org/ticket/15551
 *
 * @param object $request WP_Query
 *
 * @return object
 */
function mfsa_fix_request_redirect( $request ) {
    if ( isset( $request->query_vars['post_type'] )
         && 'companies' === $request->query_vars['post_type']
         && true === $request->is_singular
         && - 1 == $request->current_post
         && true === $request->is_paged
    ) {
        add_filter( 'redirect_canonical', '__return_false' );
    }

    return $request;
}
add_action( 'parse_query', 'mfsa_fix_request_redirect' );