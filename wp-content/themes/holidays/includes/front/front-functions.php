<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Generate a random string
 *
 * @param int $length
 * @return string
 */
function generate_random_string( $length = 10 ) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Add custom role
 */
add_action( 'after_switch_theme', 'kanda_add_user_roles', 10 );
function kanda_add_user_roles() {
    add_role(
        'agency',
        esc_html__( 'Travel Agency', 'kanda' ),
        array(
            'read' => true,  // true allows this capability
            'edit_posts' => true,
            'delete_posts' => false, // Use false to explicitly deny
        )
    );
}

/**
 * Add frontend js files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_scripts', 10 );
function kanda_enqueue_scripts() {
    if( is_page_template( 'page-front.php' ) ) {
        wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null );
        wp_enqueue_script('front', HOLIDAYS_THEME_URL . 'js/front.min.js', array( 'jquery' ), null);
    } elseif( is_page_template( 'page-portal.php' ) ) {
        wp_enqueue_script( 'portal', HOLIDAYS_THEME_URL . 'js/portal.min.js', array( 'jquery' ), null, true );
    }

}

/**
 * Add frontend css files
 */
add_action( 'wp_enqueue_scripts', 'kanda_enqueue_styles', 10 );
function kanda_enqueue_styles() {
    if( is_page_template( 'page-front.php' ) ) {
        wp_enqueue_style( 'front', HOLIDAYS_THEME_URL . 'css/front.min.css', array(), null);
    } elseif( is_page_template( 'page-portal.php' ) ) {
        wp_enqueue_style( 'portal', HOLIDAYS_THEME_URL . 'css/portal.min.css', array(), null );
    }
}


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
 * Deny travel agency access to page
 */
function kanda_deny_agency_access() {
    if( is_user_logged_in() && current_user_can( 'agency' ) ) {
        wp_redirect( site_url( '/portal' ) ); die;
    }
}

/**
 * Process login request
 */
