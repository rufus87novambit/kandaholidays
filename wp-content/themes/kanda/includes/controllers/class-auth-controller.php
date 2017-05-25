<?php

if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Auth_Controller extends Base_Controller {

    protected $name = 'auth';
    public $default_action = 'login';

    /**
     * Deny logged in user access
     */
    private function deny_user_access() {
        if( is_user_logged_in() ) {
            kanda_to( 'home' );
        }
    }

    /**
     * Login functionality
     */
    public function login() {

        $this->deny_user_access();

        add_filter( 'nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->login' ); } );

        if( isset( $_POST['kanda_login'] ) ) {

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';

            if( wp_verify_nonce( $security, 'kanda_security_login' ) ) {

                $is_valid = true;
                $errors = array();
                $validation_rules = Kanda_Config::get( 'validation->front->form_login' );

                $this->username = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
                if( ! $this->username ) {
                    $is_valid = false;
                    $errors['username'] = $validation_rules['username']['required'];
                } elseif( ! preg_match( '/^[a-z0-9\_\-]+$/', $this->username ) ) {
                    $is_valid = false;
                    $errors['username'] = $validation_rules['username']['alphanumeric'];
                }

                $this->password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
                if( ! $this->password ) {
                    $is_valid = false;
                    $errors['password'] = $validation_rules['password']['required'];
                }

                $this->remember = isset( $_POST['remember'] ) ? (bool)$_POST['password'] : 1;

                if( $is_valid ) {

                    $user = get_user_by( 'login', $this->username );
                    if( $user ) {

                        $is_activated = true;
                        if( user_can( $user, 'travel_agency' ) ) {
                            $is_activated = get_user_meta( $user->ID, 'profile_status', true );
                        }

                        if( $is_activated ) {

                            $user = wp_signon(array(
                                'user_login'    => $this->username,
                                'user_password' => $this->password,
                                'remember'      => $this->remember
                            ));

                            if ( is_wp_error( $user ) ) {
                                $errors['username'] = $errors['password'] = '';
                                $this->set_notification( 'danger', esc_html__('Invalid username / password', 'kanda'), 'front' );
                            } else {

                                do_action( 'kanda/after_user_login', $user );

                                if( user_can( $user, 'administrator' ) ) {
                                    wp_redirect( site_url( '/wp-admin' ) ); die;
                                } else {
                                    kanda_to('home');
                                }
                            }

                        } else {
                            $this->set_notification( 'warning', __( 'Your account is inactive. You will get an email once it is activated.', 'kanda' ), 'front' );
                        }
                    } else {
                        $is_valid = false;
                        $errors['username'] = esc_html__( 'Invalid username', 'kanda' );
                    }
                }
                $this->errors = $errors;
            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ), 'front' );
            }

        } else {
            $this->username = '';
            $this->password = '';
            $this->remember = 1;
        }

        $this->view = 'login';
    }

    /**
     * Register functionality
     */
    public function register() {

        add_filter( 'nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->register' ); } );

        if( isset( $_POST['kanda_register'] ) ) {

            $nonce = ( isset( $_POST['kanda_nonce'] ) && $_POST['kanda_nonce'] ) ? $_POST['kanda_nonce'] : '';
            if( wp_verify_nonce( $nonce, 'kanda_register' ) ) {

                $username = isset( $_POST['personal']['username'] ) ? sanitize_text_field( $_POST['personal']['username'] ) : '';
                $email = isset( $_POST['personal']['email'] ) ? sanitize_email( $_POST['personal']['email'] ) : '';
                $password = isset( $_POST['personal']['password'] ) ? sanitize_text_field( $_POST['personal']['password'] ) : '';
                $confirm_password = isset( $_POST['personal']['confirm_password'] ) ? sanitize_text_field( $_POST['personal']['confirm_password'] ) : '';
                $first_name = isset( $_POST['personal']['first_name'] ) ? sanitize_text_field( $_POST['personal']['first_name'] ) : '';
                $last_name = isset( $_POST['personal']['last_name'] ) ? sanitize_text_field( $_POST['personal']['last_name'] ) : '';
                $mobile = isset( $_POST['personal']['mobile'] ) ? sanitize_text_field( $_POST['personal']['mobile'] ) : '';
                $position = isset( $_POST['personal']['position'] ) ? sanitize_text_field( $_POST['personal']['position'] ) : '';

                $company_name = isset( $_POST['company']['name'] ) ? sanitize_text_field( $_POST['company']['name'] ) : '';
                $company_license = isset( $_POST['company']['license'] ) ? sanitize_text_field( $_POST['company']['license'] ) : '';
                $company_address = isset( $_POST['company']['address'] ) ? sanitize_text_field( $_POST['company']['address'] ) : '';
                $company_city = isset( $_POST['company']['city'] ) ? sanitize_text_field( $_POST['company']['city'] ) : '';
                $company_country = isset( $_POST['company']['country'] ) ? sanitize_text_field( $_POST['company']['country'] ) : '';
                $company_phone = isset( $_POST['company']['phone'] ) ? sanitize_text_field( $_POST['company']['phone'] ) : '';
                $company_website = isset( $_POST['company']['website'] ) ? sanitize_text_field( $_POST['company']['website'] ) : '';

                $has_error = false;
                $validation_rules = Kanda_Config::get( 'validation->front->form_register' );
                $validation_data = Kanda_Config::get( 'validation->front->data' );

                $this->request['fields']['personal']['username']['value'] = $username;
                if( ! $username ) {
                    $has_error = true;
                    $this->request['fields']['personal']['username'] = array_merge(
                        $this->request['fields']['personal']['username'],
                        array( 'valid' => false, 'msg' => $validation_rules['username']['required'] )
                    );
                } elseif( ! preg_match( '/^[a-z0-9\_\-]+$/', $username ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['username'] = array_merge(
                        $this->request['fields']['personal']['username'],
                        array( 'valid' => false, 'msg' => $validation_rules['username']['alphanumeric'] )
                    );
                } elseif( ! filter_var( strlen( $username ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['username_min_length'], 'max_range' => $validation_data['username_max_length'] ) ) ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['username'] = array_merge(
                        $this->request['fields']['personal']['username'],
                        array( 'valid' => false, 'msg' => $validation_rules['username']['rangelength'] )
                    );
                }

                $this->request['fields']['personal']['email']['value'] = $email;
                if( ! $email ) {
                    $has_error = true;
                    $this->request['fields']['personal']['email'] = array_merge(
                        $this->request['fields']['personal']['email'],
                        array( 'valid' => false, 'msg' => $validation_rules['email']['required'] )
                    );
                } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['email'] = array_merge(
                        $this->request['fields']['personal']['email'],
                        array( 'valid' => false, 'msg' => $validation_rules['email']['email'] )
                    );
                }

                $this->request['fields']['personal']['password']['value'] = $password;
                if( ! $password ) {
                    $has_error = true;
                    $this->request['fields']['personal']['password'] = array_merge(
                        $this->request['fields']['personal']['password'],
                        array( 'valid' => false, 'msg' => $validation_rules['password']['required'] )
                    );
                } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['password'] = array_merge(
                        $this->request['fields']['personal']['password'],
                        array( 'valid' => false, 'msg' => $validation_rules['password']['rangelength'] )
                    );
                }

                $this->request['fields']['personal']['confirm_password']['value'] = $confirm_password;
                if( ! $confirm_password ) {
                    $has_error = true;
                    $this->request['fields']['personal']['confirm_password'] = array_merge(
                        $this->request['fields']['personal']['confirm_password'],
                        array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['required'] )
                    );
                } elseif( $password && ( $confirm_password != $password ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['confirm_password'] = array_merge(
                        $this->request['fields']['personal']['confirm_password'],
                        array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['equalTo'] )
                    );
                }

                $this->request['fields']['personal']['first_name']['value'] = $first_name;
                if( ! $first_name ) {
                    $has_error = true;
                    $this->request['fields']['personal']['first_name'] = array_merge(
                        $this->request['fields']['personal']['first_name'],
                        array( 'valid' => false, 'msg' => $validation_rules['first_name']['required'] )
                    );
                }

                $this->request['fields']['personal']['last_name']['value'] = $last_name;
                if( ! $last_name ) {
                    $has_error = true;
                    $this->request['fields']['personal']['last_name'] = array_merge(
                        $this->request['fields']['personal']['last_name'],
                        array( 'valid' => false, 'msg' => $validation_rules['last_name']['required'] )
                    );
                }

                $this->request['fields']['personal']['mobile']['value'] = $mobile;
                if( $mobile && !( preg_match( '/^[\+:]*\d{9,}$/', $mobile ) ) ) {
                    $has_error = true;
                    $this->request['fields']['personal']['mobile'] = array_merge(
                        $this->request['fields']['personal']['mobile'],
                        array( 'valid' => false, 'msg' => $validation_rules['mobile']['phone_number'] )
                    );
                }

                $this->request['fields']['company']['name']['value'] = $company_name;
                if( ! $company_name ) {
                    $has_error = true;
                    $this->request['fields']['company']['name'] = array_merge(
                        $this->request['fields']['company']['name'],
                        array( 'valid' => false, 'msg' => $validation_rules['company_name']['required'] )
                    );
                }

                $this->request['fields']['company']['license']['value'] = $company_license;
                if( ! $company_license ) {
                    $has_error = true;
                    $this->request['fields']['company']['license'] = array_merge(
                        $this->request['fields']['company']['license'],
                        array( 'valid' => false, 'msg' => $validation_rules['company_license']['required'] )
                    );
                }

                $this->request['fields']['company']['phone']['value'] = $company_phone;
                if( $company_phone && !( preg_match( '/^[^:]*\d{9,}$/', $company_phone ) ) ) {
                    $has_error = true;
                    $this->request['fields']['company']['phone'] = array_merge(
                        $this->request['fields']['company']['phone'],
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
                        'role'       => Kanda_Config::get( 'agency_role' ),
                        'user_url'   => $company_website
                    ) );

                    if( is_wp_error( $user_id ) ) {
                        $this->request['message'] = $user_id->get_error_message();
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
                        update_user_meta( $user_id, 'account_type', '' );
                        update_user_meta( $user_id, 'additional_fee', 0 );
                        update_user_meta( $user_id, 'specific_addition_fee', 0 );

                        $this->request['fields']['personal']['username']['value'] = '';
                        $this->request['fields']['personal']['email']['value'] = '';
                        $this->request['fields']['personal']['password']['value'] = '';
                        $this->request['fields']['personal']['confirm_password']['value'] = '';
                        $this->request['fields']['personal']['first_name']['value'] = '';
                        $this->request['fields']['personal']['last_name']['value'] = '';
                        $this->request['fields']['personal']['mobile']['value'] = '';
                        $this->request['fields']['personal']['position']['value'] = '';

                        $this->request['fields']['company']['name']['value'] = '';
                        $this->request['fields']['company']['license']['value'] = '';
                        $this->request['fields']['company']['address']['value'] = '';
                        $this->request['fields']['company']['city']['value'] = '';
                        $this->request['fields']['company']['country']['value'] = '';
                        $this->request['fields']['company']['phone']['value'] = '';
                        $this->request['fields']['company']['website']['value'] = '';

                        $this->request['success'] = true;
                        $this->request['message'] = esc_html__( 'Your profile has been successfully created. You will get an email once it is activated.', 'kanda' );

                        do_action( 'kanda/after_new_user_registration', $user_id );
                    }
                }

            } else {
                $this->request['message'] = esc_html__( 'Invalid request', 'kanda' );
            }

        }

        $this->view = 'register';

    }

    /**
     * Forgot password functionality
     */
    public function forgot() {
        $this->deny_user_access();

        add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->forgot_password' ); });
        $this->show_form = true;

        if ( isset( $_POST['kanda_forgot'] ) ) {

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';

            if ( wp_verify_nonce($security, 'kanda_security_forgot') ) {

                $is_valid = true;
                $errors = array();
                $validation_rules = Kanda_Config::get( 'validation->front->form_forgot_password' );

                $this->username_email = isset( $_POST['username_email'] ) ? sanitize_text_field( $_POST['username_email'] ) : '';
                if( ! $this->username_email ) {
                    $is_valid = false;
                    $errors['username_email'] = $validation_rules['username_email']['required'];
                }

                if( $is_valid ) {

                    $is_email = filter_var( $this->username_email, FILTER_VALIDATE_EMAIL );

                    $user = get_user_by( ( $is_email ? 'email' : 'login' ), $this->username_email );

                    if( ! $user ) {
                        $is_valid = false;
                        $errors['username_email'] = esc_html__( 'User not found', 'kanda' );
                    }

                    if( $is_valid ) {

                        add_filter( 'nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->reset_password' ); } );

                        $reset_password_token = kanda_generate_random_string( 20 );
                        $password_reset_url = kanda_url_to( 'reset-password', array( wp_create_nonce( 'kanda_security_reset' ), $reset_password_token ) );

                        update_user_meta( $user->ID, 'forgot_password_token', $reset_password_token );

                        $to = $user->user_email;
                        $subject = kanda_get_theme_option( 'email_forgot_password_title' );
                        $message = kanda_get_theme_option( 'email_forgot_password_body' );
                        $variables = array(
                            '{{RESET_URL}}'  => sprintf( '<a href="%1$s">%1$s</a>', $password_reset_url ),
                            '{{FIRST_NAME}}' => $user->first_name,
                            '{{LAST_NAME}}'  => $user->last_name
                        );

                        if( kanda_mailer()->send_user_email( $to, $subject, $message, $variables ) ) {
                            $this->set_notification( 'success', esc_html__( 'You will receive an email with instructions on how to reset your new password to your registered email address.', 'kanda' ), 'front' );
                            $this->username_email = '';
                            $this->show_form = false;

                            do_action( 'kanda/after_forgot_password', $user );

                        } else {
                            $this->set_notification( 'danger', esc_html__( 'Oops! Something went wrong while sending email. Please try again', 'kanda' ), 'front' );
                            kanda_logger()->log( sprintf( 'There was an error while sending password reset email to user: Details: user_id=%1$d, reset_url=%2$s', $user->ID, $password_reset_url ) );
                        }

                    }
                }

                $this->errors = $errors;
            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ), 'front' );
            }

        } else {
            $this->username_email = '';
        }

        $this->view = 'forgot';
    }

    /**
     * Password reset functionality
     */
    public function reset( $args = array() ) {

        $this->deny_user_access();

        add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->forgot_password' ); });

        if ( isset( $_POST['kanda_reset'] ) ) {

            $security = isset($_POST['security']) ? $_POST['security'] : '';

            if ( wp_verify_nonce($security, 'kanda_security_reset') ) {

                $is_valid = true;
                $errors = array();

                $validation_rules = Kanda_Config::get( 'validation->front->form_reset_password' );
                $validation_data = Kanda_Config::get( 'validation->front->data' );

                $this->password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
                if( ! $this->password ) {
                    $is_valid = false;
                    $errors[ 'password' ] = $validation_rules['password']['required'];
                } elseif( ! filter_var( strlen( $this->password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                    $is_valid = false;
                    $errors[ 'password' ] = $validation_rules['password']['rangelength'];
                }

                $this->confirm_password = isset( $_POST['confirm_password'] ) ? sanitize_text_field( $_POST['confirm_password'] ) : '';
                if( ! $this->confirm_password ) {
                    $is_valid = false;
                    $errors[ 'confirm_password' ] = $validation_rules['confirm_password']['required'];
                } elseif( $this->password && ( $this->confirm_password != $this->password ) ) {
                    $is_valid = false;
                    $errors[ 'confirm_password' ] = $validation_rules['confirm_password']['equalTo'];
                }

                $this->user_id = isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';

                if( $is_valid ) {

                    $user_id = wp_update_user( array( 'ID' => $this->user_id, 'user_pass' => $this->password ) );
                    if( ! is_wp_error( $user_id ) ) {

                        delete_user_meta( $user_id, 'forgot_password_token' );

                        $user = new WP_User( $user_id );
                        $user = wp_signon(array(
                            'user_login'    => $user->user_login,
                            'user_password' => $this->password,
                            'remember'      => true
                        ));

                        do_action( 'kanda/after_password_reset', $user );

                        kanda_to( 'home' );

                    } else {
                        $this->set_notification( 'danger', esc_html__( 'Error reseting password. Please try again.', 'kanda' ), 'front' );
                    }

                }

                $this->errors = $errors;
            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ), 'front' );
            }

        } else {

            add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->reset_password' ); });

            $args = wp_parse_args( $args, array( 'ksecurity' => '' ) );

            if( wp_verify_nonce( $args['ksecurity'], 'kanda_security_reset' ) ) {

                $users = get_users( array( 'meta_key' => 'forgot_password_token', 'meta_value' => $args['key'] ) );

                if( ! empty( $users ) ) {
                    $this->user_id = $users[0]->ID;
                } else {
                    $this->show_404();
                }

            } else {
                $this->show_404();
            }

            add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->forgot_password' ); });

        }

        $this->view = 'reset';
    }

}