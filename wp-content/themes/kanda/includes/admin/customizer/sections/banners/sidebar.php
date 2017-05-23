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
$section_id = 'sidebar_banners';

/**
 * "404" page data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Sidebar', 'kanda' ),
            'description'    => esc_html__( 'Sidebar banners options.', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 12,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'sidebar_banners_slider_gallery' => array(
            'type'          => 'repeater',
            'settings'      => $theme_name . '[sidebar_banners_slider_gallery]',
            'label'         => esc_attr__( 'Gallery', 'kanda' ),
            'description'   => esc_html__( 'Upload sidebar banners here. Advisable size: 300x407px', 'kanda' ),
            'section'       => $section_id,
            'priority'      => 11,
            'row_label'     => array(
                'type'  => 'text',
                'value' => esc_attr__('Image', 'kanda' ),
            ),
            'default'       => $kanda_customizer_defaults[ 'sidebar_banners_slider_gallery' ],
            'fields' => array(
                'image' => array(
                    'type'        => 'image',
                    'label'       => esc_attr__( 'Image', 'kanda' ),
                    'description' => esc_attr__( 'Upload an image', 'kanda' ),
                    'default'     => '',
                )
            )
        ),
        //other fields go here
    )
);