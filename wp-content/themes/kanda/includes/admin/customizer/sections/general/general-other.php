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
$section_id = 'other';

/**
 * Debug data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Other', 'kanda' ),
            'description'    => esc_html__( 'Other options', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 21,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'other_default_avatar' => array(
            'type'        => 'image',
            'settings'    => $theme_name . '[other_default_avatar]',
            'label'       => __( 'Default avatar', 'kanda' ),
            'description' => __( 'Default avatar image for agencies ( min. 150x150px )', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'other_default_avatar' ],
            'priority'    => 10,
        )
        //other fields go here
    )
);
