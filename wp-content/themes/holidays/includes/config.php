<?php

class KH_Config {

    // todo -> make this configurable from admin panel
    private static $developer_email = 'israelyan.rafik@gmail.com';

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
     * Allowed actions for the front page
     *
     * @var array
     */
    private static $front_allowed_actions = array(
        'login',
        'register',
        'forgotpassword',
        'resetpassword'
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
    static function get( $property = '', $delimiter = '->' ) {
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

}