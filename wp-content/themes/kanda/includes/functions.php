<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Get dependencies router
 */
require_once ( KANDA_INCLUDES_PATH . 'router.php' );
require_once( KANDA_CUSTOMIZER_PATH . 'customizer.php' );
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
 * Theme setup
 */
add_action( 'after_setup_theme', 'kanda_setup_theme', 10 );
function kanda_setup_theme() {
    /*
     * Make theme available for translation.
     */
    load_theme_textdomain( 'kanda', get_stylesheet_directory() . '/languages' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus( array(
        'guests_nav'    => esc_html__( 'Guests Menu', 'kanda' ),
        'main_nav'      => esc_html__( 'Main Menu', 'kanda' ),
    ) );

    /**
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
    add_editor_style( array( 'editor-style.css' ) );

}

add_action( 'widgets_init', 'kanda_widgets_init', 10 );
function kanda_widgets_init() {
    $register_sidebars = kanda_get_sidebars();

    foreach( $register_sidebars as $register_sidebar ){

        register_sidebar( array(
            'name'          => $register_sidebar['name'],
            'id'            => $register_sidebar['id'],
            'description'   => $register_sidebar['description'],
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
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
 * Get enabled currencies
 *
 * @return array
 */
function kanda_get_active_currencies() {
    // todo -> Set preferred_exchanges configurable from admin panel
    $currencies = array( 'USD', 'RUB', 'EUR', 'GBP' );

    return $currencies;
}

/**
 * Get required exchange rates
 *
 * @return array
 */
function kanda_get_exchange() {
    $preferred_exchanges = kanda_get_active_currencies();
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
 * Get sidebars configuration
 *
 * @return array
 */
function kanda_get_sidebars() {
    return array(
        array(
            'name'          => esc_html__( 'Default', 'kanda' ),
            'id'            => 'default-sidebar',
            'description'   => esc_html__( 'The widgets added here will appear on all the pages.', 'kanda' ),
        )
    );
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