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

add_action( 'init', 'kanda_register_session' );
function kanda_register_session() {
    kanda_start_session();
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
 * Start session if it is not started
 */
function kanda_start_session() {
    if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Generate a random string
 *
 * @param int $length
 * @return string
 */
function kanda_generate_random_string( $length = 10 ) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get user avatar image
 *
 * @param bool|false $user_id
 * @param string $size
 * @param array $atts
 * @return string
 */
function kanda_get_user_avatar( $user_id = false, $size = 'user-avatar', $atts = array() ) {
    if( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    $avatar = kanda_get_user_avatar_url( $user_id, $size );
    $company_name = kanda_get_user_meta( $user_id, 'company_name' );
    $img_atts = '';

    foreach( $atts as $attr => $value ) {
        $img_atts .= sprintf( '%1$s="%2$s"', $attr, $value );
    }

    return sprintf( '<img src="%1$s" %2$s alt="%3$s"/>', $avatar, $img_atts, $company_name );
}

/**
 * Get user avatar url
 *
 * @param bool|false $user_id
 * @param string $size
 * @return mixed
 */
function kanda_get_user_avatar_url( $user_id = false, $size = 'user-avatar' ) {

    if( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    static $avatars;
    $avatars = $avatars ? $avatars : array();

    if( ! isset( $avatars[ $user_id ][ $size ] ) ) {
        $avatar_id = kanda_get_user_avatar_id( $user_id );
        if ( $avatar_id ) {
            list( $url ) = wp_get_attachment_image_src( $avatar_id, $size );
        } else {
            $url = kanda_get_theme_option('other_default_avatar');
        }
        $avatars[ $user_id ][ $size ] = $url;
    }

    return $avatars[ $user_id ][ $size ];
}

function kanda_get_user_avatar_id( $user_id = false ) {
    if( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    $avatar_id = kanda_get_user_meta($user_id, 'avatar');
    if ( $avatar_id && ('publish' == get_post_status( $avatar_id ) ) ) {
        return $avatar_id;
    }
    return false;

}

function kanda_user_has_avatar( $user_id = false ) {

    if( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    return (bool) kanda_get_user_avatar_id( $user_id );

}

/**
 * Get enabled currencies
 *
 * @return array
 */
function kanda_get_active_currencies() {
    $currencies = kanda_get_theme_option( 'exchange_active_currencies' );
    $currencies[] = 'AED';

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

            update_option( 'kanda_exchange_last_update', current_time( 'mysql' ) );

        } else {

            $message = "Hi developer.\n";
            $message .= sprintf("There was an error getting rates from %s.\n with following details.", $endpoint);
            $message .= sprintf("Error: %s", $error);

            kanda_mailer()->send_developer_email( 'CBA problem', $message );
            kanda_logger()->log( $message );
        }

    }

    return $rates;
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
    $exchange_rates = kanda_get_exchange_rates();

    kanda_logger()->log( json_encode( $exchange_rates ) );

    $rates = array_keys( $exchange_rates );
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
 * Show notification
 *
 * @param $notification
 */
function kanda_show_notification( $notification = array() ) {
    if( empty( $notification ) ) {
        $notification = isset( $_SESSION['kanda_notification'] ) ? (array)$_SESSION['kanda_notification'] : array();
        $_SESSION['kanda_notification'] = array();
    }

    if( isset( $notification['type'] ) && $notification['type'] && isset( $notification['message'] ) && $notification['message'] ) {
        switch ($notification['type']) {
            case 'success':
                $icon = '<i class="icon icon-checkmark"></i>';
                break;
            case 'info':
                $icon = '<i class="icon icon-info"></i>';
                break;
            case 'warning':
                $icon = '<i class="icon icon-warrning"></i>';
                break;
            case 'danger':
                $icon = '<i class="icon icon-cross"></i>';
                break;
            default:
                $icon = '';
        }
        printf('<div class="flash-message alert alert-%1$s" role="alert"><button class="alert-close-btn icon-cross2"></button> %2$s %3$s</div>', $notification['type'], $icon, $notification['message']);
    }
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
 * @param array $params
 */
function kanda_to( $name, $params = array() ) {
    if( $name == '404' ) {
        global $wp_query;

        $wp_query->set_404();
        status_header( 404 );
        get_template_part( '404' );
        exit();
    }
    $url = kanda_url_to( $name, $params );
    if( $url ) {
        wp_redirect( $url ); exit();
    }
}

/**
 * Get url to
 *
 * @param $name
 * @param string $params
 * @return bool|false|string|void
 */
function kanda_url_to( $name, $params = array() ) {
    switch( $name ) {
        case 'home';
            $url = home_url();
            break;
        case 'login':
            $url = home_url( '/login' );
            break;
        case 'register':
            $url = home_url( '/register' );
            break;
        case 'forgot-password':
            $url = home_url( '/forgot' );
            break;
        case 'reset-password':
            $url = home_url( '/reset' );
            break;
        case 'profile':
            $url = home_url( '/profile' );
            break;
        case 'hotels':
            $url = home_url( '/hotels' );
            break;
        case 'booking':
            $url = home_url( '/booking' );
            break;
        default:
            $url = false;
    }

    if( $url && ! empty( $params ) ) {
        $url .= '/' . implode( '/', $params );
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
 * @param bool|false $force
 * @return mixed|null
 */
function kanda_get_post_meta( $post_id, $key = '', $force = false ) {

    $cached_posts = &kanda_get_cached_posts();
    if( ! isset( $cached_posts[ $post_id ] ) || $force ) {

        $cached_posts[ $post_id ] = isset( $cached_posts[ $post_id ] ) ? $cached_posts[ $post_id ] : array();

        $metas = (array)get_post_meta( $post_id );

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
function kanda_get_user_meta( $user_id, $key = '', $force = false ) {

    $cached_users = &kanda_get_cached_users();
    if( ! isset( $cached_users[ $user_id ] ) || $force ) {

        $cached_users[ $user_id ] = isset( $cached_users[ $user_id ] ) ? $cached_users[ $user_id ] : array();

        $metas = (array)get_user_meta( $user_id );

        $meta_cache = array();
        foreach( $metas as $meta_key => $meta_value ) {
            $meta_cache[ $meta_key ] = maybe_unserialize( $meta_value[0] );
        }
        $cached_users[ $user_id ] = $meta_cache;
    }

    if( ! $key ) {
        return $cached_users[ $user_id ];
    }

    return isset( $cached_users[ $user_id ][ $key ] ) ? $cached_users[ $user_id ][ $key ] : null;
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
function kanda_get_term_meta( $term_id, $key = '', $force = false ) {

    $cached_terms = &kanda_get_cached_terms();
    if( ! isset( $cached_terms[ $term_id ] ) || $force ) {

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

/**
 * Upload a file
 *
 * @param $key
 * @param int $parent_post_id
 * @return array
 */
function kanda_upload_file( $key, $parent_post_id = 0 ) {

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    $upload = wp_handle_upload( $_FILES[ $key ], array( 'test_form' => false ) );

    $is_valid = true;
    $attach_id = 0;
    $message = '';
    if ( $upload && ! isset( $upload['error'] ) ) {

        /* Check the type of file. We'll use this as the 'post_mime_type'. */
        $filetype = wp_check_filetype( basename( $upload['file'] ), null );

        /* Get the path to the upload directory. */
        $wp_upload_dir = wp_upload_dir();

        /* Prepare an array of post data for the attachment. */
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename( $upload['file'] ),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        /* Insert the attachment. */
        $attach_id = wp_insert_attachment( $attachment, $upload['file'], 0, true );

        if( $attach_id ) {
            /* Make sure that this file is included, as wp_generate_attachment_metadata() depends on it. */
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            /* Generate the metadata for the attachment, and update the database record. */
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            $is_valid = wp_update_attachment_metadata($attach_id, $attach_data);
        } else {
            $is_valid = false;
            $message = $attach_id->get_error_message();
        }

    } else {
        $is_valid = false;
        $message = $upload['error'];
    }

    return $is_valid ? array( 'is_valid' => $is_valid, 'attachment_id' => $attach_id ) : array( 'is_valid' => $is_valid, 'message' => $message );
}

/**
 * Generate price for hotel
 *
 * @param $price
 * @param $hotel_code
 * @param $exit_currency
 * @param $input_currency
 * @param int $multiply_index
 * @return string
 */
function kanda_generate_price( $price, $hotel_code, $exit_currency, $input_currency, $multiply_index = 1 ) {

    if( $input_currency != $exit_currency ) {
        $converted_price = kanda_covert_currency_to( $price, $exit_currency, $input_currency );

        $price = $converted_price['amount'];
        $currency = $converted_price['currency'];
    } else {
        $currency = $exit_currency;
    }

    if( current_user_can( Kanda_Config::get( 'agency_role' ) ) ) {
        $additional_fee = kanda_get_hotel_additional_fee( $hotel_code );
        $price += $additional_fee * $multiply_index;
    }

    return sprintf('%1$s %2$s', number_format( $price, 2 ), $currency );

}

function kanda_exchange_rate( $amount, $hotel_code, $input_currency, $exit_currency ) {
    $additional_fee = kanda_get_hotel_additional_fee( $hotel_code );
}

/**
 * Get specific hotel additional fee
 *
 * @param $hotel_code
 * @return float
 */
function kanda_get_hotel_additional_fee( $hotel_code ) {
    global $wpdb;
    $query = "SELECT `pm1`.`post_id`, `pm2`.`meta_value` AS `rating`
                FROM `{$wpdb->postmeta}` AS `pm1`
                LEFT JOIN `{$wpdb->postmeta}` AS `pm2` ON `pm2`.`post_id` = `pm1`.`post_id` AND `pm2`.`meta_key` = 'hotelstarrating'
                WHERE `pm1`.`meta_key` = 'hotelcode' AND `pm1`.`meta_value` = '{$hotel_code}'";

    $hotel_metadata = $wpdb->get_row( $query );

    $additional_fee = 0;
    if( $hotel_metadata ) {
        $additional_fee = kanda_fields()->get_hotel_additional_fee( $hotel_metadata->post_id );
        if( ! $additional_fee ) {
            $rating = absint( $hotel_metadata->rating );
            if( $rating > 5 || ! $rating ) {
                $rating = 0;
            }
            $option_name = sprintf( 'pricing_additional_fee_for_%d_star_hotel', $rating );
            $additional_fee = kanda_get_theme_option( $option_name );
        }
    }

    return floatval( $additional_fee );
}

/**
 * Convert currency
 *
 * @param $amount
 * @param $exit_currency
 * @param string $input_currency
 * @return array
 */
function kanda_covert_currency_to( $amount, $exit_currency, $input_currency = 'USD' ) {
    $exchange = kanda_get_exchange();

    if( array_key_exists( $exit_currency, $exchange ) ) {
        $amount = ( $exchange[ $input_currency ][ 'Rate' ] * $amount ) / $exchange[ $exit_currency ][ 'Rate' ];
        $currency = $exit_currency;
    } elseif( $exit_currency == 'AMD' ) {
        $currency = $exit_currency;
        $amount = $exchange[ $input_currency ][ 'Rate' ] * $amount;
    } else {
        $currency = $input_currency;
    }

    return array(
        'amount'    => $amount,
        'currency'  => $currency
    );
}

/**
 * Insert an attachment from an URL address.
 *
 * @param $url
 * @param null $post_id
 * @return bool|int|WP_Error
 */
function kanda_insert_attachment_from_url( $url, $post_id = null) {

    if( ! class_exists( 'WP_Http' ) )
        include_once( ABSPATH . WPINC . '/class-http.php' );

    $http = new WP_Http();
    $response = $http->request( $url );
    if( $response['response']['code'] != 200 ) {
        return false;
    }

    $upload = wp_upload_bits( basename($url), null, $response['body'] );
    if( !empty( $upload['error'] ) ) {
        return false;
    }

    $file_path = $upload['file'];
    $file_name = basename( $file_path );
    $file_type = wp_check_filetype( $file_name, null );
    $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
    $wp_upload_dir = wp_upload_dir();

    $post_info = array(
        'guid'				=> $wp_upload_dir['url'] . '/' . $file_name,
        'post_mime_type'	=> $file_type['type'],
        'post_title'		=> $attachment_title,
        'post_content'		=> '',
        'post_status'		=> 'inherit',
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $post_info, $file_path, $post_id );

    // Include image.php
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id,  $attach_data );

    return $attach_id;

}

/**
 * Get cropped image src
 *
 * @param $url
 * @param array $args
 * @return string
 */
function kanda_get_cropped_image_src( $image_url, $args = array() ) {

    $args = wp_parse_args( $args, array(
        'width'         => 150,
        'height'        => 150,
        'src'           => $image_url,
        'crop-to-fit'   => '',
    ) );

    $url = KANDA_INCLUDES_URL . 'vendor/cimage/webroot/imgp.php';

    return add_query_arg( $args, $url );

}

/**
 * Get placeholder image for hotel
 * @return string
 */
function kanda_get_hotel_placeholder_image() {
    return KANDA_THEME_URL . 'images/back/hotel-placeholder.jpg';
}

/**
 * Get loading popup HTML
 * @return string
 */
function kanda_get_loading_popup() {
    return '<div id="loading-popup" class="loading-popup mfp-hide"></div>';
}

/**
 * Get nationalities array
 * @return array
 */
function kanda_get_nationality_choices() {
    return array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia, Plurinational State of',
        'BQ' => 'Bonaire, Sint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, the Democratic Republic of the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Curaçao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea, Republic of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia, the former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Réunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthélemy',
        'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin (French part)',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SX' => 'Sint Maarten (Dutch part)',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TJ' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TO' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela, Bolivarian Republic of',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'WF' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );
}

/**
 * Get error popup HTML
 * @return string
 */
function kanda_get_error_popup() {
    return sprintf( '<div id="error-popup" class="static-popup text-center mfp-hide"><h2 class="text-center">%s</h2><div class="popup-content"></div></div>', __( 'Error', 'kanda' ) );
}

function kanda_get_paged(){
    global $paged;
    if( is_front_page() ){
        $paged = absint( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
    }else{
        if( get_query_var( 'paged' ) ) {
            $paged = absint( get_query_var( 'paged' ) );
        } elseif( get_query_var( 'page' ) ) {
            $paged = absint( get_query_var( 'page' ) );
        } else {
            $paged = 1;
        }
    }
    return $paged;
}

function kanda_get_single_hotel_url( $args ) {
    return add_query_arg(
        array(
            'start_date'    => $args['start_date'],
            'end_date'      => $args['end_date']
        ),
        kanda_url_to( 'hotels', array( 'view', $args['hotelcode'] ) )
    );
}