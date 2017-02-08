<?php
/**
 * Kanda Theme shortcodes
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Button shortcode
 *
 * @params
 *   type   => {home|login|register|forgot-password|forgot_password}
 *   label  => button label ( default is page title if possible )
 *   to     => button url ( default is page url if possible )
 *   target => button target {1|0}
 */
add_shortcode( 'button', 'kanda_shortcode_button' );
function kanda_shortcode_button( $atts, $content = '' ) {

    $atts = shortcode_atts( array(
        'type'      => false,
        'label'     => false,
        'to'        => false,
        'target'    => 0
    ), $atts, 'button' );

    $url = $atts['to'];
    $label = $atts['label'] ? $atts['label'] : false;
    $target = $atts['target'] ? 'target="_blank"' : '';

    if( $atts['type'] ) {

        switch ( $atts['type'] ) {
            case 'home':
                $url = esc_url( home_url( '/' ) );
                if( ! $label ) {
                    $label = esc_html__( 'Home', 'kanda' );
                }
                break;
            case 'login':
                if( $page_id = kanda_fields()->get_option( 'auth_page_login' ) ) {
                    $url = get_permalink( $page_id );
                    if( ! $label ) {
                        $page = get_post( $page_id );
                        $label = apply_filters( 'the_title', $page->post_title, $page_id );
                    }
                }

                break;
            case 'register':
                if( $page_id = kanda_fields()->get_option( 'auth_page_login' ) ) {
                    $url = get_permalink( $page_id );
                    if( ! $label ) {
                        $page = get_post( $page_id );
                        $label = apply_filters( 'the_title', $page->post_title, $page_id );
                    }
                }

                break;
            case 'forgot-password':
            case 'forgot_password':
                if( $page_id = kanda_fields()->get_option( 'auth_page_login' ) ) {
                    $url = get_permalink( $page_id );
                    if( ! $label ) {
                        $page = get_post( $page_id );
                        $label = apply_filters( 'the_title', $page->post_title, $page_id );
                    }
                }

                break;
        }
    }

    if( $label ) {
        return sprintf( '<a href="%1$s" class="btn" %2$s>%3$s</a>', ( $url ? $url : '#' ), $target, $label );
    }


    return "foo = {$atts['foo']}";
}