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
require_once( KH_FRONT_PATH . 'shortcodes.php' );
/************************************************** /end Dependencies ************************************************/

/***************************************************** 2. Assets *****************************************************/
/**
 * Add frontend css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles() {
    wp_enqueue_style( 'front', KH_THEME_URL . 'css/front.min.css', array(), null);
}

/**
 * Add frontend js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null );
    wp_enqueue_script( 'front', KH_THEME_URL . 'js/front.min.js', array( 'jquery' ), null );
    wp_localize_script( 'front', 'kanda', array(
        'validation' => KH_Config::get( 'validation->front' )
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
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user logged in at %SITE_NAME% with following details.', 'kanda' ) );
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
        $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A new user registered at %SITE_NAME% with following details.', 'kanda' ) );
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
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user requested for password reset at %SITE_NAME% with following details.', 'kanda' ) );
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
    $message .= sprintf( '<p>%1$s</p>', esc_html__( 'A user with following details successfully reset profile password in at %SITE_NAME%', 'kanda' ) );
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
 * Authorization template functionality
 */
add_action( 'kanda/authorization', 'kanda_authorization_page' );
function kanda_authorization_page() {

    // deny authenticated user assess
    do_action( 'kanda/deny_user_access', KH_Config::get( 'agency_role' ) );

    global $wp_query;
    $action = sprintf( 'kanda/%s', $wp_query->post->post_name );
    if( has_action( $action ) ) {
        do_action( $action );
    } else {
        $wp_query->set_404();
    }
}
/**
 * Auth coolie expiration time
 */
add_filter( 'auth_cookie_expiration', 'kanda_auth_cookie_expiration', 10, 3 );
function kanda_auth_cookie_expiration( $length, $user_id, $remember ) {
    if( user_can( (int)$user_id, 'administrator' ) ) {
        $length = KH_Config::get( 'cookie_lifetime->authentication->administrator' );
    } else {
        $length = KH_Config::get( 'cookie_lifetime->authentication->agency' );
    }

    return $length;
}

/**
 * Process login request
 */
add_action( 'kanda/login', 'kanda_check_login', 10 );
function kanda_check_login() {

    add_filter( 'nonce_life', function () { return KH_Config::get( 'cookie_lifetime->login' ); } );

    $kanda_request = array(
        'success' => false,
        'message' => false,
        'fields'  => array(
            'username' => array(
                'value' => '',
                'valid' => true,
                'msg'   => ''
            ),
            'password' => array(
                'value' => '',
                'valid' => true,
                'msg'   => ''
            ),
            'remember' => array(
                'value' => 1
            )
        ),
    );
    if( isset( $_POST['kanda_login'] ) ) {

        $nonce = ( isset( $_POST['kanda_nonce'] ) && $_POST['kanda_nonce'] ) ? $_POST['kanda_nonce'] : '';
        if( wp_verify_nonce( $nonce, 'kanda_login' ) ) {
            $username = ( isset( $_POST['username'] ) && $_POST['username'] ) ? $_POST['username'] : '';
            $password = ( isset( $_POST['password'] ) && $_POST['password'] ) ? $_POST['password'] : '';
            $remember = isset( $_POST['remember'] ) ? (bool)$_POST['password'] : false;

            $has_error = false;
            $validation_rules = KH_Config::get( 'validation->front->form_login' );

            $kanda_request['fields']['username']['value'] = $username;
            if( ! $username ) {
                $has_error = true;
                $kanda_request['fields']['username'] = array_merge(
                    $kanda_request['fields']['username'],
                    array( 'valid' => false, 'msg' => $validation_rules['username']['required'] )
                );
            } elseif( ! ctype_alnum( $username ) ) {
                $has_error = true;
                $kanda_request['fields']['username'] = array_merge(
                    $kanda_request['fields']['username'],
                    array( 'valid' => false, 'msg' => $validation_rules['username']['alphanumeric'] )
                );
            }

            $kanda_request['fields']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => $validation_rules['password']['required'] )
                );
            }

            $kanda_request['fields']['remember']['value'] = (int)$remember;

            if( ! $has_error ) {

                $user = get_user_by( 'login', $username );
                if( $user ) {

                    $is_activated = true;
                    if( user_can( $user, 'travel_agency' ) ) {
                        $is_activated = get_user_meta( $user->ID, 'profile_status', true );
                    }

                    if( $is_activated ) {

                        $user = wp_signon(array(
                            'user_login' => $username,
                            'user_password' => $password,
                            'remember' => $remember
                        ));

                        if (is_wp_error($user)) {
                            $kanda_request['message'] = esc_html__('Invalid username / password', 'kanda');
                            $kanda_request['fields']['username']['valid'] = false;
                            $kanda_request['fields']['password']['valid'] = false;
                        } else {

                            do_action( 'kanda/after_user_login', $user );

                            wp_redirect( site_url('/portal') ); die;
                        }

                    } else {
                        $kanda_request['success'] = true;
                        $kanda_request['message'] = __( 'Your account is inactive. You will get an email once it is activated.', 'kanda' );
                    }
                } else {
                    $kanda_request['fields']['username'][ 'valid' ] = false;
                    $kanda_request['fields']['username'][ 'msg' ] = esc_html__( 'Invalid username', 'kanda' );
                }
            }
        } else {
            $kanda_request['message'] = esc_html__( 'Invalid request', 'kanda' );
        }

    }

    ob_start();
    include ( KH_THEME_PATH . 'views/front/login.php' );
    echo ob_get_clean();

}

