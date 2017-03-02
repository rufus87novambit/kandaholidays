<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Search_Cache extends Kanda_Service_Cache {

    /**
     * Holds search table name
     *
     * @var string
     */
    protected $table_search = 'iol_search';

    /**
     * Holds search results table name
     *
     * @var string
     */
    protected $table_search_results = 'iol_search_results';

    /**
     * Insert response
     *
     * @param Kanda_Service_Response $response
     * @return string
     */
    private function _insert( Kanda_Service_Response $response ) {

        global $wpdb;

        $search_table = $this->get_search_table();
        $result_table = $this->get_search_results_table();

        $date = current_time( 'mysql' );
        $request_id = $this->get_request_id( $response->request );

        /** Delete old requests */
        $this->_delete_died_requests();

        /** insert request */
        $wpdb->insert(
            $search_table,
            array(
                'id'            => $request_id,
                'created_at'    => $date,
                'provider'      => IOL_Config::get( 'id' ),
                'request'       => IOL_Helper::array_to_savable_format( $response->request ),
                'status_code'   => $response->code,
                'message'       => $response->message
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );

        /** Delete old requests data */
        $this->_delete_died_results();

        /** insert response data */
        $values = array();
        $data = $response->data;

        if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {

            $master_data = IOL_Master_Data::get_data( $response->request[ 'city' ] );

            foreach ($data['hotels']['hotel'] as $hotel ) {

                $code = $hotel['hotelcode'];
                $hotel_master_data = isset( $master_data[ $code ] ) ? (array)$master_data[ $code ] : array();
                $hotel[ 'hoteldescr' ] = isset( $hotel_master_data[ 'description' ] ) ? $hotel_master_data[ 'description' ] : '';
                $hotel[ 'images' ] = IOL_Helper::savable_format_to_array( isset( $hotel_master_data[ 'images' ] ) ? $hotel_master_data[ 'images' ] : array() );

                $values[] = sprintf(
                    '(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')',
                    $date,
                    $request_id,
                    $code,
                    $hotel['starrating'],
                    IOL_Helper::array_to_savable_format( $hotel )
                );
            }
            if ( !empty( $values ) ) {
                $values = implode(',', $values);
                $query = "INSERT INTO `{$result_table}` ( `created_at`, `request_id`, `code`, `rating`, `data` ) VALUES {$values}";

                $wpdb->query($query);
            }

        }

        return $request_id;

    }

    /**
     * Update response
     *
     * @param Kanda_Service_Response $response
     * @return string
     */
    private function _update( Kanda_Service_Response $response ) {

        global $wpdb;

        $search_table = $this->get_search_table();
        $result_table = $this->get_search_results_table();

        $date = current_time( 'mysql' );
        $request_id = $this->get_request_id( $response->request );

        /** insert request */
        $wpdb->update(
            $search_table,
            array(
                'created_at'    => $date,
                'status_code'   => $response->code,
                'message'       => $response->message
            ),
            array(
                'id' => $request_id,
            ),
            array(
                '%s',
                '%s',
                '%s',
            ),
            array(
                '%s'
            )
        );

        /** Delete old requests data */
        $this->_delete_died_results();

        /** insert response data */
        $values = array();
        $data = $response->data;

        if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {
            $master_data = IOL_Master_Data::get_data( $response->request[ 'city' ] );
            foreach ($data['hotels']['hotel'] as $hotel) {

                $code = $hotel['hotelcode'];
                $hotel_master_data = isset( $master_data[ $code ] ) ? (array)$master_data[ $code ] : array();
                $hotel[ 'hoteldescr' ] = isset( $hotel_master_data[ 'description' ] ) ? $hotel_master_data[ 'description' ] : '';
                $hotel[ 'images' ] = IOL_Helper::savable_format_to_array( isset( $hotel_master_data[ 'images' ] ) ? $hotel_master_data[ 'images' ] : array() );

                $values[] = sprintf(
                    '(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')',
                    $date,
                    $request_id,
                    $code,
                    $hotel['starrating'],
                    IOL_Helper::array_to_savable_format( $hotel )
                );

            }
            if ( !empty( $values ) ) {
                $values = implode(',', $values);
                $query = "INSERT INTO `{$result_table}` ( `created_at`, `request_id`, `code`, `rating`, `data` ) VALUES {$values}";

                $wpdb->query($query);
            }

        }

        return $request_id;
    }

    /**
     * Delete old requests
     */
    private function _delete_died_requests() {
        global $wpdb;

        $search_table = $this->get_search_table();
        $deadtime = date( 'Y-m-d H:i:s', ( strtotime( current_time( 'mysql' ) ) - IOL_Config::get( 'cache_timeout->search' ) ) );

        $query = "DELETE FROM {$search_table} WHERE `created_at` < '{$deadtime}'";
        $wpdb->query( $query );
    }

    /**
     * Delete old requests data
     */
    private function _delete_died_results() {
        global $wpdb;

        $result_table = $this->get_search_results_table();
        $deadtime = date( 'Y-m-d H:i:s', ( strtotime( current_time( 'mysql' ) ) - IOL_Config::get( 'cache_timeout->search' ) ) );

        $query = "DELETE FROM {$result_table} WHERE `created_at` < '{$deadtime}'";
        $wpdb->query( $query );
    }

    /**
     * Cache response
     *
     * @param Kanda_Service_Response $response
     * @param $type insert | update
     * @return null|string
     */
    public function cache( Kanda_Service_Response $response, $type ) {
        switch( $type ) {
            case 'insert':
                return $this->_insert( $response );
                break;
            case 'update':
                return $this->_update( $response );
        }
        return null;
    }

    /**
     * Check if request info is alive
     *
     * @param $date
     * @return bool
     */
    public function is_alive( $date ) {
        return strtotime( current_time( 'mysql' ) ) >= ( strtotime( $date ) - IOL_Config::get('cache_timeout->search') );
    }

    /**
     * Get request data by hash
     *
     * @param $request
     * @return array|null|object|void
     */
    public function get( $request ) {
        global $wpdb;
        $table = $this->get_search_table();
        $id = is_string( $request ) ? $request : $this->get_request_id( $request );

        $query = "SELECT * FROM `{$table}` WHERE `id` = '{$id}'";
        return $wpdb->get_row( $query );
    }

    /**
     * Get data by request
     *
     * @param $request
     * @param int $page
     * @param int $per_page
     * @return array|null|object
     */
    public function get_data( $request, $page, $per_page ) {
        global $wpdb;
        $table = $this->get_search_results_table();

        $request_id = is_string( $request ) ? $request : $this->get_request_id( $request );

        $query = "SELECT * FROM `{$table}` WHERE `request_id` = '{$request_id}'";
        if( $page && ( $page > 0 ) && ( $per_page > 0 ) ) {
            $offset = ( $page - 1 ) * $per_page;

            $query .= " LIMIT {$offset},{$per_page}";
        }

        $total_query = "SELECT COUNT(*) FROM `{$table}` WHERE `request_id` = '{$request_id}'";

        return array(
            'data'  => $wpdb->get_results( $query ),
            'total' => $wpdb->get_var( $total_query )
        );
    }

    /**
     * Get all data for request
     *
     * @param $request
     * @return array|null|object
     */
    public function get_all_data( $request ) {
        global $wpdb;
        $table = $this->get_search_results_table();

        $request_id = is_string( $request ) ? $request : $this->get_request_id( $request );

        $query = "SELECT * FROM `{$table}` WHERE `request_id` = '{$request_id}'";

        return $wpdb->get_results( $query );
    }

}