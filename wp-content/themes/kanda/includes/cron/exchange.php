<?php

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

	update_option( 'kanda_exchange_rates', $rates );
	update_option( 'kanda_exchange_last_update', current_time( 'mysql' ) );

} else {

	$message = "Hi developer.\n";
	$message .= sprintf("There was an error getting rates from %s.\n with following details.", $endpoint);
	$message .= sprintf("Error: %s", $error);

	kanda_mailer()->send_developer_email( 'CBA problem', $message );
	kanda_logger()->log( $message );
}