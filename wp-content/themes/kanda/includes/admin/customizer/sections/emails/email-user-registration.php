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
$section_id = 'email_user_registration';

/**
 * "Profile Activation" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'User registration', 'kanda' ),
            'description'    => esc_html__( 'User registration email settings', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 162,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'email_user_registration_title' => array(
            'type'     => 'text',
            'settings' => $theme_name . '[email_user_registration_title]',
            'label'    => esc_html__( 'Subject', 'kanda' ),
            'description'   => esc_html__( 'Variables: {{SITE_NAME}} -> Link to home page, {{FIRST_NAME}} -> User first name, {{LATS_NAME}} -> User last name, {{LOGIN_URL}} -> Link to login page', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'email_user_registration_title' ],
            'priority' => 10,
        ),
        'email_user_registration_body' => array(
            'type'          => 'textarea',
            'settings'      => $theme_name . '[email_user_registration_body]',
            'label'         => esc_html__( 'Message', 'kanda' ),
            'description'   => esc_html__( 'Variables: {{SITE_NAME}} -> Link to home page, {{FIRST_NAME}} -> User first name, {{LATS_NAME}} -> User last name, {{LOGIN_URL}} -> Link to login page', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'email_user_registration_body' ],
            'priority'      => 10,
        ),
    ),
    // other fields should go here
);