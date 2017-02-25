<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class Kanda_Service_Cache {

    /**
     * Search table name
     * @var string
     */
    private $table_search = 'service_search';

    /**
     * Search results table name
     * @var string
     */
    private $table_search_results = 'service_search_results';

    /**
     * Get search table name
     *
     * @return string
     */
    protected function get_search_table() {
        global $wpdb;
        return $wpdb->prefix . $this->table_search;
    }

    /**
     * Get search results table name
     *
     * @return string
     */
    protected function get_search_results_table() {
        global $wpdb;
        return $wpdb->prefix . $this->table_search_results;
    }

    /**
     * Get request id from request array
     *
     * @param $request
     * @return string
     */
    protected function get_request_id ( $request ) {
        return substr( md5( $this->array_to_savable_format( $request ) ), 0, 8 );
    }

    /**
     * Convert array to savable format
     *
     * @param $data
     * @return string
     */
    protected function array_to_savable_format( $data ){
        return serialize( $data );
    }

}