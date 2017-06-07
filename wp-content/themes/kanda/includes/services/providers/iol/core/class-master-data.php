<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

class IOL_Master_Data {

    /**
     * Table name
     * @var string
     */
    private static $table_name = 'iol_master_data';

    /**
     * Get prefixed table name
     * @return string
     */
    private static function get_table_name() {
        global $wpdb;

        return $wpdb->prefix . self::$table_name;
    }

    /**
     * Sync hotel posts with master data
     *
     * @param $master_data
     * @param $city
     */
    private static function sync_posts( $master_data, $city ) {

        /** Do nothing if master does not have data */
        if( empty( $master_data ) ) {
            return;
        }

        global $wpdb;

        $query = "
          SELECT `pm1`.`meta_value` AS `hotelcode`, `pm1`.`post_id`
            FROM `{$wpdb->posts}` AS `p`
            LEFT JOIN `{$wpdb->postmeta}` AS `pm1`
                ON `pm1`.`post_id` = `p`.`ID` AND `pm1`.`meta_key` = 'hotelcode'
            LEFT JOIN `{$wpdb->postmeta}` AS `pm2`
                ON `pm2`.`post_id` = `p`.`ID` AND `pm2`.`meta_key` = 'hotelcity'
            WHERE `p`.`post_type` = 'hotel' AND `pm2`.`meta_value` = '{$city}'";

        $existing_hotels = $wpdb->get_results( $query, OBJECT_K );

        /** Delete all posts that does not exists in master data -> we do not need them anymore */
        $to_delete = array_diff_key( $existing_hotels, $master_data );
        foreach( $to_delete as $hotelcode => $data ) {
            wp_delete_post( $data->post_id, true );
        }

        /** Insert posts that does not exists for admin manipulation */
        $to_insert = array_diff_key( $master_data, $existing_hotels );
        $error_count = 0;
        $success_count = 0;

        foreach( $to_insert as $hotelcode => $data ) {
            $post_array = array(
                'post_title'        => $data['hotelname'],
                'post_name'         => $hotelcode,
                'post_status'       => 'publish',
                'post_type'         => 'hotel',
                'comment_status'    => 'closed',
                'ping_status'       => 'closed',
                'meta_input' => array(
                    'hotelcode'         => $hotelcode,
                    'additional_fee'    => '',
                    'hotelcity'         => $city,
                    'hotelstarrating'   => ( isset( $data['starrating'] ) && (bool)$data['starrating'] ) ? $data['starrating'] : 'N/A',
                    'hotelphone'        => ( isset( $data['contactdetails']['contactphone'] ) && (bool)$data['contactdetails']['contactphone'] ) ? $data['contactdetails']['contactphone'] : 'N/A',
                    'hoteladdress'      => ( isset( $data['contactdetails']['contactaddress'] ) && (bool)$data['contactdetails']['contactaddress'] ) ? $data['contactdetails']['contactaddress'] : 'N/A',
                    'hotelweb'          => ( isset( $data['contactdetails']['website'] ) && (bool)$data['contactdetails']['website'] ) ? $data['contactdetails']['website'] : 'N/A',
                    'checkintime'       => (bool)$data['checkintime'] ? $data['checkintime'] . ':00' : '14:00:00',
                    'checkouttime'      => (bool)$data['checkouttime'] ? $data['checkouttime'] . ':00' : '12:00:00'
                )
            );

            $post_id = wp_insert_post( $post_array, true );
            if( is_wp_error( $post_id ) ) {
                $message = 'There was an error inserting master data: WP_Error: ' . $post_id->get_error_message() . ', Data => ' . json_encode( $post_array );
                //kanda_mailer()->send_developer_email( 'Master data', $message );
                kanda_logger()->log( $message );
                $error_count++;
            } else {
                $success_count++;
            }
        }

        /** Log execution results */
        kanda_logger()->log( sprintf( 'Master data for %1$s: Total: %2$d, Success: %3$d, Error: %4$d',
            IOL_Helper::get_city_name_from_code( $city ),
            count( $to_insert ),
            $success_count,
            $error_count
        ) );

    }

