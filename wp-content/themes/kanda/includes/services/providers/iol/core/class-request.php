<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Request {

    /**
     * Holds access data for API
     *
     * @var array|null
     */
    private $access;

    /**
     * Constructor
     */
    public function __construct() {
        $mode = IOL_Config::get( 'mode' );
        $this->access = (object)IOL_Config::get( "access->{$mode}" );
    }

    /**
     * Process API call
     *
     * @param $data
     * @param $args
     * @return Kanda_Service_Response
     */
    public function process( $data, $args ) {

        $request_args = IOL_Config::get( 'request_args' );
        $request_args[ 'body' ][ 'data' ] = $data;

        $http = wp_remote_post( $this->access->url, $request_args );
		
		//if( $_SERVER['REMOTE_ADDR'] == '109.75.46.141' ) {
			//echo '<pre>'; var_dump( $http ); die;
		//}

        $response_instance = new IOL_Response();
        $response_instance->load( $http, $args );

        return $response_instance;
    }

    /**
     * Get basic XML
     *
     * @param $type
     * @return SimpleXMLElement
     */
    public function get_basic_xml( $type ) {
        return IOL_Helper::get_basic_xml(
            $type,
            $this->access->password,
            $this->access->code,
            $this->access->token
        );
    }

}