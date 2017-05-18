<br/>
<table class="custom-table" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td colspan="5" class="heading text-center"><?php _e( 'TRAVEL VOUCHER' , 'kanda'); ?></td>
        </tr>
        <tr>
            <td colspan="1" class="no-border" style="width: 20%;"><?php _e( 'Reference No', 'kanda' ); ?>:</td>
            <td colspan="2" class="no-border" style="width: 20%;"><?php the_field( 'booking_number', $booking_id ); ?></td>
            <td rowspan="3" colspan="3" class="text-right no-border" style="vertical-align: middle; width: 60%;"><img style="width:250px;margin-right: 40px;" src="<?php echo KANDA_THEME_URL; ?>images/back/logo-travel-voucher.png" /></td>
        </tr>
        <tr>
            <td colspan="1" class="no-border"><?php _e( 'Booked By', 'kanda' ); ?>:</td>
            <td colspan="4" class="no-border"><?php echo kanda_get_user_meta( get_current_user_id(), 'company_name' ); ?></td>
        </tr>
        <tr>
            <td colspan="1" class="no-border"><?php _e( 'Booking Status', 'kanda' ); ?>:</td>
            <td colspan="4" class="no-border"><?php echo ucwords( get_field( 'booking_status', $booking_id ) ); ?></td>
        </tr>
        <tr>
            <td colspan="1" class="border-separator"></td>
            <td colspan="4" class="border-separator text-center" style="font-size: 16px;"><?php _e( 'Service Provider Details' , 'kanda'); ?></td>
        </tr>
        <?php
        $hotel_query = new WP_Query( array(
            'post_type' => 'hotel',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key'   => 'hotelcode',
                    'value' => get_field( 'hotel_code', $booking_id )
                )
            )
        ) );
        $hotels = $hotel_query->get_posts();
        $hotel = $hotels[0];
        ?>
        <tr>
            <td colspan="1"><?php _e( 'Supplier Name', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo the_field( 'hotel_name', $booking_id ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Phone', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo ( $phone = kanda_get_post_meta( 'hotelphone', $hotel->ID ) ) ? $phone : 'N/A'; ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Address', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo ( $address = kanda_get_post_meta( 'hoteladdress', $hotel->ID ) ) ? $address : 'N/A'; ?></td>
        </tr>


        <tr>
            <td colspan="1" class="border-separator"></td>
            <td colspan="4" class="border-separator text-center" style="font-size: 16px;"><?php _e( 'Travel Details' , 'kanda'); ?></td>
        </tr>
        <tr>
            <td colspan="1"><?php _e( 'Passengers', 'kanda' ); ?></td>
            <td colspan="4" class="text-center">
                <?php while( have_rows( 'adults', $booking_id ) ) { the_row(); ?>
                    <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                <?php } ?>

                <?php while( have_rows( 'children', $booking_id ) ) { the_row(); ?>
                    <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                <?php } ?>
            </td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'City', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo IOL_Helper::get_city_name_from_code( get_field( 'hotel_city', $booking_id ) ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Supplier reference', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo ( $reference = get_field( 'supplier_reference' ) ) ? $reference : 'N/A'; ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Check-in', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Check-out', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Meal Plan', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo the_field( 'meal_plan', $booking_id ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Room Type', 'kanda' ); ?></td>
            <td colspan="4" class="text-center"><?php echo the_field( 'room_type', $booking_id ); ?></td>
        </tr>

        <tr>
            <td colspan="1"><?php _e( 'Additional Requests', 'kanda' ); ?></td>
            <td colspan="4" class="text-center">
                <?php
                $additional_requests = get_field( 'additional_requests', $booking_id );
                if( $additional_requests ) {
                    $field_object = get_field_object( 'additional_requests', $booking_id );
                }
                foreach( $additional_requests as $request ) { ?>
                    <div><?php echo $field_object['choices'][$request]; ?></div>
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>

<div class="clearfix">
    <a href="<?php echo kanda_url_to( 'booking', array( 'download-voucher', $booking_id ) ) ?>" target="_blank" class="btn -secondary -sm pull-right"><?php _e( 'Download', 'kanda' ); ?></a>
</div>