    /**
     * Save master data
     *
     * @param $city
     * @param array $hotels
     * @return bool
     */
    public static function save( $city, $hotels = array() ) {
        $status = false;
        if( ! empty( $hotels ) ) {

            global $wpdb;
            $table = self::get_table_name();
            $city = strtoupper( $city );

            /** delete old */
            $wpdb->delete( $table, array( 'city' => $city ), array( '%s' ) );

            $values = array();
            $indexed_hotel_data = array();
            foreach( $hotels as $hotel ) {

                $code = $hotel['hotelcode'];

                // do not save blacklisted hotel @see IOL_Config for city blacklisted hotels
                if( static::is_hotel_blacklisted( $city, $code ) ) {
                    continue;
                }

                /** Hotel description */
                $description = '';
                if( array_key_exists( 'descriptionlist', $hotel ) && array_key_exists( 'description', $hotel['descriptionlist'] ) ) {
                    $description = IOL_Helper::is_associative_array( $hotel['descriptionlist']['description'] ) ? $hotel['descriptionlist']['description']['@content'] : $hotel['descriptionlist']['description'][0]['@content'];
                    $description = preg_replace( '/\s+/', ' ', trim( $description ) );
                }

                /** Hotel images */
                $images = array();
                if( array_key_exists( 'images', $hotel ) && array_key_exists( 'img', $hotel['images'] ) ) {
                    if( IOL_Helper::is_associative_array( $hotel['images']['img'] ) ) {
                        $images = array( str_replace( '/ThumbImage/', '/Image/', $hotel['images']['img']['imagelocation'] ) );
                    } else {
                        $images = array_map(function( $image ){
                            return str_replace( '/ThumbImage/', '/Image/', $image['imagelocation'] );
                        }, $hotel['images']['img']);
                    }
                }

                $values[] = sprintf(
                    '(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')',
                    $city,
                    $code,
                    $description,
                    IOL_Helper::array_to_savable_format( $images )
                );

                $indexed_hotel_data[ $code ] = $hotel;
            }

            static::sync_posts( $indexed_hotel_data, $city );

            if ( !empty( $values ) ) {

                static::delete_city_data( $city );

                $values = implode(',', $values);
                $query = "INSERT INTO `{$table}` ( `city`, `code`, `description`, `images` ) VALUES {$values}";

                $wpdb->query( $query );
            }

            $status = true;
        }

        if( $status ) {
            $option_name = sprintf( '%1$s_%2$s_last_update', IOL_Config::get( 'id' ), $city );
            $option_value = current_time( 'mysql' );

            update_option( $option_name, $option_value );
        }

        return $status;
    }

    /**
     * Delete provided city data
     * @param $city
     */
    public static function delete_city_data( $city ) {
        global $wpdb;
        $table = self::get_table_name();
        $city = strtoupper( $city );

        $wpdb->delete( $table, array( 'city' => $city ), array( '%s' ) );
    }

    /**
     * Get master data for specific city
     *
     * @param $city_code
     * @return array|null|object
     */
    public static function get_data( $city_code ) {

        global $wpdb;
        $table = self::get_table_name();

        $query = "SELECT `code`, `city`, `description`, `images` FROM `{$table}` WHERE `city` = '{$city_code}'";
        return $wpdb->get_results( $query, OBJECT_K );
    }

    /**
     * Check city code for blacklisting
     *
     * @param $city_code
     * @param $hotel_code
     * @return bool
     */
    private static function is_hotel_blacklisted( $city_code, $hotel_code ) {
		
        $option_name = sprintf( 'hotels_blacklist->%1$s', $city_code );
        $city_blacklist = IOL_Config::get( $option_name );

        return in_array( $hotel_code, $city_blacklist );
    }

}