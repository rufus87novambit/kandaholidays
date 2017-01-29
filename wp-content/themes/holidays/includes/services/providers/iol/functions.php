<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die('No direct script access allowed');
}

// init
add_action( 'kanda/providers/init', 'iol_init', 10 );
function iol_init() {

    define( 'IOL_LOADED', true );
    define ('IOL_EOL', "\n");
    define( 'IOL_PATH', trailingslashit( __DIR__ ) );
    define( 'IOL_CORE_PATH', trailingslashit( IOL_PATH . 'core' ) );
    define( 'IOL_CONFIG_PATH', trailingslashit( IOL_PATH . 'config' ) );

    require_once( IOL_CONFIG_PATH . 'config.php' );
    require_once( IOL_CORE_PATH . 'class-iol.php' );
    require_once( IOL_CORE_PATH . 'class-helper.php' );
    require_once( IOL_CORE_PATH . 'class-client.php' );
    require_once( IOL_CORE_PATH . 'class-hotels.php' );
}