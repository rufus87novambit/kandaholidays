<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Get dependencies router
 */
require_once ( KANDA_INCLUDES_PATH . 'router.php' );
require_once( KANDA_INCLUDES_PATH . 'fields.php' );
require_once( KANDA_INCLUDES_PATH . 'config.php' );
require_once( KANDA_INCLUDES_PATH . 'helpers/class-logger.php' );
require_once( KANDA_INCLUDES_PATH . 'helpers/class-mailer.php' );
require_once( KANDA_INCLUDES_PATH . 'helpers/shortcodes.php' );
require_once( KANDA_INCLUDES_PATH . 'cron.php' );

if( is_user_logged_in() ) {
    require_once( KANDA_BACK_PATH . 'functions.php' );
} else {
    require_once( KANDA_FRONT_PATH . 'functions.php' );
}

if( is_admin() ) {
    require_once( KANDA_ADMIN_PATH . 'functions.php' );
}

/**
 * Remove admin bar for non admins
 */
if ( ! current_user_can( 'administrator' ) ) {
    add_filter('show_admin_bar', '__return_false');
}

/**
 * Deny accesses
 */
add_action( 'get_header', 'kanda_get_header', 10, 1 );
function kanda_get_header( $name ) {
    if( ! $name ) {
        kanda_deny_guest_access();
    } elseif( $name == 'guests' ) {
        kanda_deny_user_access( Kanda_Config::get( 'agency_role' ) );
    }
}

/**
 * Add custom role
 */
add_action( 'after_switch_theme', 'kanda_add_user_roles', 10 );
function kanda_add_user_roles() {
    add_role(
        'agency',
        esc_html__( 'Travel Agency', 'kanda' ),
        array(
            'read' => true,  // true allows this capability
            'edit_posts' => true,
            'delete_posts' => false, // Use false to explicitly deny
        )
    );
}

/**
 * Deny role access
 */
function kanda_deny_user_access( $role ) {
    if( is_user_logged_in() && current_user_can( $role ) ) {
        kanda_to( 'home' );
    }
}

/**
 * Deny guest access
 */
function kanda_deny_guest_access() {
    if( ! is_user_logged_in() ) {
        kanda_to( 'login' );
    }
}

/**
 * Generate a random string
 *
 * @param int $length
 * @return string
 */
function generate_random_string( $length = 10 ) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get required exchange rates
 *
 * @return array
 */
function kanda_get_exchange() {
    // todo -> Set preferred_exchanges configurable from admin panel
    $preferred_exchanges = array( 'USD', 'RUB', 'EUR', 'GBP' );
    return array_intersect_key( kanda_get_exchange_rates(), array_flip( $preferred_exchanges ) );
}

/**
 * Get template variables
 *
 * @param bool|false $type
 * @return array
 */
function kanda_get_page_template_variables( $type = false ) {

    $is_user = is_user_logged_in();

    $return = array(
        'header' => $is_user ? null : 'guests',
        'footer' => $is_user ? null : 'guests',
    );
    switch ( $type ) {
        case '404':
            $postfix = $is_user ? 'users' : 'guests';
            $return = array_merge( $return, array(
                'title'     => kanda_fields()->get_option( sprintf( '404_page_title_for_%s', $postfix ) ),
                'content'   => kanda_fields()->get_option( sprintf( '404_page_content_for_%s', $postfix ) )
            ) );
            break;
    }

    return $return;

}

/**
 * Redirect to
 *
 * @param $name
 */
function kanda_to( $name ) {
    if( $name == '404' ) {
        global $wp_query;

        $wp_query->set_404();
        status_header( 404 );
        get_template_part( '404' );
        exit();
    }
    $url = kanda_url_to( $name );
    if( $url ) {
        wp_redirect( $url ); exit();
    }
}

/**
 * Get url to
 *
 * @param $name
 * @return bool|false|string|void
 */
function kanda_url_to( $name ) {
    switch( $name ) {
        case 'home';
            $url = home_url();
            break;
        case 'login':
            $url = get_permalink( kanda_fields()->get_option( 'kanda_auth_page_login' ) );
            break;
        case 'register':
            $url = get_permalink( kanda_fields()->get_option( 'kanda_auth_page_register' ) );
            break;
        case 'forgot-password':
            $url = get_permalink( kanda_fields()->get_option( 'kanda_auth_page_forgot' ) );
            break;
        case 'reset-password':
            $url = get_permalink( kanda_fields()->get_option( 'kanda_auth_page_reset' ) );
            break;
        default:
            $url = false;
    }

    return $url;
}