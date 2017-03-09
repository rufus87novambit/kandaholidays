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
    wp_add_inline_style('back', '
        :root {
            --bg-color: #efefef;
            --border-color:#d8d8d8;
            --text-color: #373b42;
            --color-muted: #636c72;
            --color-light: #fff;
            --color-gray:#757575;

            --brand-primary: #3d4795;
            --brand-success: #5ABD7E;
            --brand-info: #31b0d5;
            --brand-warning: #ec971f;
            --brand-danger: #D8000C;

            --brand-primary-border: #2856b6;
            --brand-success-border: #449d44;
            --brand-info-border: #5bc0de;
            --brand-warning-border: #D0C048;
            --brand-danger-border: #c9302c;
        }
    ' );
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