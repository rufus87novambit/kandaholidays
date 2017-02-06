<?php get_header(); ?>

<div class="container">
    <div class="content clearfix">
        <div id="main" class="site-main" role="main">
            <?php if( have_posts() ) { the_post(); ?>

                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                <div class="editor-content">
                    <?php the_content(); ?>
                </div>

            <?php } ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>