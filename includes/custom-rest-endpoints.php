<?php 
/**
 * This file registers custom endpoints on the WP Rest API.
 * These endpoints are used by our javascript to get and display
 * stock exchange data about companies on Stock recommendation posts
 * and Company posts.
 *
 * @since 1.0.0
 */


/**
 * Gets latest stock quote for a company by their symbol
 * Data provided by call to https://financialmodelingprep.com/ API
 *
 * @param array $request request parameters
 * 
 * @return object
 * 
 * @since 1.0.0
 */
function mfsa_rest_get_company_quote($request) {
    set_time_limit(0);

    $companySymbol = strtoupper($request['symbol']);
    $endpoint = "https://financialmodelingprep.com/api/v3/quote/{$companySymbol}?apikey=c476529e78fd5983209d711074671601";

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
        return new WP_REST_Response( [
			'message' => 'Company data not found',
		], 400 );
    } else {
        return new WP_REST_Response( json_decode($output), 200 );
    }
}


/**
 * Gets Company Key Stats by their symbol
 * Data provided by call to https://financialmodelingprep.com/ API
 *
 * @param array $request request parameters
 * 
 * @return object
 * 
 * @since 1.0.0
 */
function mfsa_rest_get_company_profile($request) {
    set_time_limit(0);

    $companySymbol = strtoupper($request['symbol']);
    $endpoint = "https://financialmodelingprep.com/api/v3/profile/{$companySymbol}?apikey=c476529e78fd5983209d711074671601";

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
        return new WP_REST_Response( [
			'message' => 'Company data not found',
		], 400 );
    } else {
        return new WP_REST_Response( json_decode($output), 200 );
    }
}


/**
 * Registers the endpoint a stock quote
 * 
 * Hooks into rest_api_init action to add a URL endpoint. 
 * This endpoint calls mfsa_rest_get_company_quote() to return the latest stock quote.
 * 
 */
function mfsa_register_company_quote_endpoint() {
	
	// Visit /wp-json/mfsa/v1/quote to get the latest quote
	register_rest_route( 'mfsa/v1', '/quote', array(
		'methods' => 'GET',
		'callback' =>  'mfsa_rest_get_company_quote',
		'permission_callback' => '__return_true',
        'args'     => [
			'symbol' => [
				'required' => true,
				'type'     => 'string',
			],
		],
    ) );
	
}
add_action( 'rest_api_init', 'mfsa_register_company_quote_endpoint' );


/**
 * Registers the endpoint for a company exchange profile
 * 
 * Hooks into rest_api_init action to add a URL endpoint. 
 * This endpoint calls mfsa_rest_get_company_profile() to return the latest stock quote.
 * 
 */
function mfsa_register_company_profile_endpoint() {
	
	// Visit /wp-json/mfsa/v1/quote to get the latest quote
	register_rest_route( 'mfsa/v1', '/profile', array(
		'methods' => 'GET',
		'callback' =>  'mfsa_rest_get_company_profile',
		'permission_callback' => '__return_true',
        'args'     => [
			'symbol' => [
				'required' => true,
				'type'     => 'string',
			],
		],
    ) );
	
}
add_action( 'rest_api_init', 'mfsa_register_company_profile_endpoint' );

?>