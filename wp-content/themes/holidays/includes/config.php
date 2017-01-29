<?php

class KH_Config {

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
     * Get configuration value
     *
     * @param string $property
     * @return null
     */
    static function get( $property = '' ) {
        if( ! $property ) {
            return null;
        }

        $property = explode( '->', $property );
        $key = array_shift( $property );

        $value = self::${$key};
        foreach( $property as $p ) {
            $value = $value[ $p ];
        }

        return $value;
    }

}