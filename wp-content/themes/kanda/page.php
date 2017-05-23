<?php get_header(); ?>

<div class="row">
    <div class="primary col-md-9">
        <?php if( have_posts() ) { the_post(); ?>
            <?php
                if( get_field( 'show_main_banner' ) ) {
                    kanda_render_banners( 'main', array( 'class' => 'main_banners' ) );
                }
            ?>
            <div class="box main-content">
                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                <?php the_content(); ?>
            </div>
        <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>