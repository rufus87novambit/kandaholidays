<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Add theme rewrite rules
 */
add_action( 'init', 'kanda_rewrite_basic', 10 );
function kanda_rewrite_basic() {
    add_rewrite_rule( 'back\/?([^\/]*)\/?([^\/]*)\/?([^\/]*)\/?', 'index.php?pagename=back&pa=$matches[1]', 'top' );

//    add_rewrite_rule(
//        'back\/([a-zA-Z]*)?(\/([a-zA-Z]*))?(\/([a-zA-Z0-9]*))?(\/([a-zA-Z0-9]*))?',
//        'index.php?pagename=back&controller=$matches[1]&action=$matches[3]&fp=$matches[5]&sp=$matches[7]',
//        'top'
//    );
}

/**
 * Transfer required actions to query_vars
 */
add_filter( 'query_vars', 'kanda_query_vars' );
function kanda_query_vars( $public_query_vars ) {
    return array_merge( $public_query_vars, array(
        'pa',
        // other variables should go here
    ) );
}

/**
 * Trigger actions depended from request
 *
 * @param $query_vars
 */
function kanda_parse_request( $query_vars ) {

    do_action( 'kanda/init' );

    $pagename = isset( $query_vars->query_vars['pagename'] ) ? $query_vars->query_vars['pagename'] : '';
    if( $pagename === 'back' ) {
        do_action( 'kanda/back/init', $query_vars->query_vars['pa'] );
    } else {
        do_action( 'kanda/front/init' );
    }
}

/**
 * Add listener to common( front & back ) initialization
 */
add_action( 'kanda/init', 'kanda_init' );
function kanda_init() {
    require_once( KH_INCLUDES_PATH . 'fields.php' );
    require_once( KH_INCLUDES_PATH . 'config.php' );
    require_once( KH_INCLUDES_PATH . 'log.php' );
    require_once( KH_INCLUDES_PATH . 'cron.php' );
    require_once( KH_INCLUDES_PATH . 'helpers/class-mailer.php' );
}

/**
 * Add listener to front initialization
 */
add_action( 'kanda/front/init', 'kanda_front_init', 10 );
function kanda_front_init() {
    require_once( KH_FRONT_PATH . 'front-functions.php' );
}

/**
 * Add listener for back initialization
 */
add_action( 'kanda/back/init', 'kanda_back_init', 10, 1 );
function kanda_back_init( $action ) {
    require_once( KH_INCLUDES_PATH . 'back/back-functions.php' );
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
 * Deny travel agency access to page
 */
add_action( 'kanda/deny_user_access', 'kanda_deny_user_access', 10, 1 );
function kanda_deny_user_access( $role ) {
    if( is_user_logged_in() && current_user_can( $role ) ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 );
        exit();
    }
}

/**
 * Remove admin bar for non admins
 */
if ( ! current_user_can( 'administrator' ) ) {
    add_filter('show_admin_bar', '__return_false');
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