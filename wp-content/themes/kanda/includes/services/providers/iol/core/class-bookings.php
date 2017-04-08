<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Bookings {

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
     * Get booking creation request XML
     * @param $args
     * @return mixed
     */
    private function get_create_booking_xml( $args ) {
        $xml = $this->request_instance->get_basic_xml( 'hotel_booking_request' );

        $passenger_details = $xml->addChild(
            IOL_Helper::parse_xml_key( 'passenger_details' )
        );

        foreach( $args['adults'] as $adult ) {

            $passenger = $passenger_details->addChild(
                IOL_Helper::parse_xml_key( 'passenger' )
            );

            $pax_number = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'pax_number' ),
                uniqid()
            );

            $room_number = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'room_no' ),
                1
            );

            $title = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'title' ),
                IOL_Helper::str_to_title( $adult['title'] )
            );

            $passenger_type = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'passenger_type' ),
                IOL_Helper::convert_passenger_type('adult')
            );

            $date_of_birth = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'date_of_birth' ),
                IOL_Helper::convert_date( $adult['date_of_birth'], Kanda_Config::get( 'display_date_format' ) )
            );

            $first_name = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'first_name' ),
                $adult['first_name']
            );

            $last_name = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'last_name' ),
                $adult['last_name']
            );

            $gender = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'gender' ),
                strtoupper( $adult['gender'] )
            );

            $nationality = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'nationality' ),
                strtoupper( $adult['nationality'] )
            );
        }

        foreach( $args['children'] as $child ) {
            $passenger = $passenger_details->addChild(
                IOL_Helper::parse_xml_key( 'passenger' )
            );

            $pax_number = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'pax_number' ),
                uniqid()
            );

            $room_number = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'room_no' ),
                1
            );

            $title = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'title' ),
                IOL_Helper::str_to_title( $child['title'] )
            );

            $passenger_type = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'passenger_type' ),
                IOL_Helper::convert_passenger_type('child')
            );

            $date_of_birth = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'date_of_birth' ),
                IOL_Helper::convert_date( $child['date_of_birth'], Kanda_Config::get( 'display_date_format' ) )
            );

            $age = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'age' ),
                DateTime::createFromFormat(Kanda_Config::get( 'display_date_format' ), $child['date_of_birth'] )->diff(new DateTime('now'))->y
            );

            $first_name = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'first_name' ),
                $child['first_name']
            );

            $last_name = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'last_name' ),
                $child['last_name']
            );

            $gender = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'gender' ),
                strtoupper( $child['gender'] )
            );

            $nationality = $passenger->addChild(
                IOL_Helper::parse_xml_key( 'nationality' ),
                strtoupper( $child['nationality'] )
            );
        }


        $hotel_details = $xml->addChild(
            IOL_Helper::parse_xml_key( 'hotel_details' )
        );

        $start_date = $hotel_details->addChild(
            IOL_Helper::parse_xml_key( 'start_date' ),
            IOL_Helper::convert_date( $args['start_date'], Kanda_Config::get( 'display_date_format' ) )
        );

        $end_date = $hotel_details->addChild(
            IOL_Helper::parse_xml_key( 'end_date' ),
            IOL_Helper::convert_date( $args['end_date'], Kanda_Config::get( 'display_date_format' ) )
        );

        $hotel_code = $hotel_details->addChild(
            IOL_Helper::parse_xml_key( 'hotel_code' ),
            $args['hotel_code']
        );

        $city_code = $hotel_details->addChild(
            IOL_Helper::parse_xml_key( 'city_code' ),
            $args['city_code']
        );

        $room_details = $hotel_details->addChild(
            IOL_Helper::parse_xml_key( 'room_details' )
        );

        $room = $room_details->addChild(
            IOL_Helper::parse_xml_key( 'room' )
        );

        $room_type_code = $room->addChild(
            IOL_Helper::parse_xml_key( 'room_type_code' ),
            $args['room_type_code']
        );

        $contract_token_id = $room->addChild(
            IOL_Helper::parse_xml_key( 'contract_token_id' ),
            $args['contract_token_id']
        );

        $room_configuration_id = $room->addChild(
            IOL_Helper::parse_xml_key( 'room_configuration_id' ),
            $args['room_configuration_id']
        );

        $meal_plan_code = $room->addChild(
            IOL_Helper::parse_xml_key( 'meal_plan_code' ),
            $args['meal_plan_code']
        );

        return $xml->asXML();
    }

    /**
     * Create booking
     *
     * @param $args
     * @return Kanda_Service_Response
     */
    public function create( $args ) {

        $xml = $this->get_create_booking_xml( $args );

        return $this->request_instance->process( $xml, $args );

    }

    private function get_cancel_booking_xml( $args ) {
        $xml = $this->request_instance->get_basic_xml( 'cancel_hotel_booking_request' );

        $booking_details = $xml->addChild(
            IOL_Helper::parse_xml_key( 'booking_details' )
        );

        $source = $booking_details->addChild(
            IOL_Helper::parse_xml_key( 'source' ),
            $args['source']
        );

        $booking_number = $booking_details->addChild(
            IOL_Helper::parse_xml_key( 'booking_number' ),
            $args['booking_number']
        );

        $sub_res_no = $booking_details->addChild(
            IOL_Helper::parse_xml_key( 'sub_res_no' ),
            $args['sub_res_no']
        );

        return $xml->asXML();

    }

    /**
     * Cancel booking
     *
     * @param $args
     * @return Kanda_Service_Response
     */
    public function cancel( $args ) {

        $xml = $this->get_cancel_booking_xml( $args );

        return $this->request_instance->process( $xml, $args );

    }

}