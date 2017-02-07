<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Get customizer default values
 *
 * @return array
 */
function kanda_get_theme_customizer_default_values() {
    return array(
    /** - Panel General Options - **/
        /** - Section Authorization pages- */
        'auth_page_login'       => 0,
        'auth_page_register'    => 0,
        'auth_page_forgot'      => 0,
        'auth_page_reset'       => 0,

        /** - Section Admin Notifications - */
        'admin_notifications_events' => array( 'on_user_login', 'on_user_register', 'on_user_forgot_password', 'on_user_password_reset' ),

        /** - Section 404 Page - **/
        '404_page_guest_page'    => 0,
        '404_page_user_page'     => 0,
        /** - Section Debug - **/

    /** - Panel Emails - **/
        'email_forgot_password_title'       => esc_html__( 'Reset Password', 'kanda' ),
        'email_forgot_password_body'        => '',
        'email_profile_activation_title'    => esc_html__( 'Profile Activated', 'kanda' ),
        'email_profile_activation_body'     => '',

    /** - Section Front Page - **/
        'front_pages_slider_animation_delay' => 7,
        'front_pages_slider_gallery'         => array(),

    /** - Section Exchange - **/
        'exchange_update_interval'          => 12,
        'exchange_active_currencies'        => array( 'USD', 'EUR', 'RUB', 'AMD' )
    );
}