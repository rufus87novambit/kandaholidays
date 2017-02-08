<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

$theme_name = kanda_get_theme_name();
$kanda_customizer_defaults = kanda_get_customizer_defaults();
$section_id = 'debug';

/**
 * Debug data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Debug', 'kanda' ),
            'description'    => esc_html__( 'Debug options', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 20,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'debug_developer_email' => array(
            'type'     => 'textarea',
            'settings' => $theme_name . '[debug_developer_email]',
            'label'    => __( 'Developer(s) email address(es)', 'kanda' ),
            'description' => esc_html__( 'Comma separated list of developer(s) email address(es)', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'debug_developer_email' ],
            'priority' => 10,
        )
        //other fields go here
    )
);