/**
 * Process register request
 */
add_action( 'kanda/register', 'kanda_check_register', 10 );
function kanda_check_register() {

    add_filter( 'nonce_life', function () { return KH_Config::get( 'cookie_lifetime->register' ); } );

    $kanda_request = array(
        'success' => false,
        'message' => false,
        'fields'  => array(
            'personal' => array(
                'username' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'email' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'password' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'confirm_password' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'first_name' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'last_name' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'mobile' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'position' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
            ),
            'company' => array(
                'name' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'license' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'address' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'city' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'country' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'phone' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                ),
                'website' => array(
                    'value' => '',
                    'valid' => true,
                    'msg'   => ''
                )
            ),
        ),
    );

    if( isset( $_POST['kanda_register'] ) ) {

        $nonce = ( isset( $_POST['kanda_nonce'] ) && $_POST['kanda_nonce'] ) ? $_POST['kanda_nonce'] : '';
        if( wp_verify_nonce( $nonce, 'kanda_register' ) ) {

            $username = isset( $_POST['personal']['username'] ) ? $_POST['personal']['username'] : '';
            $email = isset( $_POST['personal']['email'] ) ? $_POST['personal']['email'] : '';
            $password = isset( $_POST['personal']['password'] ) ? $_POST['personal']['password'] : '';
            $confirm_password = isset( $_POST['personal']['confirm_password'] ) ? $_POST['personal']['confirm_password'] : '';
            $first_name = isset( $_POST['personal']['first_name'] ) ? $_POST['personal']['first_name'] : '';
            $last_name = isset( $_POST['personal']['last_name'] ) ? $_POST['personal']['last_name'] : '';
            $mobile = isset( $_POST['personal']['mobile'] ) ? $_POST['personal']['mobile'] : '';
            $position = isset( $_POST['personal']['position'] ) ? $_POST['personal']['position'] : '';

            $company_name = isset( $_POST['company']['name'] ) ? $_POST['company']['name'] : '';
            $company_license = isset( $_POST['company']['license'] ) ? $_POST['company']['license'] : '';
            $company_address = isset( $_POST['company']['address'] ) ? $_POST['company']['address'] : '';
            $company_city = isset( $_POST['company']['city'] ) ? $_POST['company']['city'] : '';
            $company_country = isset( $_POST['company']['country'] ) ? $_POST['company']['country'] : '';
            $company_phone = isset( $_POST['company']['phone'] ) ? $_POST['company']['phone'] : '';
            $company_website = isset( $_POST['company']['website'] ) ? $_POST['company']['website'] : '';

            $has_error = false;
            $validation_rules = KH_Config::get( 'validation->front->form_register' );

            $validation_data = KH_Config::get( 'validation->front->data' );
            $kanda_request['fields']['personal']['username']['value'] = $username;
            if( ! $username ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => $validation_rules['username']['required'] )
                );
            } elseif( ! ctype_alnum( $username ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => $validation_rules['username']['alphanumeric'] )
                );
            } elseif( ! filter_var( strlen( $username ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['username_min_length'], 'max_range' => $validation_data['username_max_length'] ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => $validation_rules['username']['rangelength'] )
                );
            }

            $kanda_request['fields']['personal']['email']['value'] = $email;
            if( ! $email ) {
                $has_error = true;
                $kanda_request['fields']['personal']['email'] = array_merge(
                    $kanda_request['fields']['personal']['email'],
                    array( 'valid' => false, 'msg' => $validation_rules['email']['required'] )
                );
            } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['email'] = array_merge(
                    $kanda_request['fields']['personal']['email'],
                    array( 'valid' => false, 'msg' => $validation_rules['email']['email'] )
                );
            }

            $kanda_request['fields']['personal']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['personal']['password'] = array_merge(
                    $kanda_request['fields']['personal']['password'],
                    array( 'valid' => false, 'msg' => $validation_rules['password']['required'] )
                );
            } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['password'] = array_merge(
                    $kanda_request['fields']['personal']['password'],
                    array( 'valid' => false, 'msg' => $validation_rules['password']['rangelength'] )
                );
            }

            $kanda_request['fields']['personal']['confirm_password']['value'] = $confirm_password;
            if( ! $confirm_password ) {
                $has_error = true;
                $kanda_request['fields']['personal']['confirm_password'] = array_merge(
                    $kanda_request['fields']['personal']['confirm_password'],
                    array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['required'] )
                );
            } elseif( $password && ( $confirm_password != $password ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['confirm_password'] = array_merge(
                    $kanda_request['fields']['personal']['confirm_password'],
                    array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['equalTo'] )
                );
            }

            $kanda_request['fields']['personal']['first_name']['value'] = $first_name;
            if( ! $first_name ) {
                $has_error = true;
                $kanda_request['fields']['personal']['first_name'] = array_merge(
                    $kanda_request['fields']['personal']['first_name'],
                    array( 'valid' => false, 'msg' => $validation_rules['first_name']['required'] )
                );
            }

            $kanda_request['fields']['personal']['last_name']['value'] = $last_name;
            if( ! $last_name ) {
                $has_error = true;
                $kanda_request['fields']['personal']['last_name'] = array_merge(
                    $kanda_request['fields']['personal']['last_name'],
                    array( 'valid' => false, 'msg' => $validation_rules['last_name']['required'] )
                );
            }

            $kanda_request['fields']['personal']['mobile']['value'] = $mobile;
            if( $mobile && !( preg_match( '/^[\+:]*\d{9,}$/', $mobile ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['mobile'] = array_merge(
                    $kanda_request['fields']['personal']['mobile'],
                    array( 'valid' => false, 'msg' => $validation_rules['mobile']['phone_number'] )
                );
            }

            $kanda_request['fields']['company']['name']['value'] = $company_name;
            if( ! $company_name ) {
                $has_error = true;
                $kanda_request['fields']['company']['name'] = array_merge(
                    $kanda_request['fields']['company']['name'],
                    array( 'valid' => false, 'msg' => $validation_rules['company_name']['required'] )
                );
            }

            $kanda_request['fields']['company']['license']['value'] = $company_license;
            if( ! $company_license ) {
                $has_error = true;
                $kanda_request['fields']['company']['license'] = array_merge(
                    $kanda_request['fields']['company']['license'],
                    array( 'valid' => false, 'msg' => $validation_rules['company_license']['required'] )
                );
            }

            $kanda_request['fields']['company']['phone']['value'] = $company_phone;
            if( $company_phone && !( preg_match( '/^[^:]*\d{9,}$/', $company_phone ) ) ) {
                $has_error = true;
                $kanda_request['fields']['company']['phone'] = array_merge(
                    $kanda_request['fields']['company']['phone'],
                    array( 'valid' => false, 'msg' => $validation_rules['company_phone']['phone_number'] )
                );
            }

            if( ! $has_error ) {
                $user_id = wp_insert_user( array(
                    'user_login' => $username,
                    'user_pass'  => $password,
                    'user_email' => $email,
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'role'       => 'travel_agency',
                    'user_url'   => $company_website
                ) );

                if( is_wp_error( $user_id ) ) {
                    $kanda_request['message'] = $user_id->get_error_message();
                } else {
                    update_user_meta( $user_id, 'profile_status', 0 );
                    update_user_meta( $user_id, 'mobile', $mobile );
                    update_user_meta( $user_id, 'position', $position );
                    update_user_meta( $user_id, 'company_name', $company_name );
                    update_user_meta( $user_id, 'company_license', $company_license );
                    update_user_meta( $user_id, 'company_address', $company_address );
                    update_user_meta( $user_id, 'company_city', $company_city );
                    update_user_meta( $user_id, 'company_country', $company_country );
                    update_user_meta( $user_id, 'company_phone', $company_phone );

                    $kanda_request['fields']['personal']['username']['value'] = '';
                    $kanda_request['fields']['personal']['email']['value'] = '';
                    $kanda_request['fields']['personal']['password']['value'] = '';
                    $kanda_request['fields']['personal']['confirm_password']['value'] = '';
                    $kanda_request['fields']['personal']['first_name']['value'] = '';
                    $kanda_request['fields']['personal']['last_name']['value'] = '';
                    $kanda_request['fields']['personal']['mobile']['value'] = '';
                    $kanda_request['fields']['personal']['position']['value'] = '';

                    $kanda_request['fields']['company']['name']['value'] = '';
                    $kanda_request['fields']['company']['license']['value'] = '';
                    $kanda_request['fields']['company']['address']['value'] = '';
                    $kanda_request['fields']['company']['city']['value'] = '';
                    $kanda_request['fields']['company']['country']['value'] = '';
                    $kanda_request['fields']['company']['phone']['value'] = '';
                    $kanda_request['fields']['company']['website']['value'] = '';

                    $kanda_request['success'] = true;
                    $kanda_request['message'] = esc_html__( 'Your profile has been successfully created. You will get an email once it is activated.', 'kanda' );

                    do_action( 'kanda/after_new_user_registration', $user_id );
                }
            }

        } else {
            $kanda_request['message'] = esc_html__( 'Invalid request', 'kanda' );
        }

    }

    ob_start();
    include get_template_directory() . '/views/front/register.php';
    echo ob_get_clean();

}

