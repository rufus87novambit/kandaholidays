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

        $this->title = esc_html__( 'Edit profile', 'kanda' );
        $this->view = 'edit';

    }

}