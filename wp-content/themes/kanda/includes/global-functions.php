<?php
/**
 * Kanda Theme global functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Deny role access
 */
function kanda_deny_user_access( $role ) {
    if( is_user_logged_in() && current_user_can( $role ) ) {
        kanda_to( 'home' );
    }
}

/**
 * Deny guest access
 */
function kanda_deny_guest_access() {
    if( ! is_user_logged_in() ) {
        kanda_to( 'login' );
    }
}

/**
 * Generate a random string
 *
 * @param int $length
 * @return string
 */
function generate_random_string( $length = 10 ) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get enabled currencies
 *
 * @return array
 */
function kanda_get_active_currencies() {
    $currencies = kanda_get_theme_option( 'exchange_active_currencies' );
    return $currencies;
}

/**
 * Get exchange rates from cache / cba
 */
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
            set_transient( 'kanda_exchange_rates', $rates, kanda_get_theme_option( 'exchange_update_interval' ) * HOUR_IN_SECONDS );

        } else {

            $message = "Hi developer.\n";
            $message .= sprintf("There was an error geting rates from %s.\n with following details.", $endpoint);
            $message .= sprintf("Error: %s", $error);

            kanda_mailer()->send_developer_email( 'CBA problem', $message );
            kanda_logger()->log( $message );
        }

    }

    if( ! defined( 'DOING_CRON' ) ) {
        return $rates;
    }
}

/**
 * Get required exchange rates
 *
 * @return array
 */
function kanda_get_exchange() {
    $preferred_exchanges = kanda_get_active_currencies();
    return array_intersect_key( kanda_get_exchange_rates(), array_flip( $preferred_exchanges ) );
}

/**
 * Get available currencies ISO codes
 * @return array
 */
function kanda_get_currency_iso_array() {
    $rates = array_keys( kanda_get_exchange_rates() );
    $rates[] = 'AMD';

    sort( $rates );

    return apply_filters( 'kanda/available_currencies', array_combine( $rates, $rates ) );
}

/**
 * Get template variables
 *
 * @param bool|false $type
 * @return array
 */
function kanda_get_page_template_variables( $type = false ) {

    $is_user = is_user_logged_in();

    $return = array(
        'header' => $is_user ? null : 'guests',
        'footer' => $is_user ? null : 'guests',
    );
    switch ( $type ) {
        case '404':
            $title = '';
            $content = '';

            $field_name = sprintf( '404_page_%s_page', ( $is_user ? 'user' : 'guest' ) );
            $not_found_page_id = absint( kanda_get_theme_option( $field_name ) );

            if( $not_found_page_id && ( $not_found_page = get_post( $not_found_page_id ) ) ) {
                $title = apply_filters( 'the_title', $not_found_page->post_title, $not_found_page_id );
                $content = apply_filters( 'the_content', $not_found_page->post_content );
            }
            $return = array_merge( $return, array(
                'title'     => $title,
                'content'   => $content
            ) );
            break;
    }

    return $return;

}

/**
 * Get sidebars configuration
 *
 * @return array
 */
function kanda_get_sidebars() {
    return array(
        array(
            'name'          => esc_html__( 'Default', 'kanda' ),
            'id'            => 'default-sidebar',
            'description'   => esc_html__( 'The widgets added here will appear on all the pages.', 'kanda' ),
        )
    );
}

/**
 * Redirect to
 *
 * @param $name
 */
function kanda_to( $name ) {
    if( $name == '404' ) {
        global $wp_query;

        $wp_query->set_404();
        status_header( 404 );
        get_template_part( '404' );
        exit();
    }
    $url = kanda_url_to( $name );
    if( $url ) {
        wp_redirect( $url ); exit();
    }
}

/**
 * Get url to
 *
 * @param $name
 * @return bool|false|string|void
 */
