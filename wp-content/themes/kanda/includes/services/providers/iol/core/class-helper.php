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
	 * Get gender from title
	 * @param $title
	 *
	 * @return string
	 */
    public static function get_gender_from_title( $title ) {
	    $title = strtolower( $title );

	    return ( $title == 'mr' ) ? 'M' : 'F';
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
        return maybe_serialize( $array );
        //return addslashes( serialize( $array ) );
    }

    /**
     * Convert savable format to array
     *
     * @param $data
     * @return mixed
     */
    public static function savable_format_to_array( $data ) {
        return maybe_unserialize( $data );
        //return maybe_unserialize( stripslashes( $data ) );
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
	* Check room possible restrictions
	*/
	private static function check_room_restrictions( $room, $args ) {
		$restriction = false;
		
		if( isset( $room['restriction'] ) ) {
			$room_restrictions = $room['restriction'];
		
			$type = preg_replace( '/\s+/', '', strtolower( $room_restrictions['restrictiontype'] ) );
			$possible_restrictions = IOL_Config::get( 'possible_restrictions' );
			
			if( in_array( $type, $possible_restrictions ) ) {
				switch( $type ) {
					case 'minnights':
						if( $room_restrictions['muststaydays'] && ( $room_restrictions['muststaydays'] > $args['request']['nights_count'] ) ) {
							$restriction = array(
                                'render'    => ( $room_restrictions['muststaydays'] < 30 ),
								'type' 		=> $type,
								'message'	=> sprintf( 
									__( 'Room requires minimum stay of %1$d %2$s.', 'kanda' ), 
									$room_restrictions['muststaydays'], 
									_n( 'night', 'nights', $room_restrictions['muststaydays'], 'kanda' ) 
								)
							);
						}
						break;
					
					case 'maxnights':
                        if( $room_restrictions['muststaydays'] && ( $room_restrictions['muststaydays'] < $args['request']['nights_count'] ) ) {
                            $restriction = array(
	                            'render'    => true,
                                'type' 		=> $type,
                                'message'	=> sprintf(
                                    __( 'Room requires maximum stay of %1$d %2$s.', 'kanda' ),
                                    $room_restrictions['muststaydays'],
                                    _n( 'night', 'nights', $room_restrictions['muststaydays'], 'kanda' )
                                )
                            );
                        }
                        break;
					
					case 'nocheckin':
						$restriction = array(
							'render'    => true,
							'type' 		=> $type,
							'message'	=> __( 'No Check In.', 'kanda' )
						);
						break;
						
					case 'nocheckout':
						$restriction = array(
							'render'    => true,
							'type' 		=> $type,
							'message'	=> __( 'No Check Out.', 'kanda' )
						);
						break;
				}
			}
		}
		
		return $restriction;
	}

    /**
     * Helper method to render room details for search result
     *
     * @param $room
     * @param $args
     */
    public static function render_room_details( $room, $args ) {
		
		/*if( strtolower( $room['roomstatus'] ) == 'xx' ) {
            return;
        }*/
		
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
		$restriction = static::check_room_restrictions( $room, $args );
		if( $restriction && ! $restriction['render'] ) {
		    return;
        }
        ?>
        <div class="users-table table">
            <header class="thead">
                <div class="th" style="width: 25%"><?php esc_html_e('Property type', 'kanda'); ?></div>
                <div class="th">
                    <?php esc_html_e('Property value', 'kanda'); ?>
                    <div class="actions pull-right">
                        <a href="#<?php echo $unique_id; ?>" class="btn -sm -secondary open-popup"><?php esc_html_e('Book', 'kanda'); ?></a>
                        <a href="#<?php printf( 'daily_rate_%s', $unique_id ); ?>" class="btn -sm -secondary open-popup"><?php esc_html_e('See daily rates', 'kanda'); ?></a>
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
                        <div class="td"><?php esc_html_e('Total Rate', 'kanda'); ?></div>
                        <div class="td">
                            <?php
                            $price = kanda_generate_price($room['rate'], $args['hotelcode'], $args['currency'], $room['currcode'], $args['request']['nights_count']);
                            $price = floatval( str_replace(',', '', $price) );
                            $price += kanda_get_user_additional_fee() * $args['request']['nights_count'];
                            printf( '%1$s %2$s', $price, $args['currency'] ); ?>
                        </div>
                    </div>
                    <?php
                    $suppliment = isset( $room['supplementdetails']['supplement'] ) ? $room['supplementdetails']['supplement'] : false;

                    $has_suppliment = false;
                    if( $suppliment ) {
                        $suppliment = IOL_Helper::is_associative_array( $suppliment ) ? array( $suppliment ) : $suppliment;
                        $has_suppliment = ! empty( $suppliment );
                    }

                    if( $has_suppliment ) {
                        ?>
                        <div class="tr">
                            <div class="td"><?php esc_html_e('Suppliments Included', 'kanda'); ?></div>
                            <div class="td">
                                <?php foreach( $suppliment as $sp ) {
                                    printf( '<p>%1$s - %2$s USD</p>', $sp[ 'name' ], $sp['rate'] );
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if( $restriction ) { ?>
                    <div class="tr text-danger">
                        <div class="td"><b><?php esc_html_e('Restrictions', 'kanda'); ?></b></div>
                        <div class="td"><b><?php echo $restriction['message']; ?></b></div>
                    </div>
                <?php } ?>

                <?php

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
                                <div class="td"><?php echo absint( $discount['totaldiscountrate'] ) . ' USD'; ?></div>
                            </div>
                        <?php }
                    }
                } ?>
            </div>
        </div>
        <?php

        static::render_room_booking_confirmation_popup( $unique_id, $room, $args, $restriction );
        static::render_daily_rate_popup( array(
            'popup_id'        => sprintf( 'daily_rate_%s', $unique_id ),
            'room'            => $room,
            'start_date'      => $args['start_date'],
            'end_date'        => $args['end_date'],
            'hotel_code'      => $args['hotelcode'],
            'input_currency'  => $args['currency'],
            'output_currency' => $room['currcode']
        ) );
    }

    /**
     * Render room booking confirmation popup
     *
     * @param $popup_id
     * @param $room
     * @param $args
     */
    public static function render_room_booking_confirmation_popup( $popup_id, $room, $args, $restriction ) {
        $booking_create_url = static::get_booking_create_url( array(
            'hotel_code'            => $args['hotelcode'],
            'city_code'             => $args['request']['city'],
            'room_number'           => $room['roomnumber'],
            'request_id'            => $args['request']['request_id'],
            'room_type_code'        => $room['roomtypecode'],
            'contract_token_id'     => $room['contracttokenid'],
            'room_configuration_id' => $room['roomconfigurationid'],
            'meal_plan_code'        => $room['mealplancode'],
            'room_n'                => $args['requested_room_number']
        ) );
        ?>
        <div id="<?php echo $popup_id; ?>" class="static-popup -sm mfp-hide">
			<?php if( $restriction ) { ?>
				<h2 class="text-center"><?php _e( 'Restriction', 'kanda' ); ?></h2>
                <p class="text-center"><?php echo $restriction['message']; ?></p>
			<?php } else { ?>
                <h2 class="text-center"><?php _e( 'Booking Confirmation', 'kanda' ); ?></h2>
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
                                <div class="td"><?php esc_html_e('Total Rate', 'kanda'); ?></div>
                                <div class="td">
                                    <?php
                                    $price = kanda_generate_price($room['rate'], $args['hotelcode'], $args['currency'], $room['currcode'], $args['request']['nights_count']);
                                    $price = floatval( str_replace(',', '', $price) );
                                    $price += kanda_get_user_additional_fee() * $args['request']['nights_count'];
                                    printf( '%1$s %2$s', $price, $args['currency'] ); ?>
                                </div>
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
                                        <div class="td"><?php echo absint( $discount['totaldiscountrate'] ) . ' USD'; ?></div>
                                    </div>
                                <?php }
                            }
                        } ?>
                    </div>
                </div>

                <div class="actions text-center">
                    <a href="<?php echo $booking_create_url; ?>" class="btn -sm -secondary" target="_blank"><?php _e( 'Process', 'kanda' ); ?></a>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    public static function render_daily_rate_popup( $args ) {
        $suppliment = isset( $args['room']['supplementdetails']['supplement'] ) ? $args['room']['supplementdetails']['supplement'] : false;

        $has_suppliment = false;
        if( $suppliment ) {
            $suppliment = IOL_Helper::is_associative_array( $suppliment ) ? array( $suppliment ) : $suppliment;
            $has_suppliment = ! empty( $suppliment );
        }

        //$has_supliment = false;
        if( $has_suppliment ) {
            $suppliments = array();

            foreach( $suppliment as $sp ) {
                $suppliments[] = array(
                    'from' 	=> DateTime::createFromFormat( 'Ymd H:i:s', sprintf( '%s 00:00:00', $sp['fromdate'] ) )->getTimestamp(),
                    'to' 	=> DateTime::createFromFormat( 'Ymd H:i:s', sprintf( '%s 00:00:00', $sp['todate'] ) )->getTimestamp(),
                    'rate'	=> $sp['rate']
                );
            }
        }

        $full_period = array();
        $chunk_size = 7;
        $index = 0;

        $start_date = new DateTime( $args['start_date'] );
        $end_date = new DateTime( $args['end_date'] );
        $interval = DateInterval::createFromDateString('1 day');
        $life_period = new DatePeriod( $start_date, $interval, $end_date );

        $rates = is_array( $args['room']['ratedetails']['rate'] ) ? $args['room']['ratedetails']['rate'] : array( $args['room']['ratedetails']['rate'] );

        $user_additional_fee = kanda_get_user_additional_fee();

        foreach ( $life_period as $lp ) {
            $lp_timestamp = $lp->getTimestamp();

            $price = $rates[ $index ];
            $price = kanda_generate_price($rates[ $index ], $args['hotel_code'], $args['output_currency'], $args['input_currency']);
            $price = floatval( str_replace(',', '', $price) );
            $price += $user_additional_fee;

            if( $has_suppliment ) {
                foreach( $suppliments as $sp_item ) {
                    if( ( $lp_timestamp >= $sp_item['from'] && $lp_timestamp <= $sp_item['to'] ) ) {
                        $price += $sp_item['rate'];
                    }
                }
            }
            $full_period[] = array(
                'rate' => sprintf( '%1$s %2$s', $price, $args['output_currency'] ),
                'date' => $lp->format( "d / m" )
            );
            ++$index;
        }
        $period_chunks = array_chunk( $full_period, $chunk_size );
        ?>
        <div id="<?php echo $args['popup_id']; ?>" class="static-popup -sm mfp-hide">
            <h2 class="text-center"><?php _e( 'Daily Rate', 'kanda' ); ?></h2>

            <?php foreach( $period_chunks as $chunk ) {  ?>
                <div class="users-table table text-center">
                    <header class="thead">
                        <?php foreach ( $chunk as $data ) { ?>
                            <div class="th"><?php echo $data['date']; ?></div>
                        <?php } ?>
                    </header>
                    <div class="tbody">
                        <div class="tr">
                            <?php foreach ( $chunk as $data ) { ?>
                                <div class="td"><?php echo $data['rate']; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
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