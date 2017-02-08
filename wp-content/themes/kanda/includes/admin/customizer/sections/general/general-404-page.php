<?php
/**
 * Kanda Theme section
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}
$theme_name = kanda_get_theme_name();
$kanda_customizer_defaults = kanda_get_customizer_defaults();
$section_id = '404_page';

/**
 * "404" page data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( '404 Page', 'kanda' ),
            'description'    => esc_html__( 'You can change 404 page options here.', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 12,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        '404_page_guest_page' => array(
            'type'          => 'dropdown-pages',
            'settings'      => $theme_name . '404_page_guest_page',
            'label'         => __( '404 for guests', 'kanda' ),
            'description'   => esc_html__( 'Select 404 page to use for guests.', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ '404_page_guest_page' ],
            'priority'      => 10,
        ),
        '404_page_user_page' => array(
            'type'          => 'dropdown-pages',
            'settings'      => $theme_name . '404_page_user_page',
            'label'         => __( '404 for users', 'kanda' ),
            'description'   => esc_html__( 'Select 404 page to use for users.', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ '404_page_user_page' ],
            'priority'      => 11,
        ),
        //other fields go here
    )
);