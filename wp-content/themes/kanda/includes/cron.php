<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}
/**
 * Map
 * 1. Exchange
 */

/**************************************************** 1. Exchange ****************************************************/
/**
 * Add custom cron intervals
 */
add_filter( 'cron_schedules', 'kanda_cron_schedules' );
function kanda_cron_schedules( $schedules ) {
    $schedules['kanda_exchange'] = array(
        'interval' => kanda_get_theme_option( 'exchange_update_interval' ),
        'display' => esc_html__( 'Exchange update interval', 'kanda' )
    );
    return $schedules;
}

/**
 * Get exchange rates from cache / cba
 */
add_action( 'kanda_exchange_rates', 'kanda_cron_get_exchange_rates' );
function kanda_cron_get_exchange_rates() {
    kanda_get_exchange_rates();
}

/**
 * Call for exchange update
 */
if ( ! wp_next_scheduled( 'kanda_exchange_rates' ) ) {
    wp_schedule_event( time(), 'kanda_exchange', 'kanda_exchange_rates');
}

/**************************************************** /end Exchange ****************************************************/