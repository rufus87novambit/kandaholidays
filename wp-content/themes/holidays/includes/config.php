<?php

class KH_Config {

    /**
     * Developer email address
     *
     * @var string
     */
    private static $developer_email = '';

    private static $agency_role = 'agency';

    /**
     * Cookie lifetimes
     *
     * @var array
     */
    private static $cookie_lifetime = array(
        'authentication'    => array(
            'administrator' => 1 * DAY_IN_SECONDS,
            'agency'        => 1 * HOUR_IN_SECONDS
        ),
        'login'             => 10 * MINUTE_IN_SECONDS,
        'register'          => 10 * MINUTE_IN_SECONDS,
        'forgot_password'   => 10 * MINUTE_IN_SECONDS,
        'reset_password'    => 1  * DAY_IN_SECONDS
    );

    /**
     * Transient expiration time in seconds
     *
     * @var array
     */
    private static $transient_expiration = array(
        'exchange_update' => 12 * HOUR_IN_SECONDS
    );

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

    /**
     * Set dynamic properties
     *
     * @param array $data
     */
    static public function set( $data = array() ) {
        foreach( $data as $property => $value ) {
            if( property_exists( 'KH_Config', $property ) ) {
                self::${$property} = $value;
            }
        }
    }

}

/**
 * Set dynamic properties to constant configuration
 */
KH_Config::set( array(
    'developer_email' => explode( ', ', kanda_fields()->get_option( 'developer_email' ) ),
    'hello' => 'asdasd'
) );