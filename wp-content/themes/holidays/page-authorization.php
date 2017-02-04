<?php
/**
 * Template Name: Authorization
 */
get_header( 'guests' );

if( have_posts() ) { the_post();
?>
<div class="container-large">
    <?php the_content(); ?>
</div>
<?php }

get_footer( 'guest' ); ?>