<?php

class Base_Controller {

    /**
     * Controller name
     * @var
     */
    protected $name;

    /**
     * Default action
     * @var
     */
    public $default_action;

    /**
     * Views path
     * @var string
     */
    private $views_path;

    /**
     * Received request
     * @var
     */
    protected $request;

    /**
     * Current view to render
     * @var
     */
    protected $view;

    /**
     * Post id that need to render available content
     * @var int
     */
    protected $post_id = 0;

    /**
     * Constructor
     *
     * @param int $post_id
     */
    public function __construct( $post_id = 0 ) {
        if( $post_id ) {
            $this->post_id = $post_id;
        }

        $this->views_path = trailingslashit( KANDA_THEME_PATH . 'views' );
        $this->has_content = true;
        add_filter( 'the_content', array( $this, 'render' ), 10, 1 );
    }

    /**
     * Render available content
     *
     * @param $content
     * @return string
     */
    public function render( $content ) {
        if( $this->post_id == get_the_ID() ) {
            $template = trailingslashit($this->views_path . $this->name) . $this->view . '.php';
            if (file_exists($template)) {
                ob_start();
                include $template;
                $content = ob_get_clean();
            }
        }

        return $content;
    }

    /**
     * Partial rendering
     *
     * @param $partial
     * @return string
     */
    public function partial( $partial ) {
        $template = trailingslashit( $this->views_path . 'partials' ) . $partial . '.php';

        ob_start();
        include( $template );
        return ob_get_clean();
    }

    /**
     * Show not found page
     */
    public function show_404() {
        kanda_to( '404' );
    }

}