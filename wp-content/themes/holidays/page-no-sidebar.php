<?php
/**
 * Template name: Page without sidebar
 */
?>

<?php get_header(); ?>

<div class="container">
    <div class="editor-content">
        <?php the_post(); ?>

        <?php the_title( '<h1>', '</h1>' ); ?>

        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