function kanda_url_to( $name ) {
    switch( $name ) {
        case 'home';
            $url = home_url();
            break;
        case 'login':
            $url = get_permalink( kanda_get_theme_option( 'auth_page_login' ) );
            break;
        case 'register':
            $url = get_permalink( kanda_get_theme_option( 'auth_page_register' ) );
            break;
        case 'forgot-password':
            $url = get_permalink( kanda_get_theme_option( 'auth_page_forgot' ) );
            break;
        case 'reset-password':
            $url = get_permalink( kanda_get_theme_option( 'auth_page_reset' ) );
            break;
        default:
            $url = false;
    }

    return $url;
}

/**
 * Get Cached Posts
 * @return array
 */
function &kanda_get_cached_posts() {
    static $posts;

    if( is_null( $posts ) ) {
        $posts = array();
    }

    return $posts;
}

/**
 * Optimized way to get post meta
 *
 * @param $post_id
 * @param string $key
 * @return mixed|null
 */
function kanda_get_post_meta( $post_id, $key = '' ) {

    $cached_posts = &kanda_get_cached_posts();
    if( ! isset( $cached_posts[ $post_id ] ) ) {

        $cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();

        $metas = (array)get_term_meta( $post_id );

        $meta_cache = array();
        foreach( $metas as $meta_key => $meta_value ) {
            $meta_cache[ $meta_key ] = $meta_value[0];
        }
        $cached_posts[ $post_id ] = $meta_cache;
    }

    if( ! $key ) {
        return $cached_posts[ $post_id ];
    }

    return isset( $cached_posts[ $post_id ][ $key ] ) ? maybe_unserialize( $cached_posts[ $post_id ][ $key ] ) : null;
}

/**
 * Get Cached Users
 * @return array
 */
function &kanda_get_cached_users() {
    static $users;

    if( is_null( $users ) ) {
        $users = array();
    }

    return $users;
}

/**
 * Optimized way to get user meta
 *
 * @param $user_id
 * @param string $key
 * @return mixed|null
 */
function kanda_get_user_meta( $user_id, $key = '' ) {

    $cached_users = &kanda_get_cached_posts();
    if( ! isset( $cached_users[ $user_id ] ) ) {

        $cached_users[ $user_id ] = isset( $cached_users[ $user_id ] ) ? $cached_users[ $user_id ] : array();

        $metas = (array)get_term_meta( $user_id );

        $meta_cache = array();
        foreach( $metas as $meta_key => $meta_value ) {
            $meta_cache[ $meta_key ] = $meta_value[0];
        }
        $cached_users[ $user_id ] = $meta_cache;
    }

    if( ! $key ) {
        return $cached_users[ $user_id ];
    }

    return isset( $cached_users[ $user_id ][ $key ] ) ? maybe_unserialize( $cached_users[ $user_id ][ $key ] ) : null;
}

/**
 * Get Cached Terms
 * @return array
 */
function &kanda_get_cached_terms() {
    static $terms;

    if( is_null( $terms ) ) {
        $terms = array();
    }

    return $terms;
}

/**
 * Optimized way to get term meta
 *
 * @param $term_id
 * @param string $key
 * @return mixed|null
 */
function kanda_get_term_meta( $term_id, $key = '' ) {

    $cached_terms = &kanda_get_cached_terms();
    if( ! isset( $cached_terms[ $term_id ] ) ) {

        $cached_terms[ $term_id ] = isset( $cached_terms[ $term_id ] ) ? $cached_terms[ $term_id ] : array();

        $metas = (array)get_term_meta( $term_id );

        $meta_cache = array();
        foreach( $metas as $meta_key => $meta_value ) {
            $meta_cache[ $meta_key ] = $meta_value[0];
        }
        $cached_terms[ $term_id ] = $meta_cache;
    }

    if( ! $key ) {
        return $cached_terms[ $term_id ];
    }

    return isset( $cached_terms[ $term_id ][ $key ] ) ? maybe_unserialize( $cached_terms[ $term_id ][ $key ] ) : null;
}