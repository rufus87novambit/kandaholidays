<?php
/**
 * Kanda Theme back functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Add ajax functions
 */
require_once( KANDA_BACK_PATH . 'ajax.php' );

/**
 * Add back css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    wp_enqueue_script( 'back', KANDA_THEME_URL . 'js/back.min.js', array( 'jquery' ), null, true );
    wp_localize_script( 'back', 'kanda', kanda_get_back_localize() );
    if( is_singular( 'booking' ) ) {
        wp_localize_script( 'back', 'booking', array(
            'validation' => Kanda_Config::get( 'validation->back->form_booking_email_details' )
        ) );
    }
}

/**
 * Add back js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles(){
    wp_enqueue_style('icon-fonts', KANDA_THEME_URL .  'icon-fonts/style.css', array(), null);
    wp_enqueue_style('back', KANDA_THEME_URL . 'css/back.min.css', array(), null);
    wp_add_inline_style('back', kanda_get_color_scheme() );
}

/**
 * Get color scheme styles
 * @return string
 */
function kanda_get_color_scheme() {
    $general_body_bg = kanda_get_theme_option( 'general_body_bg' );
    $general_info_box_bg = kanda_get_theme_option( 'general_info_box_bg' );
    $general_text_color = kanda_get_theme_option( 'general_text_color' );
    $general_border_color = kanda_get_theme_option( 'general_border_color' );
    $general_primary_color = kanda_get_theme_option( 'general_primary_color' );
    $general_primary_border_color = kanda_get_theme_option( 'general_primary_border_color' );
    $general_secondary_color = kanda_get_theme_option( 'general_secondary_color' );
    $general_secondary_border_color = kanda_get_theme_option( 'general_secondary_border_color' );
    $general_success_color = kanda_get_theme_option( 'general_success_color' );
    $general_success_border_color = kanda_get_theme_option( 'general_success_border_color' );
    $general_danger_color = kanda_get_theme_option( 'general_danger_color' );
    $general_danger_border_color = kanda_get_theme_option( 'general_danger_border_color' );

    return sprintf(
        ':root {
            --body-bg: %1$s;
            --bg-color: %2$s;
            --text-color: %3$s;
            --border-color: %4$s;
            --color-muted: %5$s;

            --brand-primary: %6$s;
            --brand-primary-border: %7$s;

            --brand-secondary: %8$s;
            --brand-secondary-border: %9$s;

            --brand-success: %10$s;
            --brand-success-border: %11$s;

            --brand-danger: %12$s;
            --brand-danger-border: %13$s;
        }',
        $general_body_bg,
        $general_info_box_bg,
        $general_text_color,
        $general_border_color,
        '#636c72',
        $general_primary_color,
        $general_primary_border_color,
        $general_secondary_color,
        $general_secondary_border_color,
        $general_success_color,
        $general_success_border_color,
        $general_danger_color,
        $general_danger_border_color
    );
}

/**
 * Get localize array for js
 *
 * @return array
 */
function kanda_get_back_localize() {

    $localize = array(
        'ajaxurl'   => admin_url( 'admin-ajax.php' ),
        'themeurl'  => KANDA_THEME_URL,
        'translatable' => array(
            'invalid_request' => esc_html__( 'Invalid request', 'kanda' )
        )
    );

    return $localize;
}

/**
 * Send admin notification on new booking
 */
add_action( 'kanda/booking/create', 'kanda_booking_create_send_admin_notification', 10, 1 );
function kanda_booking_create_send_admin_notification( $booking_id ) {
    $sent = kanda_multicheck_checked( 'on_booking_create', 'admin_notifications_events' );
    if( ! $sent ) {
        return;
    }

    $subject = esc_html__( 'New Booking', 'kanda' );

    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'New booking is made at {{SITE_NAME}} with following details.', 'kanda' ) );

    $booking = get_post( $booking_id );
    $user_metas = kanda_get_user_meta( $booking->post_author );

    $message .= '<p></p>';
    $message .= '<table style="width:100%;">';
    $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Agency', 'kanda' ), $user_metas['company_name'] );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Check In', 'kanda' ), date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Check Out', 'kanda' ), date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Booking Status', 'kanda' ), ucwords( get_field( 'booking_status', $booking_id ) ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Hotel Name', 'kanda' ), get_field( 'hotel_name', $booking_id ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Room Type', 'kanda' ), get_field( 'room_type', $booking_id ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Meal Plan', 'kanda' ), get_field( 'meal_plan', $booking_id ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'City', 'kanda' ), IOL_Helper::get_city_name_from_code( get_field( 'hotel_city', $booking_id ) ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Real Price', 'kanda' ), sprintf( '%s USD', get_field( 'real_price', $booking_id ) ) );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Agency Price', 'kanda' ), sprintf( '%s USD', get_field( 'agency_price', $booking_id ) ) );
    $message .= '</table>';
    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see detailed information about booking by visiting following link', 'kanda' ) );
    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( array( 'post' => $booking_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) );

    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
        kanda_logger()->log( sprintf( 'Error sending email to admin for new booking. booking_id=%d' ), $booking_id );
    }
}

/**
 * Send admin notification on booking cancellation
 */
add_action( 'kanda/booking/cancel', 'kanda_booking_cancel_send_admin_notification' );
function kanda_booking_cancel_send_admin_notification( $booking_id ) {

    $sent = kanda_multicheck_checked( 'on_booking_cancel', 'admin_notifications_events' );
    if( ! $sent ) {
        return;
    }

    $subject = esc_html__( 'Booking Cancellation', 'kanda' );

    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'Booking has been cancelled at {{SITE_NAME}}.', 'kanda' ) );

    $message .= '<p></p>';
    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see detailed information about booking by visiting following link', 'kanda' ) );
    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( array( 'post' => $booking_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) );

    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
        kanda_logger()->log( sprintf( 'Error sending email to admin for booking cancellation. booking_id=%d' ), $booking_id );
    }
}

/**
 * Order by price
 *
 * @param $a
 * @param $b
 * @return int
 */
function kanda_price_order( $a, $b ) {
    if ( $a['rate'] == $b['rate'] ) {
        return 0;
    }
    return ( $a['rate'] < $b['rate'] ) ? -1 : 1;
}