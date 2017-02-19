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
        <button class="menu-btn" id="menuBtn">
            <span class="burger-icon"></span>
        </button><!-- .menu-btn -->
        <nav class="head-nav">
            <ul class="main-menu">
                <li class="menu-item-has-children">
                    <a href="#">Hotels</a>
                    <span class="sub-toggler"></span>
                    <ul class="sub-menu">
                        <li><a href="#">Sub Item 1</a></li>
                        <li><a href="#">Sub Item 2</a></li>
                        <li><a href="#">Sub Item 3</a></li>
                        <li><a href="#">Sub Item 4</a></li>
                        <li><a href="#">Sub Item 5</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Special</a>
                </li>
                <li>
                    <a href="#">Transfers</a>
                </li>
                <li>
                    <a href="#">Tours</a>
                </li>
                <li class="menu-item-has-children">
                    <a href="#">Visa services</a>
                    <ul class="sub-menu">
                        <li><a href="#">Sub Item 1</a></li>
                        <li><a href="#">Sub Item 2</a></li>
                        <li><a href="#">Sub Item 3</a></li>
                        <li><a href="#">Sub Item 4</a></li>
                        <li><a href="#">Sub Item 5</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Group bookings</a>
                </li>
                <li>
                    <a href="#">Top Destinations</a>
                </li>
            </ul>
        </nav>

        <div class="head-side">
            <div class="currency">
                <a href="#"><i class="icon icon-currency-usd"></i></a>
                <nav class="sub-menu">
                    <ul>
                        <?php foreach( kanda_get_exchange() as $iso => $rate ) { ?>
                            <li><span><?php echo $iso; ?> <?php echo number_format( $rate['Rate'], 2 ); ?></span></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
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

