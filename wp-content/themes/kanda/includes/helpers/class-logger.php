<?php
/**
 * Kanda Theme Logger
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}
class Kanda_Logger {

    private $file = false;
    /**
     * Singleton.
     */
    static function get_instance() {
        static $instance = null;
        if ( $instance == null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->get_and_set_or_create_log();
    }

    /**
     * Get log file path
     *
     * @return bool|string
     */
    private function get_and_set_or_create_log() {
        $date = date( 'Y-m-d', current_time( 'timestamp', true ) );
        $folder = trailingslashit( KANDA_THEME_PATH . 'logs' );

        $path = $folder . $date;
        $file = trailingslashit( $path ) . 'log.log';

        if( ! file_exists( $file ) && wp_mkdir_p( $path ) && fopen( $file, 'w+' ) ) {
            $this->file = $file;
        }
    }

    /**
     * Log message to file
     *
     * @param $message
     */
    public function log( $message = "" ) {
        if( ! $this->file ) {
            return;
        }

        $message = sprintf(
            '%1$s - %2$s',
            date( 'Y-m-d H:i:s', current_time( 'timestamp', true ) ),
            $message . "\n"
        );

        @error_log( $message, 3, $this->file );
    }

}

/**
 * Get logger instance
 *
 * @return Kanda_Logger
 */
function kanda_logger() {
    return Kanda_Logger::get_instance();
}