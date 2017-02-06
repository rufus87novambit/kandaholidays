<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Helper' ) ) {

    class IOL_Helper {

        static function get_instance() {
            static $instance;
            if ( $instance == null) {
                $instance = new self();
            }
            return $instance;
        }

        /**
         * Convert multidimensional array to xml
         *
         * @param $array
         * @return string
         */
        public function array_to_xml( $array ) {
            $xml = '';
            foreach( $array as $key => $value ) {

                $close_prefix = '';
                $key = $this->convert_xml_key( $key );

                $xml .= IOL_EOL . sprintf( '<%s>', $key );
                if( is_array( $value ) ) {
                    $xml .= $this->array_to_xml( $value );
                    $close_prefix = IOL_EOL;
                } else {
                    $xml .= is_bool( $value ) ? ( $value ? 'Y' : 'N' ) : $value;
                }
                $xml .= sprintf( '%1$s</%2$s>', $close_prefix, $key );
            }
            return $xml;
        }

        /**
         * Create correct xml key
         *
         * @param $key
         * @return mixed
         */
        public function convert_xml_key( $key ) {
            $key = strtr( $key, array( '-' => ' ', '_' => ' ' ) );
            return str_replace( ' ', '', ucwords( $key ) );
        }

        /**
         * Prepend XML header
         *
         * @param $type
         * @param $xml
         * @return mixed
         */
        public function prepend_xml_header( $type, $xml ) {
            return str_replace(
                sprintf( '<%s>', $type ),
                sprintf( '
                    <?xml version="1.0" encoding="utf-16"?>
                    <%s xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    ',
                    $type
                ),
                $xml
            );
        }

        /**
         * Convert date to a valid format
         *
         * @param $date
         * @param bool|false $format
         * @return bool|string
         */
        public function convert_date( $date, $format = false ) {
            if( ! $format ) {
                $format = get_option( 'date_format' );
            }
            $d = DateTime::createFromFormat( $format, $date );
            if( $d && $d->format($format) == $date ) {
                return $d->format( IOL_Config::get( 'date_format' ) );
            }
            return false;
        }

        /**
         * Convert multidimensional array key to uppercase / lowercase
         *
         * @param array $array
         * @param int $case
         * @return array
         */
        public function array_change_key_case_recursive( $array = array(), $case = CASE_LOWER ) {
            return array_map(
                function( $item ) use ( $case ) {
                    if( is_array( $item ) ) {
                        $item = $this->array_change_key_case_recursive($item, $case);
                    }
                    return $item;
                },
                array_change_key_case( $array, $case ) );
        }

    }

}