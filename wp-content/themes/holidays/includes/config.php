<?php

class KH_Config {

    /**
     * Developer email address
     *
     * @var string
     */
    private static $developer_email;

    private static $agency_role;

    /**
     * Cookie lifetimes
     *
     * @var array
     */
    private static $cookie_lifetime;

    /**
     * Transient expiration time in seconds
     *
     * @var array
     */
    private static $transient_expiration;

    private static $validation;

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     */
    static public function get( $property = '', $delimiter = '->' ) {
        if( ! $property ) {
            return null;
        }

        $property = explode( $delimiter, $property );
        $key = array_shift( $property );

        $value = self::${$key};
        foreach( $property as $p ) {
            $value = $value[ $p ];
        }

        return $value;
    }

    static function init() {
        self::$developer_email = explode( ', ', kanda_fields()->get_option( 'developer_email' ) );
        self::$agency_role = 'agency';
        self::$cookie_lifetime = array(
            'authentication'    => array(
                'administrator' => 1 * DAY_IN_SECONDS,
                'agency'        => 1 * HOUR_IN_SECONDS
            ),
            'login'             => 10 * MINUTE_IN_SECONDS,
            'register'          => 10 * MINUTE_IN_SECONDS,
            'forgot_password'   => 10 * MINUTE_IN_SECONDS,
            'reset_password'    => 1  * DAY_IN_SECONDS
        );
        self::$transient_expiration = array(
            'exchange_update' => 12 * HOUR_IN_SECONDS
        );
        self::$validation = array(
            'front' => array(
                'data' => array(
                    'username_min_length' => 6,
                    'username_max_length' => 25,
                    'password_min_length' => 8,
                    'password_max_length' => 50
                ),
                'form_login' => array(
                    // key => input_id
                    'username' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'alphanumeric' => esc_html__( 'Must contain only numbers and/or letters', 'kanda' )
                    ),
                    'password' => array(
                        'required' => esc_html__( 'Required', 'kanda' )
                    )
                ),
                'form_register' => array(
                    // key => input_id
                    'username' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'alphanumeric' => esc_html__( 'Must contain only numbers and/or letters', 'kanda' ),
                        'rangelength' => esc_html__( 'Username must be between {0} and {1} characters in length', 'kanda' )
                    ),
                    'email' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'email'    => esc_html__( 'Invalid email', 'kanda' )
                    ),
                    'password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'rangelength' => esc_html__( 'Password must be between {0} and {1} characters in length', 'kanda' )
                    ),
                    'confirm_password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'equalTo' => __( 'Does not match', 'kanda' )
                    ),
                    'first_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'last_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'mobile' => array(
                        'phone_number' => esc_html__( 'Invalid mobile number', 'kanda' )
                    ),
                    'company_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'company_license' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'company_phone' => array(
                        'phone_number' => esc_html__( 'Invalid phone number', 'kanda' )
                    ),
                ),
                'form_forgot_password' => array(
                    // key => input_id
                    'username_email' => array(
                        'required' => esc_html__( 'Required', 'kanda' )
                    ),
                ),
                'form_reset_password' => array(
                    // key => input_id
                    'password'          => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'rangelength' => esc_html__( 'Password must be between {0} and {1} characters in length', 'kanda' )
                    ),
                    'confirm_password'  => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'equalTo' => __( 'Does not match', 'kanda' )
                    )
                ),
            )
        );
    }

}

KH_Config::init();