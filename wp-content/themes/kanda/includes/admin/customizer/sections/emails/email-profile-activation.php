<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * "Profile Activation" email data
 */
$section = array(
    'id' => 'email_profile_activation',
    'args' => array(
        'title'          => esc_html__( 'Profile activation', 'kanda' ),
        'description'    => esc_html__( 'Profile Activation email settings', 'kanda' ),
        'panel'          => basename( __DIR__ ),
        'priority'       => 160,
        'capability'     => 'edit_theme_options',
    )
);

$fields = array(
    'email_profile_activation_title' => array(
        'type'     => 'text',
        'settings' => $theme_name . '[email_profile_activation_title]',
        'label'    => esc_html__( 'Subject', 'kanda' ),
        'section'  => $section['id'],
        'default'  => $kanda_customizer_defaults[ 'email_profile_activation_title' ],
        'priority' => 10,
    ),
    'email_profile_activation_body' => array(
        'type'          => 'textarea',
        'settings'      => $theme_name . '[email_profile_activation_body]',
        'label'         => esc_html__( 'Message', 'kanda' ),
        'description'   => esc_html__( 'Variables: {{SITE_NAME}} -> Link to home page, {{LOGIN_URL}} -> Link to login page', 'kanda' ),
        'section'       => $section['id'],
        'default'       => $kanda_customizer_defaults[ 'email_forgot_password_body' ],
        'priority'      => 10,
    ),
);

kanda_register_section_with_fields( $section, $fields );