<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL' ) ) {

    final class IOL {

        public $client;
        public $hotels;
        public $helper;
        public $cache;

        static function get_instance() {
            static $instance;
            if ( $instance == null) {
                $instance = new self();
            }
            return $instance;
        }

        /**
         * Constructor
         *
         * @param string $mode
         */
        public function __construct( $mode = 'test' ) {
            $this->client = new IOL_Client( $mode );
            $this->helper = IOL_Helper::get_instance();
            $this->cache = IOL_Cache::get_instance();

            $this->hotels = IOL_Hotels::get_instance();
        }

    }
}

function IOL() {
    return IOL::get_instance();
}