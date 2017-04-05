<?php
/**
 * Kanda Theme functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Remove admin bar for non admins
 */
if ( ! current_user_can( 'administrator' ) ) {
    $hide = true;

    $user_switching_instance = $GLOBALS['user_switching'];
    if( $user_switching_instance ) {
        if( $user_switching_instance::get_old_user() ) {
            $hide = false;
        }
    }
    if( $hide ) {
        add_filter('show_admin_bar', '__return_false');
    }
}

/**
 * Default content callback
 *
 * @param $content
 * @return string
 */
function kanda_default_page_content( $content ) {
    return sprintf( '<div class="editor-content">%1$s</div>', $content );
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

    add_image_size( 'user-avatar', 150, 150, true );

    /**
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
    add_editor_style( array( 'editor-style.css' ) );

}

/**
 * Register widgets
 */
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

add_action( 'init', 'kanda_register_post_types' );
function kanda_register_post_types() {

    $hotel_args = array(
        'labels'             => array(
            'name'               => _x( 'Hotels', 'post type general name', 'kanda' ),
            'singular_name'      => _x( 'Hotel', 'post type singular name', 'kanda' ),
            'menu_name'          => _x( 'Hotels', 'admin menu', 'kanda' ),
            'name_admin_bar'     => _x( 'Hotel', 'add new on admin bar', 'kanda' ),
            'add_new'            => _x( 'Add New', 'hotel', 'kanda' ),
            'add_new_item'       => __( 'Add New Hotel', 'kanda' ),
            'new_item'           => __( 'New Hotel', 'kanda' ),
            'edit_item'          => __( 'Edit Hotel', 'kanda' ),
            'view_item'          => __( 'View Hotel', 'kanda' ),
            'all_items'          => __( 'All Hotels', 'kanda' ),
            'search_items'       => __( 'Search Hotels', 'kanda' ),
            'parent_item_colon'  => __( 'Parent Hotels:', 'kanda' ),
            'not_found'          => __( 'No hotels found.', 'kanda' ),
            'not_found_in_trash' => __( 'No hotels found in Trash.', 'kanda' )
        ),
        'description'        => __( 'Description.', 'kanda' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'hotels/view' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'author' )
    );

    $booking_args = array(
        'labels'             => array(
            'name'               => _x( 'Bookings', 'post type general name', 'kanda' ),
            'singular_name'      => _x( 'Booking', 'post type singular name', 'kanda' ),
            'menu_name'          => _x( 'Bookings', 'admin menu', 'kanda' ),
            'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'kanda' ),
            'add_new'            => _x( 'Add New', 'booking', 'kanda' ),
            'add_new_item'       => __( 'Add New Booking', 'kanda' ),
            'new_item'           => __( 'New Booking', 'kanda' ),
            'edit_item'          => __( 'Edit Booking', 'kanda' ),
            'view_item'          => __( 'View Booking', 'kanda' ),
            'all_items'          => __( 'All Bookings', 'kanda' ),
            'search_items'       => __( 'Search Bookings', 'kanda' ),
            'parent_item_colon'  => __( 'Parent Bookings:', 'kanda' ),
            'not_found'          => __( 'No bookings found.', 'kanda' ),
            'not_found_in_trash' => __( 'No bookings found in Trash.', 'kanda' )
        ),
        'description'        => __( 'Description.', 'kanda' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'booking/view' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'author' )
    );

    register_post_type( 'hotel', $hotel_args );
    register_post_type( 'booking', $booking_args );
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
 * Remove "jquery migrate" console notice
 */
add_action( 'wp_default_scripts', 'kanda_remove_migrate_notice', 10, 1 );
function kanda_remove_migrate_notice( $scripts ) {
    if ( ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
    }
}