<?php

final class Kanda_Request {

    private $url;
    private $request;
    private $response;
    private $hash;

    public function __construct( $url, $data = array() ) {
        $this->url = $url;
        $this->request = $data;
        $this->hash = md5( is_array( $data ) ? json_encode( $data ) : $data );
    }

    public function run( $args ) {
        $args = array_merge( $args, array( 'body' => $this->request ) );
        $this->response = wp_remote_post( $this->url, $args );

        return $this;
    }

    public function get_request() {
        return $this->request;
    }

    public function get_response() {
        return $this->response;
    }

    public function get_hash() {
        return $this->hash;
    }

}