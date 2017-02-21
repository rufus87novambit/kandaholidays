<?php

class Kanda_Request_Helper {

    /**
     * Get request hash
     *
     * @param $request
     * @return string
     */
    public static function get_request_hash ( $request ) {
        return substr( md5( serialize( $request ) ), 0, 8 );
    }

}