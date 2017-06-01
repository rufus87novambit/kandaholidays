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
require_once KANDA_ADMIN_PATH . 'class-notification.php';

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

    if ( ! ( current_user_can( 'administrator' ) || kanda_is_reservator() ) ) {
        $redirect = home_url( '/' );
        exit( wp_redirect( $redirect ) );
    }
}

/**
* Column managment for 'hotel' post type
*/
add_filter( 'manage_hotel_posts_columns', 'kanda_manage_custom_edit_hotel_columns' );
function kanda_manage_custom_edit_hotel_columns($columns) {
	unset( $columns['title'] );
    unset( $columns['author'] );
    unset( $columns['date'] );
	
	$columns['title'] = __( 'Hotel Name', 'kanda' );
	if( ! kanda_is_reservator() ) {
		$columns['additional_fee'] = __( 'Additional Fee ( Per Night )', 'kanda' );
	}
    $columns['city'] = __( 'City', 'kanda' );

    return $columns;
}

/**
* Column content for 'hotel' post type
*/
add_action( 'manage_hotel_posts_custom_column' , 'kanda_render_hotel_custom_column', 10, 2 );
function kanda_render_hotel_custom_column( $column, $post_id ) {
	
    switch ( $column ) {

        case 'additional_fee' :
			$fee = get_field('additional_fee', $post_id);
			if ($fee === '') {
				$rating = absint(get_field('hotelstarrating', $post_id));
				if ($rating > 5 || !$rating) {
					$rating = 0;
				}
				$option_name = sprintf('pricing_additional_fee_for_%d_star_hotel', $rating);
				$fee = kanda_get_theme_option($option_name);

				$fee = sprintf(
					'%1$s %2$s -> $%3$s',
					($rating ? $rating : 'N/A'),
					$rating ? _n('star', 'stars', $rating, 'kanda') : '',
					number_format(floatval($fee), 2)
				);
			} else {
				$fee = sprintf('$%1$s', number_format(floatval($fee), 2));
			}
            echo $fee;
			
            break;
        case 'city' :
            echo IOL_Helper::get_city_name_from_code( get_post_meta( $post_id, 'hotelcity', true ) ); 
            break;

    }
}

/**
* Post type 'hotel' row actions
*/
add_filter('post_row_actions','kanda_custom_row_action', 10, 2);
function kanda_custom_row_action( $actions, $post ) {
	if ( $post->post_type == 'hotel' ){
		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['view'] );
		$actions['edit'] = str_replace( 'href=', 'target="_blank" href=', $actions['edit'] );
	}
	
	return $actions;
}

/**
 * Manage user row actions
 */
add_filter( 'user_row_actions', 'kanda_filter_user_row_actions', 10, 2 );
function kanda_filter_user_row_actions( array $actions, WP_User $user ) {

    if( kanda_is_reservator() && user_can( $user, 'administrator' ) ) {
        $actions = array();
    }

    return $actions;
}

/**
 * Remove administrator role for reservators
 */
add_filter( 'editable_roles', 'kanda_editable_roles', 10, 1 );
function kanda_editable_roles( $all_roles ) {
	$allowed_roles = array(
		Kanda_Config::get( 'agency_role' )
	);
    if( ! kanda_is_reservator() ) {
		$allowed_roles[] = Kanda_Config::get( 'reservator_role' );
		$allowed_roles[] = 'administrator';
    }
	
	return array_intersect_key( $all_roles, array_flip( $allowed_roles ) );
}

/**
 * Deny reservators to edit higher level users
 */
add_action( 'edit_user_profile_update', 'kanda_stop_editing_higher_users', 10, 1 );
function kanda_stop_editing_higher_users( $user_id ) {
    if( kanda_is_reservator() && user_can( $user_id, 'administrator' ) ) {
        wp_die( 'You are not allowed to edit that user' );
    }
}

/**
 * Register 'booking' post type meta boxes
 */

/**
* Send / Resend booking information
*/
add_action( 'kanda/admin/doing_email_updated_booking_details', 'kanda_admin_email_updated_booking_details', 10, 1 );
function kanda_admin_email_updated_booking_details( $booking_id ) {

    if( ! $booking_id ) {
        return;
    }
    $booking = get_post( $booking_id );
    if( ! $booking ) {
        return;
    }

    include Kanda_Mailer::get_layout_path() . 'booking-details.php';
    $booking_details = ob_get_clean();

    $user = new WP_User( $booking->post_author );
    $first_name = $last_name = '';
    if( $user ) {
        $first_name = $user->first_name;
        $last_name = $user->last_name;
    }

    $subject = kanda_get_theme_option( 'email_booking_details_title' );
    $message = kanda_get_theme_option( 'email_booking_details_body' );
    $variables = array(
        '{{BOOKING_DETAILS}}' => $booking_details,
        '{{FIRST_NAME}}'      => $first_name,
        '{{LAST_NAME}}'       => $last_name
    );

    $sent = kanda_mailer()->send_user_email( $user->user_email, $subject, $message, $variables );
    if( ! $sent ) {
        kanda_logger()->log( sprintf( 'Error sending email to user for updated details via admin panel. booking_id=%d', $booking_id ) );
        Kanda_Admin_Notification::set( 'error', __( 'Error sending email. Please try again.', 'kanda' ) );
    } else {
        Kanda_Admin_Notification::set( 'success', __( 'Email successfully sent.', 'kanda' ) );
    }
    wp_safe_redirect( get_edit_post_link( $booking_id, '' ) ); exit;

}

/**
* Handle custom requests in admin panel
*/
add_action( 'admin_init', 'kanda_check_knada_custom_requests' );
function kanda_check_knada_custom_requests() {

    if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return;
    }

    if( isset( $_REQUEST['kaction'] ) && $_REQUEST['kaction'] && ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] ) ) {
        $action = $_REQUEST['kaction'];
        $post_id = absint( $_REQUEST['post'] );
        switch( $action ) {
            case 'emailit':
                $action_name = 'email_updated_booking_details';
                break;
            default:
                $action_name = false;
        }
        if( $action_name ) {
            kanda_start_session();
            do_action(sprintf('kanda/admin/doing_%s', $action_name), $post_id);
        }
    }
}

/**
* Register 'booking' post type metaboxes
*/
add_action('add_meta_boxes', 'kanda_register_booking_meta_boxes');
function kanda_register_booking_meta_boxes() {
    add_meta_box(
        'kanda-booking-meta-box-advanced',
        __('Booking Actions', 'kanda'),
        'kanda_render_booking_post_type_side_metabox_content',
        'booking',
        'side',
        'high'
    );
}

/**
* Render 'booking' post type side metabox content
*/
function kanda_render_booking_post_type_side_metabox_content() {
    $url = add_query_arg( array( 'security' => wp_create_nonce( 'send-updated-booking-email' ), 'kaction' => 'emailit' ), get_edit_post_link() );
    printf(
        '<p>%1$s</p><a href="%2$s" class="button button-primary button-large">%3$s</a>',
        __( 'Use this button to manually send / resend email with booking details to travel agency', 'kanda' ),
        $url,
        __( 'Email it!', 'kanda' )
    );
}

/**
 * Remove add new for 'hotel' and 'booking' post type
 */
add_action( 'admin_menu', 'kanda_remove_things_from_admin_menu', 9999 );
function kanda_remove_things_from_admin_menu() {
    remove_submenu_page( 'edit.php?post_type=booking', 'post-new.php?post_type=booking' );
    remove_submenu_page( 'edit.php?post_type=hotel', 'post-new.php?post_type=hotel' );
}