<?php

/**
 * Include Request Helper
 */
require_once( trailingslashit( KANDA_SERVICES_PATH . 'core' ) . 'class-kanda-request-helper.php' );

/**
 * Include Request Response
 */
require_once( trailingslashit( KANDA_SERVICES_PATH . 'core' ) . 'class-kanda-request-response.php' );

/**
 * Include Request Cache
 */
require_once( trailingslashit( KANDA_SERVICES_PATH . 'core' ) . 'class-kanda-request-cache.php' );

/**
 * Include IOL
 */
require_once( trailingslashit( trailingslashit( KANDA_SERVICES_PATH . 'providers' ) . 'iol' ) . 'functions.php' );

Kanda_Config::$providers = apply_filters( 'kanda/providers', array() );

do_action( 'kanda/providers/init' );

// todo remove
//$criteria = array(
//    'search-criteria' => array(
//        'room-configuration' => array(
//            'room' => array(
//                'adults' => 2,
//                'child' => array(
//                    'age' => 16
//                ),
//                'room-configuration-id' => 1
//            )
//        ),
//        'start-date' => IOL_Helper::convert_date('15/03/2017', 'd/m/Y'),
//        'end-date' => IOL_Helper::convert_date('18/03/2017', 'd/m/Y'),
//        'city' => 'DXB',
//        'hotel-name' => 'ATLANTIS',
//        'include-on-request' => true,
//        'optional-supplement-y-n' => true,
//        'cancellation-policy' => false,
//        'include-hotel-data' => false,
//        'include-rate-details' => false
//    )
//);
//$response = provider_iol()->request()->search_hotels( $criteria );
//$response = provider_iol()->request()->search_by_hash( 'dcc76543' );
//
//echo '<pre>'; var_dump(
//    $response->valid(),
//    $response->get_code(),
//    $response->get_message(),
//    $response->get_data()
//); die;