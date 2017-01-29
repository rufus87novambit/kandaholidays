<?php
/**
 * Template Name: Front Layout
 */

$pagename = get_query_var( 'pagename' );
if( $pagename ) {
    $pagename = strtr( $pagename, array( '-' => '', '_' => '' ) );
}

if( ! in_array ( $pagename, KH_Config::get( 'front_allowed_actions' ) ) ) {
    $pagename = 'login';
}

do_action( sprintf( 'kanda/%s', $pagename ) );

?>