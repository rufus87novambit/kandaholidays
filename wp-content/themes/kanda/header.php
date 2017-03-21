<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta charset="UTF-8">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="back-case">
    <header class="header clearfix">
        <h1 class="back-logo">
            <a href="<?php echo home_url( '/' ); ?>">
                <img src="<?php echo KANDA_THEME_URL; ?>images/delete/logo.png" alt="<?php echo esc_html__( 'logo', 'kanda' ); ?>" />
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
                        <li><a href="#"><i class="icon icon-cog"></i> <?php esc_html_e( 'Settings', 'kanda' ); ?></a></li>
                        <li><a href="<?php echo wp_logout_url( esc_url( site_url( '/' ) ) ); ?>"><i class="icon icon-exit"></i> <?php esc_html_e( 'Logout', 'kanda' ); ?></a></li>
                    </ul>
                </div>
            </div>
        </div><!-- .head-side -->
    </header><!-- .header -->
    <section class="main">
        <div class="container">

