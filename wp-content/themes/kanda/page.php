<?php get_header(); ?>

<div class="row">
    <?php get_sidebar(); ?>
    <div class="primary col-sm-9">
        <?php if( have_posts() ) { the_post(); ?>
            <div class="box">
                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                <?php the_content(); ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php get_footer(); ?>