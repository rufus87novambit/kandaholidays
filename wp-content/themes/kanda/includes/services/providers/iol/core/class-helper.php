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
     * Get title from string
     * @param $string
     * @return bool|string
     */
    public static function str_to_title( $string ) {
        $string = strtolower( $string );

        if( $string === 'mr' ) {
            return 'Mr';
        }

        if( $string === 'mrs' ) {
            return 'Mrs';
        }

        if( $string === 'miss' ) {
            return 'Miss';
        }

        if( $string === 'ms' ) {
            return 'Ms';
        }

        return false;

    }

    /**
     * Get occupant type ( adult | child )
     * @param $type
     * @return bool|string
     */
    public static function convert_passenger_type( $type ) {
        $type = strtolower( $type );

        if( $type === 'adult' ) {
            return 'ADT';
        }

        if( $type === 'child' ) {
            return 'CHD';
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
        return addslashes( serialize( $array ) );
    }

    /**
     * Convert savable format to array
     *
     * @param $data
     * @return mixed
     */
    public static function savable_format_to_array( $data ) {
        return maybe_unserialize( stripslashes( $data ) );
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

        return isset( $cities[ $code ] ) ? $cities[ $code ] : $code;
    }

    /**
     * Helper method to render room details for search result
     *
     * @param $room
     * @param $args
     */
    public static function render_room_details( $room, $args ) {
        $room_occupants = $args['request']['room_occupants'][ $args['roomnumber'] ];
        $availability_request_url = static::get_availability_request_url( array(
            'roomtypecode' => $room['roomtypecode'],
            'contracttokenid' => $room['contracttokenid'],
            'roomconfigurationid' => $room['roomconfigurationid'],
            'mealplancode' => $room['mealplancode'],
            'adults' => $room_occupants['adults'],
            'child' => $room_occupants['child'],
            'start_date' => $args['start_date'],
            'end_date' => $args['end_date'],
            'city' => $args['request']['city'],
            'hotelcode' => $args['hotelcode']
        ) );
        $cancellation_policy_url = static::get_cancellation_policy_url( array(
            'hotel_code'        => $args['hotelcode'],
            'roomtypecode'      => $room['roomtypecode'],
            'contracttokenid'   => $room['contracttokenid'],
            'start_date'        => $args['start_date'],
            'end_date'          => $args['end_date']
        ) );

        $unique_id = uniqid();
        $must_stay_days = ( isset( $room['restriction']['muststaydays'] ) && $room['restriction']['muststaydays'] ) ? $room['restriction']['muststaydays'] : 0;
        ?>
        <div class="users-table table">
            <header class="thead">
                <div class="th" style="width: 25%"><?php esc_html_e('Property type', 'kanda'); ?></div>
                <div class="th">
                    <?php esc_html_e('Property value', 'kanda'); ?>
                    <div class="actions pull-right">
                        <a href="#<?php echo $unique_id; ?>" class="btn -sm -secondary open-popup"><?php esc_html_e('Book', 'kanda'); ?></a>
                        <a href="<?php echo $availability_request_url; ?>" class="btn -sm -secondary ajax-popup" data-popup="-sm"><?php esc_html_e( 'Availability', 'kanda' ); ?></a>
                        <a href="<?php echo $cancellation_policy_url; ?>" class="btn -sm -secondary ajax-popup" data-popup="-sm"><?php esc_html_e('Cancellation policy', 'kanda'); ?></a>
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
                        <div class="td"><?php echo kanda_generate_price($room['rate'], $args['hotelcode'], $args['currency'], $room['currcode'], $args['request']['nights_count']); ?></div>
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

        static::render_room_booking_confirmation_popup( $unique_id, $room, $args, $must_stay_days );
    }

    /**
     * Render room booking confirmation popup
     *
     * @param $popup_id
     * @param $room
     * @param $args
     */
    public static function render_room_booking_confirmation_popup( $popup_id, $room, $args, $must_stay_days ) {
        $booking_create_url = static::get_booking_create_url( array(
            'hotel_code'            => $args['hotelcode'],
            'city_code'             => $args['request']['city'],
            'room_number'           => $args['roomnumber'],
            'request_id'            => $args['request']['request_id'],
            'room_type_code'        => $room['roomtypecode'],
            'contract_token_id'     => $room['contracttokenid'],
            'room_configuration_id' => $room['roomconfigurationid'],
            'meal_plan_code'        => $room['mealplancode'],
        ) );
        ?>
        <div id="<?php echo $popup_id; ?>" class="static-popup -sm mfp-hide">
            <?php if( $args['request']['nights_count'] >= $must_stay_days ) { ?>
            <h2 class="text-center"><?php _e( 'Booking confirmation', 'kanda' ); ?></h2>
            <p class="text-center"><?php _e( 'Are you sure you want to proccess with following details?', 'kanda' ); ?></p>

            <div class="users-table table">
                <header class="thead">
                    <div class="th" style="width: 25%"><?php esc_html_e('Property type', 'kanda'); ?></div>
                    <div class="th"><?php esc_html_e('Property value', 'kanda'); ?></div>
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
                            <div class="td"><?php echo kanda_generate_price($room['rate'], $args['hotelcode'], $args['currency'], $room['currcode'], $args['request']['nights_count']); ?></div>
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

            <div class="actions text-center">
                <a href="<?php echo $booking_create_url; ?>" class="btn -sm -secondary" target="_blank"><?php _e( 'Process', 'kanda' ); ?></a>
            </div>
            <?php } else { ?>
            <h2 class="text-center"><?php _e( 'Minimum stay restriction', 'kanda' ); ?></h2>
                <p class="text-center"><?php printf( __( 'Room requires minimum stay of %1$d %2$s.', 'kanda' ), $must_stay_days, _n( 'day', 'days', $must_stay_days, 'kanda' ) ); ?></p>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Generate hotel cancellation policy request url
     *
     * @param $args
     * @return string
     */
    public static function get_cancellation_policy_url( $args ) {
        return add_query_arg( array(
            'action'        => 'hotel_cancellation_policy',
            'code'          => $args['hotel_code'],
            'roomtype'      => $args['roomtypecode'],
            'token'         => $args['contracttokenid'],
            'start_date'    => $args['start_date'],
            'end_date'      => $args['end_date'],
            'security'      => wp_create_nonce( 'kanda-get-hotel-cancellation-policy' )
        ), admin_url( 'admin-ajax.php' )
        );
    }

    /**
     * Generate hotel availability request url
     *
     * @param $args
     * @return string
     */
    public static function get_availability_request_url( $args ) {
        $children_count = isset( $args['child']['age'] ) ? count( $args['child']['age'] ) : 0;
        $query_args = array(
            'action'                => 'hotel_availability',
            'roomtypecode'          => $args['roomtypecode'],
            'contracttokenid'       => $args['contracttokenid'],
            'roomconfigurationid'   => $args['roomconfigurationid'],
            'mealplancode'          => $args['mealplancode'],
            'adults'                => $args['adults'],
            'child'                 => $children_count,
            'start_date'            => $args['start_date'],
            'end_date'              => $args['end_date'],
            'city'                  => $args['city'],
            'hotelcode'             => $args['hotelcode'],
            'security'              => wp_create_nonce( 'kanda-hotel-availability-request' )
        );

        if( $children_count ) {
            for( $i = 0; $i < $children_count; $i++ ) {
                $key = 'age' . ( $i + 1 );
                $query_args[ $key ] = $args['child']['age'][ $i ];
            }
        }
        return add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
    }

    /**
     * Generate booking creating url
     * @param $args
     * @return string
     */
    public static function get_booking_create_url( $args ) {
        $args = array_merge( $args, array(
            'security' => wp_create_nonce( 'kanda-create-booking' )
        ) );
        return add_query_arg( $args, kanda_url_to( 'booking', array( 'create' ) ) );
    }

    /**
     * Room status data converter
     * @param $status
     * @return bool
     */
    public static function room_status_data( $status ) {
        $status = strtolower( $status );
        $variations = array(
            'ok' => array(
                'icon'      => '<span class="text-success"><i class="icon icon-checkmark"></i></span>',
                'message'   => __( 'Available', 'kanda' )
            ),
            'rq' => array(
                'icon'      => '<span class="text-primary"><i class="icon icon-question"></i></span>',
                'message'   => __( 'On Request', 'kanda' )
            ),
            'xx' => array(
                'icon'      => '<span class="text-danger"><i class="icon icon-cross"></i></span>',
                'message'   =>__( 'Not Available', 'kanda' )
            )
        );
        return array_key_exists( $status, $variations ) ? $variations[ $status ] : false;
    }
}