<?php


/**
 * Ticker Meta Box Markup
 *
 * @since 1.0.0
 */
function mfsa_ticker_meta_box_markup($object) {
  wp_nonce_field( basename(__FILE__), 'mfsa-ticker-nonce' );

  $symbols = array('SBUX', 'APPL', 'FB', 'TSLA');

  $markup = '<div class="col-container">';
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
 * Register Sales Reps Meta Box
 *
 * @since 1.0.0
 */
function mfsa_add_ticker_meta_box() {
  add_meta_box( 'ticker-settings', 'Symbol', 'mfsa_ticker_meta_box_markup', array('post', 'stocks', 'companies'), 'side', 'high', null );
}
add_action( 'add_meta_boxes', 'mfsa_add_ticker_meta_box' );


/**
 * Save Sales Reps Meta Box
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

  $symbol = '';

  if ( isset( $_POST['mfsa-symbol'] ) ) {
    $symbol = $_POST['mfsa-symbol'];
  }
  update_post_meta($post_id, "_mfsa_symbol", $symbol);

}
add_action( 'save_post', 'mfsa_save_ticker_meta_box', 10, 3 );



// Add custom meta to REST API
// function mfsa_register_ticker_fields_to_rest() {
//   $customFields = array('_mfsa_symbol');

//   if ($customFields) {
//     foreach($customFields as $field) {
//       mfsa_add_custom_field_to_rest($field, 'stocks');
//     }
//   }
// }
// add_action( 'rest_api_init', 'mfsa_register_location_fields_to_rest' );

?>
