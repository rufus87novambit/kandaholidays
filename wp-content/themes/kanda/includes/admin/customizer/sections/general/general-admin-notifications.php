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
$section_id = 'admin_notifications';

/**
 * Admin notifications data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Admin Notifications', 'kanda' ),
            'description'    => esc_html__( 'Admin notifications settings', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 11,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'admin_notifications_events' => array(
            'type'        => 'multicheck',
            'settings'    => $theme_name . '[admin_notifications_events]',
            'label'       => esc_attr__( 'Notifications', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'admin_notifications_events' ],
            'priority'    => 10,
            'choices'     => array(
                'on_user_login'             => esc_attr__( 'On user login', 'kanda' ),
                'on_user_register'          => esc_attr__( 'On user register', 'kanda' ),
                'on_user_forgot_password'   => esc_attr__( 'On user forgot password request', 'kanda' ),
                'on_user_password_reset'    => esc_attr__( 'On user password reset', 'kanda' ),
            ),
        ),
        //other fields go here
    )
);