<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( ! defined( 'KH_THEME_PATH' ) ) {
    define( 'KH_THEME_PATH', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'KH_THEME_URL' ) ) {
    define( 'KH_THEME_URL', trailingslashit( get_template_directory_uri() ) );
}

if ( ! defined( 'KH_INCLUDES_PATH' ) ) {
    define( 'KH_INCLUDES_PATH', KH_THEME_PATH . 'includes/' );
}
if ( ! defined( 'KH_INCLUDES_URL' ) ) {
    define( 'KH_INCLUDES_URL', KH_THEME_URL . 'includes/' );
}

if ( ! defined( 'KH_SERVICES_PATH' ) ) {
    define( 'KH_SERVICES_PATH', KH_INCLUDES_PATH . 'services/' );
}
if ( ! defined( 'KH_SERVICES_URL' ) ) {
    define( 'KH_SERVICES_URL', KH_INCLUDES_URL . 'services/' );
}

if ( ! defined( 'KH_ADMIN_PATH' ) ) {
    define( 'KH_ADMIN_PATH', KH_INCLUDES_PATH . 'admin/' );
}
if ( ! defined( 'KH_ADMIN_URL' ) ) {
    define( 'KH_ADMIN_URL', KH_INCLUDES_URL . 'admin/' );
}

if ( ! defined( 'KH_FRONT_PATH' ) ) {
    define( 'KH_FRONT_PATH', KH_INCLUDES_PATH . 'front/' );
}
if ( ! defined( 'KH_FRONT_URL' ) ) {
    define( 'KH_FRONT_URL', KH_INCLUDES_URL . 'front/' );
}

if( isset( $_GET['server_test'] ) && $_GET['server_test'] ) {
    require_once( KH_INCLUDES_PATH . 'server-requirements.php' );
}

/**
 * Load common resources (required by both, admin and front, contexts).
 */
require_once( KH_INCLUDES_PATH . 'functions.php' );

/**
 * Load context resources.
 */
if ( is_admin() ) {
    require_once( KH_INCLUDES_PATH . 'admin/admin-functions.php' );
} else {
    add_action( 'parse_request', 'kanda_parse_request' );
}