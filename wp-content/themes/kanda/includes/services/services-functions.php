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