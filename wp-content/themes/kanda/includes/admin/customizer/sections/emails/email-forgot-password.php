<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * "Forgot Password" email data
 */
$section = array(
    'id' => 'email_forgot_password',
    'args' => array(
        'title'          => esc_html__( 'Forgot password', 'kanda' ),
        'description'    => esc_html__( 'Forgot password email settings', 'kanda' ),
        'panel'          => basename( __DIR__ ),
        'priority'       => 160,
        'capability'     => 'edit_theme_options',
    )
);

$fields = array(
    'email_forgot_password_title' => array(
        'type'     => 'text',
        'settings' => $theme_name . '[email_forgot_password_title]',
        'label'    => esc_html__( 'Subject', 'kanda' ),
        'section'  => $section['id'],
        'default'  => $kanda_customizer_defaults[ 'email_forgot_password_title' ],
        'priority' => 10,
    ),
    'email_forgot_password_body' => array(
        'type'          => 'textarea',
        'settings'      => $theme_name . '[email_forgot_password_body]',
        'label'         => esc_html__( 'Message', 'kanda' ),
        'description'   => esc_html__( 'Variables: {{RESET_URL}} -> password reset URL, {{SITE_NAME}} -> Link to home page', 'kanda' ),
        'section'       => $section['id'],
        'default'       => $kanda_customizer_defaults[ 'email_forgot_password_body' ],
        'priority'      => 10,
    ),
);

kanda_register_section_with_fields( $section, $fields );