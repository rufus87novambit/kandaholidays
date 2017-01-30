<?php

class Kanda_Log {

    /**
     * Get log file path
     *
     * @return bool|string
     */
    private static function get_log_file() {
        $date = date( 'Y/m/d', current_time( 'timestamp', true ) );
        $path = HOLIDAYS_THEME_PATH . 'logs/' . $date;

        $file = false;
        if( wp_mkdir_p( $path ) ){
            $file = $path . '/log.log';
            if( ! file_exists( $file ) && ! fopen( $file, 'w+' ) ) {
                $file = false;
            }
        }
        return $file;
    }

    /**
     * Log message to file
     *
     * @param $message
     */
    public static function log( $message = "" ) {
        $log_file = self::get_log_file();
        if( $log_file ) {
            $message = sprintf(
                '%1$s - %2$s',
                date( 'Y-m-d H:i:s', current_time( 'timestamp', true ) ),
                $message . "\n"
            );

            @error_log( $message, 3, $log_file );
        }
    }

}