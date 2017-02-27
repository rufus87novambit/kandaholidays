<?php
/**
 * Kanda Theme back functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Add ajax functions
 */
require_once( KANDA_BACK_PATH . 'ajax.php' );

/**
 * Add back css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'back', KANDA_THEME_URL . 'js/back.min.js', array( 'jquery' ), null, true );
    wp_localize_script( 'back', 'kanda', kanda_get_back_localize() );
}

/**
 * Add back js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles(){
    wp_enqueue_style('icon-fonts', KANDA_THEME_URL .  'icon-fonts/style.css', array(), null);
    wp_enqueue_style('back', KANDA_THEME_URL . 'css/back.min.css', array(), null);
}

/**
 * Get localize array for js
 *
 * @return array
 */
function kanda_get_back_localize() {
    return array(
        'ajaxurl'   => admin_url( 'admin-ajax.php' ),
        'themeurl'  => KANDA_THEME_URL,
        'translatable' => array(
            'invalid_request' => esc_html__( 'Invalid request', 'kanda' )
        )
    );
}