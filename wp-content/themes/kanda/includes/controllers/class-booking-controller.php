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

    public function create( $args ) {

        $hotel_code = isset( $_GET['hotel_code'] ) ? $_GET['hotel_code'] : '';
        $city_code = isset( $_GET['city_code'] ) ? $_GET['city_code'] : '';
        $room_number = isset( $_GET['room_number'] ) ? $_GET['room_number'] : '';
        $request_id = isset( $_GET['request_id'] ) ? $_GET['request_id'] : '';
        $room_type_code = isset( $_GET['room_type_code'] ) ? $_GET['room_type_code'] : '';
        $contract_token_id = isset( $_GET['contract_token_id'] ) ? $_GET['contract_token_id'] : '';
        $room_configuration_id = isset( $_GET['room_configuration_id'] ) ? $_GET['room_configuration_id'] : '';
        $meal_plan_code = isset( $_GET['meal_plan_code'] ) ? $_GET['meal_plan_code'] : '';
        $security = isset( $_GET['security'] ) ? $_GET['security'] : '';

        $is_valid = true;
        if( wp_verify_nonce( $security, 'kanda-create-booking' ) ) {

            if( ! $hotel_code ) {
                $is_valid = false;
            }

            if( ! $city_code ) {
                $is_valid = false;
            }

            if( ! $room_number ) {
                $is_valid = false;
            }

            if( ! $room_type_code ) {
                $is_valid = false;
            }

            if( ! $contract_token_id ) {
                $is_valid = false;
            }

            if( ! $room_configuration_id ) {
                $is_valid = false;
            }

            if( ! $meal_plan_code ) {
                $is_valid = false;
            }

            if( ! $request_id ) {
                $is_valid = false;
            }

            if( $is_valid ) {
                $request = provider_iol()->hotels()->get_request_data( $request_id );

                if( ! $request ) {
                    $is_valid = false;
                } else {
                    $request_args = IOL_Helper::savable_format_to_array( $request->request );
                    $this->adults = $request_args['room_occupants'][ $room_number ][ 'adults' ];
                    $this->children = (bool)$request_args['room_occupants'][ $room_number ][ 'child' ] ? count( $request_args['room_occupants'][ $room_number ][ 'child' ][ 'age' ] ) : 0;
                }
            }

        } else {
            $is_valid = false;
        }

        if( ! $is_valid ) {
            $this->show_404();
        }

        $this->title = __( 'Create Booking', 'kanda' );
        $this->view = 'create';
    }

}