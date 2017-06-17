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
        $requested_room_number = isset($_GET['room_n']) ? $_GET['room_n'] : 1;

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
                    $adults_count = $request_args['room_occupants'][$requested_room_number]['adults'];
                    $children_count = (bool)$request_args['room_occupants'][$requested_room_number]['child'] ? count($request_args['room_occupants'][$requested_room_number]['child']['age']) : 0;

                    $adults = array_fill(0, $adults_count, array(
                        'title' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'gender' => ''
                    ));

                    $children = array();
                    for( $i = 0; $i < $children_count; $i++ ) {
                        $children[] = array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'gender' => '',
                            'age' => $request_args[ 'room_occupants' ][ $requested_room_number ][ 'child' ][ 'age' ][ $i ]
                        );
                    }
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
        $this->requested_room_number = $requested_room_number;

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
                $requested_room_number = isset($details['room_n']) ? $details['room_n'] : '';

                if (
                    ! $hotel_code ||
                    ! $city_code ||
                    ! $room_number ||
                    ! $room_type_code ||
                    ! $contract_token_id ||
                    ! $room_configuration_id ||
                    ! $meal_plan_code ||
                    ! $request_id ||
                    ! $requested_room_number
                ) {
                    $is_valid = false;
                }

                if( $is_valid ) {

                    $request = provider_iol()->hotels()->get_request_data($request_id);

                    if ( $request ) {

                        $request_args = IOL_Helper::savable_format_to_array($request->request);
                        $adults_count = $request_args['room_occupants'][$requested_room_number]['adults'];
                        $children_count = (bool)$request_args['room_occupants'][$requested_room_number]['child'] ? count($request_args['room_occupants'][$requested_room_number]['child']['age']) : 0;

                        $adults = isset($details['adults']) ? $details['adults'] : array_fill(0, $adults_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'gender' => ''
                        ));

                        $children = isset( $details['children'] ) ? $details['children'] : array_fill(0, $children_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'age' => '',
                            'gender' => ''
                        ));

                        /** get cancellation policy */
                        $c_start_date = Datetime::createFromFormat( Kanda_Config::get( 'display_date_format' ), $request_args['start_date'] )->format( IOL_Config::get( 'date_format' ) );
                        $c_end_date = Datetime::createFromFormat( Kanda_Config::get( 'display_date_format' ), $request_args['end_date'] )->format( IOL_Config::get( 'date_format' ) );
                        $cancellation_response = provider_iol()->hotels()->hotel_cancellation_policy( $hotel_code, $room_type_code, $contract_token_id, $c_start_date, $c_end_date );

                        if( $cancellation_response->is_valid() ) {

                            $repeaters = array(
                                'adults'                => array(),
                                'children'              => array(),
                                'cancellation_policy'   => array()
                            );

                            $data = $cancellation_response->data;
                            $cancellation_policies = ( array_key_exists( 'cancellationdetails', $data ) && isset( $data['cancellationdetails']['cancellation'] ) ) ? $data['cancellationdetails']['cancellation'] : array();
                            $spare = Kanda_Config::get( 'spare_days_count' ) * 86400;
                            $account_type = get_field( 'account_type', 'user_' . get_current_user_id() );
                            $allow_booking = true;

                            for( $i = 0; $i < count( $cancellation_policies ); $i++ ) {
                                $now = time();
                                $from_timestamp = max( strtotime( $cancellation_policies[$i]['fromdate'] ), $now ) - $spare;

                                $to_timestamp = min( strtotime( $cancellation_policies[$i]['todate'] ), strtotime( $request_args['end_date'] ) );
                                if( $to_timestamp != strtotime( $request_args['end_date'] ) ) {
                                    $to_timestamp -= $spare;
                                }
                                if ( $to_timestamp <= $now ) {
                                    continue;
                                }

                                if( $account_type == 'prepaid' ) {
                                    if( ( $now >= $from_timestamp ) && ( $now < $to_timestamp ) ) {
                                        $allow_booking = false;
                                    }
                                }

                                $repeaters['cancellation_policy'][] = array(
                                    'from'          => date( 'Ymd', $from_timestamp ),
                                    'to'            => date( 'Ymd', $to_timestamp ),
                                    'charge'        => ( strtolower( $cancellation_policies[$i]['percentoramt'] ) == 'a' ) ? sprintf( '%1$d %2$s', $cancellation_policies[$i]['nighttocharge'], _n( 'night', 'nights', $cancellation_policies[$i]['nighttocharge'], 'kanda' ) ) : sprintf( '%1$d%%', intval( $cancellation_policies[$i]['value'] ) )
                                );

                            }

                            if( $allow_booking ) {
                                $booking_response = provider_iol()->bookings()->create(array(
                                    'start_date'            => $request_args['start_date'],
                                    'end_date'              => $request_args['end_date'],
                                    'hotel_code'            => $hotel_code,
                                    'city_code'             => $city_code,
                                    'room_number'           => 1, //$room_number,
                                    'room_type_code'        => $room_type_code,
                                    'contract_token_id'     => $contract_token_id,
                                    'room_configuration_id' => 1, //$room_configuration_id,
                                    'meal_plan_code'        => $meal_plan_code,
                                    'adults'                => $adults,
                                    'children'              => $children,
									'agency_ref'			=> get_user_meta( get_current_user_id(), 'company_name', true )
                                ));

                                if ( $booking_response->is_valid() ) {
                                    $data = $booking_response->data;

                                    $start_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['startdate'] );
                                    $end_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['enddate'] );
                                    $interval = $end_date->diff( $start_date );
                                    $nights_count = $interval->d;

                                    $real_price = $data['bookingdetails']['bookingtotalrate'];
                                    $real_price = kanda_covert_currency_to( $real_price, 'USD', $data['bookingdetails']['currency'] );
                                    $real_price = $real_price['amount'];

                                    $additional_fee = kanda_get_hotel_additional_fee( $data['hoteldetails']['hotelcode'] );
                                    $earnings = $additional_fee * $nights_count;
                                    $agency_fee = kanda_get_user_additional_fee() * $nights_count;
                                    $agency_price = $real_price + $earnings + $agency_fee;

                                    $earnings = number_format( $earnings, 2 );
                                    $real_price = number_format( $real_price, 2 );
                                    $agency_price = number_format( $agency_price, 2 );

                                    $hotels_query = new WP_Query(array(
                                        'post_type' => 'hotel',
                                        'post_status' => 'publish',
                                        'posts_per_page' => 1,
                                        'meta_query' => array(
                                            array(
                                                'key'     => 'hotelcode',
                                                'value'   => $data['hoteldetails']['hotelcode'],
                                                'compare' => '=',
                                            )
                                        )
                                    ));
                                    if( $hotels_query->have_posts() ) {
                                        $hotels = $hotels_query->get_posts();
                                        $hotel = $hotels[0];
                                        $hotel_city = kanda_get_post_meta( $hotel->ID, 'hotelcity' );
                                    } else {
                                        $hotel_city = '';
                                    }

                                    $meta_data = array(
                                        'start_date'            => $start_date->format( 'Ymd' ),
                                        'end_date'              => $end_date->format( 'Ymd' ),
                                        'hotel_name'            => $data['hoteldetails']['hotelname'],
                                        'hotel_code'            => $data['hoteldetails']['hotelcode'],
                                        'hotel_city'            => $hotel_city,
                                        'real_price'            => $real_price,
                                        'agency_price'          => $agency_price,
                                        'earnings'              => $earnings,
                                        'booking_status'        => $data['bookingdetails']['bookingstatus'],
                                        'room_type'             => $data['hoteldetails']['roomdetails']['room']['roomtype'],
                                        'meal_plan'             => $data['hoteldetails']['roomdetails']['room']['mealplan'],
                                        'booking_number'        => $data['bookingdetails']['bookingnumber'],
                                        'booking_date'          => $data['bookingdetails']['bookeddate'],
                                        'payment_status'        => 'unpaid',
                                        'visa_rate'             => 0,
                                        'transfer_rate'         => 0,
                                        'other_rate'            => 0,
                                        'adults'                => '',
                                        'children'              => '',
                                        'cancellation_policy'   => '',
                                        'additional_requests'   => array_keys( $details['additional_requests'] )
                                    );

                                    $keymap = array(
                                        'title'         => 'title',
                                        'first_name'    => 'firstname',
                                        'last_name'     => 'lastname',
                                        'gender'        => 'gender'
                                    );

                                    $passengers = $data['bookingdetails']['passengerdetails']['passenger'];
                                    $passengers = IOL_Helper::is_associative_array( $passengers ) ? array( $passengers ) : $passengers;

                                    $passengers_meta = array();
                                    /** adults repeater */
                                    $adults = wp_list_filter( $passengers, array(
                                        'passengertype' => 'ADT'
                                    ) );
                                    $adults = array_values( $adults );

                                    for( $i = 0; $i < count( $adults ); $i++ ) {
                                        $adult = array();
                                        foreach( $keymap as $meta_key => $response_key ) {
                                            $adult[ $meta_key ] = $adults[$i][$response_key];
                                        }
                                        $passengers_meta[] = sprintf( '%1$s %2$s', $adult['first_name'], $adult['last_name'] );
                                        $repeaters['adults'][] = $adult;
                                    }
                                    /** /end adults repeater */


                                    /** children repeater */
                                    $children = wp_list_filter( $passengers, array(
                                        'passengertype' => 'CHD'
                                    ) );
                                    $children = array_values( $children );

                                    for( $i = 0; $i < count( $children ); $i++ ) {
                                        $child = array();
                                        foreach( array_merge( $keymap, array( 'age' => 'age' ) ) as $meta_key => $response_key ) {
                                            $child[ $meta_key ] = $children[$i][$response_key];
                                        }
                                        $passengers_meta[] = sprintf( '%1$s %2$s', $child['first_name'], $child['last_name'] );
                                        $repeaters['children'][] = $child;
                                    }
                                    /** /end children repeater */

                                    $booking_id = wp_insert_post( array(
                                        'post_author' => get_current_user_id(),
                                        'post_title' => sprintf( 'PNR %1$s - %2$s', $data['bookingdetails']['bookingnumber'], $data['hoteldetails']['hotelname'] ),
                                        'post_name' => kanda_generate_random_string( 'string', 20 ),
                                        'post_status' => 'publish',
                                        'post_type' => 'booking',
                                        'meta_input' => array(
                                            'subresno' => $data['hoteldetails']['roomdetails']['room']['subresno'],
                                            'source' => $data['bookingdetails']['source'],
                                            'passenger_names' => implode( '##', $passengers_meta ),
                                            'nights_count' => $nights_count
                                        )
                                    ), true );

                                    if( is_wp_error( $booking_id ) ) {
                                        $is_valid = false;
                                        $message = __( 'Error creating booking', 'kanda' );
                                    } else {
                                        $redirect_to = add_query_arg( array( 'update' => 0 ), get_permalink( $booking_id ) );
                                        foreach( $meta_data as $meta_key => $meta_value ) {
                                            switch ( $meta_key ) {
                                                case 'payment_status':
                                                case 'booking_status':
                                                    $sanitize_value = strtolower( $meta_value );
                                                    break;
                                                default:
                                                    $sanitize_value = $meta_value;
                                            }
                                            update_field( $meta_key, $sanitize_value, $booking_id );
                                        }
                                        foreach( $repeaters as $parent_key => $rows ) {
                                            foreach( $rows as $row ) {
                                                add_row( $parent_key, $row, $booking_id );
                                            }
                                        }

                                        do_action( 'kanda/booking/create', $booking_id );
                                    }
                                } else {
                                    $is_valid = false;
                                    $message = $booking_response->message;
                                }
                            } else {
                                $is_valid = false;
                                $message = __( 'Please make the full prepayment for the below booking in order to confirm it.', 'kanda' );
                            }

                        } else {
                            $is_valid = false;
                            $message = $cancellation_response->message;
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

    /**
     * Send booking details via email
     * @param $args
     */
    public function send_details_email( $args ) {

        if( isset( $_POST['kanda_send_email'] ) ) {

            $is_valid = true;

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';
            if( wp_verify_nonce( $security, 'kanda-send-booking-data-email' ) ) {

                $email = isset( $_POST['email_address'] ) ? $_POST['email_address'] : '';
                if( !$email ) {
                    $is_valid = false;
                    $message = __( 'Email address is requeired', 'kanda' );
                } elseif( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) {
                    $is_valid = false;
                    $message = __( 'Invalid email address', 'kanda' );
                }

                if( $is_valid ) {
                    $bookings_query = new WP_Query( array(
                        'name'        => $args[ 'k_booking_slug' ],
                        'author'      => get_current_user_id(),
                        'post_type'   => 'booking',
                        'post_status' => 'publish',
                        'numberposts' => 1
                    ) );
                    if( $bookings_query->have_posts() ) {
                        $bookings = $bookings_query->get_posts();
                        $booking = $bookings[0];

                        ob_start();
                        $booking_id = $booking->ID;
                        include Kanda_Mailer::get_layout_path() . 'booking-details.php';
                        $booking_details = ob_get_clean();

                        $user = get_user_by( 'email', $email );
                        $first_name = $last_name = '';
                        if( $user ) {
                            $first_name = $user->first_name;
                            $last_name = $user->last_name;
                        }

                        $subject = kanda_get_theme_option( 'email_booking_details_title' );
                        $message = kanda_get_theme_option( 'email_booking_details_body' );
                        $variables = array(
                            '{{BOOKING_DETAILS}}' => $booking_details,
                            '{{FIRST_NAME}}'      => $first_name,
                            '{{LAST_NAME}}'       => $last_name
                        );
                        $sent = kanda_mailer()->send_user_email( $email, $subject, $message, $variables );
                        if( $sent ) {
                            $notification_type = 'success';
                            $notification_message = __( 'Email successfully sent', 'kanda' );
                        } else {
                            $notification_type = 'error';
                            $notification_message = __( 'Error sending email. Please try again later.', 'kanda' );
                        }
                        $this->set_notification( $notification_type, $notification_message );

                        kanda_to( 'booking', array( 'view', $args[ 'k_booking_slug' ] ), array( 'update' => 0 ) );
                    } else {
                        $is_valid = false;
                        $message = __( 'Invalid request', 'kanda' );
                    }
                    wp_reset_query();
                }

            } else {
                $is_valid = false;
                $message = __( 'Invalid request', 'kanda' );
            }

            if( ! $is_valid ) {
                kanda_to( 404 );
            }

        }
        kanda_to( 404 );
    }

    /**
     * Cancel booking
     */
    public function cancel_booking() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $is_valid = true;

            $security = isset($_REQUEST['security']) ? $_REQUEST['security'] : '';

            if (wp_verify_nonce($security, 'kanda-cancel-booking')) {

                $booking_id = (int)( isset( $_REQUEST['booking_id'] ) ? $_REQUEST['booking_id'] : '' );
                if( $booking_id && $booking = get_post( $booking_id ) ) {

                    if( $booking->post_author == get_current_user_id() ) {

                        $sub_res_no = kanda_get_post_meta($booking_id, 'subresno');
                        $source = kanda_get_post_meta($booking_id, 'source');
                        $booking_number = kanda_get_post_meta($booking_id, 'booking_number');

                        $response = provider_iol()->bookings()->cancel(array(
                            'sub_res_no' => $sub_res_no,
                            'source' => $source,
                            'booking_number' => $booking_number
                        ));

                        if ($response->is_valid()) {
                            $data = $response->data;

                            if ($data['currency']) {
                                $cancellation_total_amount_converted = kanda_covert_currency_to($data['totalamount'], 'USD', $data['currency']);
                                $cancellation_total_amount = $cancellation_total_amount_converted['amount'];
                            } else {
                                $cancellation_total_amount = $data['totalamount'];
                            }

                            // we need to calculate cancellation ourselves
                            if( ! $cancellation_total_amount ) {
                                $cancellation_type = false;
                                while( have_rows( 'cancellation_policy', $booking_id ) ) {
                                    the_row();

                                    $from = get_sub_field( 'from', false );
                                    $to = get_sub_field( 'to', false );

                                    $from_timestamp = strtotime( $from );
                                    $to_timestamp = strtotime( $to );
                                    $now = time();
                                    if( $now >= $from_timestamp && $now < $to_timestamp ) {
                                        $charge = get_sub_field( 'charge' );
                                        $cancellation_type = ( strpos( $charge, 'night' ) === false ) ? 'p' : 'a';
                                        $charge = preg_replace('/[^\d.]/', '', $charge);
                                        break;
                                    }
                                }

                                if( $cancellation_type == 'a' ) {
                                    $nights_count = get_post_meta($booking_id, 'nights_count', true);
                                    $agency_price = floatval( str_replace(',', '', get_field( 'agency_price', $booking_id ) ) );
                                    $cancellation_total_amount = $agency_price / $nights_count * $charge;
                                    $cancellation_total_amount = number_format($cancellation_total_amount, 2);
                                } elseif( $cancellation_type == 'p' ) {
                                    $agency_price = floatval( str_replace(',', '', get_field( 'agency_price', $booking_id ) ) );
                                    $cancellation_total_amount = $agency_price * $charge / 100;
                                    $cancellation_total_amount = number_format($cancellation_total_amount, 2);
                                }
                            }

                            update_post_meta( $booking_id, 'booking_status', 'cancelled' );
                            update_post_meta( $booking_id, 'cancellation_total_amount', $cancellation_total_amount );
                            update_field( 'agency_price', $cancellation_total_amount, $booking_id );

                            do_action( 'kanda/booking/cancel', $booking_id );

                            $redirect_to = add_query_arg( array( 'update' => 0 ), get_permalink( $booking_id ) );

                        } else {
                            $is_valid = false;
                            $message = $response->message;
                        }
                    } else {
                        $is_valid = false;
                        $message = esc_html__( 'Invalid request', 'kanda' );
                    }

                } else {
                    $is_valid = false;
                    $message = esc_html__( 'Invalid request', 'kanda' );
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

    /**
     * View voucher
     */
    function view_voucher() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $security = isset($_REQUEST['security']) ? $_REQUEST['security'] : '';
            $is_valid = true;

            if (wp_verify_nonce($security, 'kanda-view-voucher')) {

                if( !( isset( $_REQUEST['id'] ) && $_REQUEST['id'] ) ) {
                    $is_valid = false;
                    $message = __( 'Invalid Booking', 'kanda' );
                }

            } else {
                $is_valid = false;
                $message = esc_html__( 'Invalid request', 'kanda' );
            }

            if( $is_valid ) {

                $template = KANDA_THEME_PATH . 'views/partials/booking-travel-voucher.php';
                if( file_exists( $template ) ) {

                    // set variables
                    $content = $this->render_template($template, array(
                        'booking_id' => $_REQUEST['id']
                    ));
                } else {
                    $is_valid = false;
                    $message = __( 'Internal server error', 'kanda' );
                }

            }

            if( $is_valid ) {
                wp_send_json_success( $content );
            } else {
                wp_send_json_error( $message );
            }
        }
        $this->show_404();
    }

    /**
     * Download voucher
     *
     * @param $args
     */
    function download_voucher( $args ) {
        $booking = get_post( (int)$args[ 'k_booking_id' ] );

        if( $booking ) {

            $template = KANDA_THEME_PATH . 'views/partials/booking-travel-voucher-pdf.php';
            if( file_exists( $template ) ) {

                // set variables
                $content = $this->render_template($template, array(
                    'booking_id' => $args['k_booking_id']
                ));

                require_once( KANDA_INCLUDES_PATH . 'vendor/mpdf/mpdf.php' );
                $mpdf = new mPDF();
                $mpdf->WriteHTML( $content );

//                $mpdf->Output( KANDA_THEME_PATH . 'mpdf.pdf', 'F');
                $mpdf->Output( 'voucher.pdf', 'D');
                die;
            }

        } else {
            $this->show_404();
        }
    }

    /**
     * Get booking details
     */
    function get_booking_details() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $security = isset( $_REQUEST['security'] ) ? $_REQUEST['security'] : '';

            $is_valid = true;
            if( wp_verify_nonce( $security, 'kanda-get-booking-details' ) ) {

                $booking_number = isset( $_REQUEST['booking_number'] ) ? $_REQUEST['booking_number'] : false;
                if( ! $booking_number ) {
                    $is_valid = false;
                    $message = __( 'Booking number is required', 'kanda' );
                }

                $booking_source = isset( $_REQUEST['booking_source'] ) ? $_REQUEST['booking_source'] : false;
                if( ! $booking_source ) {
                    $is_valid = false;
                    $message = __( 'Booking source is required', 'kanda' );
                }

                $booking_id = isset( $_REQUEST['booking_id'] ) ? $_REQUEST['booking_id'] : false;
                if( ! $booking_id ) {
                    $is_valid = false;
                    $message = __( 'Booking id is required', 'kanda' );
                }

                if( $is_valid ) {

                    $response = provider_iol()->bookings()->booking_details( array(
                        'booking_number' => $booking_number,
                        'booking_source' => $booking_source
                    ) );

                    if( ! $response->is_valid() ) {
                        $is_valid = false;
                        $message = $response->message;
                    } else {

                        $template = KANDA_THEME_PATH . 'views/partials/booking-details.php';
                        if( file_exists( $template ) ) {
                            $data = $response->data;

                            $room = $data['hoteldetails']['roomdetails']['room'];
                            $booking_details = $data['bookingdetails'];
                            $passenger_details = $data['bookingdetails']['passengerdetails']['passenger'];

                            /** Pricing calculation **/
                            $start_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['roomdetails']['room']['startdate'] );
                            $end_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['roomdetails']['room']['enddate'] );
                            $interval = $end_date->diff( $start_date );
                            $nights_count = $interval->d;

                            $real_price = $data['hoteldetails']['roomdetails']['room']['rate'];
							
							// apply discounts to room real price
							if( isset( $data['hoteldetails']['roomdetails']['room']['discountdetails']['discount'] ) ) {
								$discounts = IOL_Helper::is_associative_array( $data['hoteldetails']['roomdetails']['room']['discountdetails']['discount'] ) ? array( $data['hoteldetails']['roomdetails']['room']['discountdetails']['discount'] ) : $data['hoteldetails']['roomdetails']['room']['discountdetails']['discount'];
								foreach( $discounts as $discount ) {
									$real_price -= abs( $discount['totaldiscountrate'] );
								}
							}
							
                            $real_price = kanda_covert_currency_to( $real_price, 'USD', $data['bookingdetails']['currency'] );
                            $real_price = $real_price['amount'];

                            $additional_fee = kanda_get_hotel_additional_fee( $data['hoteldetails']['hotelcode'] );
                            $earnings = $additional_fee * $nights_count;
                            $agency_fee = kanda_get_user_additional_fee() * $nights_count;
                            $agency_price = $real_price + $earnings + $agency_fee;

                            $earnings = number_format( $earnings, 2 );
                            $real_price = number_format( $real_price, 2 );
                            $agency_price = number_format( $agency_price, 2 );

                            /** Passenger details **/
                            $passenger_details = IOL_Helper::is_associative_array( $passenger_details ) ? array( $passenger_details ) : $passenger_details;
                            $passengers = array(
                                'adults'    => array(),
                                'children'  => array()
                            );

                            $adults = wp_list_filter( $passenger_details, array(
                                'passengertype' => 'ADT'
                            ) );
                            foreach( $adults as $adult ) {
                                $passengers['adults'][] = array(
                                    'title'         => $adult['title'],
                                    'first_name'    => $adult['firstname'],
                                    'last_name'     => $adult['lastname'],
                                    'gender'        => $adult['gender'],
                                );
                            }

                            $children = wp_list_filter( $passenger_details, array(
                                'passengertype' => 'CHD'
                            ) );

                            foreach( $children as $child ) {
                                $passengers['children'][] = array(
                                    'title'         => $child['title'],
                                    'first_name'    => $child['firstname'],
                                    'last_name'     => $child['lastname'],
                                    'age'           => $child['age'],
                                    'gender'        => $child['gender'],
                                );
                            }

                            update_field( 'start_date', $room['startdate'], $booking_id );
                            update_field( 'end_date', $room['enddate'], $booking_id );
                            update_field( 'meal_plan', $room['mealplan'], $booking_id );
                            update_field( 'room_type', $room['roomtype'], $booking_id );

                            update_field( 'booking_status', strtolower( $booking_details['bookingstatus'] ), $booking_id );
                            update_field( 'adults', $passengers['adults'], $booking_id );
                            update_field( 'children', $passengers['children'], $booking_id );

                            update_field( 'real_price', $real_price, $booking_id );
                            update_field( 'agency_price', $agency_price, $booking_id );
                            update_field( 'earnings', $earnings, $booking_id );

                            // set variables
                            $content = $this->render_template($template, array(
                                'booking_id' => $booking_id
                            ));

                        } else {
                            $is_valid = false;
                            $message = __( 'Internal server error', 'kanda' );
                        }

                    }

                }

            } else {
                $is_valid = false;
                $message = __( 'Invalid request', 'kanda' );
            }

            if( $is_valid ) {
                wp_send_json_success( array( 'content' => $content ) );
            } else {
                wp_send_json_error( array( 'message' => $message ) );
            }

        }
        $this->show_404();
    }

}