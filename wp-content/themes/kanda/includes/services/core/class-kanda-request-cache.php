<?php

class Kanda_Request_Cache {

    /**
     * Holds request table name
     * @var string
     */
    private static $table = 'service_request';

    /**
     * Generate table name with valid prefix
     * @return string
     */
    private static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$table;
    }

    /**
     * Insert cache data
     *
     * @param $hash
     * @param $request
     * @param $response
     * @param $provider
     */
    private static function _insert( $hash, $request, $response, $provider ) {
        global $wpdb;
        $wpdb->insert(
            self::get_table_name(),
            array(
                'hash'     => $hash,
                'provider' => $provider,
                'request'  => addslashes( json_encode( $request ) ),
                'response' => addslashes( json_encode(
                    array(
                        'code'      => $response->get_code(),
                        'message'   => $response->get_message(),
                        'data'      => $response->get_data()
                    )
                ) )
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
    }

    /**
     * Update cache data
     *
     * @param $hash
     * @param $request
     * @param $response
     * @param $provider
     */
    private static function _update( $hash, $request, $response, $provider ) {
        global $wpdb;
        $wpdb->update(
            self::get_table_name(),
            array(
                'created_at'    => current_time( 'mysql' ),
                'provider'      => $provider,
                'request'       => addslashes( json_encode( $request ) ),
                'response'      => addslashes( json_encode(
                    array(
                        'code'      => $response->get_code(),
                        'message'   => $response->get_message(),
                        'data'      => $response->get_data()
                    )
                ) )
            ),
            array(
                'hash' => $hash
            ),
            array(
                '%s',
                '%s',
                '%s'
            ),
            array(
                '%s'
            )
        );
    }

    /**
     * Get request data by hash
     *
     * @param $hash
     * @return null|string
     */
    public static function get_request_by_hash( $hash ) {
        global $wpdb;
        $table = self::get_table_name();

        $query = "SELECT `request` FROM `{$table}` WHERE `hash` = '{$hash}'";
        return $wpdb->get_var( $query );
    }

    /**
     * Cache response
     *
     * @param Kanda_Response $response
     * @param array $request
     * @param string $provider
     * @param bool|false $return_row
     * @return Kanda_Response
     */
    public static function cache( Kanda_Response $response, $request = array(), $provider = '', $return_row = false ) {

        $hash = Kanda_Request_Helper::get_request_hash( $request );

        try {
            $cache = self::get_by( 'hash', $hash, true );
        } catch ( Exception $e ) {
            $cache = false;
        }

        if( $cache ) {
            self::_update( $hash, $request, $response, $provider );
        } else {
            self::_insert( $hash, $request, $response, $provider );
        }

        if( $return_row ) {
            return $response;
        }
    }

    /**
     * Get results by specific key
     *
     * @param $key 'id' | 'hash' | 'request'
     * @param $value
     * @param string $fields MySQL fields to return
     * @return array|null|object|void
     * @throws Exception
     */
    public static function get_by( $key, $value, $ignore_lifetime = false, $fields = '*' ) {
        global $wpdb;

        $keys = array( 'id', 'hash', 'request' );
        if( ! in_array( $key, array( 'id', 'hash', 'request' ) ) ) {
            throw new Exception( sprintf( "Key should be %s", implode( ' | ', $keys ) ) );
        }

        if( $key == 'request' ) {
            $key = 'hash';
            $value = Kanda_Request_Helper::get_request_hash( $value );
        }
        $table = self::get_table_name();

        $where = "`{$key}` = '{$value}'";

        if( ! $ignore_lifetime && $lifetime = IOL_Config::get( 'cache_timeout->search' ) ) {
            $date = current_time('mysql');
            $date = date('Y-m-d H:i:s', (strtotime($date) - $lifetime));

            $where .= " AND `created_at` >= '{$date}'";
        }

        $query = "SELECT {$fields} FROM `{$table}` WHERE {$where}";
        return $wpdb->get_row( $query );
    }

}