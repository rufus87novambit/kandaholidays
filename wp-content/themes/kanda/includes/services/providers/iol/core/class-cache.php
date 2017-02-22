<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Request_Cache extends Kanda_Request_Cache {

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

}