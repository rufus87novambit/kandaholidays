<?php

class Kanda_Booking_Cancellation_Notification {

    private $exclude = array();
    private $days_left = null;

    /**
     * Get class instance
     *
     * @return Kanda_Booking_Cancellation_Notification
     */
    public static function get_instance() {
        static $instance;
        if ( $instance == null) {
            $instance = new self();
        }
        return $instance;
    }

    public function __construct() {

    }

    /**
     * Constructor
     *
     * @param $from
     * @param $to
     */
    public function process_range( $from, $to ) {
        $this->exclude = array();
        $this->days_left = null;

        for( $i = $from; $i<= $to; $i++ ) {
            $this->days_left = $i;

            $this->process_single_day();
        }
    }

    /**
     * Convert days to hours
     *
     * @param $days
     * @return mixed
     */
    private function days_to_hours( $days ) {
        return $days * 60 * 60 * 24;
    }

    /**
     * Process for group
     */
    public function process_single_day() {
        $query = new WP_Query(array(
            'post_type'     => 'booking',
            'post_status'   => 'publish',
            'post__not_in'  => $this->exclude,
            'meta_query'    => array(
                'relation' => 'AND',
                array(
                    'key'   => 'payment_status',
                    'value' => 'unpaid',
                ),
				array(
                    'key'   	=> 'booking_status',
                    'value' 	=> 'cancelled',
					'compare'	=> '!='
                ),
                array(
                    'key' => 'cancellation_policy_0_from',
                    'value' => array( date( 'Ymd', time() ), date( 'Ymd', time() + $this->days_to_hours( $this->days_left ) ) ),
                    'compare' => 'BETWEEN',
                )
            )
        ));

        while( $query->have_posts() ) {
            $query->the_post();
            $this->exclude[] = get_the_ID();

            $this->send_notification();
        }

        wp_reset_query();
    }

    /**
     * Send notification for specific booking
     */
    public function send_notification() {
        $booking_id = get_the_ID();
        $booking_author_id = get_the_author_meta( 'ID' );

        $subject = kanda_get_theme_option( 'email_cancellation_deadline_title' );
        $message = kanda_get_theme_option( 'email_cancellation_deadline_body' );

        $charges = '---';
        while( have_rows( 'cancellation_policy', $booking_id ) ) {
            the_row();
            $from = strtotime( get_sub_field( 'from', false ) );
            $to = strtotime( get_sub_field( 'to', false ) );
            $now = time();
            if( ( $now >= $from ) && ( $now < $to )  ) {
                $charges = get_sub_field( 'charge' );
                break;
            }
        }
        $variables = array(
            '{{HOURS_LEFT}}'            => $this->days_left * 24,
            '{{BOOKING_NUMBER}}'        => get_field( 'booking_number', $booking_id ),
            '{{PASSENGERS}}'            => strtr( kanda_get_post_meta( $booking_id, 'passenger_names' ), array( '##' => ', ' ) ),
            '{{AGENCY_NAME}}'           => kanda_get_user_meta( $booking_author_id, 'company_name' ),
            '{{HOTEL_NAME}}'            => get_field( 'hotel_name', $booking_id ),
            '{{ROOM_TYPE}}'             => get_field( 'room_type', $booking_id ),
            '{{CHECK_IN}}'              => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ),
            '{{CHECK_OUT}}'             => date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ),
            '{{CANCELLATION_CHARGES}}'  => $charges
        );
		
        $sent_user = kanda_mailer()->send_user_email( $booking_author_id, $subject, $message, $variables );
        if( $sent_user ) {
	        kanda_mailer()->send_admin_email( $subject, $message, $variables );
        } else {
	        kanda_logger()->log( sprintf( 'Error sending email to user for booking notification. booking_id=%d', $booking_id ) );
        }

    }

}