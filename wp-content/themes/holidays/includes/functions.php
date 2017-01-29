<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if( isset( $_GET['server_test'] ) && $_GET['server_test'] ) {
    require_once( HOLIDAYS_INCLUDES_PATH . 'server-requirements.php' );
}
require_once( HOLIDAYS_INCLUDES_PATH . 'config.php' );
require_once( HOLIDAYS_INCLUDES_PATH . 'cron.php' );
require_once( HOLIDAYS_INCLUDES_PATH . 'helpers/class-mailer.php' );

/**
 * Get exchange data
 *
 * @return array
 */
function kanda_get_exchange() {
    // todo -> Set preferred_exchanges configurable from admin panel
    $preferred_exchanges = array( 'USD', 'RUB', 'EUR', 'GBP' );
    return array_intersect_key( kanda_get_exchange_rates(), array_flip( $preferred_exchanges ) );
}