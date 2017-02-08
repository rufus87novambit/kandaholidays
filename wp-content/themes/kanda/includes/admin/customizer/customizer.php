<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}
include_once( KANDA_CUSTOMIZER_PATH . 'lib/kirki.php' );
include_once( KANDA_CUSTOMIZER_PATH . 'class-kanda-customizer.php' );
include_once( KANDA_CUSTOMIZER_PATH . 'panels.php' );

$customizer = kanda_customizer();
$panels = kanda_get_panels();
$customizer->add_panels();

$customizer->run();