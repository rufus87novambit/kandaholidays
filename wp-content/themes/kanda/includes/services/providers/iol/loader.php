<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

if( ! class_exists( 'IOL_Provider' ) ) {

    final class IOL_Provider extends Kanda_Service_Provider {

        /**
         * Singleton.
         */
        static function get_instance() {
            static $instance = null;
            if ($instance == null) {
                $instance = new self();
            }
            return $instance;
        }

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct();

            $this->path = trailingslashit(__DIR__);
            $this->core = trailingslashit($this->path . 'core');

            $this->load_dependant( $this->path, 'config' );

            $this->hooks();
        }

        /**
         * Attach required hooks
         */
        private function hooks(){
            add_filter( 'kanda/providers', array( $this, 'register' ), 10 );
            add_action( 'kanda/providers/init', array($this, 'init' ), 10 );
            add_action( 'admin_menu', array( $this, 'add_admin_menu_pages' ), 10 );

            add_action( 'wp_ajax_iol_master_update', array( $this, 'iol_master_update' ), 10 );
            add_action( 'wp_ajax_iol_master_delete', array( $this, 'iol_master_delete' ), 10 );
        }

        /**
         * Register provider
         *
         * @param $providers
         * @return array
         */
        public function register( $providers ) {
            $this->id = IOL_Config::get( 'id' );
            $this->name = IOL_Config::get( 'name' );
            $this->public_name = esc_html__( 'UAE', 'kanda' );

            return parent::register( $providers );
        }

        /**
         * Wake up
         */
        public function init() {
            $this->load_dependant( $this->core, 'class-helper', 'IOL_Helper' );
            $this->load_dependant( $this->core, 'class-response', 'IOL_Response' );
            $this->load_dependant( $this->core, 'class-search-cache', 'IOL_Search_Cache' );
            $this->load_dependant( $this->core, 'class-request', 'IOL_Request' );
            $this->load_dependant( $this->core, 'class-master-data', 'IOL_Master_Data' );
        }

        /**
         * Update master data
         */
        public function iol_master_update() {

            $security = isset($_REQUEST['security']) ? $_REQUEST['security'] : '';
            $is_valid = true;

            if ( wp_verify_nonce($security, 'iol-master-update') ) {

                $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';

                if ( array_key_exists($city, IOL_Config::get('cities') ) ) {

                    IOL_Helper::init_blacklist(array('Location'));

                    set_time_limit( 3000 );

                    $response = $this->hotels()->get_master_data( array( 'city' => $city ) );
                    if ($response->is_valid()) {
                        $data = $response->data;
                        $hotels = $data['masterdatadetails']['hotel'];

                        $status = IOL_Master_Data::save( $city, $hotels );

                        if ($status) {
                            $message = __('Updated', 'success');
                            $last_updated = get_option( sprintf( '%1$s_%2$s_last_update', IOL_Config::get( 'id' ), $city ), false );
                        } else {
                            $is_valid = false;
                            $message = sprintf('Cannot get master data for %s', $city);
                        }
                    } else {
                        $log = sprintf('Cannot get master data for %s', $city);
                        kanda_logger()->log($log);

                        $is_valid = false;
                        $message = $log;
                    }

                } else {
                    $is_valid = false;
                    $message = __('Invalid city', 'kanda');
                }
            } else {
                $is_valid = false;
                $message = __('Invalid request', 'kanda');
            }

            if( $is_valid ) {
                wp_send_json_success( array(
                    'message' => $message,
                    'last_updated' => $last_updated
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => $message
                ) );
            }

        }

        /**
         * Delete master data
         */
        public function iol_master_delete() {
            $security = isset($_REQUEST['security']) ? $_REQUEST['security'] : '';
            $is_valid = true;

            if ( wp_verify_nonce( $security, 'iol-master-delete' ) ) {

                $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';

                if (array_key_exists($city, IOL_Config::get('cities'))) {

                    $city = strtoupper( $city );
                    IOL_Master_Data::delete_city_data( $city );

                    global $wpdb;
                    $query = "SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = 'hotelcity' AND `meta_value` = '{$city}'";
                    $hotel_post_ids = $wpdb->get_col( $query );

                    if( empty( $hotel_post_ids ) ) {
                        $message = __( 'Noting to delete', 'kanda' );
                    } else {

                        foreach ($hotel_post_ids as $index => $hotel_post_id) {
                            $hotel_post_ids[$index] = sprintf('\'%d\'', $hotel_post_id);
                        }

                        $hotel_post_ids = implode(',', $hotel_post_ids);

                        /** delete data from postmeta */
                        $query = "DELETE FROM `{$wpdb->postmeta}` WHERE `post_id` IN ( " . $hotel_post_ids . " )";
                        $wpdb->query($query);

                        /** delete data from posts */
                        $query = "DELETE FROM `{$wpdb->posts}` WHERE `ID` IN ( " . $hotel_post_ids . " )";
                        $wpdb->query($query);

                        delete_option( sprintf( '%1$s_%2$s_last_update', IOL_Config::get( 'id' ), $city ) );

                        $message = __( 'Deleted', 'kanda' );
                    }

                    $last_updated = __( 'Never', 'kanda' );

                } else {
                    $is_valid = false;
                    $message = __('Invalid city', 'kanda');
                }

            } else {
                $is_valid = false;
                $message = __('Invalid request', 'kanda');
            }

            if( $is_valid ) {
                wp_send_json_success( array(
                    'message' => $message,
                    'last_updated' => $last_updated
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => $message
                ) );
            }
        }

        /**
         * Add admin menu pages
         */
        public function add_admin_menu_pages() {
            add_submenu_page(
                'tools.php',
                sprintf( __( '%s Master Data', 'kanda' ), IOL_Config::get( 'name' ) ),
                sprintf( __( '%s Master Data', 'kanda' ), IOL_Config::get( 'name' ) ),
                'manage_options',
                sprintf( __( '%s-master-data', 'kanda' ), IOL_Config::get( 'id' ) ),
                array( $this, 'render_admin_master_data_page' )
            );
        }

        /**
         * Render master data sync page
         */
        public function render_admin_master_data_page() {
            ?>
            <style type="text/css">
                .row {
                    width: 100%;
                    display: inline-block;
                    margin-bottom: 1rem;
                }
                .row.heading {
                    border-bottom: 1px solid #000;
                }
                .row .td,
                .row .th {
                    float: left;
                }
                .row .first {
                    width: 10%;
                }
                .row .second {
                    width: 10%;
                }
                .row .third {
                    width: 80%;
                }
            </style>
            <div class="wrap" id="iol-master-data-sync">
                <h1><?php printf( __( '%s Master Data', 'kanda' ), IOL_Config::get( 'name' ) ) ?></h1>
                <div class="sync-rows">
                    <div class="row heading">
                        <div class="th first"><h3><?php _e( 'City', 'kanda' ); ?></h3></div>
                        <div class="th second"><h3><?php _e( 'Last updated', 'kanda' ); ?></h3></div>
                        <div class="th third"><h3><?php _e( 'Actions', 'kanda' ); ?></h3></div>
                    </div>
                    <?php
                        foreach( IOL_Config::get( 'cities' ) as $city_code => $city_name ) {
                            $update_url = add_query_arg( array( 'action' => 'iol_master_update', 'security' => wp_create_nonce( 'iol-master-update' ), 'city' => $city_code ) , admin_url( 'admin-ajax.php' ) );
                            $delete_url = add_query_arg( array( 'action' => 'iol_master_delete', 'security' => wp_create_nonce( 'iol-master-delete' ), 'city' => $city_code ) , admin_url( 'admin-ajax.php' ) );
                            $last_updated = get_option( sprintf( '%1$s_%2$s_last_update', IOL_Config::get( 'id' ), $city_code ), false ); ?>
                    <div class="row">
                        <div class="td first"><strong><?php echo $city_name; ?></strong></div>
                        <div class="td second last-updated"><?php echo $last_updated ? $last_updated :  __( 'Never', 'kanda' ); ?></div>
                        <div class="td third">
                            <a href="<?php echo $update_url; ?>" class="button button-primary button-update"><?php esc_html_e( 'Update Now', 'kanda' ); ?></a>
                            <a href="<?php echo $delete_url; ?>" class="button button-secondary button-delete <?php echo $last_updated ? '' : 'kanda_hidden'; ?>"><?php esc_html_e( 'Delete', 'kanda' ); ?></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        /**
         * Get search instance
         *
         * @return IOL_Search_Cache
         */
        public function cache() {
            return new IOL_Search_Cache();
        }

        /**
         * Get hotels instance
         * @return IOL_Hotels
         */
        public function hotels() {
            $this->load_dependant( $this->core, 'class-hotels', 'IOL_Hotels' );

            return new IOL_Hotels();
        }

        public function bookings() {
            $this->load_dependant( $this->core, 'class-bookings', 'IOL_Bookings' );

            return new IOL_Bookings();
        }

    }

    /**
     * Get class instance
     */
    function provider_iol() {
        return IOL_Provider::get_instance();
    }

    provider_iol();
}