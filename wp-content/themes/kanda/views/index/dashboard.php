<div class="box main-content">
    <h2 class="page-title"><?php _e( 'My Bookings', 'kanda' ); ?></h2>

    <?php if( $this->bookings->have_posts() ) { ?>
    <div class="table-wrap">
        <div class="users-table table">
            <header class="thead">
                <div class="th bookings-list-th-name"><?php esc_html_e( 'Name', 'kanda' ); ?></div>
                <div class="th bookings-list-th-status"><?php esc_html_e( 'Status', 'kanda' ); ?></div>
                <div class="th bookings-list-th-check-in hidden-md-down"><?php esc_html_e( 'Check In Date', 'kanda' ); ?></div>
                <div class="th bookings-list-th-check-out hidden-md-down"><?php esc_html_e( 'Check Out Date', 'kanda' ); ?></div>
                <div class="th bookings-list-th-check-more"><?php esc_html_e( 'More', 'kanda' ); ?></div>
            </header>

            <div class="tbody">
                <?php while( $this->bookings->have_posts() ) { $this->bookings->the_post(); ?>
                <div class="tr">
                    <div class="td"><?php echo the_title(); ?></div>
                    <div class="td"><?php echo ucwords( get_field( 'booking_status' ) ); ?></div>
                    <div class="td hidden-md-down"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', false, false ) ) ); ?></div>
                    <div class="td hidden-md-down"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', false, false ) ) ); ?></div>
                    <div class="td">
                        <a href="<?php the_permalink(); ?>" target="_blank" class="link">
                            <span class="hidden-md-down"><?php esc_html_e( 'See details', 'kanda' ); ?></span>
                            <span class="hidden-lg-up"><?php esc_html_e( 'Details', 'kanda' ); ?></span>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <?php } else { ?>
    <p><?php _e( 'There are no bookings', 'kanda' ); ?></p>
    <?php } wp_reset_query(); ?>
</div>