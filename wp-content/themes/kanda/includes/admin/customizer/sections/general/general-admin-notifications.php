<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Admin notifications data
 */
$section = array(
    'id' => 'admin_notifications',
    'args' => array(
        'title'          => esc_html__( 'Admin Notifications', 'kanda' ),
        'description'    => esc_html__( 'Admin notifications settings', 'kanda' ),
        'panel'          => basename( __DIR__ ),
        'priority'       => 160,
        'capability'     => 'edit_theme_options',
    )
);

$fields = array(
//    'auth_page_login' => array(
//        'type'        => 'dropdown-pages',
//        'settings'    => $theme_name . 'auth_page_login',
//        'label'       => esc_html__( 'Page for "Login"', 'kanda' ),
//        'section'     => $section['id'],
//        'default'     => 0,
//        'priority'    => 10,
//    ),
    //other fields go here
);

kanda_register_section_with_fields( $section, $fields );