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
     * Holds instance data
     * @var array
     */
    private $data = array();

    /**
     * Current title
     * @var
     */
    protected $title;

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
        add_filter( 'the_title', array( $this, 'change_title' ), 10, 2 );
        add_filter( 'the_content', array( $this, 'render' ), 10, 1 );
        kanda_start_session();
    }

    /**
     * Setter
     *
     * @param string $name Variable key
     * @param midex $value Variable value
     */
    public function __set( $name, $value ) {
        if( property_exists( $this, $name ) ) {
            $this->{$name} = $value;
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * Getter
     *
     * @param $name Variable key
     * @return mixed Variable value if it exists or null otherwise
     */
    public function __get( $name ) {
        if( property_exists( $this, $name ) ) {
            return $this->{$name};
        } else if ( array_key_exists( $name, $this->data ) ) {
            return $this->data[ $name ];
        }
        return null;
    }

    /**
     * Set notification
     *
     * @param $type
     * @param string $message
     */
    protected function set_notification( $type, $message = '' ) {
        $_SESSION[ 'kanda_notification' ] = array(
            'type' => $type,
            'message' => $message
        );
    }

    /**
     * Change page title
     *
     * @param $title
     * @param null $id
     * @return mixed
     */
    public function change_title( $title, $id = null ) {
        global $wp_query;
        if( $wp_query->in_the_loop && $this->post_id == $id && $this->title ) {
            $title = $this->title;

            remove_filter( 'the_title', array( $this, 'render' ) );
        }

        return $title;
    }

    /**
     * Render available content
     *
     * @param $content
     * @return string
     */
    public function render( $content ) {
        global $wp_query;
        if( $wp_query->in_the_loop && $this->post_id == get_the_ID() ) {

            $template = trailingslashit($this->views_path . $this->name) . $this->view . '.php';
            if ( file_exists( $template ) ) {
                ob_start();
                include $template;
                $content = ob_get_clean();
            } else {
                $content = kanda_default_page_content( $content );
            }

            remove_filter( 'the_content', array( $this, 'render' ) );
        }

        return $content;
    }

    /**
     * Partial rendering
     *
     * @param $partial
     * @return string
     */
    public function partial( $partial, $args ) {
        $template = trailingslashit( $this->views_path . 'partials' ) . $partial . '.php';

        foreach( $args as $name => $value ) {
            ${$name} = $value;
        }
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