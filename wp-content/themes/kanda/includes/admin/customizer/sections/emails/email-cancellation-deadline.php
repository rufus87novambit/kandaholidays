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
$section_id = 'email_cancellation_deadline';

/**
 * "Profile Activation" email data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Cancellation Deadline', 'kanda' ),
            'description'    => esc_html__( 'Cancellation deadline details email settings', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 165,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'email_cancellation_deadline_title' => array(
            'type'     => 'text',
            'settings' => $theme_name . '[email_cancellation_deadline_title]',
            'label'    => esc_html__( 'Subject', 'kanda' ),
            'description'   => esc_html__( 'Variables: {{SITE_NAME}} -> Link to home page, {{BOOKING_NUMBER}} -> Booking number, {{AGENCY_NAME}} -> Booked agency name, {{HOTEL_NAME}} -> Hotel name, {{ROOM_TYPE}} -> Room type, {{CHECK_IN}} -> Check in date, {{CHECK_OUT}} -> Check out date, {{PASSENGERS}} -> Passenger names, {{CANCELLATION_CHARGES}} -> Cancellation charge', 'kanda' ),
            'section'  => $section_id,
            'default'  => $kanda_customizer_defaults[ 'email_cancellation_deadline_title' ],
            'priority' => 10,
        ),
        'email_cancellation_deadline_body' => array(
            'type'          => 'textarea',
            'settings'      => $theme_name . '[email_cancellation_deadline_body]',
            'label'         => esc_html__( 'Message', 'kanda' ),
            'description'   => esc_html__( 'Variables: {{SITE_NAME}} -> Link to home page, {{BOOKING_NUMBER}} -> Booking number, {{AGENCY_NAME}} -> Booked agency name, {{HOTEL_NAME}} -> Hotel name, {{ROOM_TYPE}} -> Room type, {{CHECK_IN}} -> Check in date, {{CHECK_OUT}} -> Check out date, {{PASSENGERS}} -> Passenger names, {{CANCELLATION_CHARGES}} -> Cancellation charge', 'kanda' ),
            'section'       => $section_id,
            'default'       => $kanda_customizer_defaults[ 'email_cancellation_deadline_body' ],
            'priority'      => 10,
        ),
    ),
    // other fields should go here
);