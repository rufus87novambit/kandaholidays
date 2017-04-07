<?php
/**
 * Kanda Theme router
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Get controller name by slug
 *
 * @param $slug
 * @return bool|int|string
 */
function get_controller_by_slug( $slug ) {
    $controller = false;

    $map = Kanda_Config::get( 'controller_map' );
    foreach( $map as $controller_name => $methods ) {
        if( in_array( $slug, explode( '|', $methods ) ) ) {
            $controller = $controller_name;
            break;
        }
    }

    return $controller;
}

/**
 * Disable canonical redirect for front page
 */
add_filter( 'redirect_canonical', 'kanda_disable_canonical_redirect_for_front_page' );
function kanda_disable_canonical_redirect_for_front_page( $redirect ) {
    if ( is_page() && $front_page = get_option( 'page_on_front' ) ) {
        if ( is_page( $front_page ) ) {
            $redirect = false;
        }
    }

    return $redirect;
}

/**
 * Add theme rewrite rules
 */
add_action( 'init', 'kanda_add_rewrite_rule', 10 );
function kanda_add_rewrite_rule() {
    // valid regex
    // search(\/)?([a-zA-Z0-9]*)?(\/)?([a-zA-Z0-9]*)?(\/)?([a-zA-Z0-9]*)?(\/)?

    /**
     * Map
     * 1. Auth Controller
     * 2. Hotels Controller
     */

    $rules = array(
        /******************************************** 1. Auth Controller ********************************************/
        array(
            'regex' => 'login(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$d&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'auth_page_login' ), 'auth', 'login' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'register(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$d&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'auth_page_register' ), 'auth', 'register' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'forgot(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$d&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'auth_page_forgot' ), 'auth', 'forgot' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'reset(\/([a-zA-Z0-9]*)\/([a-zA-Z0-9]+)(\/)?([a-zA-Z0-9]+)?)?',
            'query' => sprintf( 'index.php?page_id=%1$d&controller=%2$s&action=%3$s&ksecurity=$matches[2]&key=$matches[3]&unwanted=$matches[4]', (int)kanda_get_theme_option( 'auth_page_reset' ), 'auth', 'reset' ),
            'after' => 'top'
        ),
        /******************************************** /end Auth Controller ********************************************/

        /******************************************** 2. Hotels Controller ********************************************/
        array(
            'regex' => 'hotels\/result\/([a-zA-Z0-9]*)(\/([0-9]*))?(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&k_rid=$matches[1]&k_page=$matches[3]&unwanted=$matches[5]', (int)kanda_get_theme_option( 'user_page_hotel' ), 'hotels', 'results' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'hotels\/view\/([a-zA-Z0-9-]*)(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&hcode=$matches[1]&unwanted=$matches[3]', (int)kanda_get_theme_option( 'user_page_hotel' ), 'hotels', 'view_hotel' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'hotels(\/)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s', (int)kanda_get_theme_option( 'user_page_hotel' ), 'hotels', 'index' ),
            'after' => 'top'
        ),
        /******************************************** /end Hotels Controller ********************************************/

        /******************************************** 3. Profiles Controller ********************************************/

        array(
            'regex' => 'profile\/edit\/general(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'user_page_profile' ), 'profiles', 'edit' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'profile\/edit\/password(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'user_page_profile' ), 'profiles', 'password' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'profile\/edit\/photo(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'user_page_profile' ), 'profiles', 'photo' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'profile\/edit(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'user_page_profile' ), 'profiles', 'edit' ),
            'after' => 'top'
        ),
        array(
            'regex' => 'profile(\/)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s', (int)kanda_get_theme_option( 'user_page_profile' ), 'profiles', 'edit' ),
            'after' => 'top'
        ),
        /******************************************** /end Profiles Controller ********************************************/

        /******************************************** 4. Dashboard Controller ********************************************/
        array(
            'regex' => 'dashboard(\/)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s', 16, 'dashboard', 'dashboard' ),
            'after' => 'top'
        ),

        /******************************************** /end Dashboard Controller ********************************************/

        /******************************************** 5. Booking Controller ********************************************/

        array(
            'regex' => 'booking\/create(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&unwanted=$matches[2]', (int)kanda_get_theme_option( 'user_page_booking' ), 'booking', 'create' ),
            'after' => 'top'
        ),

        array(
            'regex' => 'booking\/send-email\/([a-zA-Z0-9-]*)(\/)?([a-zA-Z0-9]*)?',
            'query' => sprintf( 'index.php?page_id=%1$s&controller=%2$s&action=%3$s&k_booking_slug=$matches[1]&unwanted=$matches[3]', (int)kanda_get_theme_option( 'user_page_booking' ), 'booking', 'send_details_email' ),
            'after' => 'top'
        ),

        /******************************************** /end Booking Controller ********************************************/

        // other rules should go here
    );

    foreach( $rules as $rule ) {
        add_rewrite_rule( $rule['regex'], $rule['query'], $rule['after'] );
    }
}

/**
 * Transfer required actions to query_vars
 */
add_filter( 'query_vars', 'kanda_query_vars' );
function kanda_query_vars( $public_query_vars ) {
    return array_merge( $public_query_vars, array(
        'controller',
        'action',
        'key',
        'ksecurity',
        'k_rid',
        'k_page',
        'k_booking_slug',
        'hcode',
        'unwanted'
        // other variables should go here
    ) );
}

/**
 * Trigger actions depended from request
 *
 * @param $query_vars
 */
add_action( 'parse_request', 'kanda_parse_request' );
function kanda_parse_request( $query_vars ) {

    $controller = false;
    $action = false;

    if( isset( $query_vars->query_vars['unwanted'] ) && $query_vars->query_vars['unwanted'] ) {
        kanda_to( '404' );
    }

    if( empty( $query_vars->query_vars ) ) {
        $show_on_front = get_option( 'show_on_front' );

        if( 'page' == $show_on_front ) {
            $front_page = get_post( get_option( 'page_on_front' ) );

            $controller = get_controller_by_slug( $front_page->post_name );
            $action = $front_page->post_name;

            if( $controller ) {
                $query_vars->query_vars[ 'page_id' ] = $front_page->ID;
            }

        }
    } else {
        if( isset( $query_vars->query_vars['controller'] ) ) {
            $controller = $query_vars->query_vars['controller'];
        }

        if( isset( $query_vars->query_vars['action'] ) ) {
            $action = $query_vars->query_vars['action'];
        }
    }

    if( $controller ) {

        $controller_file = KANDA_CONTROLLERS_PATH . sprintf( 'class-%s-controller.php', $controller );
        $controller_class_name = sprintf( '%s_Controller', ucfirst( $controller ) );

        if( file_exists( $controller_file ) ) {

            require_once ( $controller_file );

            if( class_exists( $controller_class_name ) ) {
                $controller = new $controller_class_name( $query_vars->query_vars[ 'page_id' ] );

                $action = $action ? $action : $controller->default_action;

                if( method_exists( $controller, $action ) ) {
                    $controller->{$action}( $query_vars->query_vars );
                } else {
                    $controller->show_404();
                }
            }
        }
    }

    if( ! $controller ) {
        add_filter( 'the_content', 'kanda_default_page_content', 10, 1 );
    }
}