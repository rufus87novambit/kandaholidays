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
$section_id = 'header_color';

/**
 * "Forgot Password" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Header', 'kanda' ),
            'description'    => esc_html__( 'Header color options', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 160,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'header_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_background_color]',
            'label'    => esc_html__( 'Header background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_background_color' ],
            'priority' => 10,
        ),
        'header_shadow_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_shadow_color]',
            'label'    => esc_html__( 'Header shadow color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_shadow_color' ],
            'priority' => 11,
            'choices'     => array(
                'alpha' => true
            ),
        ),
        'header_menu_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_menu_color]',
            'label'    => esc_html__( 'Header menu color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_menu_color' ],
            'priority' => 12
        ),
        'header_sub_menu_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_sub_menu_background_color]',
            'label'    => esc_html__( 'Header sub-menu background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_sub_menu_background_color' ],
            'priority' => 13
        ),
        'header_sub_menu_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_sub_menu_color]',
            'label'    => esc_html__( 'Header sub-menu color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_sub_menu_color' ],
            'priority' => 14
        ),
        'header_sub_menu_item_hover_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_sub_menu_item_hover_background_color]',
            'label'    => esc_html__( 'Header sub- item hover background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_sub_menu_item_hover_background_color' ],
            'priority' => 15
        ),
        'header_sub_menu_item_hover_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[header_sub_menu_item_hover_color]',
            'label'    => esc_html__( 'Header sub- item hover color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'header_sub_menu_item_hover_color' ],
            'priority' => 16
        )
        // other fields should go here
    )
);