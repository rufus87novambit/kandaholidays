<?php

//$success = false;
//try {
//    $client = new SoapClient( 'http://api.cba.am/exchangerates.asmx?wsdl' , array(
//        'version' => SOAP_1_1
//    ));
//    $result = $client->__soapCall("ExchangeRatesLatest", array());
//    if ( is_soap_fault( $result ) ) {
//        $error = $result->faultstring;
//    } else {
//        $success = true;
//        $data = $result->ExchangeRatesLatestResult;
//    }
//} catch ( Exception $e ) {
//    $error = $e->getMessage();
//}
//if ( $success ) {
//
//    $rates = $data->Rates->ExchangeRate;
//    echo '<pre>'; var_dump( $rates ); die;
//
//} else {
//    wp_mail( KH_Config::get( 'developer_email' ), get_bloginfo( 'sitename' ), 'message' );
//}
//die;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( ! defined( 'HOLIDAYS_THEME_PATH' ) ) {
    define( 'HOLIDAYS_THEME_PATH', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'HOLIDAYS_THEME_URL' ) ) {
    define( 'HOLIDAYS_THEME_URL', trailingslashit( get_template_directory_uri() ) );
}

if ( ! defined( 'HOLIDAYS_INCLUDES_PATH' ) ) {
    define( 'HOLIDAYS_INCLUDES_PATH', HOLIDAYS_THEME_PATH . 'includes/' );
}
if ( ! defined( 'HOLIDAYS_INCLUDES_URL' ) ) {
    define( 'HOLIDAYS_INCLUDES_URL', HOLIDAYS_THEME_URL . 'includes/' );
}

if ( ! defined( 'HOLIDAYS_SERVICES_PATH' ) ) {
    define( 'HOLIDAYS_SERVICES_PATH', HOLIDAYS_INCLUDES_PATH . 'services/' );
}
if ( ! defined( 'HOLIDAYS_SERVICES_URL' ) ) {
    define( 'HOLIDAYS_SERVICES_URL', HOLIDAYS_INCLUDES_URL . 'services/' );
}

if ( ! defined( 'HOLIDAYS_ADMIN_PATH' ) ) {
    define( 'HOLIDAYS_ADMIN_PATH', HOLIDAYS_INCLUDES_PATH . 'admin/' );
}
if ( ! defined( 'HOLIDAYS_ADMIN_URL' ) ) {
    define( 'HOLIDAYS_ADMIN_URL', HOLIDAYS_INCLUDES_URL . 'admin/' );
}

if ( ! defined( 'HOLIDAYS_FRONT_PATH' ) ) {
    define( 'HOLIDAYS_FRONT_PATH', HOLIDAYS_INCLUDES_PATH . 'front/' );
}
if ( ! defined( 'HOLIDAYS_FRONT_URL' ) ) {
    define( 'HOLIDAYS_FRONT_URL', HOLIDAYS_INCLUDES_URL . 'front/' );
}

/**
 * Load common resources (required by both, admin and front, contexts).
 */
require_once( HOLIDAYS_INCLUDES_PATH . 'functions.php' );

/**
 * Load context resources.
 */
if ( is_admin() ) {
    require_once( HOLIDAYS_INCLUDES_PATH . 'admin/admin-functions.php' );
} else {
    require_once( HOLIDAYS_INCLUDES_PATH . 'front/front-functions.php' );
}