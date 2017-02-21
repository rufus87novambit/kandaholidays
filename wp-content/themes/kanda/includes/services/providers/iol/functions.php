<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL' ) ) {

    final class IOL {

        public $path;

        public $core;

        /**
         * Singleton.
         */
        static function get_instance() {
            static $instance = null;
            if ($instance == null) {
                $instance = new self();
            }
            return $instance;
        }

        /**
         * Constructor
         */
        public function __construct() {
            $this->path = trailingslashit(__DIR__);
            $this->core = trailingslashit($this->path . 'core');

            require_once($this->path . 'config.php');

            $this->hooks();
        }

        /**
         * Attach required hooks
         */
        private function hooks(){
            add_filter('kanda/providers', array($this, 'register'), 10);
            add_action('kanda/providers/init', array($this, 'init'), 10);
        }

        /**
         * Register provider
         *
         * @param $providers
         * @return array
         */
        public function register( $providers ) {
            return array_merge($providers, array(
                IOL_Config::get( 'id' ) => IOL_Config::get( 'name' )
            ));
        }

        /**
         * Wake up
         */
        public function init() {
            require_once($this->core . 'class-helper.php');
            require_once($this->core . 'class-request.php');
        }

        /**
         * Get request instance
         *
         * @return IOL_Request
         */
        public function request() {
            return new IOL_Request();
        }

    }

    /**
     * Get class instance
     */
    function provider_iol() {
        return IOL::get_instance();
    }

    provider_iol();
}