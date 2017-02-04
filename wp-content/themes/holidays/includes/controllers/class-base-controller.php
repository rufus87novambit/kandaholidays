<?php

class Base_Controller {

    protected $name;
    public $default_action;
    private $views_path;
    protected $request;
    protected $view;
    private $has_content = false;

    public function __construct() {
        $this->views_path = trailingslashit( KANDA_THEME_PATH . 'views' );
        $this->has_content = true;
        add_filter( 'the_content', array( $this, 'render' ), 10, 1 );
    }

    public function render( $content ) {
        global $wp_query;

        if( $wp_query->in_the_loop ) {
            $template = trailingslashit($this->views_path . $this->name) . $this->view . '.php';
            if (file_exists($template)) {
                ob_start();
                include $template;
                $content = ob_get_clean();
            }
        }

        return $content;
    }

    public function partial( $partial ) {
        $template = trailingslashit( $this->views_path . 'partials' ) . $partial . '.php';

        ob_start();
        include( $template );
        return ob_get_clean();
    }

    public function show_404() {
        kanda_to( '404' );
    }

}