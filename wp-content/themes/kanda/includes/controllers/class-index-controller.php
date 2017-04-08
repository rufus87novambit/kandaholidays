<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Index_Controller extends Base_Controller {

    protected $name = 'index';
    public $default_action = 'dashboard';

    public function __construct( $post_id = 0 ) {
        if( ! is_user_logged_in() ) {
            kanda_to( 'login' );
        }

        parent::__construct( $post_id );
    }

    /**
     * Render content
     * @param $content
     * @return string
     */
    public function render( $content ) {
        $content .= parent::render( $content );

        return $content;
    }

    /**
     * Add specific hooks for dashboard action
     */
    private function dashboard_add_hooks() {
        add_filter( 'kanda/controller_content', array( $this, 'dashboard_change_content' ), 10, 1 );
    }

    /**
     * Change HTML for dashboard action
     * @param $content
     * @return string
     */
    public function dashboard_change_content( $content ) {
        return '</div><div>' . $content;
    }


    public function dashboard() {

        $this->dashboard_add_hooks();

        $user_id = get_current_user_id();
        $limit = 5;
        $this->bookings = new WP_Query( array(
            'post_type'         => 'booking',
            'post_status'       => 'publish',
            'order'             => 'DESC',
            'order_by'          => 'date',
            'author'            => $user_id,
            'posts_per_page'    => $limit,
            'meta_query' => array(
                array(
                    'key' => 'end_date',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            ),
        ) );

        $this->view = 'dashboard';
    }

}