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
            add_action( 'admin_head', array( $this, 'check_master_sync_request' ), 10 );
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
         * Check for master sync request
         */
        public function check_master_sync_request() {

            if( isset( $_POST[ 'iol-master-sync' ] ) ) {

                $notice = array( 'type' => 'error', 'message' => '' );
                $security = isset( $_POST['iol-security'] ) ? $_POST['iol-security'] : '';

                if( wp_verify_nonce(  $security, 'iol-master-sync') ) {
                    $city = isset( $_POST['sync-city'] ) ? $_POST['sync-city'] : '';
                    if( array_key_exists( $city, IOL_Config::get( 'cities' ) ) ) {

                        $response = $this->hotels()->get_master_data( array( 'city' => $city ) );
                        if( $response->is_valid() ) {
                            $data = $response->data;
                            $hotels = $data[ 'masterdatadetails' ][ 'hotel' ];

                            $status =  IOL_Master_Data::save( $city, $hotels );
                            if( $status ) {
                                $notice['message'] = __( 'Updated', 'success' );
                                $notice['type'] = 'success';
                            } else {
                                $notice['message'] = sprintf( 'Cannot get master data for %s', $city );
                            }
                        } else {
                            $log = sprintf( 'Cannot get master data for %s', $city );
                            kanda_logger()->log( $log );

                            $notice['message'] = $log;
                        }

                    } else {
                        $notice['message'] = __('Invalid city', 'kanda');
                    }
                } else {
                    $notice['message'] = __('Invalid request', 'kanda');
                }

                $_SESSION['kanda-admin-notice'] = $notice;

                add_action( 'admin_notices', function(){
                    if( isset( $_SESSION['kanda-admin-notice'] ) && (bool)$_SESSION['kanda-admin-notice'] ) {
                        $type = $_SESSION['kanda-admin-notice']['type'];
                        $message = $_SESSION['kanda-admin-notice']['message'];

                        printf( '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>', $type, $message );
                        $_SESSION['kanda-admin-notice'] = false;
                    }

                } );

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
            <div class="wrap">
                <h1><?php printf( __( '%s Master Data', 'kanda' ), IOL_Config::get( 'name' ) ) ?></h1>
                <div class="sync-rows" method="post" action="<?php admin_url( 'tool.php?page=iol-master-data' ); ?>">
                    <div class="row heading">
                        <div class="th first"><h3><?php _e( 'City', 'kanda' ); ?></h3></div>
                        <div class="th second"><h3><?php _e( 'Last updated', 'kanda' ); ?></h3></div>
                        <div class="th third"><h3><?php _e( 'Actions', 'kanda' ); ?></h3></div>
                    </div>
                    <?php foreach( IOL_Config::get( 'cities' ) as $city_code => $city_name ) { ?>
                    <div class="row">
                        <form method="post">
                            <input type="hidden" name="sync-city" value="<?php echo $city_code ?>" />
                            <input type="hidden" name="iol-security" value="<?php echo wp_create_nonce( 'iol-master-sync' ); ?>" />
                            <div class="td first"><strong><?php echo $city_name; ?></strong></div>
                            <div class="td second"><?php echo get_option( sprintf( '%1$s_%2$s_last_update', IOL_Config::get( 'id' ), $city_code ), __( 'Never', 'kanda' ) ); ?></div>
                            <div class="td third"><?php submit_button( __( 'Update now', 'kanda' ), 'primary', 'iol-master-sync', false ); ?></div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        /**
         * Get hotels instance
         * @return IOL_Hotels
         */
        public function hotels() {
            $this->load_dependant( $this->core, 'class-hotels', 'IOL_Hotels' );

            return new IOL_Hotels();
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