add_action( 'kanda/login', 'kanda_check_login', 10 );
function kanda_check_login() {

    kanda_deny_agency_access();

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

            $kanda_request['fields']['username']['value'] = $username;
            if( ! $username ) {
                $has_error = true;
                $kanda_request['fields']['username'] = array_merge(
                    $kanda_request['fields']['username'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['remember']['value'] = (int)$remember;

            if( ! $has_error ) {

                $user = get_user_by( 'login', $username );
                if( $user ) {

                    $is_activated = true;
                    if( user_can( $user, 'travel_agency' ) ) {
                        $is_activated = get_user_meta( $user->ID, 'is_active', true );
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
    include ( HOLIDAYS_THEME_PATH . 'template-parts/home/login.php' );
    echo ob_get_clean();

}

/**
 * Process register request
 */
add_action( 'kanda/register', 'kanda_check_register', 10 );
function kanda_check_register() {

    kanda_deny_agency_access();

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

            $username_min_length = 6;
            $username_max_length = 25;
            $kanda_request['fields']['personal']['username']['value'] = $username;
            if( ! $username ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( ! ctype_alnum( $username ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Must contain only numbers and/or letters', 'kanda' ) )
                );
            } elseif( ! filter_var( strlen( $username ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $username_min_length, 'max_range' => $username_max_length ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['username'] = array_merge(
                    $kanda_request['fields']['personal']['username'],
                    array( 'valid' => false, 'msg' => sprintf( esc_html__( 'Username must be between %1$d and %2$d characters in length', 'kanda' ), $username_min_length, $username_max_length ) )
                );
            }

            $kanda_request['fields']['personal']['email']['value'] = $email;
            if( ! $email ) {
                $has_error = true;
                $kanda_request['fields']['personal']['email'] = array_merge(
                    $kanda_request['fields']['personal']['email'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['email'] = array_merge(
                    $kanda_request['fields']['personal']['email'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Invalid email', 'kanda' ) )
                );
            }

            $password_min_length = 8;
            $password_max_length = 50;
            $kanda_request['fields']['personal']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['personal']['password'] = array_merge(
                    $kanda_request['fields']['personal']['password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $password_min_length, 'max_range' => $password_max_length ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['password'] = array_merge(
                    $kanda_request['fields']['personal']['password'],
                    array( 'valid' => false, 'msg' => sprintf( esc_html__( 'Password must be between %1$d and %2$d characters in length', 'kanda' ), $password_min_length, $password_max_length ) )
                );
            }

            $kanda_request['fields']['personal']['confirm_password']['value'] = $confirm_password;
            if( ! $confirm_password ) {
                $has_error = true;
                $kanda_request['fields']['personal']['confirm_password'] = array_merge(
                    $kanda_request['fields']['personal']['confirm_password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( $password && ( $confirm_password != $password ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['password'] = array_merge(
                    $kanda_request['fields']['personal']['password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Passwords don\'t match', 'kanda' ) )
                );
                $kanda_request['fields']['personal']['confirm_password'] = array_merge(
                    $kanda_request['fields']['personal']['confirm_password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Passwords don\'t match', 'kanda' ) )
                );
            }

            $kanda_request['fields']['personal']['first_name']['value'] = $first_name;
            if( ! $first_name ) {
                $has_error = true;
                $kanda_request['fields']['personal']['first_name'] = array_merge(
                    $kanda_request['fields']['personal']['first_name'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['personal']['last_name']['value'] = $last_name;
            if( ! $last_name ) {
                $has_error = true;
                $kanda_request['fields']['personal']['last_name'] = array_merge(
                    $kanda_request['fields']['personal']['last_name'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['personal']['last_name']['value'] = $last_name;
            if( ! $last_name ) {
                $has_error = true;
                $kanda_request['fields']['personal']['last_name'] = array_merge(
                    $kanda_request['fields']['personal']['last_name'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['personal']['mobile']['value'] = $mobile;
            if( $mobile && !( preg_match( '/^[^:]*\d{9,}$/', $mobile ) ) ) {
                $has_error = true;
                $kanda_request['fields']['personal']['mobile'] = array_merge(
                    $kanda_request['fields']['personal']['mobile'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Invalid mobile number', 'kanda' ) )
                );
            }

            $kanda_request['fields']['company']['name']['value'] = $company_name;
            if( ! $company_name ) {
                $has_error = true;
                $kanda_request['fields']['company']['name'] = array_merge(
                    $kanda_request['fields']['company']['name'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            }

            $kanda_request['fields']['company']['license']['value'] = $company_license;
            if( ! $company_license ) {
                $has_error = true;
                $kanda_request['fields']['company']['license'] = array_merge(
                    $kanda_request['fields']['company']['license'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
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
                    update_user_meta( $user_id, 'is_active', 0 );
                    update_user_meta( $user_id, 'mobile', $mobile );
                    update_user_meta( $user_id, 'position', $position );
                    update_user_meta( $user_id, 'company_name', $company_name );
                    update_user_meta( $user_id, 'company_license', $company_license );
                    update_user_meta( $user_id, 'company_address', $company_address );
                    update_user_meta( $user_id, 'company_city', $company_address );
                    update_user_meta( $user_id, 'company_country', $company_country );
                    update_user_meta( $user_id, 'company_phone', $company_country );

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

                    do_action( 'kanda/new_user_registration', $user_id );
                }
            }

        } else {
            $kanda_request['message'] = esc_html__( 'Invalid request', 'kanda' );
        }

    }

    ob_start();
    include get_template_directory() . '/template-parts/home/register.php';
    echo ob_get_clean();

}

/**
 * Process forgot password request
 */
add_action( 'kanda/forgotpassword', 'kanda_check_forgot_password', 10 );
function kanda_check_forgot_password() {

    kanda_deny_agency_access();

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

            $kanda_request['fields']['username_email']['value'] = $username_email;
            if( ! $username_email ) {
                $has_error = true;
                $kanda_request['fields']['username_email'] = array_merge(
                    $kanda_request['fields']['username_email'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
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
                    $password_reset_url = wp_nonce_url( add_query_arg( array( 'rpt' => $reset_password_token ), site_url( '/reset-password' ) ), 'kanda_reset_password', 'rps' );
                    update_user_meta( $user->ID, 'forgot_password_token', $reset_password_token );

                    $site_name = get_bloginfo( 'name' );
                    $subject = sprintf( '%1%s: %2$s', $site_name, esc_html__( 'Reset Password', 'kanda' ) );
                    $headers = array(
                        'Content-Type: text/html; charset=UTF-8',
                        'From: My Name <noreply@kandaholidays.com>'
                    );

                    $message = sprintf( '<p>%s</p>', esc_html__( 'Hello', 'kanda' ) );
                    $message .= sprintf( '<p>%1$s %2$s</p>', esc_html__( 'We have received a password reset request at', 'kanda' ), sprintf( '<a href="%1$s">%2$s</a>', site_url( '/' ), $site_name ) );
                    $message .= sprintf( '<p>%1$s %2$s</p>', esc_html__( 'Please use this link to reset your password', 'kanda' ), sprintf( '<a href="%1$s">%1$s</a>', $password_reset_url ) );

                    $message .= sprintf( '<p>%s</p>', esc_html__( 'If you did not reqest a password reset, just ignore this email.', 'kanda' ) );

                    $message .= sprintf( '<p style="margin-top:30px;">%1$s</p><p>%2$s</p>', esc_html__( 'Best Regards.', 'kanda' ), $site_name );

                    $sent = wp_mail( $user->user_email, $subject, $message, $headers );

                    if( $sent ) {
                        $kanda_request['success'] = true;
                        $kanda_request['message'] = esc_html__( 'We have sent and email. Please follow instrcutions in it to reset your password', 'kanda' );
                        $kanda_request['fields']['username_email']['value'] = '';
                    } else {
                        $kanda_request['message'] = esc_html__( 'There was an error sending email. Please try again', 'kanda' );
                    }

                }
            }
        }

    }

    ob_start();
    include get_template_directory() . '/template-parts/home/forgot.php';
    echo ob_get_clean();

}

/**
 * Process reset password request
 */
add_action( 'kanda/resetpassword', 'kanda_check_reset_password', 10 );
function kanda_check_reset_password() {

    kanda_deny_agency_access();

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

            $password_min_length = 8;
            $password_max_length = 50;
            $kanda_request['fields']['password']['value'] = $password;
            if( ! $password ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $password_min_length, 'max_range' => $password_max_length ) ) ) ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => sprintf( esc_html__( 'Password must be between %1$d and %2$d characters in length', 'kanda' ), $password_min_length, $password_max_length ) )
                );
            }

            $kanda_request['fields']['confirm_password']['value'] = $confirm_password;
            if( ! $confirm_password ) {
                $has_error = true;
                $kanda_request['fields']['confirm_password'] = array_merge(
                    $kanda_request['fields']['confirm_password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Required', 'kanda' ) )
                );
            } elseif( $password && ( $confirm_password != $password ) ) {
                $has_error = true;
                $kanda_request['fields']['password'] = array_merge(
                    $kanda_request['fields']['password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Passwords don\'t match', 'kanda' ) )
                );
                $kanda_request['fields']['confirm_password'] = array_merge(
                    $kanda_request['fields']['confirm_password'],
                    array( 'valid' => false, 'msg' => esc_html__( 'Passwords don\'t match', 'kanda' ) )
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

        if( wp_verify_nonce( $key, 'kanda_reset_password' ) && $key ) {

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
    include get_template_directory() . '/template-parts/home/reset.php';
    echo ob_get_clean();

}

if ( current_user_can( 'administrator' ) ) {
    add_filter('show_admin_bar', '__return_false');
}

// search request example
//add_action('init', 'search_request', 11);
function search_request() {

    if( ! defined( 'IOL_LOADED' ) ) return;

    $criteria = array(
        'search-criteria' => array(
            'room-configuration' => array(
                'room' => array(
                    'adults' => 2,
                    'child' => array(
                        'age' => 16
                    ),
                    'room-configuration-id' => 1
                )
            ),
            'start-date' => Kanda_IOL_module()->helper->convert_date('15/03/2017', 'd/m/Y'),
            'end-date' => Kanda_IOL_module()->helper->convert_date('18/03/2017', 'd/m/Y'),
            'city' => 'DXB',
            'hotel-name' => 'ATLANTIS',
            'include-on-request' => true,
            'optional-supplement-y-n' => true,
            'cancellation-policy' => false,
            'include-hotel-data' => false,
            'include-rate-details' => false
        )
    );

    $args = array(
        'cache_lifetime' => Kanda_IOL_Config::get('cache_timeout', 'search')
    );

    $response = Kanda_IOL_module()->hotels->search($criteria, $args);
    echo '<pre>'; var_dump( $response ); die;
}