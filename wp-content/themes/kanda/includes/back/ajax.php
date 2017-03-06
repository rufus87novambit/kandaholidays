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

add_action( 'wp_ajax_kanda_upload_avatar', 'kanda_ajax_upload_avatar' );
function kanda_ajax_upload_avatar() {

    $is_valid = true;
    if( check_ajax_referer( 'kanda-upload-avatar', 'security', false ) ) {

        /* handle file upload */
        $file = $_FILES[ 'avatar' ];
        $upload = wp_handle_upload( $file, array( 'test_form' => false ) );

        if( $upload && isset( $upload[ 'error' ] ) ) {
            $is_valid = false;
            $message = $upload[ 'error' ];
        }

        if( $is_valid ) {

            $filename = $upload[ 'file' ];
            $file_type = wp_check_filetype( basename( $filename ), null );
            $wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid'              => $wp_upload_dir['url'] . '/' . basename( $filename ),
                'post_mime_type'    => $file_type['type'],
                'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content'      => '',
                'post_status'       => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $filename );
            if ( $attach_id ) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                wp_update_attachment_metadata( $attach_id, $attach_data );

                update_user_meta( get_current_user_id(), 'avatar', $attach_id );
                list( $full_url, $full_width, $full_height, $full_is_intermediate ) = wp_get_attachment_image_src( $attach_id, 'full' );
                list( $thumb_url, $thumb_width, $thumb_height, $thumb_is_intermediate ) = wp_get_attachment_image_src( $attach_id, 'user-avatar' );

            } else {
                $is_valid = false;
                $message = __( 'Error uploading file', 'kanda' );
            }
        }

    } else {
        $is_valid = false;
        $message = esc_html__( 'Invalid request', 'kanda' );
    }

    if( $is_valid ) {
        wp_send_json_success( array(
            'full_url'  => $full_url,
            'thumb_url' => $thumb_url
        ) );
    } else {
        wp_send_json_error( array(
            'message' => $message
        ) );
    }

}

/**
 * Ajax search hotels
 */
add_action( 'wp_ajax_search_hotels', 'kanda_search_hotels' );
function kanda_search_hotels() {

    if( ! class_exists( 'Hotels_Controller' ) ) {
        require_once ( KANDA_CONTROLLERS_PATH . 'class-hotels-controller.php' );
    }

    $controller = new Hotels_Controller();
    $controller->search();
}

/**
 * Ajax get hotel details
 */
add_action( 'wp_ajax_hotel_details', 'kanda_get_hotel_details' );
function kanda_get_hotel_details(){
    if( ! class_exists( 'Hotels_Controller' ) ) {
        require_once ( KANDA_CONTROLLERS_PATH . 'class-hotels-controller.php' );
    }

    $controller = new Hotels_Controller();
    $controller->get_hotel_details();
}

/**
 * Ajax get hotel cancellation policy
 */
add_action( 'wp_ajax_hotel_cancellation_policy', 'kanda_get_hotel_cancellation_policy' );
function kanda_get_hotel_cancellation_policy() {
    if( ! class_exists( 'Hotels_Controller' ) ) {
        require_once ( KANDA_CONTROLLERS_PATH . 'class-hotels-controller.php' );
    }

    $controller = new Hotels_Controller();
    $controller->get_cancellation_policy();
}

/**
 * Get specific hotels list
 */
add_action( 'wp_ajax_city_hotels', 'kanda_get_city_hotels_list' );
function kanda_get_city_hotels_list( $city = false ) {
    $is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

    $city = $is_ajax ? $_REQUEST[ 'city' ] : $city;
    $is_valid = true;
    if( $city && array_key_exists( $city, IOL_Config::get( 'cities' ) ) ) {

        global $wpdb;
        $query = "SELECT `p`.`post_title` FROM `wp_posts` AS `p`
                    LEFT JOIN `wp_postmeta` AS `pm` ON `pm`.`post_id` = `p`.`ID` AND `meta_key` = 'hotelcity'
                    WHERE `p`.`post_type` = 'hotel' AND `pm`.`meta_value` = '{$city}'";

        $results = $wpdb->get_col( $query );
    } else {
        $is_valid = false;
        $message = __( 'Invalid city', 'kanda' );
    }

    if( $is_ajax ) {
        $is_valid ? wp_send_json_success( $results ) : wp_send_json_error( $message );
    } else {
        return $is_valid ? array( 'is_valid' => $is_valid, 'results' => $results ) : array( 'is_valid' => $is_valid, 'message' => $message );
    }

}