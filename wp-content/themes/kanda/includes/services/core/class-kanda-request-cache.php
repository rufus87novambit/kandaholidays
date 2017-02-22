<?php

class Kanda_Request_Cache {

    /**
     * Holds request table name
     * @var string
     */
    protected static $search_table = 'service_search';

    /**
     * Holds response data table name
     * @var string
     */
    protected static $search_results_table = 'service_search_results';

    /**
     * Generate search table name with valid prefix
     * @return string
     */
    protected static function get_search_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$search_table;
    }

    /**
     * Generate search results table name with valid prefix
     * @return string
     */
    protected static function get_search_results_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$search_results_table;
    }

    /**
     * Insert cache data
     *
     * @param $hash
     * @param $request
     * @param $response
     * @param $provider
     */
    protected static function _insert( $hash, $request, $response, $provider ) {

        global $wpdb;

        $search_table = self::get_search_table_name();
        $date = current_time( 'mysql' );
        $wpdb->insert(
            $search_table,
            array(
                'hash'          => $hash,
                'created_at'    => $date,
                'provider'      => $provider,
                'request'       => addslashes( json_encode( $request ) ),
                'response'      => addslashes( json_encode(
                    array(
                        'code'      => $response->get_code(),
                        'message'   => $response->get_message()
                    )
                ) )
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        $response_data = $response->get_data();
        if( isset( $response_data[ 'hotels' ][ 'hotel' ] ) ) {

            $search_results_table = self::get_search_results_table_name();

            $wpdb->delete(
                $search_results_table,
                array( 'hash' => $hash ),
                array( '%s' )
            );

            $values = array();

            foreach ( (array)$response_data[ 'hotels' ][ 'hotel' ] as $hotel ) {
                $values[] = sprintf(
                    '(\'%1$s\', \'%2$s\', \'%3$s\')',
                    $date,
                    $hash,
                    addslashes( json_encode( $hotel ) )
                );
            }

            if (!empty($values)) {
                $values = implode(',', $values);
                $query = "INSERT INTO `{$search_results_table}` ( `created_at`, `hash`, `hotel` ) VALUES {$values}";
                echo $query; die;

                $wpdb->query($query);
            }

        }

    }

    /**
     * Update cache data
     *
     * @param $hash
     * @param $request
     * @param $response
     * @param $provider
     */
    protected static function _update( $hash, $request, $response, $provider ) {

        global $wpdb;

        $search_table = self::get_search_table_name();
        $date = current_time( 'mysql' );
        $wpdb->update(
            $search_table,
            array(
                'created_at'    => $date,
                'provider'      => $provider,
                'request'       => addslashes( json_encode( $request ) ),
                'response'      => addslashes( json_encode(
                    array(
                        'code'      => $response->get_code(),
                        'message'   => $response->get_message()
                    )
                ) )
            ),
            array(
                'hash' => $hash
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s'
            ),
            array(
                '%s'
            )
        );

        $response_data = $response->get_data();
        if( isset( $response_data[ 'hotels' ][ 'hotel' ] ) ) {

            $search_results_table = self::get_search_results_table_name();

            $wpdb->delete(
                $search_results_table,
                array('hash' => $hash),
                array('%s')
            );

            $values = array();
            foreach ( $response_data[ 'hotels' ][ 'hotel' ] as $hotel ) {
                $values[] = sprintf(
                    '(\'%1$s\', \'%2$s\', \'%3$s\')',
                    $date,
                    $hash,
                    addslashes( json_encode( $hotel ) )
                );
            }
            if (!empty($values)) {
                $values = implode(',', $values);
                $query = "INSERT INTO `{$search_results_table}` ( `created_at`, `hash`, `hotel` ) VALUES {$values}";

                $wpdb->query($query);
            }

        }
    }

    /**
     * Get request data by hash
     *
     * @param $hash
     * @return null|string
     */
    public static function get_request_by_hash( $hash ) {
        global $wpdb;
        $table = self::get_search_table_name();

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
     * @param $key 'hash' or 'request'
     * @param $value
     * @param bool|false $ignore_lifetime
     * @param bool|false $limit
     * @param int $offset
     * @return array|null|object|void
     * @throws Exception
     */
    public static function get_by( $key, $value, $ignore_lifetime = false, $limit = false, $offset = 0 ) {
        global $wpdb;

        $keys = array( 'hash', 'request' );
        if( ! in_array( $key, array( 'hash', 'request' ) ) ) {
            throw new Exception( sprintf( "Key should be %s", implode( ' | ', $keys ) ) );
        }

        if( $key == 'request' ) {
            $key = 'hash';
            $value = Kanda_Request_Helper::get_request_hash( $value );
        }
        $service_search_table = self::get_search_table_name();
        $service_search_results = self::get_search_results_table_name();

        $where = "`ss`.`{$key}` = '{$value}'";

        if( ! $ignore_lifetime && $lifetime = IOL_Config::get( 'cache_timeout->search' ) ) {
            $date = current_time('mysql');
            $date = date('Y-m-d H:i:s', (strtotime($date) - $lifetime));

            $where .= " AND `ss`.`created_at` >= '{$date}'";
        }

        if( $limit ) {
            $limit = "LIMIT {$offset}, {$limit}";
        }

        $query = "SELECT `ss`.`hash`, `ss`.`request`, `ss`.`response`, `ssr`.`hotel`
                    FROM `{$service_search_table}` AS `ss`
                    LEFT JOIN `{$service_search_results}` AS `ssr` ON `ss`.`hash` = `ssr`.`hash`
                    WHERE {$where} {$limit}";

        return $wpdb->get_results( $query );
    }

}