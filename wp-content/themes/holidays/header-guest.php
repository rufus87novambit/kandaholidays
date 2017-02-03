<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="UTF-8">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php get_template_part( 'views/front/slides' ); ?>

        <div class="wrapper">

            <header class="page-header clearfix">
                <h1 class="logo site-title">
                    <a href="<?php echo esc_url( '/' ); ?>">
                        <img src="<?php echo KH_THEME_URL; ?>images/delete/logo.png" alt="<?php esc_html_e( 'logo', 'kanda' ); ?>">
                    </a>
                </h1>
            </header>