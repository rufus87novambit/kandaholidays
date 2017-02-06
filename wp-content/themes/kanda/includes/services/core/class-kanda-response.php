<?php

class Kanda_Response {

    private $request;
    private $code;
    private $body;

    public function __construct( Kanda_Request $request ) {
        $this->request = $request;

        $response = $request->get_response();
        $this->code = wp_remote_retrieve_response_code( $response );
        $this->body = wp_remote_retrieve_response_code( $response );
    }

    public function get_code() {
        return $this->code;
    }

    public function get_body() {
        return $this->body;
    }



}