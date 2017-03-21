<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Helper {

    private static $black_list = array();

    public static function init_blacklist( array $tags ) {
        static::$black_list = $tags;
    }
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
     * Remove blacklisted tags
     *
     * @param $xmlstr
     * @return string
     */
    private static function blacklist_watchdog( $xmlstr ) {
        $document = new DOMDocument();
        $document->loadXML( $xmlstr );

        $dom_elements_to_remove = array();
        foreach (static::$black_list as $tag_name) {
            $dom_node_list = $document->getElementsByTagname( $tag_name );
            foreach ( $dom_node_list as $dom_element ) {
                $dom_elements_to_remove[] = $dom_element;
            }
        }

        foreach( $dom_elements_to_remove as $dom_element ){
            $dom_element->parentNode->removeChild( $dom_element );
        }

        return $document->saveXML();
    }

    /**
     * convert xml string to php array - useful to get a serializable value
     *
     * @param string $xmlstr
     * @return array
     */
    private static function xml_to_array( $xmlstr ) {

        $xmlstr = static::blacklist_watchdog( $xmlstr );

        $document = new DOMDocument();
        $document->loadXML( $xmlstr );

        $root = $document->documentElement;
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

    public static function render_room_details( $room, $args ) {
        ?>
        <div class="users-table table">
            <header class="thead">
                <div class="th"
                     style="width: 25%"><?php esc_html_e('Property type', 'kanda'); ?></div>
                <div class="th">
                    <?php esc_html_e('Property value', 'kanda'); ?>
                    <div class="actions pull-right">
                        <a href="javascript:void(0);"
                           class="btn -sm -secondary book-btn"><?php esc_html_e('Book', 'kanda'); ?></a>
                        <a href="<?php echo static::get_cancellation_policy_url($args['hotelcode'], $room['roomtypecode'], $room['contracttokenid'], $args['start_date'], $args['end_date']); ?>"
                           class="btn -sm -secondary ajax-popup"
                           data-popup="confirmation"><?php esc_html_e('Cancellation policy', 'kanda'); ?></a>
                    </div>
                </div>
            </header>
            <div class="tbody">
                <?php if (isset($room['roomtype']) && $room['roomtype']) { ?>
                    <div class="tr">
                        <div class="td"><?php esc_html_e('Room Type', 'kanda'); ?></div>
                        <div class="td"><?php echo $room['roomtype']; ?></div>
                    </div>
                <?php }
                if (isset($room['mealplan']) && $room['mealplan']) { ?>
                    <div class="tr">
                        <div class="td"><?php esc_html_e('Meal Plan', 'kanda'); ?></div>
                        <div class="td"><?php echo $room['mealplan']; ?></div>
                    </div>
                <?php }
                if (isset($room['rate']) && $room['rate']) { ?>
                    <div class="tr">
                        <div class="td"><?php esc_html_e('Rate', 'kanda'); ?></div>
                        <div class="td"><?php echo kanda_generate_price($room['rate'], $args['hotelcode'], $args['currency'], $room['currcode'], $args['nights_count']); ?></div>
                    </div>
                <?php }

                if ((bool)$room['discountdetails'] && array_key_exists('discount', $room['discountdetails'])) {
                    $room_discounts = $room['discountdetails']['discount'];
                    $room_discounts = IOL_Helper::is_associative_array($room_discounts) ? array($room_discounts) : $room_discounts;
                    foreach ($room_discounts as $discount) {
                        if (isset($discount['discountname']) && $discount['discountname']) { ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e('Discount Name', 'kanda'); ?></div>
                                <div class="td"><?php echo $discount['discountname']; ?></div>
                            </div>
                        <?php }
                        if (isset($discount['discounttype']) && $discount['discounttype']) { ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e('Discount Type', 'kanda'); ?></div>
                                <div class="td"><?php echo $discount['discounttype']; ?></div>
                            </div>
                        <?php }
                        if (isset($discount['discountnotes']) && $discount['discountnotes']) { ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e('Discount Notes', 'kanda'); ?></div>
                                <div class="td"><?php echo $discount['discountnotes']; ?></div>
                            </div>
                        <?php }
                        if (isset($discount['totaldiscountrate']) && $discount['totaldiscountrate']) { ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e('Discount Total Rate', 'kanda'); ?></div>
                                <div class="td"><?php echo absint( $discount['totaldiscountrate'] ); ?></div>
                            </div>
                        <?php }
                    }
                } ?>
            </div>
        </div>
        <?php
    }

    public static function get_cancellation_policy_url( $hotel_code, $roomtypecode, $contracttokenid, $start_date, $end_date ) {
        return add_query_arg( array(
            'action'        => 'hotel_cancellation_policy',
            'code'          => $hotel_code,
            'roomtype'      => $roomtypecode,
            'token'         => $contracttokenid,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'security'      => wp_create_nonce( 'kanda-get-hotel-cancellation-policy' )
        ), admin_url( 'admin-ajax.php' )
        );
    }

}