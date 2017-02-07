<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * "Forgot Password" email data
 */
$section = array(
    'id' => 'exchange',
    'args' => array(
        'title'          => esc_html__( 'Exchange', 'kanda' ),
        'description'    => esc_html__( 'Edit exchange settings', 'kanda' ),
        'priority'       => 14,
        'capability'     => 'edit_theme_options',
    )
);

$fields = array(
    'exchange_update_interval' => array(
        'type'          => 'slider',
        'settings'      => $theme_name . '[exchange_update_interval]',
        'label'         => esc_html__( 'Update interval', 'kanda' ),
        'description'   => esc_html__( 'Edit exchange synchronization interval (in hours) from CBA', 'kanda' ),
        'section'       => $section['id'],
        'default'       => $kanda_customizer_defaults[ 'exchange_update_interval' ],
        'priority'      => 10,
        'choices'     => array(
            'min'  => 1,
            'max'  => 24,
            'step' => 1,
        ),
    ),
    'exchange_active_currencies' => array(
        'type'          => 'select',
        'settings'      => $theme_name . '[exchange_active_currencies]',
        'label'         => esc_attr__( 'Active currencies', 'kanda' ),
        'description'   => esc_html__( 'Choose available currencies', 'kanda' ),
        'section'       => $section['id'],
        'multiple'      => 999,
        'priority'      => 11,
        'default'       => $kanda_customizer_defaults[ 'exchange_active_currencies' ],
        'choices'       => kanda_get_currency_iso_array(),
    ),
);

kanda_register_section_with_fields( $section, $fields );