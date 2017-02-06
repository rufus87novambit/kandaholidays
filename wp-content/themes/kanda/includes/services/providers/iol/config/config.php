<?php

class IOL_Config {

    private static $id = 1;
    /**
     * Access details
     *
     * @var array
     */
    private static $url = array(
        'test' => array(
            'code'      => 'IOLWSDEV',
            'password'  => 'webservices',
            'token'     => 'TRA_STG!03042015_125718',
            'url'       => 'http://www.v3.staging.illusionswebservices.iwtx.com/illusionsHotelSearch.ashx',
        ),
        'live' => array(
            'code'      => 'PLACE LIVE CODE HERE',
            'password'  => 'PLACE LIVE PASS HERE',
            'token'     => 'PRbA2!pW@9Q-FZdFYkYm5&hEUUbX4^WDe5wh?Z^V86aTbV6U#B4p6&Xwy#R@',
            'url'       => 'http://www.v3.illusionswebservices.iwtx.com/illusionsHotelSearch.ashx'
        )
    );

    /**
     * Date format
     *
     * @var string
     */
    private static $date_format = 'Ymd';

    /**
     * Request caching lifetime in seconds
     *
     * @var array
     */
    private static $cache_timeout = array(
        'search' => 15 * MINUTE_IN_SECONDS
    );

    private static $request_args = array(
        'timeout' => 300,
        'sslverify' => false,
        'headers' => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept:text/xml'
        ),
        'body' => array()
    );

    /**
     * Get configuration value
     *
     * @param string $property
     * @param bool|false $sub_1
     * @param bool|false $sub_2
     * @param bool|false $sub_3
     * @return null
     */
    static function get( $property = '', $sub_1 = false, $sub_2 = false, $sub_3 = false ) {
        if( ! $property ) {
            return null;
        }

        $value = self::${$property};
        if( $sub_1 ) {
            $value = $value[ $sub_1 ];
            if( $sub_2 ) {
                $value = $value[ $sub_2 ];
                if( $sub_3 ) {
                    $value = $value[ $sub_3 ];
                }
            }
        }

        return $value;
    }

}