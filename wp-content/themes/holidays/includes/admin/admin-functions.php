<?php

require_once( KH_INCLUDES_PATH . 'fields.php' );
require_once( KH_INCLUDES_PATH . 'config.php' );
require_once( KH_INCLUDES_PATH . 'helpers/class-mailer.php' );

add_filter( 'additional_capabilities_display', function(){ return false; } );

/**
 * Add admin css files
 */
add_action( 'admin_enqueue_scripts', 'kanda_admin_enqueue_styles' );
function kanda_admin_enqueue_styles() {
    wp_enqueue_style( 'kanda-admin', KH_THEME_URL . 'css/admin.min.css', array(), null);
}

/**
 * Add admin css files
 */
add_action( 'admin_enqueue_scripts', 'kanda_admin_enqueue_scripts' );
function kanda_admin_enqueue_scripts() {
    wp_enqueue_script( 'kanda-admin', KH_THEME_URL . 'js/admin.min.js', array('jquery'), null);
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
            if( user_can( (int)$user_id, KH_Config::get( 'agency_role' ) ) ) {
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