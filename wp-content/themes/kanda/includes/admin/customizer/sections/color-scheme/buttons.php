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
$section_id = 'button_color';

/**
 * "Forgot Password" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Buttons', 'kanda' ),
            'description'    => esc_html__( 'Buttons color options', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 160,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'button_primary_background' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_primary_background]',
            'label'    => esc_html__( 'Primary button background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_primary_background' ],
            'priority' => 10,
        ),
        'button_primary_background_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_primary_background_hover]',
            'label'    => esc_html__( 'Primary button active state background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_primary_background_hover' ],
            'priority' => 10,
        ),
        'button_primary_border' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_primary_border]',
            'label'    => esc_html__( 'Primary button border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_primary_border' ],
            'priority' => 10,
        ),
        'button_primary_border_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_primary_border_hover]',
            'label'    => esc_html__( 'Primary button active state border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_primary_border_hover' ],
            'priority' => 10,
        ),
        'button_secondary_background' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_secondary_background]',
            'label'    => esc_html__( 'Secondary button background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_secondary_background' ],
            'priority' => 10,
        ),
        'button_secondary_background_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_secondary_background_hover]',
            'label'    => esc_html__( 'Secondary button active state background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_secondary_background_hover' ],
            'priority' => 10,
        ),
        'button_secondary_border' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_secondary_border]',
            'label'    => esc_html__( 'Secondary button border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_secondary_border' ],
            'priority' => 10,
        ),
        'button_secondary_border_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_secondary_border_hover]',
            'label'    => esc_html__( 'Secondary button active state border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_secondary_border_hover' ],
            'priority' => 10,
        ),
        'button_danger_background' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_danger_background]',
            'label'    => esc_html__( 'Danger button background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_danger_background' ],
            'priority' => 10,
        ),
        'button_danger_background_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_danger_background_hover]',
            'label'    => esc_html__( 'Danger button active state background', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_danger_background_hover' ],
            'priority' => 10,
        ),
        'button_danger_border' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_danger_border]',
            'label'    => esc_html__( 'Danger button border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_danger_border' ],
            'priority' => 10,
        ),
        'button_danger_border_hover' => array(
            'type'     => 'color',
            'settings' => $theme_name . '[button_danger_border_hover]',
            'label'    => esc_html__( 'Danger button active state border color', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'button_danger_border_hover' ],
            'priority' => 10,
        ),
        // other fields should go here
    )
);