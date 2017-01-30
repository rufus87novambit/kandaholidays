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
    add_rewrite_rule( 'portal\/?([^\/]*)\/?([^\/]*)\/?([^\/]*)\/?', 'index.php?pagename=portal&pa=$matches[1]', 'top' );
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

    do_action( 'kanda/common/init' );

    $pagename = isset( $query_vars->query_vars['pagename'] ) ? $query_vars->query_vars['pagename'] : '';
    if( $pagename === 'portal' ) {
        do_action( 'kanda/portal/init', $query_vars->query_vars['pa'] );
    } else {
        do_action( 'kanda/front/init' );
    }
}

/**
 * Add listener to common( front & portal ) initialization
 */
add_action( 'kanda/common/init', 'kanda_common_init' );
function kanda_common_init() {
    require_once( KH_INCLUDES_PATH . 'config.php' );
    require_once( KH_INCLUDES_PATH . 'log.php' );
    require_once( KH_INCLUDES_PATH . 'cron.php' );
    require_once( KH_INCLUDES_PATH . 'fields.php' );
    require_once( KH_INCLUDES_PATH . 'helpers/class-mailer.php' );
}

/**
 * Add listener to front initialization
 */
add_action( 'kanda/front/init', 'kanda_front_init', 10 );
function kanda_front_init() {
    require_once( KH_INCLUDES_PATH . 'front/front-functions.php' );
}

/**
 * Add listener for portal initialization
 */
add_action( 'kanda/portal/init', 'kanda_portal_init', 10, 1 );
function kanda_portal_init( $action ) {
    require_once( KH_INCLUDES_PATH . 'portal/portal-functions.php' );
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
function kanda_deny_user_access( $role ) {
    if( is_user_logged_in() && current_user_can( $role ) ) {
        wp_redirect( site_url( '/portal' ) ); die;
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