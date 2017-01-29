<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Hotels' ) ) {


    class IOL_Hotels {

        static function get_instance() {
            static $instance;
            if ( $instance == null) {
                $instance = new self();
            }
            return $instance;
        }

        /**
         * Search hotels by criteria
         *
         * @param array $criteria
         * @param array $args
         * @return array|mixed|null|object
         */
        public function search( $criteria = array(), $args = array() ) {
            return IOL()->client->get( 'hotel-search-request', $criteria, $args );
        }

    }

}