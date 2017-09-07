<?php
/**
 * Kanda Theme "ACF" plugin helper
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

class Kanda_Fields {

	public $active;
	private $plugin = 'advanced-custom-fields-pro/acf.php';

	/**
	 * Singleton.
	 */
	static function get_instance() {
		static $instance = null;
		if ( $instance == null) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->check_activity();

		if( ! $this->active ) {
			return;
		}

		if( is_admin() ) {
			add_filter( 'acf/update_value/name=send_activation_email', array( $this, 'send_activation_email' ), 10, 3 );
			add_filter('acf/prepare_field/name=hotelcode', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=hotelcity', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=hotelstarrating', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=hotelphone', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=hoteladdress', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=hotelweb', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=checkintime', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=checkouttime', array( $this, 'make_readonly' ) );
			add_filter('acf/prepare_field/name=total_rate', array( $this, 'make_readonly' ) );
			if( kanda_is_reservator() ) {
				/** Booking fields */

				// Payment Status
				add_filter('acf/prepare_field/key=field_58e7e589c870a', array( $this, 'make_readonly' ) );

				// Real Price
				add_filter('acf/prepare_field/key=field_58e53d430570a', array( $this, 'make_private' ) );
				add_filter('acf/prepare_field/key=field_58e53d430570a', array( $this, 'make_readonly' ) );

				// Agency Price
				add_filter('acf/prepare_field/key=field_58e53d530570b', array( $this, 'make_readonly' ) );

				// Earnings
				add_filter('acf/prepare_field/key=field_58e53d640570c', array( $this, 'make_private' ) );
				add_filter('acf/prepare_field/key=field_58e53d640570c', array( $this, 'make_readonly' ) );

				//add_filter('acf/prepare_field/key=field_58e7e6c5c870b', array( $this, 'make_hidden' ) );	// Visa Rate
				//add_filter('acf/prepare_field/key=field_58e7e6d7c870c', array( $this, 'make_hidden' ) );	// Transfr Rate
				//add_filter('acf/prepare_field/key=field_58e7e6f0c870d', array( $this, 'make_hidden' ) );	// Other Rate
				add_filter('acf/prepare_field/key=field_58e7f15068426', array( $this, 'make_readonly' ) );	// Paid Amount

				/** User fields */
				//add_filter('acf/prepare_field/key=field_5920c267c2adb', array( $this, 'make_hidden' ) );	// Additional Fee
				//add_filter('acf/prepare_field/key=field_5925da9890612', array( $this, 'make_hidden' ) );	// Specific Additional Fee

			}

			add_filter('acf/prepare_field/name=hotel_city', array( $this, 'get_city_name' ) );
			add_filter('acf/prepare_field/name=hotelcity', array( $this, 'get_city_name' ) );
			add_filter('acf/load_value/name=total_rate', array($this, 'prepare_total_rate_field_value'), 10, 3 );
		}

	}

	/**
	 * Check acf plugin status
	 */
	private function check_activity() {
		if( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$this->active = is_plugin_active( $this->plugin );
	}

	/**
	 * Send / resend profile activation email
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 * @return int
	 */
	public function send_activation_email( $value, $post_id, $field ) {

		if( $value ) {

			$user_id = preg_replace('/[^0-9]/', '', $post_id);
			$user = get_user_by('id', (int)$user_id);

			if ( $user ) {

				$subject = kanda_get_theme_option( 'email_profile_activation_title' );
				$message = kanda_get_theme_option( 'email_profile_activation_body' );
				$variables = array( '{{LOGIN_URL}}' => sprintf( '<a href="%1$s">%1$s</a>', kanda_url_to( 'login' ) ) );

				$sent = kanda_mailer()->send_user_email( $user->user_email, $subject, $message, $variables );
				if( ! $sent ) {
					kanda_logger()->log( sprintf( 'Error sending email to user for account activation notification. Details: user_id=%d', $user->ID ) );
				}
			}

			// Set back to 0 to give resend functionality
			$value = 0;

		}

		return $value;
	}

	/**
	 * Make field readonly
	 *
	 * @param $field
	 * @return mixed
	 */
	public function make_readonly( $field ) {
		$field['readonly'] = true;
		$field['disabled'] = true;

		return $field;
	}

	/**
	 * Make field value private
	 * @param $field
	 * @return mixed
	 */
	public function make_private( $field ) {
		$field['value'] = kanda_get_private_field_value();
		$field['readonly'] = true;
		$field['disabled'] = true;

		return $field;
	}

	/**
	 * Do not render a field
	 * This is a conditional function so it must called after checking some condition
	 */
	public function make_hidden( $field ) {
		return false;
	}

	/**
	 * Get city human value
	 *
	 * @param $field
	 * @return mixed
	 */
	public function get_city_name( $field ) {

		if( $field['value'] ) {
			$city_name = IOL_Helper::get_city_name_from_code( $field['value'] );
			if( $city_name ) {
				$field['value'] = $city_name;
			}
		}
		return $field;
	}

	/**
	 * Get hotel additional fee
	 *
	 * @param $hotel_post_id
	 * @return mixed|null|void
	 */
	public function get_hotel_additional_fee( $hotel_post_id ) {
		return get_field( 'additional_fee', $hotel_post_id );
	}

	public function prepare_total_rate_field_value( $value, $post_id, $field ) {
		$total_rate = 0;
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', get_field( 'agency_price', $post_id )) );
		$total_rate += floatval( get_field( 'agency_price', $post_id ) );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', get_field( 'visa_rate', $post_id )) );
		$total_rate += floatval( get_field( 'visa_rate', $post_id ) );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', get_field( 'transfer_rate', $post_id )) );
		$total_rate += floatval( get_field( 'transfer_rate', $post_id ) );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', get_field( 'other_rate', $post_id )) );
		$total_rate += floatval( get_field( 'other_rate', $post_id ) );

		$value = number_format( $total_rate, 2 );

		return $value;
	}

}

/**
 * Get fields helper instance
 *
 * @return Kanda_Fields
 */
function kanda_fields() {
	return Kanda_Fields::get_instance();
}

kanda_fields();