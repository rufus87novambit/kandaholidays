<?php get_header(); ?>

    <div class="row">
        <div class="primary col-md-9">
            <?php if( have_posts() ) { the_post(); ?>
                <div class="box main-content">
                    <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                    <h4><?php esc_html_e( 'Booking details', 'kanda' ); ?></h4>
                    <div class="users-table table">
                        <header class="thead">
                            <div class="th"><?php esc_html_e( 'Property Name', 'kanda' ); ?></div>
                            <div class="th"><?php esc_html_e( 'Property Value', 'kanda' ); ?></div>
                        </header>
                        <div class="tbody">
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Booking Status', 'kanda' ); ?></div>
                                <div class="td"><?php the_field( 'booking_status' ); ?></div>
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
                                <div class="td"><?php esc_html_e( 'Start Date', 'kanda' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', false, false ) ) ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'End Date', 'kanda' ); ?></div>
                                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', false, false ) ) ); ?></div>
                            </div>
                            <div class="tr">
                                <div class="td"><?php esc_html_e( 'Price', 'kanda' ); ?></div>
                                <div class="td"><?php echo get_field( 'agency_price' ) . ' USD'; ?></div>
                            </div>
                        </div>
                    </div>

                    <h4><?php esc_html_e( 'Passenger details', 'kanda' ); ?></h4>

                    <?php if( have_rows( 'adults' ) ) { ?>
                    <h6><?php esc_html_e( 'Adults', 'kanda' ); ?></h6>
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
                </div>
            <?php } ?>
        </div>
        <?php get_sidebar(); ?>
    </div>

<?php get_footer(); ?>