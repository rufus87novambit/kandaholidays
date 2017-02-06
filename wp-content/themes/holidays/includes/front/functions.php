<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/*
 * Map
 *
 * 1. Dependencies
 * 2. Assets
 * 3. Action callbacks
 * 4. Authorization template
 */

/************************************************** 1. Dependencies **************************************************/

/************************************************** /end Dependencies ************************************************/

/***************************************************** 2. Assets *****************************************************/
/**
 * Add frontend css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles() {
    wp_enqueue_style( 'front', KANDA_THEME_URL . 'css/front.min.css', array(), null);
}

/**
 * Add frontend js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null );
    wp_enqueue_script( 'front', KANDA_THEME_URL . 'js/front.min.js', array( 'jquery' ), null );
    wp_localize_script( 'front', 'kanda', array(
        'validation' => Kanda_Config::get( 'validation->front' )
    ) );
}
/***************************************************** /end Assets ***************************************************/

/************************************************* 3. Action callbacks ***********************************************/
/**
 * Send notification to admin after user login
 * @param $user
 */
add_action( 'kanda/after_user_login', 'kanda_after_user_login', 10, 1 );
function kanda_after_user_login( $user ) {

    /* Do not send if admin does not want */
    $sent = kanda_fields()->get_option( 'send_admin_notification_on_user_login' );
    if( ! $sent || user_can( $user, 'administrator' ) ) {
        return;
    }

    $user_meta = get_user_meta( $user->ID );

    $subject = esc_html__( 'User Login', 'kanda' );

    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user logged in at {{SITE_NAME}} with following details.', 'kanda' ) );
    $message .= '<table style="width:100%;">';
    $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Username', 'kanda' ), $user->user_login );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Email', 'kanda' ), $user->user_email );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'First Name', 'kanda' ), $user->first_name );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Last Name', 'kanda' ), $user->last_name );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Company Name', 'kanda' ), $user_meta['company_name'][0] );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'License ID', 'kanda' ), $user_meta['company_license'][0] );
    $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Profile Status', 'kanda' ), $user_meta['profile_status'][0] ? esc_html__( 'Active', 'kanda' ) : esc_html__( 'Inactive', 'kanda' ) );
    $message .= '</table>';

    $message .= '<p></p>';
    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see more detailed information about user by visiting following link', 'kanda' ) );
    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( 'user_id', $user->ID, admin_url( 'user-edit.php' ) ) );

    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
        Kanda_Log::log( sprintf( 'Error sending email to admin for new registered user. user_id=%d' ), $user->ID );
    }
}

//do_action( 'kanda/after_user_login', new WP_User(2) );

/**
 * Send notification to admin after new user registration if required
 *
 * @param $user_id
 */
add_action( 'kanda/after_new_user_registration', 'kanda_after_new_user_registration', 10, 1 );
function kanda_after_new_user_registration( $user_id ) {

    /* Do not send if admin does not want */
    $sent = kanda_fields()->get_option( 'send_admin_notification_on_user_register' );
    if( ! $sent ) {
        return;
    }

    $user = get_user_by( 'id', (int)$user_id );
    if( $user ) {

        $user_meta = get_user_meta( $user->ID );

        $subject = esc_html__( 'New User Registration', 'kanda' );

        $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
        $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A new user registered at {{SITE_NAME}} with following details.', 'kanda' ) );
        $message .= '<table style="width:100%;">';
            $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Username', 'kanda' ), $user->user_login );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Email', 'kanda' ), $user->user_email );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'First Name', 'kanda' ), $user->first_name );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Last Name', 'kanda' ), $user->last_name );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Company Name', 'kanda' ), $user_meta['company_name'][0] );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'License ID', 'kanda' ), $user_meta['company_license'][0] );
            $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Profile Status', 'kanda' ), $user_meta['profile_status'][0] ? esc_html__( 'Active', 'kanda' ) : esc_html__( 'Inactive', 'kanda' ) );
        $message .= '</table>';

        $message .= '<p></p>';
        $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see more detailed information and activate / deactivate user profile by visiting following link', 'kanda' ) );
        $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( 'user_id', $user->ID, admin_url( 'user-edit.php' ) ) );

        if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
            Kanda_Log::log( sprintf( 'Error sending email to admin for new registered user. user_id=%d' ), $user->ID );
        }

    }
}

