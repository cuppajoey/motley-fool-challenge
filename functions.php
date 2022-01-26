<?php

    function mf_load_styles() {
        wp_register_script( 'load-exchange-company-data', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0', true );

        wp_enqueue_script( 'load-exchange-company-data' );
    }
    add_action('wp_enqueue_scripts', 'mf_load_styles');