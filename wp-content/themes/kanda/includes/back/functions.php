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
    $deps = array( 'jquery' );
    $localize = array();
    $localize_key = false;
    if( is_singular( 'booking' ) ) {
        $localize = array(
            'validation' => Kanda_Config::get( 'validation->back->form_booking_email_details' )
        );
        $localize_key = 'booking';
    } elseif( is_page_template( 'booking-list.php' ) ) {
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );

        $localize = array(
            'hotel_names' => kanda_get_hotels_for_autocomplete()
        );
        $localize_key = 'bookings_data';
        $deps = array_merge( $deps, array( 'jquery-ui-datepicker', 'jquery-ui-autocomplete' ) );
    }


    wp_enqueue_script( 'back', KANDA_THEME_URL . 'js/back.min.js', $deps, null, true );
    wp_localize_script( 'back', 'kanda', kanda_get_back_localize() );

    if( $localize_key && ! empty( $localize ) ) {
        wp_localize_script( 'back', $localize_key, $localize );
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
//add_action( 'kanda/booking/create', 'kanda_booking_create_send_admin_notification', 10, 1 );
//function kanda_booking_create_send_admin_notification( $booking_id ) {
//    $sent = kanda_multicheck_checked( 'on_booking_create', 'admin_notifications_events' );
//    if( ! $sent ) {
//        return;
//    }
//
//    $subject = sprintf( '%1$s - %2$s', esc_html__( 'Booking confirmation', 'kanda' ), get_field( 'booking_number', $booking_id ) );
//
//    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
//    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'New booking is made at {{SITE_NAME}} with following details.', 'kanda' ) );
//
//    $booking = get_post( $booking_id );
//    $user_metas = kanda_get_user_meta( $booking->post_author );
//
//    $message .= '<p></p>';
//    $message .= '<table style="width:100%;">';
//    $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Agency', 'kanda' ), $user_metas['company_name'] );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Check In', 'kanda' ), date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Check Out', 'kanda' ), date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Booking Status', 'kanda' ), ucwords( get_field( 'booking_status', $booking_id ) ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Hotel Name', 'kanda' ), get_field( 'hotel_name', $booking_id ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Room Type', 'kanda' ), get_field( 'room_type', $booking_id ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Meal Plan', 'kanda' ), get_field( 'meal_plan', $booking_id ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'City', 'kanda' ), IOL_Helper::get_city_name_from_code( get_field( 'hotel_city', $booking_id ) ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Real Price', 'kanda' ), sprintf( '%s USD', get_field( 'real_price', $booking_id ) ) );
//    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Agency Price', 'kanda' ), sprintf( '%s USD', get_field( 'agency_price', $booking_id ) ) );
//    $message .= '</table>';
//    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see detailed information about booking by visiting following link', 'kanda' ) );
//    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( array( 'post' => $booking_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) );
//
//    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
//        kanda_logger()->log( sprintf( 'Error sending email to admin for new booking. booking_id=%d' ), $booking_id );
//    }
//}

/**
 * Send a notification to travel agency
 */
add_action( 'kanda/booking/create', 'kanda_booking_create_send_notifications', 10, 1 );
function kanda_booking_create_send_notifications( $booking_id ) {

    $booking = get_post( $booking_id );

    $subject = kanda_get_theme_option( 'email_booking_confirmation_title' );
    $message = kanda_get_theme_option( 'email_booking_confirmation_body' );

    $cancellation_html = '';
    while( have_rows( 'cancellation_policy', $booking_id ) ) {
        the_row();
        $cancellation_html .= sprintf(
            '<p>%1$s - %2$s: %3$s</p>',
            date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'from', false ) ) ),
            date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'to', false ) ) ),
            get_sub_field( 'charge' )
        );
    }
    $variables = array(
        '{{BOOKING_NUMBER}}'        => get_field( 'booking_number', $booking_id ),
        '{{PASSENGERS}}'            => strtr( kanda_get_post_meta( $booking_id, 'passenger_names' ), array( '##' => ', ' ) ),
        '{{AGENCY_NAME}}'           => kanda_get_user_meta( $booking->post_author, 'company_name' ),
        '{{HOTEL_NAME}}'            => get_field( 'hotel_name', $booking_id ),
        '{{ROOM_TYPE}}'             => get_field( 'room_type', $booking_id ),
        '{{CHECK_IN}}'              => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ),
        '{{CHECK_OUT}}'             => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ),
        '{{CANCELLATION_DETAILS}}'  => $cancellation_html
    );

    $sent_user = kanda_mailer()->send_user_email( $booking->post_author, $subject, $message, $variables );
    if( ! $sent_user ) {
        kanda_logger()->log( sprintf( 'Error sending email to user for new booking. booking_id=%d', $booking_id ) );
    }

    $sent_to_admin = kanda_multicheck_checked( 'on_booking_create', 'admin_notifications_events' );
    if( $sent_to_admin ) {
        $sent_admin = kanda_mailer()->send_admin_email($subject, $message, $variables);
        if (!$sent_admin) {
            kanda_logger()->log(sprintf('Error sending email to admin for new booking. booking_id=%d', $booking_id ) );
        }
    }
}

