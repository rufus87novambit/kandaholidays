<?php
/**
 * Kanda Theme customizer functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Include depended library
 */
include_once( KANDA_CUSTOMIZER_PATH . 'lib/kirki.php' );

/**
 * Include theme customizer
 */
include_once( KANDA_CUSTOMIZER_PATH . 'class-kanda-customizer.php' );

/**
 * Include theme panles
 */
include_once( KANDA_CUSTOMIZER_PATH . 'panels.php' );

/**
 * Start customizer registration
 */
function kanda_process_customizer_registration() {
    $customizer = kanda_customizer();
    $panels = kanda_get_panels();
    $customizer->add_panels( $panels );

    $customizer->run();
}

/**
 * Check if multicheck box has checked specific value
 *
 * @param $checkbox
 * @param $field
 * @return bool
 */
function kanda_multicheck_checked( $checkbox, $field ) {
    return in_array( $checkbox, kanda_get_theme_option( $field ) );
}

kanda_process_customizer_registration();