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
$section_id = 'color_scheme';

/**
 * "Forgot Password" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Color Scheme', 'kanda' ),
            'description'    => esc_html__( 'Theme Color Scheme Options', 'kanda' ),
            'priority'       => 15,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'general_body_bg' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_body_bg]',
            'label'    => esc_html__( 'Body Background Color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_body_bg' ],
            'priority' => 10,
            'choices'     => array(
                'alpha' => true,
            ),
        ),
        'general_info_box_bg' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_info_box_bg]',
            'label'    => esc_html__( 'Content boxes background color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_info_box_bg' ],
            'priority' => 10,
            'choices'     => array(
                'alpha' => true,
            ),
        ),
        'general_text_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_text_color]',
            'label'    => esc_html__( 'Text Color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_text_color' ],
            'priority' => 10,
        ),
        'general_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_border_color]',
            'label'    => esc_html__( 'Separators / Boxes border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_border_color' ],
            'priority' => 10,
        ),
        'general_primary_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_primary_color]',
            'label'    => esc_html__( 'Primary color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_primary_color' ],
            'priority' => 10,
        ),
        'general_primary_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_primary_border_color]',
            'label'    => esc_html__( 'Primary border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_primary_border_color' ],
            'priority' => 10,
        ),
        'general_secondary_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_secondary_color]',
            'label'    => esc_html__( 'Secondary color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_secondary_color' ],
            'priority' => 10,
        ),
        'general_secondary_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_primary_border_color]',
            'label'    => esc_html__( 'Secondary border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_primary_border_color' ],
            'priority' => 10,
        ),
        'general_success_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_success_color]',
            'label'    => esc_html__( 'Success color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_success_color' ],
            'priority' => 10,
        ),
        'general_success_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_primary_border_color]',
            'label'    => esc_html__( 'Success border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_primary_border_color' ],
            'priority' => 10,
        ),
        'general_danger_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_danger_color]',
            'label'    => esc_html__( 'Danger color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_danger_color' ],
            'priority' => 10,
        ),
        'general_danger_border_color' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[general_danger_border_color]',
            'label'    => esc_html__( 'Danger border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'general_danger_border_color' ],
            'priority' => 10,
        )
        // other fields should go here
    )
);