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
     * Generate XML from provided args
     *
     * @param $type
     * @param $args
     * @return mixed
     */
    private function generate_xml( $type, $args ) {



        $args = array( $type => array_merge( array(
            'profile' => array(
                'password'      => $this->access->password,
                'code'          => $this->access->code,
                'token-number'  => $this->access->token
            )
        ), $args ) );

        return IOL_Helper::replace_xml_header(
            IOL_Helper::convert_xml_key( $type ),
            IOL_Helper::array_to_xml( $args )
        );
    }

    /**
     * Process API call
     *
     * @param $data XML data
     * @param $unfiltered Data as array
     *
     * @return Kanda_Response
     */
    private function process( $data, $unfiltered ) {

        $request_args = array(
            'timeout' => 300,
            'sslverify' => false,
            'headers' => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Accept:text/xml'
            ),
            'body' => array( 'data' => $data ),
        );

        $result = wp_remote_post( $this->access->url, $request_args );

        return $this->response( $result, $unfiltered );
    }

    /**
     * Create response
     *
     * @param $response
     * @param $request
     * @return Kanda_Response
     */
    private function response( $response, $request ) {

        $code = wp_remote_retrieve_response_code( $response );
        $data = wp_remote_retrieve_body( $response );
        $message = '';

        if( $code == 200 ) {
            $data = IOL_Helper::convert_xml_to_readable( $data );
        } else {
            $message = sprintf( 'Request error: Request: %1$s, Code: %2$d', json_encode( $request ), $code );
            kanda_logger()->log( $message );
        }

        return new Kanda_Response( $code, $data, $message );
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

    /**
     * Search by hash
     *
     * @param $hash
     * @return Kanda_Response
     */
    public function search_by_hash( $hash ) {
        $request_args = IOL_Request_Cache::get_request_by_hash( $hash );
        if( $request_args ) {
            $request_args = json_decode( stripslashes( $request_args ), true );

            $response = $this->search_hotels( $request_args );
        } else {
            $response = new Kanda_Response( 404, array(), esc_html__( 'Invalid hash', 'kanda' ) );
        }

        return $response;
    }

    /**
     * Search hotels
     *
     * @param $xml
     * @param array $request_args
     * @param int $page
     * @param int $per_page
     * @return Kanda_Response
     * @throws Exception
     */
    public function search_hotels( $xml, $request_args = array(), $page = 1, $per_page = 10 ) {

        $cache = IOL_Request_Cache::get_by( 'request', $request_args, false, $per_page, ( ( $page - 1 ) * $per_page ) );
        if( $cache ) {
            $first_result = json_decode( stripslashes( $cache[0]->response ) );

            $response = new Kanda_Response( $first_result->code, $cache, $first_result->message );
        } else {

            $response = $this->process( $xml, $request_args );

            if( $response->valid() ) {
                IOL_Request_Cache::cache( $response, $request_args, 'iol' );
                return $this->search_hotels( $xml, $request_args, $page, $per_page );
            }
        }

        return $response;
    }

}