/**
 * Process forgot password request
 */
add_action( 'kanda/forgot-password', 'kanda_check_forgot_password', 10 );
function kanda_check_forgot_password() {

    add_filter('nonce_life', function () { return KH_Config::get( 'cookie_lifetime->forgot_password' ); });

    $kanda_request = array(
        'success' => false,
        'message' => false,
        'fields' => array(
            'username_email' => array(
                'value' => '',
                'valid' => true,
                'msg' => ''
            )
        ),
    );
    if ( isset( $_POST['kanda_forgot'] ) ) {

        $nonce = (isset($_POST['kanda_nonce']) && $_POST['kanda_nonce']) ? $_POST['kanda_nonce'] : '';
        if (wp_verify_nonce($nonce, 'kanda_forgot')) {
            $username_email = (isset($_POST['username_email']) && $_POST['username_email']) ? $_POST['username_email'] : '';

            $has_error = false;
            $validation_rules = KH_Config::get( 'validation->front->form_forgot_password' );

            $kanda_request['fields']['username_email']['value'] = $username_email;
            if( ! $username_email ) {
                $has_error = true;
                $kanda_request['fields']['username_email'] = array_merge(
                    $kanda_request['fields']['username_email'],
                    array( 'valid' => false, 'msg' => $validation_rules['username_email']['required'] )
                );
            }

            if( ! $has_error ) {
                $is_email = filter_var( $username_email, FILTER_VALIDATE_EMAIL );

                if( $is_email ) {
                    $user = get_user_by( 'email', $username_email );
                } else {
                    $user = get_user_by( 'login', $username_email );
                }

                if( ! $user ) {
                    $kanda_request['fields']['username_email'] = array_merge(
                        $kanda_request['fields']['username_email'],
                        array( 'valid' => false, 'msg' => esc_html__( 'User not found', 'kanda' ) )
                    );
                } else {

                    add_filter('nonce_life', function () { return KH_Config::get( 'cookie_lifetime->reset_password' ); });

                    $reset_password_token = generate_random_string( 20 );
                    $password_reset_url = wp_nonce_url(
                        add_query_arg( array( 'rpt' => $reset_password_token ), site_url( '/reset-password' ) ),
                        'kanda_reset_password',
                        'rps'
                    );
                    $password_reset_url = sprintf( '<a href="%1$s">%1$s</a>', $password_reset_url );

                    update_user_meta( $user->ID, 'forgot_password_token', $reset_password_token );

                    $to = $user->user_email;
                    $subject = kanda_fields()->get_option( 'kanda_email_forgot_password_subject' );
                    $message = kanda_fields()->get_option( 'kanda_email_forgot_password_body' );
                    $variables = array( '%RESET_LINK%' => $password_reset_url );

                    if( kanda_mailer()->send_user_email( $to, $subject, $message, $variables ) ) {
                        $kanda_request['success'] = true;
                        $kanda_request['message'] = esc_html__( 'An email with instructions is sent to your email address.', 'kanda' );
                        $kanda_request['fields']['username_email']['value'] = '';

                        do_action( 'kanda/after_forgot_password', $user );

                    } else {
                        $kanda_request['message'] = esc_html__( 'Oops! Something went wrong while sending email. Please try again', 'kanda' );
                        Kanda_Log::log( sprintf( 'There was an error while sending password reset email to user: Details: user_id=%1$d, reset_url=%2$s', $user->ID, $password_reset_url ) );
                    }

                }
            }
        }

    }

    ob_start();
    include get_template_directory() . '/views/front/forgot.php';
    echo ob_get_clean();

}

