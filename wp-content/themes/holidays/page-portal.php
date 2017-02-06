<?php
/**
 * Template Name: Portal
 */
get_header();
?>
    <div class="portal">
        <header class="header clearfix">
                <h1 class="portal-logo">
                    <a href="<?php echo site_url( '/portal' ); ?>">
                        <img src="<?php echo KH_THEME_URL; ?>images/delete/logo.png" width="250" alt="<?php echo esc_html__( 'logo', 'kanda' ); ?>" />
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
                    <a href="#" class="avatar"><img src="<?php echo KH_THEME_URL; ?>images/delete/profile.jpg" alt="john doe" /></a>
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
                            <?php foreach( kanda_get_exchange() as $iso => $rate ) { ?>
                                <li><span><?php echo $iso; ?> <?php echo number_format( $rate['Rate'], 2 ); ?></span></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
        </header><!-- .header -->
        <section class="main">
            <div class="container">
                <div class="row">
                    <aside class="sidebar col-sm-4">
                        <div class="box">
                            <ul class="side-nav">
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                            </ul>
                            <ul class="side-nav">
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                                <li><a href="">sdfsdf</a></li>
                            </ul>
                        </div>
                    </aside>
                    <div class="primary col-sm-8">
                        <div class="box">

                            <form class="form-block">
                                <fieldset class="fieldset sep-btm">
                                    <h4 class="form-title">Form title</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <div class="select-wrap">
                                                <select class="custom-select" name="name[]">
                                                    <option class="placeholder" selected disabled>Select label</option>
                                                    <option>value1</option>
                                                    <option>value2</option>
                                                    <option>value3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control" placeholder="Input text">
                                        </div>
                                    </div>
                                    <div class="form-notice text-right">* Form notice example</div>
                                    <div class="ctrl-group">
                                        <label class="ctrl-field -rbtn">
                                            <input type='radio' class="ctrl-inp" name="radio_group3" checked>
                                            <span class="ctrl-btn"></span>
                                            <span class="ctrl-label">radio label</span>
                                        </label>
                                        <label class="ctrl-field -rbtn">
                                            <input type='radio' class="ctrl-inp" name="radio_group3">
                                            <span class="ctrl-btn"></span>
                                            <span class="ctrl-label">radio label</span>
                                        </label>
                                    </div>
                                    <div class="ctrl-group">
                                        <label class="ctrl-field -chbox">
                                            <input type='checkbox' class="ctrl-inp" name="" checked>
                                            <span class="ctrl-btn"></span>
                                            <span class="ctrl-label">Checkbox label</span>
                                        </label>
                                        <label class="ctrl-field -chbox">
                                            <input type='checkbox' class="ctrl-inp" name="" checked>
                                            <span class="ctrl-btn"></span>
                                            <span class="ctrl-label">Checkbox label</span>
                                        </label>
                                        <label class="ctrl-field -chbox">
                                            <input type='checkbox' class="ctrl-inp" name="">
                                            <span class="ctrl-btn"></span>
                                            <span class="ctrl-label">Checkbox label</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- .main -->
    </div>
<?php
get_footer();
?>