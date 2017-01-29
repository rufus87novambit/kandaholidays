<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Client' ) ) {

    class IOL_Client {

        private $url;
        private $code;
        private $password;
        private $token;

        private $request;

        public function __construct( $mode ) {
            $accesses = IOL_Config::get( 'url', $mode );

            $this->url = $accesses[ 'url' ];
            $this->code = $accesses[ 'code' ];
            $this->password = $accesses[ 'password' ];
            $this->token = $accesses[ 'token' ];
        }

        /**
         * Get access credentials configuration
         * @return array
         */
        private function get_config() {
            return array(
                'profile' => array(
                    'password' => $this->password,
                    'code' => $this->code,
                    'token-number' => $this->token
                )
            );
        }

        /**
         * Add access credentials configuration to request data
         *
         * @param $type
         * @param array $data
         * @return array
         */
        private function add_config_data( $type, $data = array() ) {
            return array(
                $type => array_merge( $this->get_config(), $data )
            );
        }

        /**
         * Get data
         *
         * @param $type
         * @param array $data
         * @param array $args
         * @return array|mixed|null|object
         */
        public function get( $type, $data = array(), $args = array() ) {

//            $args = wp_parse_args( $args, array(
//                'cache_lifetime' => false
//            ) );
//
//            if( $args['cache_lifetime'] ) {
//                $cached = Kanda_IOL_Cache::get_instance()->get_from_cache( $data, $args['cache_lifetime'] );
//                if ( $cached ) {
//                    return $cached;
//                }
//            }

            return $this->_get( $type, $data );

        }

        /**
         * Prepare XML for request
         *
         * @param $type
         * @param array $data
         * @return mixed
         */
        private function prepare_xml( $type, $data = array() ) {

            $helper = IOL()->helper;

            $type = $helper->convert_xml_key( $type );
            $data = $this->add_config_data( $type, $data );
            $xml = IOL()->helper->array_to_xml( $data );

            return $helper->prepend_xml_header( $type, $xml );
        }

        /**
         * Setup data and process request
         *
         * @param $type
         * @param array $data
         * @return array
         */
        private function _get( $type, $data = array() ) {

            $this->request = $data;
            $xml = $this->prepare_xml( $type, $data );

            return $this->request( $xml );
        }

        /**
         * Process request to endpoint
         *
         * @param $xml
         * @return array
         */
        private function request( $xml ) {

            $response = wp_remote_post( $this->url, array(
                'timeout' => 300,
                'sslverify' => false,
                'headers' => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Accept:text/xml'
                ),
                'body' => array( 'data' => $xml ),
            ) );

            return $this->process_response( $response );

        }

        /**
         * Prepare response
         *
         * @param $response
         * @return array
         */
        private function prepare_response( $response ) {
            $response['data'] = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data'] );
            $xml = simplexml_load_string( $response['data'] );

            $response = json_decode( json_encode( $xml ), true );

            return IOL()->helper->array_change_key_case_recursive( $response );
        }

        /**
         * Parse request response
         *
         * @param $response
         * @return array
         */
        private function process_response( $response ) {

            $return = array(
                'success'   => false,
                'response'  => array(),
                'has_error' => false,
                'code'      => 0
            );

            $response = array(
                'data' => wp_remote_retrieve_body( $response ),
                'code' => wp_remote_retrieve_response_code( $response )
            );

            if( $response['code'] == 200 ) {

                $response = $this->prepare_response( $response );

                $return['success'] = true;
                $return['response'] = $response;
                $return['has_error'] = isset( $response['errormessage'] );
                $return['code'] = 200;

                // cache results
                IOL()->cache->cache( $this->request, $return );

            } else {
                $return['code'] = $response['code'];
            }

            return $return;

        }

        /**
         * Get last request
         *
         * @return mixed
         */
        public function get_request() {
            $request = $this->request;
            $this->request = null;

            return $request;
        }

    }

}