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

    /**
     * Hotels main page
     * @param $args
     */
    public function index( $args ) {
        $this->view = 'index';

    }

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
                        $redirect_to = kanda_url_to( 'hotels', array( 'result', $response->get_request_id(), 1 ) );
                    } else {
                        $is_valid = false;
                        $message = $response->get_message();
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
     * Hotels search request results
     * @param $args
     */
    public function results( $args ) {
        $request_id = $args[ 'k_rid' ];
        $page = absint( $args[ 'k_page' ] );
        $page = $page ? $page : 1;

        $response = provider_iol()->hotels()->search_by_id( $request_id, $page );

        if( $response->is_valid() ) {
            $this->request = $response->get_request();
            $this->hotels = $response->get_data();

            $hotel_codes = wp_list_pluck( $this->hotels, 'hotelcode' );
            $hotel_codes_sql = array();
            foreach( $hotel_codes as $hotel_code ) {
                $hotel_codes_sql[] = sprintf( '\'%s\'', $hotel_code );
            }

            global $wpdb;
            $query = "SELECT `pm`.`meta_value` AS `code`, `p`.*
                        FROM `{$wpdb->postmeta}` AS `pm`
                        LEFT JOIN `{$wpdb->posts}` AS `p` ON `p`.`ID` = `pm`.`post_id`
                        WHERE `pm`.`meta_key` = 'hotelcode' AND `pm`.`meta_value` IN ( " . implode( ',', $hotel_codes_sql ) . " )";

            $this->hotel_posts = $wpdb->get_results( $query, OBJECT_K );
        } else {
            $this->show_404();
        }

        $this->title = esc_html__( 'Search results', 'kanda' );
        $this->view = 'results';
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

                $code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : false;
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
                        $message = $response->get_message();
                    }

                }

            } else {
                $is_valid = false;
                $message = __( 'Invalid request', 'kanda' );
            }

            if( $is_valid ) {

                $template = KANDA_THEME_PATH . 'views/partials/popup-hotel-details.php';
                if( file_exists( $template ) ) {
                    $data = $response->get_data();
                    $hotel = $data['details'];

                    ob_start();
                    include( $template );
                    return ob_get_clean();
                } else {
                    return __( 'Internal server error', 'kanda' );
                }
            } else {
                return $message;
            }
        }
        $this->show_404();
    }

    /************************ Helper methods ************************/

    /**
     * Get hotel Google Map url
     *
     * @param $geolocation
     * @return bool|string
     */
    public function get_hotel_google_map_url( $geolocation ) {
        if( isset( $geolocation['latitude'] ) && isset( $geolocation['longitude'] ) ) {
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
    public function get_hotel_details_request_url( $args ) {
        return add_query_arg(
            array(
                'action'        => 'hotel_details',
                'security'      => wp_create_nonce( 'kanda-get-hotel-details' ),
                'code'          => $args['hotelcode'],
                'start_date'    => $args['start_date'],
                'end_date'      => $args['end_date']
            ),
            admin_url( 'admin-ajax.php' )
        );
    }


}