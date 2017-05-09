<h2 class="text-center"><?php esc_html_e( 'Travel Vaucher', 'kanda' ); ?></h2>

<div class="table-wrap">
    <div class="users-table table text-center">
        <header class="thead">
            <div class="th text-left"><?php _e( 'Booking Details' , 'kanda'); ?></div>
            <div class="th"></div>
        </header>
        <div class="tbody">
            <div class="tr">
                <div class="td"><?php _e( 'Reference No', 'kanda' ); ?></div>
                <div class="td"><?php the_field( 'booking_number', $booking_id ); ?></div>
            </div>
            <div class="tr">
                <div class="td"><?php _e( 'Booked By', 'kanda' ); ?></div>
                <div class="td"><?php echo kanda_get_user_meta( get_current_user_id(), 'company_name' ); ?></div>
            </div>
            <div class="tr">
                <div class="td"><?php _e( 'Booking Status', 'kanda' ); ?></div>
                <div class="td"><?php echo ucwords( get_field( 'booking_status', $booking_id ) ); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrap">
    <div class="users-table table text-center">
        <header class="thead">
            <div class="th text-left"><?php _e( 'Service Provider Details' , 'kanda'); ?></div>
            <div class="th"></div>
        </header>
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
        <div class="tbody">
            <div class="tr">
                <div class="td"><?php _e( 'Supplier Name', 'kanda' ); ?></div>
                <div class="td"><?php echo the_field( 'hotel_name', $booking_id ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Phone', 'kanda' ); ?></div>
                <div class="td"><?php echo ( $phone = kanda_get_post_meta( 'hotelphone', $hotel->ID ) ) ? $phone : 'N/A'; ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Address', 'kanda' ); ?></div>
                <div class="td"><?php echo ( $address = kanda_get_post_meta( 'hoteladdress', $hotel->ID ) ) ? $address : 'N/A'; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrap">
    <div class="users-table table text-center">
        <header class="thead">
            <div class="th text-left"><?php _e( 'Travel Details' , 'kanda'); ?></div>
            <div class="th"></div>
        </header>
        <div class="tbody">
            <div class="tr">
                <div class="td"><?php _e( 'Passengers', 'kanda' ); ?></div>
                <div class="td">
                    <?php while( have_rows( 'adults', $booking_id ) ) { the_row(); ?>
                    <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                    <?php } ?>

                    <?php while( have_rows( 'children', $booking_id ) ) { the_row(); ?>
                        <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'City', 'kanda' ); ?></div>
                <div class="td"><?php echo IOL_Helper::get_city_name_from_code( get_field( 'hotel_city', $booking_id ) ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Supplier reference', 'kanda' ); ?></div>
                <div class="td"><?php echo ( $reference = get_field( 'supplier_reference' ) ) ? $reference : 'N/A'; ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Check-in', 'kanda' ); ?></div>
                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Check-out', 'kanda' ); ?></div>
                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Meal Plan', 'kanda' ); ?></div>
                <div class="td"><?php echo the_field( 'meal_plan', $booking_id ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Room Type', 'kanda' ); ?></div>
                <div class="td"><?php echo the_field( 'room_type', $booking_id ); ?></div>
            </div>

            <div class="tr">
                <div class="td"><?php _e( 'Additional Requests', 'kanda' ); ?></div>
                <div class="td">
                    <?php
                    $additional_requests = get_field( 'additional_requests', $booking_id );
                    if( $additional_requests ) {
                        $field_object = get_field_object( 'additional_requests', $booking_id );
                    }
                    foreach( $additional_requests as $request ) { ?>
                        <div><?php echo $field_object['choices'][$request]; ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix">
    <a href="<?php echo kanda_url_to( 'booking', array( 'download-voucher', $booking_id ) ) ?>" target="_blank" class="btn -secondary -sm pull-right"><?php _e( 'Download', 'kanda' ); ?></a>
</div>