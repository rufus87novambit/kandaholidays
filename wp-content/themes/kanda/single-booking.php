<?php
if( get_queried_object()->post_author != get_current_user_id() ) {
    kanda_to( 404 );
}
get_header(); ?>

    <div class="row">
        <div class="primary col-md-9">
            <?php if( have_posts() ) { the_post(); ?>
                <div class="box main-content">
                    <?php kanda_show_notification(); ?>

                    <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                    <h4><?php esc_html_e( 'Booking details', 'kanda' ); ?></h4>
                    <div class="users-table table">
                        <header class="thead">
                            <div class="th"><?php esc_html_e( 'Property Name', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Property Value', 'kanda' ); ?></div>
                        </header>
                        <div class="tbody">
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'hotel_name' ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Room Type', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'room_type' ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Meal Plan', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'meal_plan' ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Booked Date', 'kanda' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'booking_date', false, false ) ) ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Check In', 'kanda' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', false, false ) ) ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Check Out', 'kanda' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', false, false ) ) ); ?></div>
                            </div>
                            <?php
                            $total_rate = 0;
                            $hotel_rate = get_field( 'agency_price' );
                            ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Hotel Rate', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', $hotel_rate ); ?></div>
                            </div>
                            <?php
                                $visa_rate = get_field( 'visa_rate' );
                                $total_rate += $visa_rate;
                            ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Visa Rate', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', $visa_rate ); ?></div>
                            </div>
                            <?php
                                $transfer_rate = get_field( 'transfer_rate' );
                                $total_rate += $transfer_rate;
                            ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Transfer Rate', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', $transfer_rate ); ?></div>
                            </div>
                            <?php
                                $other_rate = get_field( 'other_rate' );
                                $total_rate += $other_rate;
                            ?>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Other Rate', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', $other_rate ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Total Rate', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', $total_rate ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Paid Amount', 'kanda' ); ?></div>
                                <div class="td"><?php printf( '%s USD', get_field( 'paid_amount' ) ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Payment Status', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'payment_status' ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Booking Status', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'booking_status' ); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if( have_rows( 'cancellation_policy' ) ) { ?>
                    <h4><?php esc_html_e( 'Cancellation', 'kanda' ); ?></h4>
                    <div class="users-table table">
                        <header class="thead">
                            <div class="th"><?php esc_html_e( 'From', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'To', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Charge', 'kanda' ); ?></div>
                        </header>
                        <div class="tbody">
                            <?php while( have_rows( 'cancellation_policy' ) ) { the_row(); ?>
                            <div class="tr">
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'from', false, false ) ) ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'to', false, false ) ) ); ?></div>
                                <div class="td"><?php the_sub_field( 'charge' ); ?></div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <h4><?php esc_html_e( 'Passenger details', 'kanda' ); ?></h4>

                    <?php if( have_rows( 'adults' ) ) { ?>
                    <h6><?php esc_html_e( 'Adults', 'kanda' ); ?></h6>
                    <div class="users-table table">
                        <header class="thead">
                            <div class="th"><?php esc_html_e( 'Title', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'First Name', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Last Name', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Date Of Birth', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Nationality', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Gender', 'kanda' ); ?></div>
                        </header>
                        <div class="tbody">
                            <?php while( have_rows( 'adults' ) ) { the_row(); ?>
                            <div class="tr">
                                <div class="td"><?php the_sub_field( 'title' ); ?></div>
                                <div class="td"><?php the_sub_field( 'first_name' ); ?></div>
                                <div class="td"><?php the_sub_field( 'last_name' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'date_of_birth', false, false ) ) ); ?></div>
                                <div class="td"><?php the_sub_field( 'nationality' ); ?></div>
                                <div class="td"><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if( have_rows( 'children' ) ) { ?>
                        <h6><?php esc_html_e( 'Children', 'kanda' ); ?></h6>
                        <div class="users-table table">
                            <header class="thead">
                                <div class="th"><?php esc_html_e( 'Title', 'kanda' ); ?></div>
                                <div class="th"><?php esc_html_e( 'First Name', 'kanda' ); ?></div>
                                <div class="th"><?php esc_html_e( 'Last Name', 'kanda' ); ?></div>
                                <div class="th"><?php esc_html_e( 'Date Of Birth Name', 'kanda' ); ?></div>
                                <div class="th"><?php esc_html_e( 'Nationality', 'kanda' ); ?></div>
                                <div class="th"><?php esc_html_e( 'Gender', 'kanda' ); ?></div>
                            </header>
                            <div class="tbody">
                                <?php while( have_rows( 'children' ) ) { the_row(); ?>
                                    <div class="tr">
                                        <div class="td"><?php the_sub_field( 'title' ); ?></div>
                                        <div class="td"><?php the_sub_field( 'first_name' ); ?></div>
                                        <div class="td"><?php the_sub_field( 'last_name' ); ?></div>
                                        <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_sub_field( 'date_of_birth', false, false ) ) ); ?></div>
                                        <div class="td"><?php the_sub_field( 'nationality' ); ?></div>
                                        <div class="td"><?php echo "m" == strtolower( get_sub_field( 'gender' ) ) ? esc_html__( 'Male', 'kanda' ) : __( 'Female', 'kanda' ); ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="actions pull-right">
                                <a href="#send-email-popup" class="open-popup btn -sm -primary"><?php _e( 'Send Email', 'kanda' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div id="send-email-popup" class="static-popup -sm mfp-hide">
                <form class="form-block" action="<?php echo kanda_url_to( 'booking', array( 'send-email', get_queried_object()->post_name ) ); ?>" id="form_booking_email_details" method="post">
                    <div class="form-group row clearfix">
                        <label class="form-label" for="email_address"><?php esc_html_e( 'Email Address', 'kanda' ); ?></label>
                        <div>
                            <input type="text" id="email_address" name="email_address" class="form-control" value="<?php echo get_the_author_meta( 'email' ); ?>">
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <footer class="form-footer clearfix">
                        <input type="hidden" name="security" value="<?php echo wp_create_nonce( 'kanda-send-booking-data-email' ); ?>" />
                        <input type="submit" name="kanda_send_email" value="<?php _e( 'Send', 'kanda' ); ?>" class="btn -sm -secondary pull-right">
                    </footer>
                </form>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>

<?php get_footer(); ?>