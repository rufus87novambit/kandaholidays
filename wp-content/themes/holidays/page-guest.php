<?php
/**
 * Template Name: For Guests
 */
?>
<?php do_action( 'kanda/deny_user_access', KH_Config::get( 'agency_role' ) ); ?>

<?php get_header( 'guest' ); ?>

<?php if( have_posts() ) { the_post(); ?>
<div class="container">
    <div class="page-wrapper bordered-box">

        <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <div class="entry-content"><?php the_content(); ?></div>
        </article>

    </div>
</div>
<?php } ?>

<?php get_footer( 'guest' ); ?>