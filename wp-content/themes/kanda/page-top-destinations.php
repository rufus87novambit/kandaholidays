<?php
/**
 * Template Name: Top Destinations List
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

                $wp_query = new WP_Query(array(
                    'post_type'         => 'top-destination',
                    'post_status'       => 'publish',
                    'order'             => 'DESC',
                    'orderby'           => 'menu_order',
                    'posts_per_page'    => 3,
                    'paged'  => kanda_get_paged(),
                ));
                if( have_posts() ) { ?>
                <ul class="articles-list">
                <?php while( have_posts() ) { the_post();
                    get_template_part( 'loop-top-destination' );
                }
                ?>
                </ul>
                <div class="pagination text-center">
                    <?php the_posts_pagination(array(
                        'mid_size'           => 1,
                        'prev_text'          => '&laquo;',
                        'next_text'          => '&raquo',
                        'screen_reader_text' => ' '
                    )); ?>
                </div>
                <?php } ?>
                <?php $wp_query = $tmp_query; ?>
            </div>
        <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>

