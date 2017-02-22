<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Helper' ) ) {

    class IOL_Helper {

        /**
         * Convert multidimensional array key to uppercase / lowercase
         *
         * @param array $array
         * @param int $case
         * @return array
         */
        public static function array_change_key_case_recursive( $array = array(), $case = CASE_LOWER ) {
            return array_map(
                function( $item ) use ( $case ) {
                    if( is_array( $item ) ) {
                        $item = self::array_change_key_case_recursive($item, $case);
                    }
                    return $item;
                },
                array_change_key_case( $array, $case ) );
        }

        /**
         * Change XML encoding to recognizable one
         *
         * @param SimpleXMLElement $xml
         * @return mixed
         */
        public static function set_xml_encoding( SimpleXMLElement $xml ) {
            return str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="utf-16" ?>', $xml->asXML() );
        }

        /**
         * Create correct xml key
         *
         * @param $key
         * @return mixed
         */
        public static function parse_xml_key( $key ) {
            $key = strtr( $key, array( '-' => ' ', '_' => ' ' ) );
            return str_replace( ' ', '', ucwords( $key ) );
        }

        /**
         * Get basic XML
         *
         * @param $type
         * @param $password
         * @param $code
         * @param $token
         * @return SimpleXMLElement
         */
        public static function get_basic_xml( $type, $password, $code, $token ) {
            $xml = new SimpleXMLElement( '<' . self::parse_xml_key( $type ) . ' />' );

            $xml->addAttribute( 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema' );
            $xml->addAttribute( 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance' );

            $profile = $xml->addChild( self::parse_xml_key( 'profile' ) );
            $profile->addChild( self::parse_xml_key( 'password' ), $password );
            $profile->addChild( self::parse_xml_key( 'code' ), $code );
            $profile->addChild( self::parse_xml_key( 'token-number' ), $token );

            return $xml;
        }

        /**
         * Convert date to a valid format
         *
         * @param $date
         * @param bool|false $format
         * @return bool|string
         */
        public static function convert_date( $date, $format = false ) {
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
         * Convert xml to readable format
         *
         * @param $xml
         * @return array
         */
        public static function convert_xml_to_readable( $xml ) {
            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml );
            $xml = simplexml_load_string( $xml );

            $xml = json_decode( json_encode( $xml ), true );

            return self::array_change_key_case_recursive( $xml );
        }

        /**
         *Covert boolean to recognizable string
         *
         * @param $value
         * @return bool
         */
        public static function bool_to_string( $value ) {
            return$value ? 'Y' : 'N';;
        }

    }

}