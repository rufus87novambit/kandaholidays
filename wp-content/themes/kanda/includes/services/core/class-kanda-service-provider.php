<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class Kanda_Service_Provider {

    /**
     * Provider location
     *
     * @var
     */
    public $path;

    /**
     * Provider core files location
     *
     * @var
     */
    public $core;

    /**
     * Provider unique id
     * @var
     */
    public $id;

    /**
     * Provider name
     * @var
     */
    public $name;

    /**
     * Provider public name
     *
     * @var
     */
    public $public_name;

    /**
     * Constructor
     */
    public function __construct(){}

    /**
     * Load dependant file
     *
     * @param $file_path
     * @param $file_name
     * @param bool|false $class
     */
    protected function load_dependant( $file_path, $file_name, $class = false ) {
        $load = false;
        if( $class ) {
            if( ! class_exists( $class ) ) {
                $load = true;
            }
        } else {
            $load = true;
        }

        if( $load ) {
            require_once( $file_path . $file_name . '.php' );
        }
    }

    /**
     * @param $providers
     * @return array
     */
    public function register( $providers ) {
        $providers[] = array(
            'id'            => $this->id,
            'name'          => $this->name,
            'public_name'   => $this->public_name
        );

        return $providers;
    }

}