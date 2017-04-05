<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Booking_Controller extends Base_Controller {

    protected $name = 'booking';
    public $default_action = 'list';

    public function __construct($post_id = 0) {
        if (!is_user_logged_in()) {
            kanda_to('login');
        }

        parent::__construct($post_id);
    }

    private function create_add_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'create_enqueue_scripts' ), 11 );
    }

    public function create_enqueue_scripts() {
        global $wp_scripts;

        $back_script = $wp_scripts->query( 'back', 'registered' );

        if( ! $back_script ) {
            return false;
        }
        if( !in_array( 'jquery-ui-datepicker', $back_script->deps ) ){
            $back_script->deps[] = 'jquery-ui-datepicker';
        }
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_localize_script( 'back', 'booking', array(
            'validation' => Kanda_Config::get( 'validation->back->form_create_booking' )
        ));
    }

    /**
     * Get request for booking
     * @param $args
     */
    public function create( $args ) {

        $this->create_add_hooks();

        $is_valid = true;
        $hotel_code = isset($_GET['hotel_code']) ? $_GET['hotel_code'] : '';
        $city_code = isset($_GET['city_code']) ? $_GET['city_code'] : '';
        $room_number = isset($_GET['room_number']) ? $_GET['room_number'] : '';
        $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
        $room_type_code = isset($_GET['room_type_code']) ? $_GET['room_type_code'] : '';
        $contract_token_id = isset($_GET['contract_token_id']) ? $_GET['contract_token_id'] : '';
        $room_configuration_id = isset($_GET['room_configuration_id']) ? $_GET['room_configuration_id'] : '';
        $meal_plan_code = isset($_GET['meal_plan_code']) ? $_GET['meal_plan_code'] : '';

        if (
            ! $hotel_code ||
            ! $city_code ||
            ! $room_number ||
            ! $room_type_code ||
            ! $contract_token_id ||
            ! $room_configuration_id ||
            ! $meal_plan_code ||
            ! $request_id
        ) {
            $is_valid = false;
        }

        if( $is_valid ) {

            $security = isset( $_GET['security'] ) ? $_GET['security'] : '';

            if ( wp_verify_nonce($security, 'kanda-create-booking') ) {

                $request = provider_iol()->hotels()->get_request_data($request_id);

                if ( $request ) {
                    $request_args = IOL_Helper::savable_format_to_array($request->request);
                    $adults_count = $request_args['room_occupants'][$room_number]['adults'];
                    $children_count = (bool)$request_args['room_occupants'][$room_number]['child'] ? count($request_args['room_occupants'][$room_number]['child']['age']) : 0;

                    $adults = array_fill(0, $adults_count, array(
                        'title' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'date_of_birth' => '',
                        'gender' => '',
                        'nationality' => 'AM'
                    ));

                    $children = array_fill(0, $children_count, array(
                        'title' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'date_of_birth' => '',
                        'gender' => '',
                        'nationality' => 'AM'
                    ));
                } else {
                    $is_valid = false;
                }

            } else {
                $is_valid = false;
            }
        }


        if( ! $is_valid ) {
            $this->show_404();
        }

        $this->adults = $adults;
        $this->children = $children;
        $this->hotel_code = $hotel_code;
        $this->city_code = $city_code;
        $this->room_number = $room_number;
        $this->room_type_code = $room_type_code;
        $this->contract_token_id = $contract_token_id;
        $this->room_configuration_id = $room_configuration_id;
        $this->meal_plan_code = $meal_plan_code;
        $this->request_id = $request_id;

        $this->title = __( 'Create Booking', 'kanda' );
        $this->view = 'create';
    }

    /**
     * Ajax request for hotel booking
     */
    public function create_booking() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $is_valid = true;

            parse_str( $_POST['details'], $details );
            $security = isset( $details['security'] ) ? $details['security'] : '';

            if( wp_verify_nonce( $security, 'kanda-save-booking' ) ) {

                $hotel_code = isset($details['hotel_code']) ? $details['hotel_code'] : '';
                $city_code = isset($details['city_code']) ? $details['city_code'] : '';
                $room_number = isset($details['room_number']) ? $details['room_number'] : '';
                $request_id = isset($details['request_id']) ? $details['request_id'] : '';
                $room_type_code = isset($details['room_type_code']) ? $details['room_type_code'] : '';
                $contract_token_id = isset($details['contract_token_id']) ? $details['contract_token_id'] : '';
                $room_configuration_id = isset($details['room_configuration_id']) ? $details['room_configuration_id'] : '';
                $meal_plan_code = isset($details['meal_plan_code']) ? $details['meal_plan_code'] : '';

                if (
                    ! $hotel_code ||
                    ! $city_code ||
                    ! $room_number ||
                    ! $room_type_code ||
                    ! $contract_token_id ||
                    ! $room_configuration_id ||
                    ! $meal_plan_code ||
                    ! $request_id
                ) {
                    $is_valid = false;
                }

                if( $is_valid ) {

                    $request = provider_iol()->hotels()->get_request_data($request_id);

                    if ( $request ) {
                        $errors = array(
                            'adults' => array(),
                            'children' => array()
                        );
                        $request_args = IOL_Helper::savable_format_to_array($request->request);
                        $adults_count = $request_args['room_occupants'][$room_number]['adults'];
                        $children_count = (bool)$request_args['room_occupants'][$room_number]['child'] ? count($request_args['room_occupants'][$room_number]['child']['age']) : 0;

                        $adults = isset($details['adults']) ? $details['adults'] : array_fill(0, $adults_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'date_of_birth' => '',
                            'gender' => ''
                        ));

                        $children = isset( $details['children'] ) ? $details['children'] : array_fill(0, $children_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'date_of_birth' => '',
                            'gender' => ''
                        ));

                        $response = provider_iol()->bookings()->create(array(
                            'start_date'            => $request_args['start_date'],
                            'end_date'              => $request_args['end_date'],
                            'hotel_code'            => $hotel_code,
                            'city_code'             => $city_code,
                            'room_type_code'        => $room_type_code,
                            'contract_token_id'     => $contract_token_id,
                            'room_configuration_id' => $room_configuration_id,
                            'meal_plan_code'        => $meal_plan_code,
                            'adults'                => $adults,
                            'children'              => $children
                        ));

                        if ( $response->is_valid() ) {
                            $data = $response->data;

                            $start_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['startdate'] );
                            $end_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['enddate'] );
                            $interval = $end_date->diff( $start_date );
                            $nights_count = $interval->d;

                            $real_price = $data['bookingdetails']['bookingtotalrate'];
                            $real_price = kanda_covert_currency_to( $real_price, 'USD', $data['bookingdetails']['currency'] );
                            $real_price = $real_price['amount'];

                            $additional_fee = kanda_get_hotel_additional_fee( $data['hoteldetails']['hotelcode'] );
                            $earnings = $additional_fee * $nights_count;
                            $agency_price = $real_price + $earnings;

                            $earnings = number_format( $earnings, 2 );
                            $real_price = number_format( $real_price, 2 );
                            $agency_price = number_format( $agency_price, 2 );

                            $meta_data = array(
                                'start_date'     => $start_date->format( 'Ymd' ),
                                'end_date'       => $end_date->format( 'Ymd' ),
                                'hotel_name'     => $data['hoteldetails']['hotelname'],
                                'hotel_code'     => $data['hoteldetails']['hotelcode'],
                                'real_price'     => $real_price,
                                'agency_price'   => $agency_price,
                                'earnings'       => $earnings,
                                'booking_status' => $data['bookingdetails']['bookingstatus'],
                                'room_type'      => $data['hoteldetails']['roomdetails']['room']['roomtype'],
                                'meal_plan'      => $data['hoteldetails']['roomdetails']['room']['mealplan'],
                                'booking_number' => $data['bookingdetails']['bookingnumber'],
                                'adults'         => '',
                                'children'       => ''
                            );

                            $nationalities = kanda_get_nationality_choices();
                            $keymap = array(
                                'title'         => 'title',
                                'first_name'    => 'firstname',
                                'last_name'     => 'lastname',
                                'date_of_birth' => 'dateofbirth',
                                'nationality'   => 'nationality',
                                'gender'        => 'gender'
                            );

                            $passengers = $data['bookingdetails']['passengerdetails']['passenger'];
                            $adults = wp_list_filter( $passengers, array(
                                'passengertype' => 'ADT'
                            ) );
                            $adults = array_values( $adults );

                            $repeater = array(
                                'adults' => array(),
                                'children' => array()
                            );
                            for( $i = 0; $i < count( $adults ); $i++ ) {
                                $adult = array();
                                foreach( $keymap as $meta_key => $response_key ) {
                                    if( $response_key == 'nationality' ) {
                                        $meta_value = $nationalities[ $adults[$i][$response_key] ];
                                    } else {
                                        $meta_value = $adults[$i][$response_key];
                                    }

                                    $adult[ $meta_key ] = $meta_value;
                                }
                                $repeater['adults'][] = $adult;
                            }

                            $children = wp_list_filter( $passengers, array(
                                'passengertype' => 'CHD'
                            ) );
                            $children = array_values( $children );

                            for( $i = 0; $i < count( $children ); $i++ ) {
                                $child = array();
                                foreach( $keymap as $meta_key => $response_key ) {
                                    if( $response_key == 'nationality' ) {
                                        $meta_value = $nationalities[ $children[$i][$response_key] ];
                                    } else {
                                        $meta_value = $children[$i][$response_key];
                                    }

                                    $child[ $meta_key ] = $meta_value;
                                }
                                $repeater['children'][] = $child;
                            }

                            $booking_id = wp_insert_post( array(
                                'post_author' => get_current_user_id(),
                                'post_title' => sprintf( '%1$s - #%2$s', $data['hoteldetails']['hotelname'], $data['bookingdetails']['bookingnumber'] ),
                                'post_name' => kanda_generate_random_string( 20 ),
                                'post_status' => 'publish',
                                'post_type' => 'booking'
                            ), true );

                            if( is_wp_error( $booking_id ) ) {
                                $is_valid = false;
                                // to something
                            } else {
                                $redirect_to = get_permalink( $booking_id );
                                foreach( $meta_data as $meta_key => $meta_value ) {
                                    update_field( $meta_key, $meta_value, $booking_id );
                                }
                                foreach( $repeater as $parent_key => $rows ) {
                                    foreach( $rows as $row ) {
                                        add_row( $parent_key, $row, $booking_id );
                                    }
                                }
                            }
                        } else {
                            $is_valid = false;
                            $message = $response->message;
                        }
                    } else {
                        $is_valid = false;
                        $message = esc_html__( 'Invalid request', 'kanda' );
                    }

                }
            } else {
                $is_valid = false;
                $message = esc_html__( 'Invalid request', 'kanda' );
            }

            if( $is_valid ) {
                wp_send_json_success( array(
                    'redirect_to' => $redirect_to
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => $message
                ) );
            }
        }
        $this->show_404();
    }

}