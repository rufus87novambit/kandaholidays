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
     * Save master data
     *
     * @param $city
     * @param array $hotels
     * @return bool
     */
    public static function save( $city, $hotels = array() ) {
        if( ! empty( $hotels ) ) {

            global $wpdb;
            $table = self::get_table_name();

            $values = array();
            foreach( $hotels as $hotel ) {

                $code = $hotel['hotelcode'];
                $description = '';
                $images = array();
                if( array_key_exists( 'descriptionlist', $hotel ) && array_key_exists( 'description', $hotel['descriptionlist'] ) ) {
                    $description = is_array( $hotel['descriptionlist']['description'] ) ? $hotel['descriptionlist']['description'][0] : $hotel['descriptionlist']['description'];
                    $description = preg_replace( '/\s+/', ' ', trim( $description ) );
                }

                if( array_key_exists( 'images', $hotel ) && array_key_exists( 'img', $hotel['images'] ) ) {
                    if( IOL_Helper::is_associative_array( $hotel['images']['img'] ) ) {
                        $images = array( $hotel['images']['img']['imagelocation'] );
                    } else {
                        $images = array_map(function( $image ){
                            return $image['imagelocation'];
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
            }

            if ( !empty( $values ) ) {

                $wpdb->delete( $table, array( 'city' => $city ), array( '%s' ) );

                $values = implode(',', $values);
                $query = "INSERT INTO `{$table}` ( `city`, `code`, `description`, `images` ) VALUES {$values}";

                $wpdb->query( $query );

                return true;
            }
        }
        return false;
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

}