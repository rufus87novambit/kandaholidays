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

        $action = (isset($args['sub-action']) && $args['sub-action']) ? sprintf('edit_%s', $args['sub-action']) : 'edit_profile';
        if( method_exists( $this, $action ) ) {
            $this->$action( $args );
        } else {
            $this->show_404();
        }

    }

    /**
     * Edit general options
     *
     * @param $args
     */
    public function edit_profile( $args ) {

        if( isset( $_POST['kanda_save'] ) ) {

            $user = wp_get_current_user();
            $user_meta = kanda_get_user_meta( get_current_user_id() );

            $this->user_login = $user->user_login;
            $this->company_name = $user_meta['company_name'];
            $this->company_license = $user_meta['company_license'];

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
                    $errors['company_website'] = esc_html__( 'Invalid URL', 'kanda' );
                }

                $this->company_website = isset( $_POST['company_website'] ) ? $_POST['company_website'] : '';
                if( $this->company_website && ! preg_match( '/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/', $this->company_website ) ) {
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
                        kanda_to( 'profile', array( 'edit' ) );
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
        $this->view = 'edit-general';

    }

    /**
     * Edit password
     *
     * @param $args
     */
    public function edit_password( $args ) {

        if( isset( $_POST['kanda_save'] ) ) {

            $security = isset($_POST['security']) ? $_POST['security'] : '';
            if (wp_verify_nonce($security, 'kanda_save_password')) {

                $is_valid = true;
                $is_current_password_valid = true;
                $user = wp_get_current_user();
                $errors = array();

                $this->old_password = isset( $_POST['old_password'] ) ? $_POST['old_password'] : '';
                if( ! $this->old_password ) {
                    $is_current_password_valid = false;
                    $is_valid = false;
                    $errors['old_password'] = esc_html__( 'Required', 'kanda' );
                } else {
                    if( ! wp_check_password( $this->old_password, $user->user_pass, $user->ID ) ) {
                        $is_current_password_valid = false;
                        $is_valid = false;
                        $errors['old_password'] = esc_html__( 'Invalid password', 'kanda' );
                    }
                }

                $this->new_password = isset( $_POST['new_password'] ) ? $_POST['new_password'] : '';
                $this->confirm_password = isset( $_POST['confirm_password'] ) ? $_POST['confirm_password'] : '';

                if( $is_current_password_valid ) {
                    $validation_data = Kanda_Config::get( 'validation->front->data' );
                    $validation_rules = Kanda_Config::get( 'validation->front->form_register' );

                    if( ! $this->new_password ) {
                        $is_valid = false;
                        $errors['new_password'] =  esc_html__( 'Required', 'kanda' );
                    } elseif( $this->new_password == $this->old_password ) {
                        $is_valid = false;
                        $errors['new_password'] =  esc_html__( 'Current and new passwords are the same', 'kanda' );
                    } elseif( ! filter_var( strlen( $this->new_password ), FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => $validation_data['password_min_length'], 'max_range' => $validation_data['password_max_length'] ) ) ) ) {
                        $is_valid = false;
                        $errors['new_password'] = strtr( $validation_rules['password']['rangelength'], array( '{0}' => $validation_data['password_min_length'], '{1}' => $validation_data['password_max_length'] ) );
                    }

                    if( ! $this->confirm_password ) {
                        $is_valid = false;
                        $errors['confirm_password'] =  esc_html__( 'Required', 'kanda' );
                    } elseif( $this->new_password && ( $this->new_password != $this->confirm_password ) ) {
                        $is_valid = false;
                        $match_message = esc_html__( 'Passwords does not match', 'kanda' );
                        $errors['confirm_password'] = $match_message;
                        if( ! isset( $errors['new_password'] ) ) {
                            $errors['new_password'] = $match_message;
                        }

                    }
                }

                if( $is_valid ) {
                    $userdata = array(
                        'ID'            => $user->ID,
                        'user_pass'     => $this->new_password
                    );
                    $user_id = wp_update_user( $userdata );

                    if( is_wp_error( $user_id ) ) {
                        $this->set_notification( 'danger', esc_html__( 'Error updating. Please try again.', 'kanda' ) );
                    } else {
                        $this->set_notification( 'success', esc_html__( 'Password successfully updated.', 'kanda' ) );
                        kanda_to( 'profile', array( 'edit', 'password' ) );
                    }
                } else {
                    $this->errors = $errors;
                    $this->set_notification( 'danger', esc_html__( 'Validation errors occurred. Please fix invalid fields.', 'kanda' ) );
                }

            }
        } else {
            $this->old_password = '';
            $this->new_password = '';
            $this->confirm_password = '';
        }

        $this->title = esc_html__( 'Change password', 'kanda' );
        $this->view = 'edit-password';

    }


    /**
     * Enqueue scripts for edit photo action
     */
    public function edit_photo_enqueue_scripts() {

        wp_enqueue_script( 'plupload-all' );
        wp_localize_script( 'back', 'avatar_uploader_config', array(
            'runtimes'            => 'html5,silverlight,flash,html4',
            'browse_button'       => 'avatar-upload-browse',
            'container'           => 'avatar-upload-ui',
            'drop_element'        => 'avatar-upload-ui',
            'ajaxurl' 			  => admin_url('admin-ajax.php'),
            'file_data_name'      => 'avatar',
            'multiple_queues'     => true,
            'max_file_size'       => '5mb',
            'url'                 => admin_url( 'admin-ajax.php' ),
            'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
            'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
            'filters'             => array(
                array(
                    'title'      => esc_html__( 'Allowed Files', 'kanda' ),
                    'extensions' => 'jpg,jpeg,jpe,gif,png,bmp'
                )
            ),
            'multipart'           => true,
            'urlstream_upload'    => true,
            'multi_selection'     => false,
            'multipart_params'    => array(
                'security'     => wp_create_nonce( 'kanda-upload-avatar' ),
                'action'       => 'kanda_upload_avatar',
            ),
        ) );
    }

    /**
     * Add hooks to edit photo action
     */
    private function edit_photo_add_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'edit_photo_enqueue_scripts' ), 11 );
    }

    /**
     * Delete user avatar
     * @return bool
     */
    private function delete_avatar( $user_id = false ) {
        if( ! $user_id ) {
            $user_id = get_current_user_id();
        }
        $avatar_id = kanda_get_user_avatar_id( $user_id );

        $status = false;
        if( $avatar_id ) {
            $status = (bool)wp_delete_attachment( $avatar_id, true );
            if($status) {
                delete_user_meta( $user_id, 'avatar' );
                delete_user_meta( $user_id, 'avatar_coordinates' );
            }
        }
        return $status;
    }

    /**
     * Edit profile photo
     *
     * @param $args
     */
    public function edit_photo( $args ) {

        $this->edit_photo_add_hooks();

        if( isset( $_POST[ 'kanda-delete-avatar' ] ) ) {
            $security = isset( $_POST['avatar-delete-security'] ) ? $_POST['avatar-delete-security'] : '';
            if( wp_verify_nonce( $security, 'kanda-delete-avatar' ) ) {
                $status = $this->delete_avatar();
                if( $status ) {
                    $this->set_notification( 'success', esc_html__( 'Image successfully deleted', 'kanda' ) );
                    kanda_to( 'profile', array( 'edit', 'photo' ) );
                } else {
                    $this->set_notification( 'danger', esc_html__( 'Error deleting image', 'kanda' ) );
                }
            } else {
                $this->set_notification( 'danger', esc_html__( 'Invalid request', 'kanda' ) );
            }
        } elseif( isset( $_POST['kanda-save-avatar'] ) ) {
            $security = isset( $_POST['avatar-save-security'] ) ? $_POST['avatar-save-security'] : '';
            if( wp_verify_nonce( $security, 'kanda-save-avatar' ) ) {
                $coordinates = isset( $_POST['coordinates'] ) ? $_POST['coordinates'] : json_encode( array() );
                $coordinates = json_decode( stripslashes( $coordinates ), true );

                $avatar_id = kanda_get_user_avatar_id();
                $attachment = get_attached_file( kanda_get_user_avatar_id() );
                $attachment_data = wp_get_attachment_metadata( $avatar_id );

                $status = false;
                if ( !empty($attachment_data['sizes']['user-avatar']) && ( $avatar_file = str_replace( basename( $attachment ), $attachment_data['sizes']['user-avatar']['file'], $attachment ) ) && file_exists( $avatar_file ) ) {
                    $image_editor = wp_get_image_editor( $attachment );

                    $crop = $image_editor->crop( $coordinates['x'], $coordinates['y'], $coordinates['width'], $coordinates['height'], 150, 150 );
                    if( $crop ) {
                        $avatar = $image_editor->save( $avatar_file );
                        if( ! is_wp_error( $avatar ) ) {
                            $status = true;
                        } else {
                            kanda_logger()->log( $avatar->get_error_message() );
                        }
                    } else {
                        kanda_logger()->log( $crop->get_error_message() );
                    }
                }

                if( $status ) {
                    $this->set_notification( 'success', esc_html__( 'Image successfully saved', 'kanda' ) );
                    update_user_meta( get_current_user_id(), 'avatar_coordinates', json_encode( $coordinates ) );
                    kanda_to( 'profile', array( 'edit', 'photo' ) );
                } else {
                    $this->set_notification( 'danger', esc_html__( 'Error saving data. Please try again' ) );
                    $this->coordinates = json_encode( $coordinates );
                }

            } else {
                $this->set_notification( 'danger', esc_html__( 'Error saving. Please try again', 'kanda' ) );
            }
        } else {
            $coordinates = kanda_get_user_meta( get_current_user_id(), 'avatar_coordinates' );
            $coordinates = $coordinates ? $coordinates : json_encode( array() );
        }

        $this->coordinates = str_replace( '"', '\'', $coordinates );
        $this->title = esc_html__( 'Edit photo', 'kanda' );
        $this->view = 'edit-photo';

    }



}