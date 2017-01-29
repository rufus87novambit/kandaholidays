<?php
/**
 * Template Name: Portal
 */
get_header();
?>
<div class="portal clearfix">
    <header>
        <div class="clearfix">
            <h1 class="portal-logo">
                <a href="<?php echo site_url( '/portal' ); ?>">
                    <img src="<?php echo HOLIDAYS_THEME_URL; ?>images/delete/logo.png" alt="<?php echo esc_html__( 'logo', 'kanda' ); ?>" />
                </a>
            </h1>
            <nav class="main-menu">
                <ul>
                    <li class="menu-item-has-children">
                        <a href="#">Hotels</a>
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

            <div class="agency-logo">
    <!--            <a href="#" class="avatar avatar-default"><i class="icon icon-plane"></i></a>-->
                <a href="#" class="avatar"><img src="<?php echo HOLIDAYS_THEME_URL; ?>images/delete/profile.jpg" alt="john doe" /></a>
                <div class="sub-menu">
                    <ul>
                        <li><a href="#"><i class="icon icon-user-tie"></i> <?php esc_html_e( 'Edit Profile', 'kanda' ); ?></a></li>
                        <li><a href="#"><i class="icon icon-cog"></i> <?php esc_html_e( 'Settings', 'kanda' ); ?></a></li>
                        <li><a href="<?php echo wp_logout_url( esc_url( site_url( '/' ) ) ); ?>"><i class="icon icon-exit"></i> <?php esc_html_e( 'Logout', 'kanda' ); ?></a></li>
                    </ul>
                </div>
            </div>

            <div class="currency">
                <a href="#"><i class="icon icon-currency-usd"></i></a>
                <nav class="sub-menu">
                    <ul>
                        <li><span><i class="icon icon-currency-usd"></i> 484.00</span></li>
                        <li><span><i class="icon icon-currency-euro"></i> 515.10</span></li>
                        <li><span><i class="icon icon-currency-rur"></i> 8.24</span></li>
                        <li><span><i class="icon icon-currency-gbp"></i> 603.10</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</div>
<?php
get_footer();
?>