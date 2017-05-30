<?php
/**
 * Kanda Theme "Admin Notification" helper
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Admin_Notification {

    /**
     * Setup a notification
     * @param $type
     * @param $message
     */
    public static function set( $type, $message ) {
        kanda_start_session();

        $_SESSION[ 'kanda_notification' ] = array(
            'type' 		=> $type,
            'message' 	=> $message
        );
    }

    /**
     * Render a notification
     */
    public static function render() {
        kanda_start_session();
        if( isset( $_SESSION['kanda_notification'] ) ) {
            $notification =  (array)$_SESSION['kanda_notification'];

            if( isset( $notification['type'] ) && $notification['type'] && isset( $notification['message'] ) && $notification['message'] ) {
                printf( '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>', $notification['type'], $notification['message'] );
                $_SESSION['kanda_notification'] = array();
            }
        }

    }

}

/**
 * Render admin notification
 */
add_action( 'admin_notices', array( 'Kanda_Admin_Notification', 'render' ) );