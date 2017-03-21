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
$section_id = 'pricing';
/**
 * "Exchange" data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Pricing', 'kanda' ),
            'description'    => esc_html__( 'Additional fees for N star hotels ( USD )', 'kanda' ),
            'priority'       => 16,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'pricing_additional_fee_for_0_star_hotel' => array(
            'type'          => 'text',
            'settings'      => $theme_name . '[pricing_additional_fee_for_0_star_hotel]',
            'label'         => esc_html__( 'Unavailable rating', 'kanda' ),
            'description'   => esc_html__( 'Additional fee for hotels with unavailable rating', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'pricing_additional_fee_for_0_star_hotel' ],
            'priority'      => 10,
        ),
        'pricing_additional_fee_for_2_star_hotel' => array(
            'type'          => 'text',
            'settings'      => $theme_name . '[pricing_additional_fee_for_2_star_hotel]',
            'label'         => esc_html__( '2 stars', 'kanda' ),
            'description'   => esc_html__( 'Additional fee for 2 star hotels', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'pricing_additional_fee_for_2_star_hotel' ],
            'priority'      => 10,
        ),
        'pricing_additional_fee_for_3_star_hotel' => array(
            'type'          => 'text',
            'settings'      => $theme_name . '[pricing_additional_fee_for_3_star_hotel]',
            'label'         => esc_html__( '3 stars', 'kanda' ),
            'description'   => esc_html__( 'Additional fee for 3 star hotels', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'pricing_additional_fee_for_3_star_hotel' ],
            'priority'      => 10,
        ),
        'pricing_additional_fee_for_4_star_hotel' => array(
            'type'          => 'text',
            'settings'      => $theme_name . '[pricing_additional_fee_for_4_star_hotel]',
            'label'         => esc_html__( '4 stars', 'kanda' ),
            'description'   => esc_html__( 'Additional fee for 4 star hotels', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'pricing_additional_fee_for_4_star_hotel' ],
            'priority'      => 10,
        ),
        'pricing_additional_fee_for_5_star_hotel' => array(
            'type'          => 'text',
            'settings'      => $theme_name . '[pricing_additional_fee_for_5_star_hotel]',
            'label'         => esc_html__( '5 stars', 'kanda' ),
            'description'   => esc_html__( 'Additional fee for 5 star hotels', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'pricing_additional_fee_for_5_star_hotel' ],
            'priority'      => 10,
        ),
    )
);