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
    protected $table_search;

    /**
     * Search results table name
     * @var string
     */
    protected $table_search_results;

    /**
     * Get search table name
     *
     * @return string
     */
    public function get_search_table() {
        global $wpdb;
        return $wpdb->prefix . $this->table_search;
    }

    /**
     * Get search results table name
     *
     * @return string
     */
    public function get_search_results_table() {
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
        return substr( md5( IOL_Helper::array_to_savable_format( $request ) ), 0, 8 );
    }

}