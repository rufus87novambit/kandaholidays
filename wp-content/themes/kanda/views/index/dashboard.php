<div class="box main-content">
    <h2 class="page-title"><?php _e( 'Active Bookings', 'kanda' ); ?></h2>

    <?php if( $this->bookings->have_posts() ) { ?>
    <div class="table-wrap">
        <div class="users-table table">
            <header class="thead">
                <div class="th" style="width: 50%;"><?php esc_html_e( 'Name', 'kanda' ); ?></div>
                <div class="th"><?php esc_html_e( 'Start Date', 'kanda' ); ?></div>
                <div class="th"><?php esc_html_e( 'End Date', 'kanda' ); ?></div>
                <div class="th"><?php esc_html_e( 'Actions', 'kanda' ); ?></div>
            </header>

            <div class="tbody">
                <?php while( $this->bookings->have_posts() ) { $this->bookings->the_post(); ?>
                <div class="tr">
                    <div class="td" style="width: 50%;"><?php echo the_title(); ?></div>
                    <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'start_date', false, false ) ) ); ?></div>
                    <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), strtotime( get_field( 'end_date', false, false ) ) ); ?></div>
                    <div class="td"><a href="<?php the_permalink(); ?>" target="_blank" class="link"><?php esc_html_e( 'See details', 'kanda' ); ?></a></div>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <?php } else { ?>
    <p><?php _e( 'There are no bookings', 'kanda' ); ?></p>
    <?php } wp_reset_query(); ?>
</div>