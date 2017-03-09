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
$section_id = 'general_color';

/**
 * "Forgot Password" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'General', 'kanda' ),
            'description'    => esc_html__( 'General color options', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 160,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'general_primary_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_primary_color]',
            'label'    => esc_html__( 'Theme primary color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_primary_color' ],
            'priority' => 10,
        ),
        'general_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_background_color]',
            'label'    => esc_html__( 'Theme background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_background_color' ],
            'priority' => 11,
            'choices'     => array(
                'alpha' => true
            ),
        ),
        'general_content_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_content_background_color]',
            'label'    => esc_html__( 'Content background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_content_background_color' ],
            'priority' => 12,
        ),
        'general_content_text_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_content_text_color]',
            'label'    => esc_html__( 'Content text color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_content_text_color' ],
            'priority' => 13,
        ),
        'general_content_shadow_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_content_shadow_color]',
            'label'    => esc_html__( 'Content shadow color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_content_shadow_color' ],
            'priority' => 14,
            'choices'     => array(
                'alpha' => true
            )
        ),
        'general_sidebar_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_sidebar_background_color]',
            'label'    => esc_html__( 'Sidebar background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_sidebar_background_color' ],
            'priority' => 15
        ),
        'general_sidebar_shadow_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_sidebar_shadow_color]',
            'label'    => esc_html__( 'Sidebar shadow color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_sidebar_shadow_color' ],
            'priority' => 16,
            'choices'     => array(
                'alpha' => true
            )
        ),
        'general_input_fields_background_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_input_fields_background_color]',
            'label'    => esc_html__( 'Theme input fields background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_input_fields_background_color' ],
            'priority' => 17,
        ),
        'general_input_fields_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_input_fields_border_color]',
            'label'    => esc_html__( 'Theme input fields border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_input_fields_border_color' ],
            'priority' => 18,
            'choices'     => array(
                'alpha' => true
            )
        ),
        'general_success_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_success_color]',
            'label'    => esc_html__( 'Theme success color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_success_color' ],
            'priority' => 18,
            'choices'     => array(
                'alpha' => true
            )
        ),
        'general_danger_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_danger_color]',
            'label'    => esc_html__( 'Theme error color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_danger_color' ],
            'priority' => 18,
            'choices'     => array(
                'alpha' => true
            )
        ),
        // other fields should go here
    )
);