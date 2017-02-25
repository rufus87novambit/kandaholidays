<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Hotels {

    /**
     * Current request instance
     * @var IOL_Request
     */
    private $request_instance;

    /**
     * Constructor
     */
    public function __construct() {
        $this->request_instance = new IOL_Request();
    }

    /**
     * Get cache instance
     *
     * @return IOL_Search_Cache
     */
    private function get_cache_instance() {
        return new IOL_Search_Cache();
    }

    /**
     * Generate XML
     *
     * @param $args
     * @return mixed|SimpleXMLElement
     */
    private function generate_xml( $args ) {

        $xml = $this->request_instance->get_basic_xml( 'hotel-search-request' );

        $search_criteria = $xml->addChild(
            IOL_Helper::parse_xml_key( 'search-criteria' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'start-date' ),
            IOL_Helper::convert_date( $args['check_in'], 'd F, Y' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'end-date' ),
            IOL_Helper::convert_date( $args['check_out'], 'd F, Y' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'city' ),
            strtoupper( $args['city'] )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include-on-request' ),
            IOL_Helper::bool_to_string( true )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include-hotel-data' ),
            IOL_Helper::bool_to_string( true )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include-rate-details' ),
            IOL_Helper::bool_to_string( false )
        );

        $room_configuration = $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'room-configuration' )
        );

        for( $i = 1; $i <= $args['rooms_count']; $i++ ) {
            $room = $room_configuration->addChild(
                IOL_Helper::parse_xml_key( 'room' )
            );

            $room->addChild(
                IOL_Helper::parse_xml_key( 'adults' ),
                intval( $args['room_occupants'][ $i ][ 'adults' ] )
            );

            if( (bool)$args['room_occupants'][ $i ][ 'child' ] ) {
                foreach( $args['room_occupants'][ $i ][ 'child' ][ 'age' ] as $age ) {
                    $child = $room->addChild(
                        IOL_Helper::parse_xml_key( 'child' )
                    );

                    $child->addChild(
                        IOL_Helper::parse_xml_key( 'age' ),
                        intval( $age )
                    );
                }
            }
        }

        $xml = IOL_Helper::set_xml_encoding( $xml );

        return $xml;
    }

    /**
     * Search hotels
     *
     * @param $args
     * @param int $page
     * @return Kanda_Service_Response
     */
    public function search( $args, $page = 1 ) {

        $cache_instance = $this->get_cache_instance();
        $cached = $cache_instance->get( $args );

        if( $cached ) {
            // get it from cache
            if( $cache_instance->is_alive( $cached->created_at ) ) {

                $data = $cache_instance->get_data($cached->id, $page, IOL_Config::get('sql_limit->search'));

                $response_data = array();
                foreach( $data as $d ) {
                    $response_data[] = unserialize( $d->data );
                }
                $response = new Kanda_Service_Response();

                $response
                    ->set_code($cached->status_code)
                    ->set_request($args)
                    ->set_data($response_data);
            }
            // outdated / need to update
            else {
                $args = unserialize( $cached->request );
                $xml = $this->generate_xml( $args );

                $response = $this->request_instance->process( $xml, $args );

                if( $response->is_valid() ) {
                    $cache_instance->cache( $response, 'update' );

                    $data = $response->get_data();
                    if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {
                        $limit = IOL_Config::get( 'sql_limit->search' );
                        $offset = ( $page - 1 ) * $limit;
                        $data = array_slice( $data[ 'hotels' ][ 'hotel' ], $offset, $limit );
                    } else {
                        $data = array();
                    }

                    $response->set_data( $data );
                }
            }

        }
        // new request statement
        else {
            $xml = $this->generate_xml( $args );
            $response = $this->request_instance->process( $xml, $args );

            if( $response->is_valid() ) {
                $cache_instance->cache( $response, 'insert' );

                $data = $response->get_data();
                if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {
                    $limit = IOL_Config::get( 'sql_limit->search' );
                    $offset = ( $page - 1 ) * $limit;
                    $data = array_slice( $data[ 'hotels' ][ 'hotel' ], $offset, $limit );
                } else {
                    $data = array();
                }

                $response->set_data( $data );
            }
        }

        return $response;
    }

    /**
     * Search hotel by request id
     *
     * @param $request_id
     * @param int|false $page false|-1 to get all results
     * @return Kanda_Service_Response
     */
    public function search_by_id( $request_id, $page = 1 ) {

        $cache_instance = $this->get_cache_instance();
        $cached = $cache_instance->get( $request_id );

        if( $cached ) {
            $args = unserialize( $cached->request );
            $response = $this->search_hotels( $args, $page );
        } else {
            $response = new Kanda_Service_Response();

            $response
                ->set_code( 404 )
                ->set_message( __( 'Invalid request', 'kanda' ) );
        }

        return $response;

    }

}