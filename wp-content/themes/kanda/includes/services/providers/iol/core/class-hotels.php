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
    private function generate_search_xml( $args ) {

        $xml = $this->request_instance->get_basic_xml( 'hotel_search_request' );

        $search_criteria = $xml->addChild(
            IOL_Helper::parse_xml_key( 'search_criteria' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'city' ),
            strtoupper( $args['city'] )
        );

        if( isset( $args['hotel_name'] ) && $args['hotel_name'] ) {
            $search_criteria->addChild(
                IOL_Helper::parse_xml_key('hotel_name'),
                trim( $args['hotel_name'] )
            );
        }

        if( isset( $args['star_rating'] ) && $args['star_rating'] ) {
            $star_rating_configuration = $search_criteria->addChild(
                IOL_Helper::parse_xml_key( 'star_rating_configuration' )
            );
            $star_rating_configuration->addChild(
                IOL_Helper::parse_xml_key('star_rating'),
                intval( $args['star_rating'] )
            );
        }

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include_on_request' ),
            IOL_Helper::bool_to_string( (bool)$args['include_on_request'] )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'nationality' ),
            $args['nationality']
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'start_date' ),
            IOL_Helper::convert_date( $args['start_date'], 'd F, Y' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'end_date' ),
            IOL_Helper::convert_date( $args['end_date'], 'd F, Y' )
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

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include_hotel_data' ),
            IOL_Helper::bool_to_string( true )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'include-rate-details' ),
            IOL_Helper::bool_to_string( true )
        );

        return $xml->asXML();
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
                    $response_data[] = IOL_Helper::savable_format_to_array ( $d->data );
                }
                $response = new Kanda_Service_Response();

                $response
                    ->set_code($cached->status_code)
                    ->set_request($args)
                    ->set_request_id( $cached->id )
                    ->set_data($response_data);
            }
            // outdated / need to update
            else {
                $args = IOL_Helper::savable_format_to_array( $cached->request );
                $xml = $this->generate_search_xml( $args );

                $response = $this->request_instance->process( $xml, $args );

                if( $response->is_valid() ) {
                    $request_id = $cache_instance->cache( $response, 'update' );

                    $data = $response->get_data();
                    if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {
                        $limit = IOL_Config::get( 'sql_limit->search' );
                        $offset = ( $page - 1 ) * $limit;
                        $data = array_slice( $data[ 'hotels' ][ 'hotel' ], $offset, $limit );
                    } else {
                        $data = array();
                    }

                    $response
                        ->set_request_id( $request_id )
                        ->set_data( $data );
                }
            }

        }
        // new request statement
        else {
            $xml = $this->generate_search_xml( $args );

            $response = $this->request_instance->process( $xml, $args );

            if( $response->is_valid() ) {
                $request_id = $cache_instance->cache( $response, 'insert' );

                $data = $response->get_data();
                if( isset( $data[ 'hotels' ][ 'hotel' ] ) ) {
                    $limit = IOL_Config::get( 'sql_limit->search' );
                    $offset = ( $page - 1 ) * $limit;
                    $data = array_slice( $data[ 'hotels' ][ 'hotel' ], $offset, $limit );
                } else {
                    $data = array();
                }

                $response
                    ->set_request_id( $request_id )
                    ->set_data( $data );
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
            $args = IOL_Helper::savable_format_to_array( $cached->request );
            $response = $this->search( $args, $page );
        } else {
            $response = new Kanda_Service_Response();

            $response
                ->set_code( 404 )
                ->set_message( __( 'Invalid request', 'kanda' ) );
        }

        return $response;

    }

    /**
     * Generate hotel details XML
     *
     * @param $args
     * @return mixed|SimpleXMLElement
     */
    private function generate_hotel_details_xml( $args ) {
        $xml = $this->request_instance->get_basic_xml( 'hotel_details_request' );

        $search_criteria = $xml->addChild(
            IOL_Helper::parse_xml_key( 'search_criteria' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'hotel_code' ),
            $args['hotel_code']
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'start_date' ),
            IOL_Helper::convert_date( $args['start_date'], 'd F, Y' )
        );

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'end_date' ),
            IOL_Helper::convert_date( $args['end_date'], 'd F, Y' )
        );

        return $xml->asXML();
    }

    /**
     * Get hotel details
     *
     * @param $code
     * @param $start_date
     * @param $end_date
     * @return Kanda_Service_Response
     */
    public function hotel_details( $code, $start_date, $end_date ) {
        $args = array(
            'hotel_code' => $code,
            'start_date' => $start_date,
            'end_date'   => $end_date
        );

        $xml = $this->generate_hotel_details_xml( $args );

        return $this->request_instance->process( $xml, $args );
    }

    /**
     * Generate master data XML
     * @param $args
     * @return mixed
     */
    private function generate_master_data_xml( $args ) {
        $xml = $this->request_instance->get_basic_xml( 'retrieve_master_data' );

        $master_data = $xml->addChild(
            IOL_Helper::parse_xml_key( 'master_data' )
        );

        $master_data->addChild(
            IOL_Helper::parse_xml_key( 'hotel_detail' ),
            IOL_Helper::bool_to_string( $args[ 'hotel_detail' ] )
        );

        $master_data->addChild(
            IOL_Helper::parse_xml_key( 'hotel_facilities' ),
            IOL_Helper::bool_to_string( $args[ 'hotel_facilities' ] )
        );

        $master_data->addChild(
            IOL_Helper::parse_xml_key( 'hotel_messages' ),
            IOL_Helper::bool_to_string( $args[ 'hotel_messages' ] )
        );

        $geo = $master_data->addChild(
            IOL_Helper::parse_xml_key( 'geo' )
        );

        $geo->addChild(
            IOL_Helper::parse_xml_key( 'country' ),
            $args[ 'country' ]
        );

        $geo->addChild(
            'ISOCountryCode',
            $args[ 'iso' ]
        );

        $geo->addChild(
            IOL_Helper::parse_xml_key( 'city' ),
            $args[ 'city' ]
        );

        return $xml->asXML();
    }

    /**
     * Get Master Data
     *
     * @return Kanda_Service_Response
     */
    public function get_master_data( $args = array() ) {
        $args = wp_parse_args( $args, array(
            'hotel_detail' => true,
            'hotel_facilities' => true,
            'hotel_messages' => true,
            'country' => 'United Arab Emirates',
            'iso' => 'AE',
            'city' => '',
        ) );

        $xml = $this->generate_master_data_xml( $args );

        return $this->request_instance->process( $xml, $args );
    }

}