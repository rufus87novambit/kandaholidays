<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class Kanda_Service_Response {

    protected $vars = array();

    public function __construct() {}

    /**
     * Setter
     *
     * @param string $name Variable key
     * @param mixed $value Variable value
     */
    public function __set( $name, $value ) {
        $this->vars[ $name ] = $value;
    }

    /**
     * Getter
     *
     * @param $name Variable key
     * @return mixed Variable value if it exists or null otherwise
     */
    public function __get( $name ) {
        if ( array_key_exists( $name, $this->vars ) ) {
            return $this->vars[ $name ];
        }
        return null;
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

            $data = IOL_Helper::convert_xml_to_readable( $xml );

            if( isset( $data['errormessage'] ) ) {
                $this->code = 404;
                $this->message = $data['errormessage']['error']['errors']['msg'];
            } else {
                $this->data = IOL_Helper::convert_xml_to_readable( $xml );
            }
        }

    }

}