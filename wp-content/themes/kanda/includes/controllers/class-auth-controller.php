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

        $this->request = array(
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
                $username = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
                $password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
                $remember = isset( $_POST['remember'] ) ? (bool)$_POST['password'] : false;

                $has_error = false;
                $validation_rules = Kanda_Config::get( 'validation->front->form_login' );

                $this->request['fields']['username']['value'] = $username;
                if( ! $username ) {
                    $has_error = true;
                    $this->request['fields']['username'] = array_merge(
                        $this->request['fields']['username'],
                        array( 'valid' => false, 'msg' => $validation_rules['username']['required'] )
                    );
                } elseif( ! preg_match( '/^[a-z0-9\_\-]+$/', $username ) ) {
                    $has_error = true;
                    $this->request['fields']['username'] = array_merge(
                        $this->request['fields']['username'],
                        array( 'valid' => false, 'msg' => $validation_rules['username']['alphanumeric'] )
                    );
                }

                $this->request['fields']['password']['value'] = $password;
                if( ! $password ) {
                    $has_error = true;
                    $this->request['fields']['password'] = array_merge(
                        $this->request['fields']['password'],
                        array( 'valid' => false, 'msg' => $validation_rules['password']['required'] )
                    );
                }

                $this->request['fields']['remember']['value'] = (int)$remember;

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
                                $this->request['message'] = esc_html__('Invalid username / password', 'kanda');
                                $this->request['fields']['username']['valid'] = false;
                                $this->request['fields']['password']['valid'] = false;
                            } else {

                                do_action( 'kanda/after_user_login', $user );

                                kanda_to( 'home' );
                            }

                        } else {
                            $this->request['success'] = true;
                            $this->request['message'] = __( 'Your account is inactive. You will get an email once it is activated.', 'kanda' );
                        }
                    } else {
                        $this->request['fields']['username'][ 'valid' ] = false;
                        $this->request['fields']['username'][ 'msg' ] = esc_html__( 'Invalid username', 'kanda' );
                    }
                }
            } else {
                $this->request['message'] = esc_html__( 'Invalid request', 'kanda' );
            }

        }

        $this->view = 'login';
    }

    /**
     * Register functionality
     */
    public function register() {

        add_filter( 'nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->register' ); } );

        $this->request = array(
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

        $this->request = array(
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
                $username_email = isset($_POST['username_email'] ) ? sanitize_text_field( $_POST['username_email'] ) : '';

                $has_error = false;
                $validation_rules = Kanda_Config::get( 'validation->front->form_forgot_password' );

                $this->request['fields']['username_email']['value'] = $username_email;
                if( ! $username_email ) {
                    $has_error = true;
                    $this->request['fields']['username_email'] = array_merge(
                        $this->request['fields']['username_email'],
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
                        $this->request['fields']['username_email'] = array_merge(
                            $this->request['fields']['username_email'],
                            array( 'valid' => false, 'msg' => esc_html__( 'User not found', 'kanda' ) )
                        );
                    } else {

                        add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->reset_password' ); });

                        $reset_password_token = generate_random_string( 20 );
                        $password_reset_url = home_url( '/reset/' . wp_create_nonce( 'kanda_reset_password' ) . '/' . $reset_password_token );

                        update_user_meta( $user->ID, 'forgot_password_token', $reset_password_token );

                        $to = $user->user_email;
                        $subject = kanda_get_theme_option( 'email_forgot_password_title' );
                        $message = kanda_get_theme_option( 'email_forgot_password_body' );
                        $variables = array( '{{RESET_URL}}' => sprintf( '<a href="%1$s">%1$s</a>', $password_reset_url ) );

                        if( kanda_mailer()->send_user_email( $to, $subject, $message, $variables ) ) {
                            $this->request['success'] = true;
                            $this->request['message'] = esc_html__( 'An email with instructions is sent to your email address.', 'kanda' );
                            $this->request['fields']['username_email']['value'] = '';

                            do_action( 'kanda/after_forgot_password', $user );

                        } else {
                            $this->request['message'] = esc_html__( 'Oops! Something went wrong while sending email. Please try again', 'kanda' );
                            kanda_logger()->log( sprintf( 'There was an error while sending password reset email to user: Details: user_id=%1$d, reset_url=%2$s', $user->ID, $password_reset_url ) );
                        }

                    }
                }
            }

        }

        $this->view = 'forgot';
    }

    /**
     * Password reset functionality
     */
    public function reset( $args = array() ) {

        $this->deny_user_access();

        add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->forgot_password' ); });

        $this->request = array(
            'success' => false,
            'message' => false,
            'has_error' => true,
            'fields' => array(
                'user_id' => array(
                    'value' => '',
                    'valid' => true,
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
                $password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
                $confirm_password = isset( $_POST['confirm_password'] ) ? sanitize_text_field( $_POST['confirm_password'] ) : '';
                $user_id = isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';

                $has_error = false;

                $validation_rules = Kanda_Config::get( 'validation->front->form_reset_password' );
                $validation_data = Kanda_Config::get( 'validation->front->data' );

                $this->request['fields']['password']['value'] = $password;
                if( ! $password ) {
                    $has_error = true;
                    $this->request['fields']['password'] = array_merge(
                        $this->request['fields']['password'],
                        array( 'valid' => false, 'msg' => $validation_rules['password']['required'] )
                    );
                } elseif( ! filter_var( strlen( $password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                    $has_error = true;
                    $this->request['fields']['password'] = array_merge(
                        $this->request['fields']['password'],
                        array( 'valid' => false, 'msg' => $validation_rules['password']['rangelength'] )
                    );
                }

                $this->request['fields']['confirm_password']['value'] = $confirm_password;
                if( ! $confirm_password ) {
                    $has_error = true;
                    $this->request['fields']['confirm_password'] = array_merge(
                        $this->request['fields']['confirm_password'],
                        array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['required'] )
                    );
                } elseif( $password && ( $confirm_password != $password ) ) {
                    $has_error = true;
                    $this->request['fields']['confirm_password'] = array_merge(
                        $this->request['fields']['confirm_password'],
                        array( 'valid' => false, 'msg' => $validation_rules['confirm_password']['equalTo'] )
                    );
                }

                $this->request['fields']['user_id']['value'] = $user_id;
                $this->request['has_error'] = false;

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

                        kanda_to( 'home' );

                    } else {
                        $this->request['message'] = esc_html__( 'Error reseting password. Please try again.', 'kanda' );
                    }

                }
            }

        } else {

            add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->reset_password' ); });

            $args = wp_parse_args( $args, array( 'ksecurity' => '' ) );

            if( wp_verify_nonce( $args['ksecurity'], 'kanda_reset_password' ) ) {

                $users = get_users( array( 'meta_key' => 'forgot_password_token', 'meta_value' => $args['key'] ) );

                if( ! empty( $users ) ) {
                    $this->request['has_error'] = false;
                    $this->request['fields']['user_id']['value'] = $users[0]->ID;
                }

            } elseif( $args['key'] ) {
                $this->request['message'] = __( 'Invalid or outdated token', 'kanda' );
            } else {
                $this->show_404();
            }

            add_filter('nonce_life', function () { return Kanda_Config::get( 'cookie_lifetime->forgot_password' ); });

        }

        $this->view = 'reset';
    }

}