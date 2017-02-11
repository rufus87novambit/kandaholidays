<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Profiles_Controller extends Base_Controller {

    protected $name = 'profiles';
    public $default_action = 'view';

    public function __construct( $post_id = 0 ) {
        if( ! is_user_logged_in() ) {
            kanda_to( 'login' );
        }

        parent::__construct( $post_id );
    }

    /**
     * Profile view
     * @param $args
     */
    public function view( $args ) {

        $this->view = 'view';

    }

    /**
     * Profile edit
     * @param $args
     */
    public function edit( $args ) {

        if( isset( $_POST['kanda_save'] ) ) {

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';
            if( wp_verify_nonce( $security, 'kanda_save_profile' ) ) {

                $is_valid = true;
                $errors = array();

                $this->user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';
                if( ! $this->user_email ) {
                    $is_valid = false;
                    $errors[ 'user_email' ] = esc_html__( 'Required', 'kanda' );
                } elseif( ! filter_var( $this->user_email, FILTER_VALIDATE_EMAIL ) ) {
                    $errors[ 'user_email' ] = esc_html__( 'Invalid email address', 'kanda' );
                }

                $this->first_name = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
                if( ! $this->first_name ) {
                    $is_valid = false;
                    $errors[ 'first_name' ] = esc_html__( 'Required', 'kanda' );
                }

                $this->last_name = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
                if( ! $this->last_name ) {
                    $is_valid = false;
                    $errors[ 'last_name' ] = esc_html__( 'Required', 'kanda' );
                }

                $this->mobile = isset( $_POST['mobile'] ) ? $_POST['mobile'] : '';
                if( $this->mobile && !( preg_match( '/^[^:]*\d{9,}$/', $this->mobile ) ) ) {
                    $is_valid = false;
                    $errors[ 'mobile' ] = esc_html__( 'Invalid mobile number', 'kanda' );
                }

                $this->position = isset( $_POST['position'] ) ? $_POST['position'] : '';

                $this->company_address = isset( $_POST['company_address'] ) ? $_POST['company_address'] : '';

                $this->company_city = isset( $_POST['company_city'] ) ? $_POST['company_city'] : '';

                $this->company_country = isset( $_POST['company_country'] ) ? $_POST['company_country'] : '';

                $this->company_phone = isset( $_POST['company_phone'] ) ? $_POST['company_phone'] : '';
                if( $this->company_phone && !( preg_match( '/^[^:]*\d{9,}$/', $this->company_phone ) ) ) {
                    $is_valid = false;
                    $errors[ 'company_phone' ] = esc_html__( 'Invalid phone number', 'kanda' );
                }

                $this->company_website = isset( $_POST['website'] ) ? $_POST['website'] : '';
                if( $this->company_website && ( filter_var( $this->company_website, FILTER_VALIDATE_URL) === false ) ) {
                    $is_valid = false;
                    $errors['company_website'] =  esc_html__( 'Invalid URL', 'kanda' );
                }

                if( $is_valid ) {
                    $user_id = wp_update_user( array(
                        'ID' => get_current_user_id(),
                        'user_email' => $this->user_email,
                        'first_name' => $this->first_name,
                        'last_name'  => $this->last_name,
                        'user_url'   => $this->company_website
                    ) );

                    if( is_wp_error( $user_id ) ) {
                        $this->set_notification( 'danger', esc_html__( 'Error updating. Please try again.', 'kanda' ) );
                    } else {
                        update_user_meta( $user_id, 'mobile', $this->mobile );
                        update_user_meta( $user_id, 'position', $this->position );
                        update_user_meta( $user_id, 'company_address', $this->company_address );
                        update_user_meta( $user_id, 'company_city', $this->company_city );
                        update_user_meta( $user_id, 'company_country', $this->company_country );
                        update_user_meta( $user_id, 'company_phone', $this->company_phone );

                        $this->set_notification( 'success', esc_html__( 'Profile successfully updated', 'kanda' ) );
                    }
                } else {
                    $this->errors = $errors;
                    $this->set_notification( 'danger', esc_html__( 'Validation errors occurred. Please fix invalid fields.', 'kanda' ) );
                }
            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ) );
            }
        } else {
            $user = wp_get_current_user();
            $user_meta = kanda_get_user_meta( get_current_user_id() );

            $this->user_login = $user->user_login;
            $this->company_name = $user_meta['company_name'];
            $this->company_license = $user_meta['company_license'];

            $this->user_email = $user->user_email;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->mobile = $user_meta['mobile'];
            $this->position = $user_meta['position'];
            $this->company_address = $user_meta['company_address'];
            $this->company_city = $user_meta['company_city'];
            $this->company_country = $user_meta['company_country'];
            $this->company_phone = $user_meta['company_phone'];
            $this->company_website = $user->user_url;
        }

        $this->title = esc_html__( 'Edit profile', 'kanda' );
        $this->view = 'edit';

    }

}