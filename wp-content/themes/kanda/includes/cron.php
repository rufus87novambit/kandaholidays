<?php
/**
 * Add custom cron intervals
 */
add_filter( 'cron_schedules', 'kanda_cron_schedules' );
function kanda_cron_schedules( $schedules ) {
//    $schedules['weekly'] = array(
//        'interval' => 604800,
//        'display' => __('Once Weekly')
//    );
    return $schedules;
}

/**
 * Get exchange rates from cache / cba
 */
add_action( 'kanda_exchange_rates', 'kanda_get_exchange_rates' );


/**
 * Hourly call for exchange update
 */
if ( ! wp_next_scheduled( 'kanda_exchange_rates' ) ) {
    wp_schedule_event( time(), 'hourly', 'kanda_exchange_rates');
}