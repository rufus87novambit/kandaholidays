<?php

class Kanda_Request_Cache {

    private $table = 'service_request_cache';

    public function __construct() {}

    private function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . $this->table;
    }

    public function cache( $data, $hash, $provider_id, $user_id = null ) {

        $user_id = $user_id ? $user_id : get_current_user_id();

        global $wpdb;
        return $wpdb->insert(
            $this->get_table_name(),
            array(
                'hash'          => $hash,
                'user_id'       => $user_id,
                'provider_id'   => $provider_id,
                'data'          => $data
            ),
            array(
                '%s',
                '%d',
                '%d',
                '%s'
            )
        );
    }

    public function get( $hash, $provider_id, $user_id = null ) {
        global $wpdb;
        $user_id = $user_id ? $user_id : get_current_user_id();

        $query = $wpdb->prepare(
            "SELECT `data` FROM `%s` WHERE `hash` = '%s' AND `provider_id` = %d AND `user_id` = %d",
            $this->get_table_name(), $hash, $provider_id, $user_id
        );

        return $wpdb->get_row( $query );
    }

}