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