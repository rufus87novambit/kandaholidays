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

        $search_criteria->addChild(
            IOL_Helper::parse_xml_key( 'optional-supplement-Y-N' ),
            IOL_Helper::bool_to_string( true )
        );

        return $xml->asXML();
    }

    /**
     * Get default args for search request
     *
     * @return array
     */
    private function get_search_request_detault_args() {
        return array(
            'page'      => 1,
            'limit'     => IOL_Config::get('sql_limit->search'),
            'order_by'  => 'name',
            'order'     => 'asc'
        );
    }

    /**
     * Search hotels
     *
     * @param $request_args
     * @param array $args
     * @return IOL_Response|Kanda_Service_Response
     */
    public function search( $request_args, $args = array() ) {

        $args = wp_parse_args( $args, $this->get_search_request_detault_args() );

        $cache_instance = $this->get_cache_instance();
        $cached = $cache_instance->get( $request_args );

        if( is_null( $args['limit'] ) ) {
            $args['limit'] = IOL_Config::get('sql_limit->search');
        } elseif( $args['limit'] < 0 ) {
            $args['limit'] = -1;
        } else {
            $args['limit'] = absint($args['limit']);
        }

        if( $cached ) {
            // get it from cache
            if( $cache_instance->is_alive( $cached->created_at ) ) {

                $cached_data = $cache_instance->get_data( $cached->id, $args );

                $response_data = array();
                foreach( $cached_data['data'] as $d ) {
                    $response_data[] = IOL_Helper::savable_format_to_array ( $d->data );
                }
                $response = new IOL_Response();

                $response->code = $cached->status_code;
                $response->request = $request_args;
                $response->request_id = $cached->id;
                $response->data = $response_data;
                $response->total = $cached_data['total'];
                $response->per_page = $args['limit'];

            }
            // outdated / need to update
            else {
                $request_args = IOL_Helper::savable_format_to_array( $cached->request );
                $xml = $this->generate_search_xml( $request_args );

                $response = $this->request_instance->process( $xml, $request_args );

                if( $response->is_valid() ) {
                    $request_id = $cache_instance->cache( $response, 'update' );

                    if( isset( $response->data[ 'hotels' ][ 'hotel' ] ) ) {
                        $offset = ( $args['page'] - 1 ) * $args['limit'];
                        $data = array_slice( $response->data[ 'hotels' ][ 'hotel' ], $offset, $args['limit'] );
                        $total = count( $response->data[ 'hotels' ][ 'hotel' ] );
                    } else {
                        $data = array();
                        $total = 0;
                    }

                    $response->request_id = $request_id;
                    $response->data = $data;
                    $response->total = $total;
                    $response->per_page = $args['limit'];
                }
            }

        }
        // new request statement
        else {
            $xml = $this->generate_search_xml( $request_args );

            $response = $this->request_instance->process( $xml, $request_args );

            if( $response->is_valid() ) {
                $request_id = $cache_instance->cache( $response, 'insert' );

                if( isset( $response->data[ 'hotels' ][ 'hotel' ] ) ) {
                    $offset = ( $args['page'] - 1 ) * $args['limit'];
                    $data = array_slice( $response->data[ 'hotels' ][ 'hotel' ], $offset, $args['limit'] );
                    $total = count( $response->data[ 'hotels' ][ 'hotel' ] );
                } else {
                    $data = array();
                    $total = 0;
                }

                $response->request_id = $request_id;
                $response->data = $data;
                $response->total = $total;
                $response->per_page = $args['limit'];
            }
        }

        return $response;
    }

    /**
     * Search hotel by request id
     *
     * @param $request_id
     * @param array $args
     * @return IOL_Response|Kanda_Service_Response
     */
    public function search_by_id( $request_id, $args = array() ) {

        $args = wp_parse_args( $args, $this->get_search_request_detault_args() );

        $cache_instance = $this->get_cache_instance();
        $cached = $cache_instance->get( $request_id );

        if( $cached ) {
            $request_args = IOL_Helper::savable_format_to_array( $cached->request );
            $response = $this->search( $request_args, $args );
        } else {
            $response = new IOL_Response();

            $response->code = 404;
            $response->message = __( 'Invalid request', 'kanda' );
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