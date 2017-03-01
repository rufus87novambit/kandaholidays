<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Provider' ) ) {

    final class IOL_Provider extends Kanda_Service_Provider {

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
            parent::__construct();

            $this->path = trailingslashit(__DIR__);
            $this->core = trailingslashit($this->path . 'core');

            $this->load_dependant( $this->path, 'config' );

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
            $this->id = IOL_Config::get( 'id' );
            $this->name = IOL_Config::get( 'name' );
            $this->public_name = esc_html__( 'UAE', 'kanda' );

            return parent::register( $providers );
        }

        /**
         * Wake up
         */
        public function init() {
            $this->load_dependant( $this->core, 'class-helper', 'IOL_Helper' );
            $this->load_dependant( $this->core, 'class-cache', 'IOL_Search_Cache' );
            $this->load_dependant( $this->core, 'class-request', 'IOL_Request' );
            $this->load_dependant( $this->core, 'class-master-data', 'IOL_Master_Data' );
        }

        /**
         * Get hotels instance
         * @return IOL_Hotels
         */
        public function hotels() {
            $this->load_dependant( $this->core, 'class-hotels', 'IOL_Hotels' );

            return new IOL_Hotels();
        }

    }

    /**
     * Get class instance
     */
    function provider_iol() {
        return IOL_Provider::get_instance();
    }

    provider_iol();
}