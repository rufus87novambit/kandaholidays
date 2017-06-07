<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta charset="UTF-8">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="back-case">
    <header class="header">
        <div class="clearfix">
            <h1 class="back-logo">
                <a href="<?php echo home_url( '/' ); ?>">
                    <?php the_custom_logo(); ?>
                </a>
            </h1>

            <?php wp_nav_menu( array(
                'theme_location' => 'main_nav',
                'menu_class' => 'main-menu',
                'container' => 'nav',
                'container_class' => 'head-nav',
                'container_id' => '',
            ) ); ?>

            <div class="head-side">
                <button class="menu-btn" id="menuBtn">
                    <span class="burger-icon"></span>
                </button><!-- .menu-btn -->
                <div class="agency-logo">
                    <a href="<?php echo kanda_url_to( 'profile' ); ?>" class="avatar"><?php echo kanda_get_user_avatar( false, 'user-avatar', array('class' => 'user-avatar', 'data-default' => kanda_get_user_avatar_url() ) ); ?></a>
                    <div class="sub-menu">
                        <ul>
                            <li><a href="<?php echo kanda_url_to( 'profile', array( 'edit' ) ); ?>"><i class="icon icon-user-tie"></i> <?php esc_html_e( 'Edit profile', 'kanda' ); ?></a></li>
                            <li><a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'password' ) ); ?>"><i class="icon icon-key"></i> <?php esc_html_e( 'Change password', 'kanda' ); ?></a></li>
                            <li><a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'photo' ) ); ?>"><i class="icon icon-image"></i> <?php esc_html_e( 'Edit avatar', 'kanda' ); ?></a></li>
                            <li><a href="<?php echo wp_logout_url( esc_url( site_url( '/' ) ) ); ?>"><i class="icon icon-exit"></i> <?php esc_html_e( 'Logout', 'kanda' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div><!-- .head-side -->
        </div>
        <?php if( ! current_user_can( Kanda_Config::get( 'agency_role' ) ) ) { ?>
        <div class="clearfix text-center cost-price-warning bg-danger">
            <h4>!!! <?php _e( 'Rates are presented', 'kanda' ); ?> !!!</h4>
        </div>
        <?php } ?>
    </header><!-- .header -->
    <section class="main">
        <div class="container">

