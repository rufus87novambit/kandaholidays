<h3><?php _e( 'Room details', 'kanda' ); ?></h3>
<table cellpadding="3px" cellspacing="0" border="3px" style="width:100%; border:1px solid #311b92;">
    <thead>
        <tr>
            <th><?php _e( 'Property Name', 'kanda' ); ?></th>
            <th><?php _e( 'Property Value', 'kanda' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></td>
            <td><?php the_field( 'hotel_name', $booking_id ); ?></td>
        </tr>
        <?php if( $city_code = get_field( 'hotel_city' ) ) { ?>
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
        </tr>
        <tr>
            <td><?php esc_html_e( 'Check Out', 'kanda' ); ?></td>
            <td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ); ?></td>
        </tr>
        <?php
        $total_rate = 0;
        $hotel_rate = get_field( 'agency_price', $booking_id );
        ?>
        <tr>
            <td><?php esc_html_e( 'Hotel Rate', 'kanda' ); ?></td>
            <td><?php printf( '%s USD', $hotel_rate ); ?></td>
        </tr>
        <?php
        $visa_rate = get_field( 'visa_rate', $booking_id );
        $total_rate += $visa_rate;
        ?>
        <tr>
            <td><?php esc_html_e( 'Visa Rate', 'kanda' ); ?></td>
            <td><?php printf( '%s USD', $visa_rate ); ?></td>
        </tr>
        <?php
        $transfer_rate = get_field( 'transfer_rate', $booking_id );
        $total_rate += $transfer_rate;
        ?>
        <tr>
            <td><?php esc_html_e( 'Transfer Rate', 'kanda' ); ?></td>
            <td><?php printf( '%s USD', $transfer_rate ); ?></td>
        </tr>
        <?php
        $other_rate = get_field( 'other_rate', $booking_id );
        $total_rate += $other_rate;
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
            <td><?php printf( '%s USD', get_field( 'paid_amount', $booking_id ) ); ?></td>
        </tr>
        <tr>
            <td><?php esc_html_e( 'Payment Status', 'kanda' ); ?></td>
            <td><?php the_field( 'payment_status', $booking_id ); ?></td>
        </tr>
        <tr>
            <td><?php esc_html_e( 'Booking Status', 'kanda' ); ?></td>
            <td><?php the_field( 'booking_status', $booking_id ); ?></td>
        </tr>
    </tbody>
</table>

<?php if( have_rows( 'cancellation_policy', $booking_id ) ) { ?>
<h4><?php esc_html_e( 'Cancellation Policy', 'kanda' ); ?></h4>
<table cellpadding="3px" cellspacing="0" border="3px" style="width:100%; border:1px solid #311b92;">
    <thead>
        <tr>
            <th><?php esc_html_e( 'From', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'To', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Charge', 'kanda' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php while( have_rows( 'cancellation_policy', $booking_id ) ) { the_row(); ?>
            <tr>
                <td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'from', false ) ) ); ?></td>
                <td><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'to', false ) ) ); ?></td>
                <td><?php the_sub_field( 'charge' ); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>

<h3><?php _e( 'Passenger Details', 'kanda' ); ?></h3>

<?php if( have_rows( 'adults', $booking_id ) ) { ?>
<h4><?php _e( 'Adults', 'kanda' ); ?></h4>
<table cellpadding="3px" cellspacing="0" border="3px" style="width:100%; border:1px solid #311b92;">
    <thead>
        <tr>
            <th><?php esc_html_e( 'Title', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'First Name', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Last Name', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Gender', 'kanda' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php while( have_rows( 'adults', $booking_id ) ) { the_row(); ?>
        <tr>
            <td><?php the_sub_field( 'title' ); ?></td>
            <td><?php the_sub_field( 'first_name' ); ?></td>
            <td><?php the_sub_field( 'last_name' ); ?></td>
            <td><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>

<?php if( have_rows( 'children', $booking_id ) ) { ?>
    <h4><?php _e( 'Children', 'kanda' ); ?></h4>
    <table cellpadding="3px" cellspacing="0" border="3px" style="width:100%;border:1px solid #311b92;">
        <thead>
        <tr>
            <th><?php esc_html_e( 'Title', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'First Name', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Last Name', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Gender', 'kanda' ); ?></th>
            <th><?php esc_html_e( 'Age', 'kanda' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php while( have_rows( 'children', $booking_id ) ) { the_row(); ?>
            <tr>
                <td><?php the_sub_field( 'title' ); ?></td>
                <td><?php the_sub_field( 'first_name' ); ?></td>
                <td><?php the_sub_field( 'last_name' ); ?></td>
                <td><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></td>
                <td><?php the_sub_field( 'age' ); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
