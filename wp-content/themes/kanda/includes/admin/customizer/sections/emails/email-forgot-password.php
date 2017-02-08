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
$section_id = 'email_forgot_password';

/**
 * "Forgot Password" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Forgot password', 'kanda' ),
            'description'    => esc_html__( 'Forgot password email settings', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 160,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'email_forgot_password_title' => array(
            'type'     => 'text',
            'settings' => $theme_name . '[email_forgot_password_title]',
            'label'    => esc_html__( 'Subject', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'email_forgot_password_title' ],
            'priority' => 10,
        ),
        'email_forgot_password_body' => array(
            'type'          => 'textarea',
            'settings'      => $theme_name . '[email_forgot_password_body]',
            'label'         => esc_html__( 'Message', 'kanda' ),
            'description'   => esc_html__( 'Variables: {{RESET_URL}} -> password reset URL, {{SITE_NAME}} -> Link to home page', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'email_forgot_password_body' ],
            'priority'      => 10,
        ),
        // other fields should go here
    )
);