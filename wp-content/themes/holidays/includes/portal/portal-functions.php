<?php

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