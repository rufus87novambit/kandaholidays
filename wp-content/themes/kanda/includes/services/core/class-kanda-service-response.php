<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class Kanda_Service_Response {

    private $request;
    private $code;
    private $data;
    private $message = '';

    public function __construct() {}

    /**
     * Set request
     *
     * @param array $request
     * @return $this
     */
    public function set_request( $request = array() ) {
        $this->request = $request;

        return $this;
    }

    /**
     * Set response code
     *
     * @param $code
     * @return $this
     */
    public function set_code( $code ) {
        $this->code = $code;

        return $this;
    }

    /**
     * Set response data
     *
     * @param array $data
     * @return $this
     */
    public function set_data( $data = array() ) {
        $this->data = $data;

        return $this;
    }

    /**
     * Set response message
     *
     * @param string $message
     * @return $this
     */
    public function set_message( $message = '' ) {
        $this->message = $message;

        return $this;
    }

    /**
     * Get request
     *
     * @return mixed
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Get response code
     *
     * @return mixed
     */
    public function get_code() {
        return $this->code;
    }

    /**
     * Get response data
     *
     * @return mixed
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * Get response message
     *
     * @return string
     */
    public function get_message() {
        return $this->message;
    }

    /**
     * Check response validity
     *
     * @return bool
     */
    public function is_valid() {
        return ( $this->code == 200 );
    }

    /**
     * Load HTTP response
     *
     * @param $http
     * @param $request
     */
    public function load( $http, $request ) {

        $this->request = $request;

        if( is_wp_error( $http ) ) {
            $this->code = $http->get_error_code();
            $this->message = $http->get_error_message();

            kanda_logger()->log( sprintf( 'Request error: Request: %1$s, Code: %2$d', json_encode( $request ), $this->code ) );
        } else {
            $this->code = wp_remote_retrieve_response_code( $http );

            $xml = wp_remote_retrieve_body( $http );
            $this->data = IOL_Helper::convert_xml_to_readable( $xml );
        }

    }

}