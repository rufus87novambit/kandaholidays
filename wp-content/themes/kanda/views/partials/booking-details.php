<?php
$booking_status = kanda_get_post_meta( $booking_id, 'booking_status' );
$booking = get_post( $booking_id );
?>
	<h4><?php esc_html_e( 'Booking Details', 'kanda' ); ?></h4>
	<table class="custom-table" cellpadding="0" cellspacing="0">
		<tr>
			<td class="title text-center" colspan="2"><?php _e( 'Itinerary', 'kanda' ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Supplier Reference', 'kanda' ); ?></td>
			<td><?php echo ( $reference = get_field( 'supplier_reference', $booking_id ) ) ? $reference : 'N/A'; ?></td>
		</tr>
		<?php
		$hotel_name = get_field( 'hotel_name', $booking_id );
		$hotel_code = kanda_get_post_meta( $booking_id, 'hotel_code' );
		$hotel_permalink = kanda_get_single_hotel_url( array( 'hotelcode' => $hotel_code, 'start_date' => get_field( 'start_date', $booking_id, false ), 'end_date' => get_field( 'end_date', $booking_id, false ) ) );
		?>
		<tr>
			<td><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></td>
			<td><a href="<?php echo $hotel_permalink; ?>" target="_blank" class="link"><?php echo $hotel_name; ?></a></td>
		</tr>
		<?php if( $city_code = get_field( 'hotel_city', $booking_id ) ) { ?>
			<tr>
				<td><?php esc_html_e( 'City', 'kanda' ); ?></td>
				<td><?php echo IOL_Helper::get_city_name_from_code( $city_code ); ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td><?php esc_html_e( 'Room Type', 'kanda' ); ?></td>
			<td><?php the_field( 'room_type', $booking_id ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Meal Plan', 'kanda' ); ?></td>
			<td><?php the_field( 'meal_plan', $booking_id ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Booked Date', 'kanda' ); ?></td>
			<td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'booking_date', $booking_id, false ) ) ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Check In', 'kanda' ); ?></td>
			<td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ); ?></td>
		<tr>
			<td><?php esc_html_e( 'Check Out', 'kanda' ); ?></td>
			<td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ); ?></td>
		</tr>
		<?php
		$total_rate = 0;
		$hotel_rate = get_field( 'agency_price', $booking_id );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', $hotel_rate) );
		$total_rate += floatval( $hotel_rate );
		?>
		<tr>
			<td><?php esc_html_e( 'Hotel Rate', 'kanda' ); ?></td>
			<td><?php printf( '%s USD', $hotel_rate ); ?></td>
		</tr>
		<?php
		$visa_rate = get_field( 'visa_rate', $booking_id );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', $visa_rate) );
		$total_rate += floatval( $visa_rate );
		?>
		<tr>
			<td><?php esc_html_e( 'Visa Rate', 'kanda' ); ?></td>
			<td><?php printf( '%s USD', $visa_rate ); ?></td>
		</tr>
		<?php
		$transfer_rate = get_field( 'transfer_rate', $booking_id );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', $transfer_rate) );
		$total_rate += floatval( $transfer_rate );
		?>
		<tr>
			<td><?php esc_html_e( 'Transfer Rate', 'kanda' ); ?></td>
			<td><?php printf( '%s USD', $transfer_rate ); ?></td>
		</tr>
		<?php
		$other_rate = get_field( 'other_rate', $booking_id );
		//$total_rate += floatval( preg_replace('/[^\d.]/', '', $other_rate) );
		$total_rate += floatval($other_rate );
		?>
		<tr>
			<td><?php esc_html_e( 'Other Rate', 'kanda' ); ?></td>
			<td><?php printf( '%s USD', $other_rate ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Total Rate', 'kanda' ); ?></td>
			<td><?php printf( '%s USD', $total_rate ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Paid Amount', 'kanda' ); ?></td>
			<td><?php printf( '%d USD', get_field( 'paid_amount', $booking_id ) ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Payment Status', 'kanda' ); ?></td>
			<td><?php echo ucwords( get_field( 'payment_status', $booking_id ) ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Booking Status', 'kanda' ); ?></td>
			<td><?php echo ucwords( get_field( 'booking_status', $booking_id ) ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Additional Requests', 'kanda' ); ?></td>
			<td>
				<?php
				$additional_requests = get_field( 'additional_requests', $booking_id );
				if( $additional_requests ) {
					$field_object = get_field_object( 'additional_requests', $booking_id );
				}
				?>
				<ul class="list-disc">
					<?php foreach( (array)$additional_requests as $request ) { ?>
						<li><?php echo isset( $field_object['choices'][$request] ) ? $field_object['choices'][$request] : $request; ?></li>
					<?php } ?>
				</ul>
			</td>
		</tr>
	</table>

<?php if( have_rows( 'cancellation_policy', $booking_id ) ) { ?>
	<h4><?php esc_html_e( 'Cancellation Policy', 'kanda' ); ?></h4>
	<table class="custom-table" cellpadding="0" cellspacing="0">
		<tr>
			<td class="title"><?php esc_html_e( 'From', 'kanda' ); ?></td>
			<td class="title"><?php esc_html_e( 'To', 'kanda' ); ?></td>
			<td class="title"><?php esc_html_e( 'Charge', 'kanda' ); ?></td>
		</tr>
		<tr>
			<?php while( have_rows( 'cancellation_policy', $booking_id ) ) { the_row(); ?>
		<tr>
			<td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'from', false, false ) ) ); ?></td>
			<td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'to', false, false ) ) ); ?></td>
			<td><?php the_sub_field( 'charge' ); ?></td>
		</tr>
		<?php } ?>
		</tr>
	</table>
