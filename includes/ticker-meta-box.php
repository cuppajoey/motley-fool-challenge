<?php
/**
 * This file contains the markup and logic used to register custom
 * post_meta for Posts, Stock Recommendations, and Companies. We
 * use this post_meta to link different content together.
 *
 * @since 1.0.0
 */

/**
 * Builds the markup for a meta boxes found on the post edit screen
 *
 * @since 1.0.0
 */
function mfsa_ticker_meta_box_markup($object) {
  wp_nonce_field( basename(__FILE__), 'mfsa-ticker-nonce' );

  $exchanges = array('NASDAQ', 'NYSE', 'LSE');
  $symbols = array('SBUX', 'APPL', 'FB', 'TSLA');

  $markup = '<div class="col-container">';
      $markup .= '<p class="form-field">';
        $markup .= '<label for="exchange">Select Exchange</label><br>';
        $markup .= '<select id="exchange" name="mfsa-exchange" class="components-select-control__input" style="box-sizing: border-box;">';
          $curExchange = get_post_meta($object->ID, "_mfsa_exchange", true);
          $markup .= '<option>Select exchange</option>';
          foreach ($exchanges as $exchange) {
            if ($curExchange != '' && $curExchange == $exchange) {
              $markup .= '<option value="' .$exchange. '" selected>' .$exchange. '</option>';
            } else {
              $markup .= '<option value="' .$exchange. '">' .$exchange. '</option>';
            }
          }
        $markup .= '</select>';
      $markup .= '</p>';
      
      $markup .= '<p class="form-field">';
        $markup .= '<label for="symbol">Select Company Symbol</label><br>';
        $markup .= '<select id="symbol" name="mfsa-symbol" class="components-select-control__input" style="box-sizing: border-box;">';
          $curSymbol = get_post_meta($object->ID, "_mfsa_symbol", true);
          $markup .= '<option>Select Symbol</option>';
          foreach ($symbols as $symbol) {
            if ($curSymbol != '' && $curSymbol == $symbol) {
              $markup .= '<option value="' .$symbol. '" selected>' .$symbol. '</option>';
            } else {
              $markup .= '<option value="' .$symbol. '">' .$symbol. '</option>';
            }
          }
        $markup .= '</select>';
      $markup .= '</p>';
    $markup .= '</div>';

  echo $markup;

}


/**
 * Register Ticker Meta Box
 *
 * @since 1.0.0
 */
function mfsa_add_ticker_meta_box() {
  add_meta_box( 'ticker-settings', 'Symbol', 'mfsa_ticker_meta_box_markup', array('post', 'stocks', 'companies'), 'side', 'high', null );
}
add_action( 'add_meta_boxes', 'mfsa_add_ticker_meta_box' );


/**
 * Save Ticker Meta Box Data
 *
 * @since 1.0.0
 */
function mfsa_save_ticker_meta_box( $post_id, $post, $update ) {
  if ( !isset( $_POST['mfsa-ticker-nonce'] ) || ! wp_verify_nonce( $_POST['mfsa-ticker-nonce'], basename(__FILE__) ) )
    return $post_id;

  if ( !current_user_can( 'edit_post', $post_id ) )
    return $post_id;

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return $post_id;

  $allowedPostTypes = array('post', 'stocks', 'companies');
  if ( ! in_array($post->post_type, $allowedPostTypes) )
    return $post_id;

  $exchange = '';
  $symbol = '';

  if ( isset( $_POST['mfsa-exchange'] ) ) {
    $exchange = $_POST['mfsa-exchange'];
  }
  update_post_meta($post_id, "_mfsa_exchange", $exchange);

  if ( isset( $_POST['mfsa-symbol'] ) ) {
    $symbol = $_POST['mfsa-symbol'];
  }
  update_post_meta($post_id, "_mfsa_symbol", $symbol);

}
add_action( 'save_post', 'mfsa_save_ticker_meta_box', 10, 3 );

?>
