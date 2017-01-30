<?php

add_filter( 'cron_schedules', 'kanda_cron_schedules' );
function kanda_cron_schedules( $schedules ) {
//    $schedules['weekly'] = array(
//        'interval' => 604800,
//        'display' => __('Once Weekly')
//    );
    return $schedules;
}

add_action( 'kanda_exchange_rates', 'kanda_get_exchange_rates' );
function kanda_get_exchange_rates ( $force = false ) {

    $transient_name = 'kanda_exchange_rates';
    $rates = get_transient( $transient_name );

    if( $force || !$rates ) {

        $endpoint = 'http://api.cba.am/exchangerates.asmx?wsdl';
        $success = false;
        try {
            $client = new SoapClient($endpoint, array(
                'version' => SOAP_1_1
            ));
            $result = $client->__soapCall("ExchangeRatesLatest", array());
            if (is_soap_fault($result)) {
                $error = $result->faultstring;
            } else {
                $success = true;
                $data = $result->ExchangeRatesLatestResult;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        if ($success) {
            $rates = array();
            foreach( $data->Rates->ExchangeRate as $rate ) {
                $rates[ $rate->ISO ] = $rate;
            }
            $rates = json_decode( json_encode( $rates ), true );
            set_transient( 'kanda_exchange_rates', $rates, KH_Config::get( 'transient_expiration->exchange_update' ) );

        } else {

            $message = "Hi developer.\n";
            $message .= sprintf("There was an error geting rates from %s.\n with following details.", $endpoint);
            $message .= sprintf("Error: %s", $error);

            Kanda_Mailer()->send_developer_email( 'CBA problem', $message );
            Kanda_Log::log( $message );
        }

    }

    if( ! defined( 'DOING_CRON' ) ) {
        return $rates;
    }
}

/**
 * Hourly call for exchange update
 */
wp_schedule_event( time(), 'hourly', 'kanda_exchange_rates' );