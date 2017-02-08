<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

$theme_name = kanda_get_theme_name();
$kanda_customizer_defaults = kanda_get_customizer_defaults();
$section_id = 'front_pages';

/**
 * "Front page" data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Front Pages', 'kanda' ),
            'description'    => esc_html__( 'Change front pages settings', 'kanda' ),
            'priority'       => 13,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'front_pages_slider_animation_delay' => array(
            'type'          => 'slider',
            'settings'      => $theme_name . '[front_pages_slider_animation_delay]',
            'label'         => esc_html__( 'Slider animation delay', 'kanda' ),
            'description'   => esc_html__( 'Change slider animation delay in sections', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'front_pages_slider_animation_delay' ],
            'priority'      => 10,
            'choices'       => array(
                'min'  => '1',
                'max'  => '30',
                'step' => '1',
            ),
        ),
        'front_pages_slider_gallery' => array(
            'type'          => 'repeater',
            'settings'      => $theme_name . '[front_pages_slider_gallery]',
            'label'         => esc_attr__( 'Gallery', 'kanda' ),
            'description'   => esc_html__( 'As this is a background slider please upload images with size >= 1600x800px', 'kanda' ),
            'section'       => $section_id,
            'priority'      => 11,
            'row_label'     => array(
                'type'  => 'text',
                'value' => esc_attr__('Image', 'kanda' ),
            ),
            'default'       => $kanda_customizer_defaults[ 'front_pages_slider_gallery' ],
            'fields' => array(
                'image' => array(
                    'type'        => 'image',
                    'label'       => esc_attr__( 'Image', 'kanda' ),
                    'description' => esc_attr__( 'Upload an image', 'kanda' ),
                    'default'     => '',
                )
            )
        ),
    )
);