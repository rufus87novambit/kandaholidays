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
            echo '<pre>'; var_dump($_POST); die;
        }

        $this->view = 'index';

    }

}