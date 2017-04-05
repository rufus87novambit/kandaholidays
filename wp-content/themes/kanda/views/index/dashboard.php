<div class="row">
    <div class="col-sm-6">
        <div class="box main-content">
            <h2 class="page-title"><?php _e( 'Active Bookings', 'kanda' ); ?></h2>

            <?php if( $this->bookings->have_posts() ) { ?>
            <div class="table-wrap">
                <div class="users-table table">
                    <header class="thead">
                        <div class="th"><?php esc_html_e( 'Name', 'kanda' ); ?></div>
                        <div class="th"><?php esc_html_e( 'Actions', 'kanda' ); ?></div>
                    </header>
                    <div class="tbody">
                        <?php while( $this->bookings->have_posts() ) { $this->bookings->the_post(); ?>
                        <div class="tr">
                            <div class="td"><?php echo the_title(); ?></div>
                            <div class="td">
                                <a href="<?php the_permalink(); ?>" class="btn -sm -primary"><?php esc_html_e( 'See details', 'kanda' ); ?></a>
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
    </div>

    <div class="col-sm-6">
        <div class="box main-content">
            <h2 class="page-title"><?php _e( 'Recent searches', 'kanda' ); ?></h2>
        </div>
    </div>
</div>