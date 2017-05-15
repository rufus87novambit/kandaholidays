<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Customizer default values
 */
return array(
/** - Panel General Options - **/
    /** - Section Authorization pages- */
    'auth_page_login'       => 0,
    'auth_page_register'    => 0,
    'auth_page_forgot'      => 0,
    'auth_page_reset'       => 0,
    'user_page_profile'     => 0,
    'user_page_hotel'       => 0,
    'user_page_booking'     => 0,

    /** - Section Admin Notifications - */
    'admin_notifications_events' => array(
        'on_user_login',
        'on_user_register',
        'on_user_forgot_password',
        'on_user_password_reset',
        'on_booking_create',
        'on_booking_cancel'
    ),

    /** - Section 404 Page - **/
    '404_page_guest_page'    => 0,
    '404_page_user_page'     => 0,

    /** - Section Debug - **/
    'debug_developer_email' => '',

    /** - Section Debug - **/
    'other_default_avatar' => KANDA_THEME_URL . 'images/back/profile.png',

/** - Panel Emails - **/
    'email_forgot_password_title'       => esc_html__( 'Reset Password', 'kanda' ),
    'email_forgot_password_body'        => '',
    'email_profile_activation_title'    => esc_html__( 'Profile Activated', 'kanda' ),
    'email_profile_activation_body'     => '',
    'email_booking_details_title'       => esc_html__( 'Booking Details', 'kanda' ),
    'email_booking_details_body'        => '',
    'email_booking_confirmation_title'  => esc_html__( 'Booking Confirmation - {{BOOKING_NUMBER}}', 'kanda' ),
    'email_booking_confirmation_body'   => '',
    'email_booking_cancellation_title'  => esc_html__( 'Booking Cancellation - {{BOOKING_NUMBER}}', 'kanda' ),
    'email_booking_cancellation_body'   => '',

/** - Section Front Page - **/
    'front_pages_slider_animation_delay' => 7,
    'front_pages_slider_gallery'         => array(),

/** - Section Exchange - **/
    'exchange_update_interval'          => 12,
    'exchange_active_currencies'        => array( 'USD', 'EUR', 'RUB', 'AMD' ),

/** - Section Color Scheme - **/
    'general_body_bg' => '#C5CAE9',
    'general_info_box_bg' => '#EDE7F6',
    'general_text_color' => '#373b42',
    'general_border_color' => '#d8d8d8',
    'general_primary_color' => '#311B92',
    'general_primary_border_color' => '#2856b6',
    'general_secondary_color' => '#673AB7',
    'general_secondary_border_color' => '#5E35B1',
    'general_success_color' => '#5ABD7E',
    'general_success_border_color' => '#449d44',
    'general_danger_color' => '#D8000C',
    'general_danger_border_color' => '#c9302c',

/** - Section Pricing - **/
    'pricing_additional_fee_for_0_star_hotel' => 5,
    'pricing_additional_fee_for_2_star_hotel' => 5,
    'pricing_additional_fee_for_3_star_hotel' => 5,
    'pricing_additional_fee_for_4_star_hotel' => 5,
    'pricing_additional_fee_for_5_star_hotel' => 5,
);