/**
 * Send admin notification on booking cancellation
 */
//add_action( 'kanda/booking/cancel', 'kanda_booking_cancel_send_admin_notification' );
//function kanda_booking_cancel_send_admin_notification( $booking_id ) {
//
//    $sent = kanda_multicheck_checked( 'on_booking_cancel', 'admin_notifications_events' );
//    if( ! $sent ) {
//        return;
//    }
//
//    $subject = esc_html__( 'Booking Cancellation', 'kanda' );
//
//    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
//    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'Booking has been cancelled at {{SITE_NAME}}.', 'kanda' ) );
//
//    $message .= '<p></p>';
//    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see detailed information about booking by visiting following link', 'kanda' ) );
//    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( array( 'post' => $booking_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) );
//
//    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
//        kanda_logger()->log( sprintf( 'Error sending email to admin for booking cancellation. booking_id=%d' ), $booking_id );
//    }
//}

add_action( 'kanda/booking/cancel', 'kanda_booking_cancel_send_notifications', 10, 1 );
function kanda_booking_cancel_send_notifications( $booking_id ) {

    $booking = get_post( $booking_id );

    $subject = kanda_get_theme_option( 'email_booking_cancellation_title' );
    $message = kanda_get_theme_option( 'email_booking_cancellation_body' );

    $charges = get_field( 'cancellation_total_amount', $booking_id );
    $charges = $charges ? $charges : 0;

    $variables = array(
        '{{BOOKING_NUMBER}}'        => get_field( 'booking_number', $booking_id ),
        '{{PASSENGERS}}'            => strtr( kanda_get_post_meta( $booking_id, 'passenger_names' ), array( '##' => ', ' ) ),
        '{{AGENCY_NAME}}'           => kanda_get_user_meta( $booking->post_author, 'company_name' ),
        '{{HOTEL_NAME}}'            => get_field( 'hotel_name', $booking_id ),
        '{{ROOM_TYPE}}'             => get_field( 'room_type', $booking_id ),
        '{{CHECK_IN}}'              => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ),
        '{{CHECK_OUT}}'             => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ),
        '{{CANCELLATION_CHARGES}}'  => sprintf( '%s USD', $charges )
    );

    $sent_user = kanda_mailer()->send_user_email( $booking->post_author, $subject, $message, $variables );
    if( ! $sent_user ) {
        kanda_logger()->log( sprintf( 'Error sending email to user for new booking. booking_id=%d' ), $booking_id );
    }

    $sent_to_admin = kanda_multicheck_checked( 'on_booking_cancel', 'admin_notifications_events' );
    if( $sent_to_admin ) {
        $sent_admin = kanda_mailer()->send_admin_email($subject, $message, $variables);
        if (!$sent_admin) {
            kanda_logger()->log(sprintf('Error sending email to admin for new booking. booking_id=%d', $booking_id ) );
        }
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

/**
 * Get WP_Query args for bookings list of current user
 * @return array
 */
function kanda_get_booking_query_args() {

    $meta_query = array();

    if( isset( $_GET['search'] ) && $_GET['search'] ) {
        $names = array();
        if( isset( $_GET['pfn'] ) && $_GET['pfn'] ) {
            $names[] = sanitize_text_field( $_GET['pfn'] );
        }
        if( isset( $_GET['pln'] ) && $_GET['pln'] ) {
            $names[] = sanitize_text_field( $_GET['pln'] );
        }

        if( ! empty( $names ) ) {
            $meta_query[] = array(
                'key'       => 'passenger_names',
                'value'     => implode( ' ', $names ),
                'compare'   => 'LIKE'
            );
        }

        if( isset( $_GET['city'] ) && $_GET['city'] ) {
            $meta_query[] = array(
                'key'       => 'hotel_city',
                'value'     => sanitize_text_field( $_GET['city'] ),
                'compare'   => '='
            );
        }


        if( isset( $_GET['hotel_name'] ) && $_GET['hotel_name'] && ( $hotel_code = kanda_get_hotel_code_by_name( $_GET['hotel_name'] ) ) ) {
            $meta_query[] = array(
                'key'       => 'hotel_code',
                'value'     => $hotel_code,
                'compare'   => '='
            );
        }

        if( isset( $_GET['brn'] ) && $_GET['brn'] ) {
            $meta_query[] = array(
                'key'       => 'booking_number',
                'value'     => sanitize_text_field( $_GET['brn'] ),
                'compare'   => '='
            );
        }

        if( isset( $_GET['check_in'] ) && $_GET['check_in'] ) {
            $meta_query[] = array(
                'key'       => 'start_date',
                'value'     => sanitize_text_field( $_GET['check_in'] ),
                'compare'   => '='
            );
        }

        if( isset( $_GET['status'] ) && $_GET['status'] ) {
            $meta_query[] = array(
                'key'       => 'booking_status',
                'value'     => sanitize_text_field( $_GET['status'] ),
                'compare'   => '='
            );
        }

        if( count( $meta_query ) > 1 ) {
            $meta_query['realtion'] = 'AND';
        }

    } else {
        $meta_query[] = array(
            'key'       => 'end_date',
            'value'     => date('Ymd'),
            'compare'   => '>=',
            'type' => 'DATE'
        );
    }

    $args = array(
        'post_type' => 'booking',
        'post_status' => 'publish',
        'author' => get_current_user_id(),
        'paged'  => kanda_get_paged(),
        'order' => 'DESC',
        'orderby' => 'date',
        'posts_per_page' => 10,
        'meta_query' => $meta_query
    );

    return $args;
}

/**
 * Prepare hotel names for autocomplete
 * @return array
 */
function kanda_get_hotels_for_autocomplete() {
    global $wpdb;
    $query = "SELECT `post_title` FROM `wp_posts` WHERE `post_type` = 'hotel'";

    $hotels = array();
    $results = $wpdb->get_results( $query, OBJECT_K );

    return array_keys( $results );
}

/**
 * Get hotel code by name
 * @param $name
 * @return null|string
 */
function kanda_get_hotel_code_by_name( $name ) {
    global $wpdb;

    $query = "SELECT meta_value
                FROM {$wpdb->postmeta}
                WHERE
                `post_id` = ( SELECT ID FROM `{$wpdb->posts}` WHERE `post_title` = '{$name}' AND `post_type` = 'hotel' ) AND
                `meta_key` = 'hotelcode'";

    return $wpdb->get_var( $query );
}

/**
 * Render banners
 * @param $location main | sidebar
 * @param array $args
 */
function kanda_render_banners( $location, $args = array() ) {
    $args = wp_parse_args( $args, array(
        'before' => '',
        'after'  => '',
        'class'  => 'slider'
    ) );

    $banners = kanda_get_theme_option( $location . '_banners_slider_gallery', array() );
    shuffle( $banners );
    if( $banners ) {
        echo $args['before'];
        ?>
        <div class="<?php echo $args['class']; ?>">
            <?php foreach ($banners as $banner) { ?>
                <div><?php echo wp_get_attachment_image( $banner['image'], 'full' ); ?></div>
            <?php } ?>
        </div>
        <?php
        echo $args['after'];
    }
}

add_shortcode( 'banners_slider', 'kanda_banners_slider' );
function kanda_banners_slider( $atts ) {
    $atts = shortcode_atts( array(
        'location' => ''
    ), $atts, 'banners_slider' );

    $html = '';
    if( $atts['location'] ) {
        $class = ( $atts['location'] == 'main' ) ? 'main_banners' : 'slider';
        ob_start();
        kanda_render_banners( $atts['location'], array( 'class' => $class ) );
        $html = ob_get_clean();
    }

    return $html;
}