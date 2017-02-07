<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Debug data
 */
$section = array(
    'id' => 'debug',
    'args' => array(
        'title'          => esc_html__( 'Debug', 'kanda' ),
        'description'    => esc_html__( 'Debug options', 'kanda' ),
        'panel'          => basename( __DIR__ ),
        'priority'       => 20,
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
