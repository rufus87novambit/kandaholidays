<?php
/**
 * Kanda Theme functions and definitions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Include configuration
 */
require_once( KANDA_INCLUDES_PATH . 'config.php' );

/**
 * Include logger
 */
require_once( KANDA_INCLUDES_PATH . 'helpers/class-logger.php' );

/**
 * Include mailer
 */
require_once( KANDA_INCLUDES_PATH . 'helpers/class-mailer.php' );

/**
 * Include shortcodes
 */
require_once( KANDA_INCLUDES_PATH . 'helpers/shortcodes.php' );

/**
 * Include global functions
 */
require_once( KANDA_INCLUDES_PATH . 'global-functions.php' );

/**
 * Include customizer
 */
require_once( KANDA_CUSTOMIZER_PATH . 'customizer.php' );

/**
 * Include router
 */
require_once ( KANDA_INCLUDES_PATH . 'router.php' );

/**
 * Include "ACF" plugin helper
 */
require_once( KANDA_INCLUDES_PATH . 'helpers/class-acf-fields.php' );

/**
 * Include cron
 */
require_once( KANDA_INCLUDES_PATH . 'cron.php' );

/**
 * Include theme common functions
 */
require_once( KANDA_INCLUDES_PATH . 'theme-functions.php' );

/**
 * Include theme services functions
 */
require_once( KANDA_SERVICES_PATH . 'services-functions.php' );

/**
 * Include functions based on user authentication status
 */
if( is_user_logged_in() ) {
    require_once( KANDA_BACK_PATH . 'functions.php' );
} else {
    require_once( KANDA_FRONT_PATH . 'functions.php' );
}

/**
 * Includes functions only for admin panel
 */
if( is_admin() ) {
    require_once( KANDA_ADMIN_PATH . 'functions.php' );
}