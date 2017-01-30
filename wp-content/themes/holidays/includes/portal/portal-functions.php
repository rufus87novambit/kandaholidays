<?php

/**
 * Get exchange data
 *
 * @return array
 */

/**
 * Add portal css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    wp_enqueue_script( 'portal', KH_THEME_URL . 'js/portal.min.js', array( 'jquery' ), null, true );
}

/**
 * Add portal js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles() {
    wp_enqueue_style( 'portal', KH_THEME_URL . 'css/portal.min.css', array(), null );
}

/**
 * Remove admin bar for non admins
 */
if ( ! current_user_can( 'administrator' ) ) {
    add_filter('show_admin_bar', '__return_false');
}

// search request example
//add_action('init', 'search_request', 11);
function search_request() {

    if( ! defined( 'IOL_LOADED' ) ) return;

    $criteria = array(
        'search-criteria' => array(
            'room-configuration' => array(
                'room' => array(
                    'adults' => 2,
                    'child' => array(
                        'age' => 16
                    ),
                    'room-configuration-id' => 1
                )
            ),
            'start-date' => Kanda_IOL_module()->helper->convert_date('15/03/2017', 'd/m/Y'),
            'end-date' => Kanda_IOL_module()->helper->convert_date('18/03/2017', 'd/m/Y'),
            'city' => 'DXB',
            'hotel-name' => 'ATLANTIS',
            'include-on-request' => true,
            'optional-supplement-y-n' => true,
            'cancellation-policy' => false,
            'include-hotel-data' => false,
            'include-rate-details' => false
        )
    );

    $args = array(
        'cache_lifetime' => Kanda_IOL_Config::get('cache_timeout', 'search')
    );

    $response = Kanda_IOL_module()->hotels->search($criteria, $args);
    echo '<pre>'; var_dump( $response ); die;
}