<?php
/**
 * Kanda Theme admin functions
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

require_once KANDA_ADMIN_PATH . 'page-booking-search.php';

/**
 * Remove additional capabilities
 */
add_filter( 'additional_capabilities_display', 'kanda_additional_capabilities_display' );
function kanda_additional_capabilities_display(){
    return false;
}

/**
 * Add admin css files
 */
add_action( 'admin_enqueue_scripts', 'kanda_admin_enqueue_styles' );
function kanda_admin_enqueue_styles() {
    if( get_current_screen()->id == 'booking_page_search_bookings' ) {
        wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    }
    wp_enqueue_style( 'kanda-admin', KANDA_THEME_URL . 'css/admin.min.css', array(), null);
}

/**
 * Add admin css files
 */
add_action( 'admin_enqueue_scripts', 'kanda_admin_enqueue_scripts' );
function kanda_admin_enqueue_scripts() {
    if( get_current_screen()->id == 'booking_page_search_bookings' ) {
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }
    wp_enqueue_script( 'kanda-admin', KANDA_THEME_URL . 'js/admin.min.js', array('jquery'), null);
}

/******************************************* Add extra fields to users table *******************************************/

/**
 * Add additional columns to users table
 */
add_filter( 'manage_users_columns', 'kanda_manage_users_columns' );
function kanda_manage_users_columns( $columns ) {

    unset( $columns['posts'] );

    return array_merge( $columns, array(
        'company-name' => esc_html__( 'Company', 'kanda' ),
        'status' => esc_html__( 'Status', 'kanda' )
    ) );
}

/**
 * Add user custom columns content
 */
add_filter( 'manage_users_custom_column', 'kanda_manage_users_custom_column', 10, 3 );
function kanda_manage_users_custom_column( $value, $column_name, $user_id ) {

    $empty_value = '-----';

    switch ($column_name) {
        case 'status' :
            $value = $empty_value;
            if( user_can( (int)$user_id, Kanda_Config::get( 'agency_role' ) ) ) {
                $status = get_the_author_meta('profile_status', $user_id);
                if ($status) {
                    $icon = 'checkmark';
                    $color = 'success';
                } else {
                    $icon = 'cross';
                    $color = 'danger';
                }
                $value =  sprintf('<span class="color-%1$s row-%1$s"><i class="icon icon-%2$s"></i></span>', $color, $icon);
            }
            break;
        case 'company-name':
            $company_name = get_the_author_meta( 'company_name', $user_id );
            $value = $company_name ? $company_name : $empty_value;
            break;
        default:
    }

    return $value;
}

/******************************************* /end Add extra fields to users table *******************************************/

/**
 * Remove permalink edit box function single hotel admin edit
 */
add_filter( 'get_sample_permalink_html', 'kanda_hide_permalinks' );
function kanda_hide_permalinks( $html ){
    global $post;
    if( $post->post_type == 'hotel' ) {
        $html = preg_replace('~<span id="edit-slug-buttons".*</span>~Ui', '', $html);
    }
    return $html;
}

/**
 * Prevent agency role access to admin panel
 */
add_action( 'admin_init', 'kanda_prevent_agency_access_to_admin', 10, 1 );
function kanda_prevent_agency_access_to_admin() {
    if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return;
    }

    if ( current_user_can( Kanda_Config::get( 'agency_role' ) ) ) {
        $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
        exit( wp_redirect( $redirect ) );
    }
}