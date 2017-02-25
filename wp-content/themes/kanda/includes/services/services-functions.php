<?php

if( ! defined( 'KANDA_SERVICES_CORE_PATH' ) ) {
    define( 'KANDA_SERVICES_CORE_PATH', trailingslashit( KANDA_SERVICES_PATH . 'core' ) );
}

if( ! defined( 'KANDA_SERVICES_PROVIDERS_PATH' ) ) {
    define( 'KANDA_SERVICES_PROVIDERS_PATH', trailingslashit( KANDA_SERVICES_PATH . 'providers' ) );
}

/***************************************** Load Service Provider Core Files *****************************************/
/**
 * Include Service Provider
 */
require_once( KANDA_SERVICES_CORE_PATH . 'class-kanda-service-provider.php' );

/**
 * Include Request Response
 */
require_once( KANDA_SERVICES_CORE_PATH . 'class-kanda-service-cache.php' );

/**
 * Include Request Cache
 */
require_once( KANDA_SERVICES_CORE_PATH . 'class-kanda-service-response.php' );

/************************************** /end Load Service Provider Core Files ***************************************/

/**
 * Include IOL
 */
require_once( trailingslashit( KANDA_SERVICES_PROVIDERS_PATH . 'iol' ) . 'loader.php' );


/**
 * Register providers
 */
Kanda_Config::$providers = apply_filters( 'kanda/providers', array() );

/**
 * Providers wake up hook
 */
do_action( 'kanda/providers/init' );