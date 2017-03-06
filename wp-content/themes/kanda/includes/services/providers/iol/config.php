<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

/**
 * IOL configuration
 *
 * Class IOL_Config
 */
class IOL_Config {

    /**
     * Provider ID
     * @var string
     */
    private static $id = 'iol';

    /**
     * Provider name
     * @var string
     */
    private static $name = 'IOL';

    /**
     * Provider mode test | live
     * @var string
     */

    private static $mode = 'live';

    /**
     * Access details
     *
     * @var array
     */
    private static $access = array(
        'test' => array(
            'code'      => 'IOLWSDEV',
            'password'  => 'webservices',
            'token'     => 'TRA_STG!03042015_125718',
            'url'       => 'http://www.v3.staging.illusionswebservices.iwtx.com/illusionsHotelSearch.ashx',
        ),
        'live' => array(
            'code'      => 'TRA_KANDA_PROD',
            'password'  => 'TRA_KANDA_XML',
            'token'     => 'KCL960313',
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
        'search'        => 3 * HOUR_IN_SECONDS
    );

    /**
     * MySQL LIMIT
     * @var array
     */
    private static $sql_limit = array(
        'search' => 5
    );

    /**
     * Cities code/name association
     * @var array
     */
    private static $cities = array(
        'AUH'   => 'Abu Dhabi',
        'AJMA'  => 'Ajman',
        'ALAZ'  => 'Al Ain',
        'DXB'   => 'Dubai',
        'FUJA'  => 'Fujairah',
        'RASK ' => 'Ras al Khaimah'
    );

    /**
     * Request args
     * @var array
     */
    private static $request_args = array(
        'timeout' => 300,
        'sslverify' => false,
        'headers' => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept:text/xml'
        ),
        'body' => array(),
    );

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     */
    public static function get( $property = '', $delimiter = '->' ) {
        try {
            $value = self::_get( $property, $delimiter );
        } catch( Exception $e ) {
            echo $e->getMessage(); die;
        }

        return $value;
    }

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     * @throws Exception
     */
    private static function _get( $property = '', $delimiter = '->' ) {
        if( ! $property ) {
            return null;
        }

        $property = explode( $delimiter, $property );
        $key = array_shift( $property );

        $value = self::${$key};
        foreach( $property as $p ) {
            if( ! array_key_exists( $p, $value ) ) {
                throw new Exception( sprintf( "Cannot find property \"%s\"", $p ) );
            }
            $value = $value[ $p ];
        }

        return $value;
    }

}