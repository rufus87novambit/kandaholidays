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
    wp_add_inline_style('back', kanda_get_color_scheme() );
}

/**
 * Get color scheme styles
 * @return string
 */
function kanda_get_color_scheme() {
    $general_body_bg = kanda_get_theme_option( 'general_body_bg' );
    $general_info_box_bg = kanda_get_theme_option( 'general_info_box_bg' );
    $general_text_color = kanda_get_theme_option( 'general_text_color' );
    $general_border_color = kanda_get_theme_option( 'general_border_color' );
    $general_primary_color = kanda_get_theme_option( 'general_primary_color' );
    $general_primary_border_color = kanda_get_theme_option( 'general_primary_border_color' );
    $general_secondary_color = kanda_get_theme_option( 'general_secondary_color' );
    $general_secondary_border_color = kanda_get_theme_option( 'general_secondary_border_color' );
    $general_success_color = kanda_get_theme_option( 'general_success_color' );
    $general_success_border_color = kanda_get_theme_option( 'general_success_border_color' );
    $general_danger_color = kanda_get_theme_option( 'general_danger_color' );
    $general_danger_border_color = kanda_get_theme_option( 'general_danger_border_color' );

    return sprintf(
        ':root {
            --body-bg: %1$s;
            --bg-color: %2$s;
            --text-color: %3$s;
            --border-color: %4$s;
            --color-muted: %5$s;

            --brand-primary: %6$s;
            --brand-primary-border: %7$s;

            --brand-secondary: %8$s;
            --brand-secondary-border: %9$s;

            --brand-success: %10$s;
            --brand-success-border: %11$s;

            --brand-danger: %12$s;
            --brand-danger-border: %13$s;
        }',
        $general_body_bg,
        $general_info_box_bg,
        $general_text_color,
        $general_border_color,
        '#636c72',
        $general_primary_color,
        $general_primary_border_color,
        $general_secondary_color,
        $general_secondary_border_color,
        $general_success_color,
        $general_success_border_color,
        $general_danger_color,
        $general_danger_border_color
    );
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