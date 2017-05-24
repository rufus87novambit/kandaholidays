<?php
/**
 * Template Name: Booking List
 */
get_header(); ?>
<div class="row">
    <div class="primary col-md-9">
    <?php if( have_posts() ) { the_post(); ?>
        <div class="box main-content">

            <form class="form-block" id="form_booking_search" method="get" action="<?php the_permalink(); ?>">
                <fieldset class="fieldset clearfix sep-btm">
                    <div class="row">
                        <div class="col-lg-11">
                            <legend><?php esc_html_e( 'SEARCH CRITERIA', 'kanda' ); ?></legend>

                            <div class="row">
                                <div class="col-lg-4">
                                    <?php $brn = isset( $_GET['brn'] ) && $_GET['brn'] ? $_GET['brn'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label" for="brn"><?php esc_html_e( 'Booking Reference Number', 'kanda' ); ?></label>
                                        <div>
                                            <input type="text" id="brn" name="brn" class="form-control" value="<?php echo $brn; ?>">
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <?php $pfn = isset( $_GET['pfn'] ) && $_GET['pfn'] ? $_GET['pfn'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label" for="pfn"><?php esc_html_e( 'Passenger First Name', 'kanda' ); ?></label>
                                        <div>
                                            <input id="pfn" name="pfn" type="text" class="form-control -sm" value="<?php echo $pfn; ?>">
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <?php $status = isset( $_GET['status'] ) && $_GET['status'] ? $_GET['status'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label" for="status"><?php esc_html_e( 'Status', 'kanda' ); ?></label>
                                        <div class="select-wrap">
                                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="status" id="status">
                                                <option value=""><?php _e( 'All', 'kanda' ); ?></option>
                                                <option value="requested" <?php selected( 'requested', $status ); ?>><?php _e( 'On Request', 'kanda' ); ?></option>
                                                <option value="confirmed" <?php selected( 'confirmed', $status ); ?>><?php _e( 'Confirmed', 'kanda' ); ?></option>
                                                <option value="cancelled" <?php selected( 'cancelled', $status ); ?>><?php _e( 'Cancelled', 'kanda' ); ?></option>
                                                <option value="paid" <?php selected( 'paid', $status ); ?>><?php _e( 'Paid', 'kanda' ); ?></option>
                                            </select>
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <?php $hotel_name = isset( $_GET['hotel_name'] ) && $_GET['hotel_name'] ? $_GET['hotel_name'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label" for="booking_hotel_name"><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></label>
                                        <div>
                                            <input type="text" id="booking_hotel_name" name="hotel_name" class="form-control" value="<?php echo $hotel_name; ?>">
                                            <div id="autocomplete-wrap"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <?php $pln = isset( $_GET['pln'] ) && $_GET['pln'] ? $_GET['pln'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label" for="pln"><?php esc_html_e( 'Passenger Last Name', 'kanda' ); ?></label>
                                        <div>
                                            <input id="pln" name="pln" type="text" class="form-control -sm" value="<?php echo $pln; ?>">
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group clearfix">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <input type="submit" name="search" value="<?php _e( 'Search', 'kanda' ); ?>" class="btn -secondary -block">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <?php $city = isset( $_GET['city'] ) && $_GET['city'] ? $_GET['city'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label"><?php esc_html_e( 'Booked City', 'kanda' ); ?></label>
                                        <div class="select-wrap">
                                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="city">
                                                <option value=""><?php _e( 'All', 'kanda' ); ?></option>
                                                <?php foreach( IOL_Config::get( 'cities' ) as $city_code => $city_name ) { ?>
                                                    <option value="<?php echo $city_code; ?>" <?php selected( $city, $city_code ); ?>><?php echo $city_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <?php $check_in = ( isset( $_GET['check_in'] ) && $_GET['check_in'] ) ? $_GET['check_in'] : ''; ?>
                                    <div class="form-group clearfix">
                                        <label class="form-label"><?php esc_html_e( 'Check In date', 'kanda' ); ?></label>
                                        <div class="calendar-field">
                                            <input type="text" name="check_in" class="form-control datepicker-all-dates deny-typing" value="<?php echo $check_in; ?>">
                                            <div class="form-control-feedback"><small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <a href="<?php the_permalink(); ?>" class="btn -danger -block"><?php _e( 'Reset', 'kanda' ); ?></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </form>

            <?php
            global $wp_query;
            $tmp_query = $wp_query;

            $wp_query = new WP_Query( kanda_get_booking_query_args() );

            if( have_posts() ) {
            ?>
            <div class="users-table table">
                <header class="thead">
                    <div class="th" style="width: 40%;"><?php esc_html_e( 'Name', 'kanda' ); ?></div>
                    <div class="th" style="width: 10%;"><?php esc_html_e( 'Status', 'kanda' ); ?></div>
                    <div class="th" style="width: 20%;"><?php esc_html_e( 'Check In Date', 'kanda' ); ?></div>
                    <div class="th" style="width: 20%;"><?php esc_html_e( 'Check Out Date', 'kanda' ); ?></div>
                    <div class="th" style="width: 10%;"><?php esc_html_e( 'More', 'kanda' ); ?></div>
                </header>
                <div class="tbody">
                    <?php while( have_posts() ) { the_post(); ?>
                    <div class="tr">
                        <div class="td" style="width: 50%;"><?php echo the_title(); ?></div>
                        <div class="td"><?php echo ucwords( get_field( 'booking_status' ) ); ?></div>
                        <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', false, false ) ) ); ?></div>
                        <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', false, false ) ) ); ?></div>
                        <div class="td"><a href="<?php the_permalink(); ?>" target="_blank" class="link"><?php esc_html_e( 'See details', 'kanda' ); ?></a></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="pagination">
                <?php the_posts_pagination(array(
                    'mid_size'           => 1,
                    'prev_text'          => '&laquo;',
                    'next_text'          => '&raquo',
                    'screen_reader_text' => ' '
                )); ?>
            </div>
            <?php } else {
                printf( '<p>%s</p>', __( 'No bookings found', 'kanda' ) );
            }
            $wp_query = $tmp_query;
            ?>
        </div>
    <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>