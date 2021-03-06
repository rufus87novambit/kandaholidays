<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="UTF-8">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php get_template_part( 'views/partials/slides' ); ?>

        <div class="wrapper">

            <header class="page-header clearfix">
                <h1 class="logo site-title">
                    <a href="<?php echo get_option( 'home' ); ?>">
                        <img src="<?php echo KANDA_THEME_URL; ?>images/delete/logo.png" alt="<?php esc_html_e( 'logo', 'kanda' ); ?>">
                    </a>
                </h1>
            </header>