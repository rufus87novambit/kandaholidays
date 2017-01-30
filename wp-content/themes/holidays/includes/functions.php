<?php


// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Add theme rewrite rules
 */
add_action( 'init', 'kanda_rewrite_basic' );
function kanda_rewrite_basic() {
    add_rewrite_rule('portal\/?([^\/]*)\/?([^\/]*)\/?([^\/]*)\/?', 'index.php?pagename=portal&pa=$matches[1]', 'top');
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
    $pagename = isset( $query_vars->query_vars['pagename'] ) ? $query_vars->query_vars['pagename'] : '';
    if( $pagename === 'portal' ) {
        do_action( 'kanda/portal/init', $query_vars->query_vars['pa'] );
    }
}

/**
 * Init portal functionality
 */
add_action( 'kanda/portal/init', 'kanda_portal_init', 10, 1 );
function kanda_portal_init( $action ) {
    require_once( KH_INCLUDES_PATH . 'config.php' );
    require_once( KH_INCLUDES_PATH . 'log.php' );
    require_once( KH_INCLUDES_PATH . 'cron.php' );
    require_once( KH_INCLUDES_PATH . 'helpers/class-mailer.php' );

    require_once( KH_INCLUDES_PATH . 'portal/portal-functions.php' );
}