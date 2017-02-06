<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Hotels_Controller extends Base_Controller {

    protected $name = 'hotels';
    public $default_action = 'index';

    public function __contruct() {
        if( is_user_logged_in() ) {
            kanda_to( 'login' );
        }

        parent::__construct();
    }

    public function index( $args ) {
        $this->view = 'index';

    }

}