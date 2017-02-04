<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( ! defined( 'KANDA_THEME_PATH' ) ) {
    define( 'KANDA_THEME_PATH', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'KANDA_THEME_URL' ) ) {
    define( 'KANDA_THEME_URL', get_template_directory_uri() . '/' );
}

if ( ! defined( 'KANDA_INCLUDES_PATH' ) ) {
    define( 'KANDA_INCLUDES_PATH', trailingslashit( KANDA_THEME_PATH . 'includes' ) );
}
if ( ! defined( 'KANDA_INCLUDES_URL' ) ) {
    define( 'KANDA_INCLUDES_URL', KANDA_THEME_URL . 'includes/' );
}

if ( ! defined( 'KANDA_CONTROLLERS_PATH' ) ) {
    define( 'KANDA_CONTROLLERS_PATH', trailingslashit( KANDA_INCLUDES_PATH . 'controllers' ) );
}
if ( ! defined( 'KANDA_CONTROLLERS_URL' ) ) {
    define( 'KANDA_CONTROLLERS_URL', KANDA_INCLUDES_URL . 'controllers/' );
}

if ( ! defined( 'KANDA_SERVICES_PATH' ) ) {
    define( 'KANDA_SERVICES_PATH', trailingslashit( KANDA_INCLUDES_PATH . 'services' ) );
}
if ( ! defined( 'KANDA_SERVICES_URL' ) ) {
    define( 'KANDA_SERVICES_URL', KANDA_INCLUDES_URL . 'services/' );
}

if ( ! defined( 'KANDA_FRONT_PATH' ) ) {
    define( 'KANDA_FRONT_PATH', trailingslashit( KANDA_INCLUDES_PATH . 'front' ) );
}
if ( ! defined( 'KANDA_FRONT_URL' ) ) {
    define( 'KANDA_FRONT_URL', KANDA_INCLUDES_URL . 'front/' );
}

if ( ! defined( 'KANDA_BACK_PATH' ) ) {
    define( 'KANDA_BACK_PATH', trailingslashit( KANDA_INCLUDES_PATH . 'back' ) );
}
if ( ! defined( 'KANDA_BACK_URL' ) ) {
    define( 'KANDA_BACK_URL', KANDA_INCLUDES_URL . 'back/' );
}

if ( ! defined( 'KANDA_ADMIN_PATH' ) ) {
    define( 'KANDA_ADMIN_PATH', trailingslashit( KANDA_INCLUDES_PATH . 'admin' ) );
}
if ( ! defined( 'KANDA_ADMIN_URL' ) ) {
    define( 'KANDA_ADMIN_URL', KANDA_INCLUDES_URL . 'admin/' );
}

/**
 * Call for ?server_test=1 to check theme requirements compatibility
 */
if( isset( $_GET['server_test'] ) && $_GET['server_test'] ) {
    require_once( KANDA_INCLUDES_PATH . 'server-requirements.php' );
}

/**
 * Load common resources (required by both, admin and front, contexts).
 */
require_once( KANDA_INCLUDES_PATH . 'functions.php' );