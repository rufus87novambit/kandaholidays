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

        if( isset( $_POST['kanda_search'] ) ) {

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';

            if( wp_verify_nonce( $security, 'kanda-hotel-search' ) ) {

                $is_valid = true;
                $errors = array();

                $this->city = isset( $_POST['city'] ) ? $_POST['city'] : '';
                if( ! $this->city ) {
                    $is_valid = false;
                    $errors['city'] = esc_html__( 'Required', 'kanda' );
                }

                $this->hotel_name = isset( $_POST['hotel_name'] ) ? $_POST['hotel_name'] : '';
                $this->rating = isset( $_POST['hotel_rating'] ) ? $_POST['hotel_rating'] : 2;
                $this->include_on_request = isset( $_POST['include_on_request'] ) ? $_POST['include_on_request'] : 'Y';
                $this->nationality = isset( $_POST['nationality'] ) ? $_POST['nationality'] : '';
                $this->currency = isset( $_POST['currency'] ) ? $_POST['currency'] : 'USD';

                $this->checkin_date = isset( $_POST['check_in'] ) ? $_POST['check_in'] : '';
                if( ! $this->checkin_date ) {
                    $is_valid = false;
                    $errors['checkin_date'] = esc_html__( 'Required', 'kanda' );
                }

                $this->checkout_date = isset( $_POST['check_out'] ) ? $_POST['check_out'] : '';
                if( ! $this->checkout_date ) {
                    $is_valid = false;
                    $errors['checkout_date'] = esc_html__( 'Required', 'kanda' );
                }

                $this->nights_count = isset( $_POST['nights_count'] ) ? $_POST['nights_count'] : 1;
                $this->rooms_count = isset( $_POST['rooms_count'] ) ? $_POST['rooms_count'] : 1;
                $this->room_occupants = isset( $_POST['room_occupants'] ) ? $_POST['room_occupants'] : 1;

                if( true ) {

                    $args = array_diff_key( $_POST, array_flip( array( 'security', 'kanda_search' ) ) );

                    $response = provider_iol()->hotels()->search( $args );

                    echo '<pre>'; var_dump(
                        $response->is_valid(),
                        $response->get_code(),
                        $response->get_message(),
                        $response->get_data()
                    ); die;

                }

            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ) );
            }

        }

        $this->view = 'index';

    }

    public function results( $args ) {
        $this->view = 'search-results';
    }

}