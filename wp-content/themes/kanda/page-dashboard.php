<?php
/**
 * Template Name: Dashboard
 */
?>
<?php get_header(); ?>

<div class="row">
    <div class="primary col-md-9">
        <?php if( have_posts() ) { the_post(); ?>
            <?php the_content(); ?>
        <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>