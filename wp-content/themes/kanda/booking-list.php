<?php
/**
 * Template Name: Booking List
 */
get_header(); ?>
<div class="row">
    <div class="primary col-md-9">
    <?php if( have_posts() ) { the_post(); ?>
        <div class="box main-content">
            <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

            <?php
            global $wp_query;
            $tmp_query = $wp_query;

            $wp_query = new WP_Query( array(
                'post_type' => 'booking',
                'post_status' => 'publish',
                'author' => get_current_user_id(),
                'paged'  => kanda_get_paged(),
                'order' => 'DESC',
                'orderby' => 'date',
                'posts_per_page' => get_option( 'posts_per_page' ),
                'meta_query' => array(
                    array(
                        'key' => 'end_date',
                        'value' => date('Ymd'),
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                )
            ) );

            if( have_posts() ) {
            ?>
            <div class="users-table table">
                <header class="thead">
                    <div class="th" style="width: 50%;"><?php esc_html_e( 'Name', 'kanda' ); ?></div>
                    <div class="th"><?php esc_html_e( 'Start Date', 'kanda' ); ?></div>
                    <div class="th"><?php esc_html_e( 'End Date', 'kanda' ); ?></div>
                    <div class="th"><?php esc_html_e( 'Actions', 'kanda' ); ?></div>
                </header>
                <div class="tbody">
                    <?php while( have_posts() ) { the_post(); ?>
                    <div class="tr">
                        <div class="td" style="width: 50%;"><?php echo the_title(); ?></div>
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
            <?php }
            $wp_query = $tmp_query;
            ?>
        </div>
    <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer();