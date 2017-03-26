<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Hotels_Controller extends Base_Controller {

    protected $name = 'hotels';
    public $default_action = 'index';

    public function __construct( $post_id = 0 ) {
        if( ! is_user_logged_in() ) {
            kanda_to( 'login' );
        }

        parent::__construct( $post_id );
    }

    /************************************************** Index **************************************************/
    /**
     * Specific hooks for index
     */
    private function index_add_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'index_enqueue_scripts' ), 11 );
    }

    /**
     * Add specific data for index
     */
    public function index_enqueue_scripts() {
        global $wp_scripts;

        $back_script = $wp_scripts->query( 'back', 'registered' );

        if( ! $back_script ) {
            return false;
        }
        if( !in_array( 'jquery-ui-datepicker', $back_script->deps ) ){
            $back_script->deps[] = 'jquery-ui-datepicker';
        }
        if( !in_array( 'jquery-ui-autocomplete', $back_script->deps ) ){
            $back_script->deps[] = 'jquery-ui-autocomplete';
        }

        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_localize_script( 'back', 'hotel', array(
            'validation' => Kanda_Config::get( 'validation->back->form_hotel_search' )
        ));
    }
    /**
     * Hotels main page
     * @param $args
     */
    public function index( $args ) {
        $this->index_add_hooks();

        $this->view = 'index';
    }

    /************************************************** Search **************************************************/

    /**
     * Handle search request
     */
    public function search() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $is_valid = true;

            parse_str( $_POST['criteria'], $criteria );
            $security = isset( $criteria['security'] ) ? $criteria['security'] : '';

            if( wp_verify_nonce( $security, 'kanda-hotel-search' ) ) {

                if( $criteria ) {
                    $response = provider_iol()->hotels()->search( $criteria );
                    if( $response->is_valid() ) {
                        $redirect_to = kanda_url_to( 'hotels', array( 'result', $response->request_id, 1 ) );
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

    /************************************************** Results **************************************************/
    /**
     * Hotels search request results
     * @param $args
     */
    public function results( $args ) {

        $this->index_add_hooks();

        $request_id = $args[ 'k_rid' ];

        $page = absint( $args[ 'k_page' ] );
        $this->page = $page = $page ? $page : 1;

        $limit = isset( $_GET['per_page'] ) ? $_GET['per_page'] : null;
        $order_by = $this->order_by = isset( $_GET['order_by'] ) ? $_GET['order_by'] : 'name';
        $order = $this->order = isset( $_GET['order'] ) ? $_GET['order'] : 'asc';

        $args = array(
            'page'      => $page,
            'limit'     => $limit,
            'order_by'  => $order_by,
            'order'     => $order
        );

        $response = provider_iol()->hotels()->search_by_id( $request_id, $args );
        $this->currency = isset( $_GET['currency'] ) ? $_GET['currency'] : $response->request['currency'];

        if( $response->is_valid() ) {
            $this->response = $response;
            $this->request_id = $request_id;
        } else {
            $this->show_404();
        }

        $this->title = esc_html__( 'Search results', 'kanda' );
        $this->view = 'results';
    }

    /************************************************** Hotel details **************************************************/

    /**
     * Single hotel
     *
     * @param $args
     */
    public function view_hotel( $args ) {

        $is_valid = true;
        $start_date = isset( $_GET['start_date'] ) ? $_GET['start_date'] : '';

        if( ! $start_date ) {
            $is_valid = false;
        } else {
            $d = DateTime::createFromFormat( 'Ymd', $start_date );
            if( ! $d || ( $d->format('Ymd') !== $start_date ) ) {
                $is_valid = false;
            }
        }

        $end_date = isset( $_GET['end_date'] ) ? $_GET['end_date'] : '';
        if( ! $end_date ) {
            $is_valid = false;
        } else {
            $d = DateTime::createFromFormat( 'Ymd', $end_date );
            if( ! $d || ( $d->format('Ymd') !== $end_date ) ) {
                $is_valid = false;
            }
        }

        if( $is_valid ) {
            global $wpdb;

            $query = "SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = 'hotelcode' AND `meta_value` = '{$args['hcode']}'";
            $post_id = $wpdb->get_var( $query );

            if( $post_id ) {

                $this->hotel_code = $args['hcode'];
                $this->security = wp_create_nonce( 'kanda-get-hotel-details' );
                $this->start_date = date( Kanda_Config::get( 'display_date_format' ), strtotime( $start_date ) );
                $this->end_date = date( Kanda_Config::get( 'display_date_format' ), strtotime( $end_date ) );

                $hotel_post = get_post( (int)$post_id );
                $this->title = get_the_title( $hotel_post );
            } else {
                $is_valid = false;
            }
        }

        if( ! $is_valid ) {
            $this->show_404();
        }

        $this->view = 'view';
    }

    /**
     * Get hotel details
     *
     * @return string|void
     */
    public function get_hotel_details() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $security = isset( $_REQUEST['security'] ) ? $_REQUEST['security'] : '';

            $is_valid = true;
            if( wp_verify_nonce( $security, 'kanda-get-hotel-details' ) ) {

                $code = isset( $_REQUEST['hotel'] ) ? $_REQUEST['hotel'] : false;
                if( ! $code ) {
                    $is_valid = false;
                    $message = __( 'Hotel code is required', 'kanda' );
                }

                $start_date = isset( $_REQUEST['start_date'] ) ? $_REQUEST['start_date'] : false;
                if( ! $start_date ) {
                    $is_valid = false;
                    $message = __( 'Start date is required', 'kanda' );
                }

                $end_date = isset( $_REQUEST['end_date'] ) ? $_REQUEST['end_date'] : false;
                if( ! $end_date ) {
                    $is_valid = false;
                    $message = __( 'End date is required', 'kanda' );
                }

                if( $is_valid ) {

                    $response = provider_iol()->hotels()->hotel_details( $code, $start_date, $end_date );
                    if( ! $response->is_valid() ) {
                        $is_valid = false;
                        $message = $response->message;
                    } else {

                        $template = KANDA_THEME_PATH . 'views/partials/hotel-details.php';
                        if( file_exists( $template ) ) {
                            $data = $response->data;
                            $hotel = $data['details'];

                            $cached_hotel = provider_iol()->cache()->get_data_by( 'code', $code );

                            ob_start();
                            include( $template );
                            $content = ob_get_clean();

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

    public function check_hotel_availability() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $security = isset( $_REQUEST['security'] ) ? $_REQUEST['security'] : '';

            $is_valid = true;
            if( wp_verify_nonce( $security, 'kanda-hotel-availability-request' ) ) {

                $roomtypecode = isset( $_REQUEST['roomtypecode'] ) ? $_REQUEST['roomtypecode'] : '';
                if( ! $roomtypecode ) {
                    $is_valid = false;
                    $message = __( 'Room Type is required', 'kanda' );
                }

                $contracttokenid = isset( $_REQUEST['contracttokenid'] ) ? $_REQUEST['contracttokenid'] : '';
                if( ! $contracttokenid ) {
                    $is_valid = false;
                    $message = __( 'Contract token ID is required', 'kanda' );
                }

                $roomconfigurationid = isset( $_REQUEST['roomconfigurationid'] ) ? $_REQUEST['roomconfigurationid'] : '';
                if( ! $roomconfigurationid ) {
                    $is_valid = false;
                    $message = __( 'Room Configuration ID is required', 'kanda' );
                }

                $mealplancode = isset( $_REQUEST['mealplancode'] ) ? $_REQUEST['mealplancode'] : '';
                if( ! $mealplancode ) {
                    $is_valid = false;
                    $message = __( 'Meal Plan Code is required', 'kanda' );
                }

                $start_date = isset( $_REQUEST['start_date'] ) ? $_REQUEST['start_date'] : '';
                if( ! $start_date ) {
                    $is_valid = false;
                    $message = __( 'Start date is required', 'kanda' );
                }

                $end_date = isset( $_REQUEST['end_date'] ) ? $_REQUEST['end_date'] : '';
                if( ! $end_date ) {
                    $is_valid = false;
                    $message = __( 'End date is required', 'kanda' );
                }

                $city = isset( $_REQUEST['city'] ) ? $_REQUEST['city'] : '';
                if( ! $city ) {
                    $is_valid = false;
                    $message = __( 'City is required', 'kanda' );
                }

                $hotelcode = isset( $_REQUEST['hotelcode'] ) ? $_REQUEST['hotelcode'] : '';
                if( ! $hotelcode ) {
                    $message = __( 'Hotel Code is required', 'kanda' );
                }

                $adults = isset( $_REQUEST['adults'] ) ? $_REQUEST['adults'] : '';
                if( ! $adults ) {
                    $is_valid = false;
                    $message = __( 'Adults count is required', 'kanda' );
                }

                $children_count = isset( $_REQUEST['child'] ) ? $_REQUEST['child'] : 0;
                $children = array();
                for( $i = 0; $i < $children_count; $i++ ) {
                    if( isset( $_REQUEST['age' . ($i + 1)] ) ) {
                        $children['age'][] = $_REQUEST['age' . ($i + 1)];
                    } else {
                        $message = __( 'One of children ages is missed', 'kanda' );
                        $is_valid = false;
                        break;
                    }
                }

                if( $is_valid ) {

                    $response = provider_iol()->hotels()->hotel_availability_request( array(
                        'roomtypecode' => $roomtypecode,
                        'contracttokenid' => $contracttokenid,
                        'roomconfigurationid' => 1,
                        'mealplancode' => $mealplancode,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'city' => $city,
                        'hotelcode' => $hotelcode,
                        'adults' => $adults,
                        'children' => $children
                    ) );

                    if( ! $response->is_valid() ) {
                        $is_valid = false;
                        $message = $response->message;
                    } else {

                        $template = KANDA_THEME_PATH . 'views/partials/hotel-availability.php';
                        if( file_exists( $template ) ) {

                            if( isset( $response->data['hotels']['hotel']['roomtypedetails']['rooms']['room']['roomstatusdetails']['status'] ) ) {
                                $availability = (array) $response->data['hotels']['hotel']['roomtypedetails']['rooms']['room']['roomstatusdetails']['status'];
                            } else {
                                $availability = false;
                            }
                            $content = $this->render_template( $template, array(
                                'availability' => $availability,
                                'period' => array(
                                    'start_date' => $response->request['start_date'],
                                    'end_date'   => $response->request['end_date']
                                )
                            ) );

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
                wp_send_json_success( $content );
            } else {
                wp_send_json_error( $message );
            }

        }
        $this->show_404();
    }

    /************************************************** Helper methods **************************************************/

    public function get_cancellation_policy() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $is_valid = true;
            $security = isset( $_REQUEST['security'] ) ? $_REQUEST['security'] : '';
            if( wp_verify_nonce( $security, 'kanda-get-hotel-cancellation-policy' ) ) {

                $hotel_code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : false;
                if( ! $hotel_code ) {
                    $is_valid = false;
                    $message = __( 'Hotel code is required', 'kanda' );
                }

                $room_type_code = isset( $_REQUEST['roomtype'] ) ? $_REQUEST['roomtype'] : false;
                if( ! $room_type_code ) {
                    $is_valid = false;
                    $message = __( 'Room type is required', 'kanda' );
                }

                $contract_token_id = isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : false;
                if( ! $contract_token_id ) {
                    $is_valid = false;
                    $message = __( 'Contract token is required', 'kanda' );
                }

                $start_date = isset( $_REQUEST['start_date'] ) ? $_REQUEST['start_date'] : false;
                if( ! $start_date ) {
                    $is_valid = false;
                    $message = __( 'Start date is required', 'kanda' );
                }

                $end_date = isset( $_REQUEST['end_date'] ) ? $_REQUEST['end_date'] : false;
                if( ! $end_date ) {
                    $is_valid = false;
                    $message = __( 'End date is required', 'kanda' );
                }

                if( $is_valid ) {

                    $response = provider_iol()->hotels()->hotel_cancellation_policy( $hotel_code, $room_type_code, $contract_token_id, $start_date, $end_date );
                    if( ! $response->is_valid() ) {
                        $is_valid = false;
                        $message = $response->message;
                    } else {

                        $template = KANDA_THEME_PATH . 'views/partials/hotel-cancellation-policy.php';
                        if( file_exists( $template ) ) {
                            $data = $response->data;
                            $cancellation_policies = ( array_key_exists( 'cancellationdetails', $data ) && isset( $data['cancellationdetails']['cancellation'] ) ) ? $data['cancellationdetails']['cancellation'] : array();
                            $request = $response->request;

                            ob_start();
                            include( $template );
                            $content = ob_get_clean();

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
                wp_send_json_success( $content );
            } else {
                wp_send_json_error( $message );
            }


        } else {
            $this->show_404();
        }
    }

    /**
     * Get hotel Google Map url
     *
     * @param $geolocation
     * @return bool|string
     */
    public function get_hotel_google_map_url( $geolocation ) {
        if( isset( $geolocation['latitude'] ) && (bool)$geolocation['latitude'] && isset( $geolocation['longitude'] ) && (bool)$geolocation['longitude'] ) {
            return sprintf( 'https://maps.google.com/?q=%1$s+%2$s', $geolocation['latitude'], $geolocation['longitude'] );
        }
        return false;
    }

    /**
     * Get hotel details request URL
     *
     * @param $args
     * @return string
     */
    public function get_single_hotel_url( $args ) {
        return add_query_arg(
            array(
                'start_date'    => $args['start_date'],
                'end_date'      => $args['end_date']
            ),
            kanda_url_to( 'hotels', array( 'view', $args['hotelcode'] ) )
        );
    }

}