/**
 * Send notification to admin after user forgot password success
 *
 * @param $user
 */
add_action( 'kanda/after_forgot_password', 'kanda_after_forgot_password', 10, 1 );
function kanda_after_forgot_password( $user ) {

    /* Do not send if admin does not want */
    $sent = kanda_fields()->get_option( 'send_admin_notification_on_user_forgot_password' );
    if( ! $sent ) {
        return;
    }

    $user_meta = get_user_meta( $user->ID );

    $subject = esc_html__( 'Forgot password request', 'kanda' );

    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user requested for password reset at {{SITE_NAME}} with following details.', 'kanda' ) );
    $message .= '<table style="width:100%;">';
        $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Username', 'kanda' ), $user->user_login );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Email', 'kanda' ), $user->user_email );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'First Name', 'kanda' ), $user->first_name );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Last Name', 'kanda' ), $user->last_name );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Company Name', 'kanda' ), $user_meta['company_name'][0] );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'License ID', 'kanda' ), $user_meta['company_license'][0] );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Profile Status', 'kanda' ), $user_meta['profile_status'][0] ? esc_html__( 'Active', 'kanda' ) : esc_html__( 'Inactive', 'kanda' ) );
    $message .= '</table>';

    $message .= '<p></p>';
    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see more detailed information about user by visiting following link', 'kanda' ) );
    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( 'user_id', $user->ID, admin_url( 'user-edit.php' ) ) );

    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
        Kanda_Log::log( sprintf( 'Error sending email to admin for new registered user. user_id=%d' ), $user->ID );
    }
}

/**
 * Send notification to admin after successfull password change
 *
 * @param $user
 */
add_action( 'kanda/after_password_reset', 'kanda_after_password_reset', 10, 1 );
function kanda_after_password_reset( $user ) {

    /* Do not send if admin does not want */
    $sent = kanda_fields()->get_option( 'send_admin_notification_on_user_password_reset' );
    if( ! $sent ) {
        return;
    }

    $user_meta = get_user_meta( $user->ID );

    $subject = esc_html__( 'Profile password reset', 'kanda' );

    $message = sprintf( '<p>%1$s</p>', esc_html__( 'Hi.', 'kanda' ) );
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user with following details successfully reset profile password in at {{SITE_NAME}}', 'kanda' ) );
    $message .= '<table style="width:100%;">';
        $message .= '<tr><td style="width:17%;"></td><td style="width:83%;"></td></tr>';
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Username', 'kanda' ), $user->user_login );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Email', 'kanda' ), $user->user_email );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'First Name', 'kanda' ), $user->first_name );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Last Name', 'kanda' ), $user->last_name );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Company Name', 'kanda' ), $user_meta['company_name'][0] );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'License ID', 'kanda' ), $user_meta['company_license'][0] );
        $message .= sprintf( '<tr><td>%1$s:</td><td>%2$s</td></tr>', esc_html__( 'Profile Status', 'kanda' ), $user_meta['profile_status'][0] ? esc_html__( 'Active', 'kanda' ) : esc_html__( 'Inactive', 'kanda' ) );
    $message .= '</table>';

    $message .= '<p></p>';
    $message .= sprintf( '<p>%s</p>', esc_html__( 'You can see more detailed information about user by visiting following link', 'kanda' ) );
    $message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', add_query_arg( 'user_id', $user->ID, admin_url( 'user-edit.php' ) ) );

    if( ! kanda_mailer()->send_admin_email( $subject, $message ) ) {
        Kanda_Log::log( sprintf( 'Error sending email to admin for new registered user. user_id=%d' ), $user->ID );
    }
}
/************************************************ /end Action callbacks **********************************************/

/********************************************* 4. Authorization template *********************************************/

/**
 * Auth coolie expiration time
 */
add_filter( 'auth_cookie_expiration', 'kanda_auth_cookie_expiration', 10, 3 );
function kanda_auth_cookie_expiration( $length, $user_id, $remember ) {
    if( user_can( (int)$user_id, 'administrator' ) ) {
        $length = Kanda_Config::get( 'cookie_lifetime->authentication->administrator' );
    } else {
        $length = Kanda_Config::get( 'cookie_lifetime->authentication->agency' );
    }

    return $length;
}

/******************************************** /end Authorization template ********************************************/