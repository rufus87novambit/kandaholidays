<?php

class Kanda_Response {

    private $code;
    private $data;
    private $message;

    public function __construct( $code, $data, $message = '' ) {
        $this->code = $code;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Get response code
     *
     * @return mixed
     */
    public function get_code() {
        return $this->code;
    }

    /**
     * Get response data
     *
     * @return mixed
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * Get response message
     *
     * @return string
     */
    public function get_message() {
        return $this->message;
    }

    /**
     * Check response validity
     *
     * @return bool
     */
    public function valid() {
        return ( $this->code == 200 );
    }

}