<style type="text/css">
    body {
        font-family: 'DejaVu Sans Condensed';
        font-size: 14px;
    }
    p {
        text-align: justify;
        margin-bottom: 4pt;
        margin-top:0pt;
    }

    table {
        font-family: 'DejaVu Sans Condensed';
        line-height: 1.2;
        margin-top: 2pt;
        margin-bottom: 5pt;
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #000;
    }

    thead {
        font-weight: bold;
        vertical-align: bottom;
    }
    tfoot {
        font-weight: bold;
        vertical-align: top;
    }
    thead td {
        font-weight: bold;
    }
    tfoot td {
        font-weight: bold;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
    .text-right {
        text-align: right;
    }

    th {
        font-weight: bold;
        vertical-align: top;
        padding-left: 2mm;
        padding-right: 2mm;
        padding-top: 0.5mm;
        padding-bottom: 0.5mm;
    }

    td {
        padding-left: 2mm;
        vertical-align: top;
        padding-right: 2mm;
        padding-top: 2mm;
        padding-bottom: 2mm;
    }
    td.border-bottom {
        border-bottom: 1px solid #000;
    }
    td.border-left {
        border-left: 1px solid #000;
    }
    td.border-right {
        border-right: 1px solid #000;
    }
    td.border-top {
        border-top: 1px solid #000;
    }

    th p { margin:0pt;  }
    td p { margin:0pt;  }

    table.widecells td {
        padding-left: 5mm;
        padding-right: 5mm;
    }
    table.tallcells td {
        padding-top: 3mm;
        padding-bottom: 3mm;
    }

    hr {	width: 70%; height: 1px;
        text-align: center; color: #999999;
        margin-top: 8pt; margin-bottom: 8pt; }

    a {	color: #000066; font-style: normal; text-decoration: underline;
        font-weight: normal; }

    pre { font-family: 'DejaVu Sans Mono'; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

</style>

<table cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3" class="text-center border-bottom"><?php esc_html_e( 'Travel Vaucher', 'kanda' ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Reference No', 'kanda' ); ?></td>
            <td><?php the_field( 'booking_number', $booking_id ); ?></td>
            <td rowspan="3" style="vertical-align: middle;"><img style="width:150px;" src="<?php echo KANDA_THEME_URL; ?>images/back/logo-pdf.png" /></td>
        </tr>
        <tr>
            <td><?php _e( 'Booked By', 'kanda' ); ?></td>
            <td><?php echo kanda_get_user_meta( get_current_user_id(), 'company_name' ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Booking Status', 'kanda' ); ?></td>
            <td><?php echo ucwords( get_field( 'booking_status', $booking_id ) ); ?></td>
        </tr>

        <tr>
            <td colspan="3" class="text-center border-top border-bottom"><?php _e( 'Service Provider Details' , 'kanda'); ?></td>
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
            <td><?php _e( 'Supplier Name', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo the_field( 'hotel_name', $booking_id ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Phone', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo ( $phone = kanda_get_post_meta( 'hotelphone', $hotel->ID ) ) ? $phone : 'N/A'; ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Address', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo ( $address = kanda_get_post_meta( 'hoteladdress', $hotel->ID ) ) ? $address : 'N/A'; ?></td>
        </tr>
        <tr>
            <td colspan="3" class="text-center border-top border-bottom"><?php _e( 'Travel Details' , 'kanda'); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Passengers', 'kanda' ); ?></td>
            <td colspan="2" class="text-center">
                <?php while( have_rows( 'adults', $booking_id ) ) { the_row(); ?>
                    <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                <?php } ?>

                <?php while( have_rows( 'children', $booking_id ) ) { the_row(); ?>
                    <div><?php printf( '%1$s %2$s %3$s', get_sub_field( 'title' ), get_sub_field( 'first_name' ), get_sub_field( 'last_name' ) ); ?></div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td><?php _e( 'City', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo IOL_Helper::get_city_name_from_code( get_field( 'hotel_city', $booking_id ) ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Supplier reference', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo ( $reference = get_field( 'supplier_reference' ) ) ? $reference : 'N/A'; ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Check-in', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', $booking_id, false ) ) ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Check-out', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', $booking_id, false ) ) ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Meal Plan', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo the_field( 'meal_plan', $booking_id ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Room Type', 'kanda' ); ?></td>
            <td colspan="2" class="text-center"><?php echo the_field( 'room_type', $booking_id ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Additional Info', 'kanda' ); ?></td>
            <td colspan="2" class="text-center">
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

<div class="table-wrap">
    <div class="users-table table text-center">
        <header class="thead">
            <div class="th text-left"></div>
            <div class="th"></div>
        </header>

        <div class="tbody">
            <div class="tr">
                <div class="td"></div>
                <div class="td"></div>
            </div>

            <div class="tr">
                <div class="td"></div>
                <div class="td"></div>
            </div>

            <div class="tr">
                <div class="td"></div>
                <div class="td"></div>
            </div>
        </div>
    </div>
</div>