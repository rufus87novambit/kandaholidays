<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

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
     * Check if is associative array
     *
     * @param array $array
     * @return bool
     */
    public static function is_associative_array( array $array ) {
        if ( array() === $array ) return false;
        return array_keys( $array ) !== range(0, count( $array ) - 1);
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
            $format = Kanda_Config::get( 'display_date_format' );
        }

        $d = DateTime::createFromFormat( $format, $date );
        if( $d && $d->format($format) == $date ) {
            return $d->format( IOL_Config::get( 'date_format' ) );
        }
        return false;
    }

    /**
     * convert xml string to php array - useful to get a serializable value
     *
     * @param string $xmlstr
     * @return array
     */
    private static function xml_to_array( $xmlstr ) {
        $doc = new DOMDocument();
        $doc->loadXML( $xmlstr );

        $root = $doc->documentElement;
        $output = self::dom_node_to_array( $root );
        $output['@root'] = $root->tagName;
        return $output;
    }

    /**
     * Dom node to array converter
     * @param $node
     * @return array|string
     */
    private static function dom_node_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::dom_node_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                    $output = array('@content'=>$output); //Change output into an array.
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    /**
     * Convert xml to readable format
     *
     * @param $xml
     * @return array
     */
    public static function convert_xml_to_readable( $xml ) {
        $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml );
        $xml = preg_replace('/^\s*\/\/<!\[CDATA\[([\s\S]*)\/\/\]\]>\s*\z/', '$1', $xml);
        $xml = preg_replace( '#</?(html|head|body)[^>]*>#i', '', $xml );
        $xml = preg_replace( '/style=(["\'])[^\1]*?\1/i', '', $xml, -1 );

        $xml_array = self::xml_to_array( $xml );

        return self::array_change_key_case_recursive( $xml_array );
    }

    /**
     *Covert boolean to recognizable string
     *
     * @param $value
     * @return bool
     */
    public static function bool_to_string( $value ) {
        return $value ? 'Y' : 'N';
    }

    /**
     * Convert array to savable format
     *
     * @param array $array
     * @return string
     */
    public static function array_to_savable_format( array $array ){
        return serialize( $array );
    }

    /**
     * Convert savable format to array
     *
     * @param $data
     * @return mixed
     */
    public static function savable_format_to_array( $data ) {
        return maybe_unserialize( $data );
    }

    /**
     * Get cities
     * @return null
     */
    public static function get_cities() {
        return IOL_Config::get( 'cities' );
    }

    /**
     * Get city name from city code
     *
     * @param $code
     * @return string|null
     */
    public static function get_city_name_from_code( $code ) {
        $cities = self::get_cities();

        return isset( $cities[ $code ] ) ? $cities[ $code ] : null;
    }

}