<?php } ?>

	<h4><?php esc_html_e( 'Passenger Details', 'kanda' ); ?></h4>

<?php if( have_rows( 'adults', $booking_id ) ) { ?>
	<h6><?php esc_html_e( 'Adults', 'kanda' ); ?></h6>
	<table class="custom-table" cellpadding="0" cellspacing="0">
		<tr>
			<td class="title" style="width: 10%;"><?php esc_html_e( 'Title', 'kanda' ); ?></td>
			<td class="title" style="width: 30%;"><?php esc_html_e( 'First Name', 'kanda' ); ?></td>
			<td class="title" style="width: 30%;"><?php esc_html_e( 'Last Name', 'kanda' ); ?></td>
			<td class="title" style="width: 20%;"><?php esc_html_e( 'Gender', 'kanda' ); ?></td>
			<td class="title" style="width: 10%;"></td>
		</tr>
		<?php while( have_rows( 'adults', $booking_id ) ) { the_row(); ?>
			<tr>
				<td><?php the_sub_field( 'title' ); ?></td>
				<td><?php the_sub_field( 'first_name' ); ?></td>
				<td><?php the_sub_field( 'last_name' ); ?></td>
				<td colspan="2"><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

<?php if( have_rows( 'children', $booking_id ) ) { ?>
	<h6><?php esc_html_e( 'Children', 'kanda' ); ?></h6>
	<table class="custom-table" cellpadding="0" cellspacing="0">
		<tr>
			<td class="title" style="width: 10%;"><?php esc_html_e( 'Title', 'kanda' ); ?></td>
			<td class="title" style="width: 30%;"><?php esc_html_e( 'First Name', 'kanda' ); ?></td>
			<td class="title" style="width: 30%;"><?php esc_html_e( 'Last Name', 'kanda' ); ?></td>
			<td class="title" style="width: 20%;"><?php esc_html_e( 'Gender', 'kanda' ); ?></td>
			<td class="title" style="width: 10%;"><?php esc_html_e( 'Age', 'kanda' ); ?></td>
		</tr>
		<?php while( have_rows( 'children', $booking_id ) ) { the_row(); ?>
			<tr>
				<td><?php the_sub_field( 'title' ); ?></td>
				<td><?php the_sub_field( 'first_name' ); ?></td>
				<td><?php the_sub_field( 'last_name' ); ?></td>
				<td><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></td>
				<td><?php the_sub_field( 'age' ); ?></td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="actions pull-right">
				<a href="#send-email-popup" class="open-popup btn -sm -primary"><?php _e( 'Send Email', 'kanda' ); ?></a>
				<a href="<?php echo add_query_arg( array( 'action' => 'view_voucher', 'security' => wp_create_nonce( 'kanda-view-voucher' ),'id' => $booking_id ), admin_url( 'admin-ajax.php' ) ); ?>" class="btn -sm -secondary ajax-popup" data-popup="-sm"><?php _e( 'View Voucher', 'kanda' ); ?></a>
				<?php if( $booking_status != 'cancelled' ) { ?>
					<a href="#cancel-booking-popup" class="open-popup btn -sm -danger"><?php _e( 'Cancel Booking', 'kanda' ); ?></a>
				<?php } ?>
			</div>
		</div>
	</div>

	<div id="send-email-popup" class="static-popup -sm mfp-hide">
		<form class="form-block" action="<?php echo kanda_url_to( 'booking', array( 'send-email', $booking->post_name ) ); ?>" id="form_booking_email_details" method="post">
			<div class="form-group row clearfix">
				<label class="form-label" for="email_address"><?php esc_html_e( 'Email Address', 'kanda' ); ?></label>
				<div>
					<input type="text" id="email_address" name="email_address" class="form-control" value="<?php echo get_the_author_meta( 'email', $booking->post_author ); ?>">
					<div class="form-control-feedback"><small></small></div>
				</div>
			</div>
			<footer class="form-footer clearfix">
				<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'kanda-send-booking-data-email' ); ?>" />
				<input type="submit" name="kanda_send_email" value="<?php _e( 'Send', 'kanda' ); ?>" class="btn -sm -secondary pull-right">
			</footer>
		</form>
	</div>

<?php if( $booking_status != 'cancelled' ) { ?>
	<div id="cancel-booking-popup" class="static-popup -sm mfp-hide">
		<h2 class="text-center"><?php _e('Booking Cancellation', 'kanda'); ?></h2>

		<p class="text-center"><?php _e('Are you sure you want to cancel booking', 'kanda'); ?></p>

		<form class="form-block"
		      action="<?php echo kanda_url_to('booking', array('send-email', $booking->post_name)); ?>"
		      id="form_booking_email_details" method="post">
			<footer class="form-footer clearfix text-center">
				<a id="btn-cancel-booking"
				   href="<?php echo add_query_arg(array('booking_id' => $booking_id, 'security' => wp_create_nonce('kanda-cancel-booking')), admin_url('admin-ajax.php')); ?>"
				   class="btn -sm -secondary"><?php _e('Cancel Booking', 'kanda'); ?></a>
			</footer>
		</form>
	</div>
<?php } ?>