/**
 * Process reset password request
 */
add_action( 'kanda/reset-password', 'kanda_check_reset_password', 10 );
function kanda_check_reset_password() {

    add_filter('nonce_life', function () { return KH_Config::get( 'cookie_lifetime->forgot_password' ); });

    $kanda_request = array(
        'success' => false,
        'message' => false,
        'has_error' => true,
        'fields' => array(
            'user_id' => array(
                'value' => '',
                'value' => true,
                'msg'   => ''
            ),
            'password' => array(
                'value' => '',
                'valid' => true,
                'msg' => ''
            ),
            'confirm_password' => array(
                'value' => '',
                'valid' => true,
                'msg' => ''
            )
        ),
    );
    if (isset($_POST['kanda_reset'])) {

        $nonce = (isset($_POST['kanda_nonce']) && $_POST['kanda_nonce']) ? $_POST['kanda_nonce'] : '';

        if ( wp_verify_nonce($nonce, 'kanda_reset') ) {
            $password = (isset($_POST['password']) && $_POST['password']) ? $_POST['password'] : '';
            $confirm_password = (isset($_POST['confirm_password']) && $_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            $user_id = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : '';

            $has_error = false;
            $validation_data = KH_Config::get( 'validation->front->form_reset_password' );

            $kanda_request['fields']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => $validation_data['password']['required'] )
                );
            } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => $validation_data['password']['rangelength'] )
                );
            }

            $kanda_request['fields']['confirm_password']['value'] = $confirm_password;
            if( ! $confirm_password ) {
                $has_error = true;
                $kanda_request['fields']['confirm_password'] = array_merge(
                    $kanda_request['fields']['confirm_password'],
                    array( 'valid' => false, 'msg' => $validation_data['confirm_password']['required'] )
                );
            } elseif( $password && ( $confirm_password != $password ) ) {
                $has_error = true;
                $kanda_request['fields']['confirm_password'] = array_merge(
                    $kanda_request['fields']['confirm_password'],
                    array( 'valid' => false, 'msg' => $validation_data['confirm_password']['equalTo'] )
                );
            }

            $kanda_request['fields']['user_id']['value'] = $user_id;
            $kanda_request['has_error'] = false;

            if( ! $has_error ) {

                // do something
                $user_id = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $password ) );
                if( ! is_wp_error( $user_id ) ) {

                    delete_user_meta( $user_id, 'forgot_password_token' );

                    $user = new WP_User( $user_id );
                    $user = wp_signon(array(
                        'user_login'    => $user->user_login,
                        'user_password' => $password,
                        'remember'      => true
                    ));

                    do_action( 'kanda/after_password_reset', $user );

                    wp_redirect( site_url( '/' ) ); die;

                } else {
                    $kanda_request['message'] = esc_html__( 'Error reseting password. Please try again.', 'kanda' );
                }

            }
        }

    } else {

        add_filter('nonce_life', function () { return KH_Config::get( 'cookie_lifetime->reset_password' ); });

        $rpt = isset( $_GET['rpt'] ) ? $_GET['rpt'] : '';
        $key = isset( $_GET['rps'] ) ? $_GET['rps'] : '';

        if( wp_verify_nonce( $key, 'kanda_reset_password' ) ) {

            $users = get_users( array( 'meta_key' => 'forgot_password_token', 'meta_value' => $rpt ) );

            if( ! empty( $users ) ) {
                $kanda_request['has_error'] = false;
                $kanda_request['fields']['user_id']['value'] = $users[0]->ID;
            }

        } else {
            $kanda_request['message'] = __( 'Invalid request', 'kanda' );
        }

        add_filter('nonce_life', function () { return KH_Config::get( 'cookie_lifetime->forgot_password' ); });

    }

    ob_start();
    include get_template_directory() . '/views/front/reset.php';
    echo ob_get_clean();

}
/******************************************** /end Authorization template ********************************************/