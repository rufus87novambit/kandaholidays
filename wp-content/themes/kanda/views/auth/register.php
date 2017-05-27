<?php get_header( 'guests' ); ?>

<?php
$form_links = array(
    'login' => sprintf( '<span>%1$s</span> <a href="%2$s">%3$s</a>', esc_html__( 'Back to', 'kanda' ), kanda_url_to( 'login' ), esc_html__( 'Login', 'kanda' ) )
);
?>

<section class="main clearfix">
    <div class="home-form-wrapper bordered-box clearfix">
        <div class="form form-register clearfix">

            <h1 class="page-title"><?php esc_html_e( 'Registration', 'kanda' ); ?></h1>

            <?php kanda_show_notification(); ?>

            <form id="form_register" method="post">
                <div class="clearfix">
                    <div class="column">

                        <div class="row clearfix">
                            <label for="username"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['username']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="1" id="username" name="personal[username]" type="text" value="<?php echo $this->request['fields']['personal']['username']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['username']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="email"><?php esc_html_e( 'Email', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['email']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="2" id="email" name="personal[email]" type="text" value="<?php echo $this->request['fields']['personal']['email']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['email']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['password']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="3" id="password" name="personal[password]" type="password" value="<?php echo $this->request['fields']['personal']['password']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['password']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="confirm_password"><?php esc_html_e( 'Confirm', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['confirm_password']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="4" id="confirm_password" name="personal[confirm_password]" type="password" value="<?php echo $this->request['fields']['personal']['confirm_password']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['confirm_password']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="first_name"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['first_name']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="5" id="first_name" name="personal[first_name]" type="text" value="<?php echo $this->request['fields']['personal']['first_name']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['first_name']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="last_name"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['last_name']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="6" id="last_name" name="personal[last_name]" type="text" value="<?php echo $this->request['fields']['personal']['last_name']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['last_name']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="mobile"><?php esc_html_e( 'Mobile', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['mobile']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="7" id="mobile" name="personal[mobile]" type="text" class="optional" value="<?php echo $this->request['fields']['personal']['mobile']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['mobile']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="position"><?php esc_html_e( 'Position', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['personal']['position']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="8" id="position" name="personal[position]" type="text" value="<?php echo $this->request['fields']['personal']['position']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['personal']['position']['msg']; ?></div>
                            </div>
                        </div>

                    </div>

                    <div class="column">

                        <div class="row clearfix">
                            <label for="company_name"><?php esc_html_e( 'Company Name', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['name']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="9" id="company_name" name="company[name]" type="text" value="<?php echo $this->request['fields']['company']['name']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['name']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_license"><?php esc_html_e( 'License ID', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['license']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="10" id="company_license" name="company[license]" type="text" value="<?php echo $this->request['fields']['company']['license']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['license']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_address"><?php esc_html_e( 'Address', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['address']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="11" id="company_address" name="company[address]" type="text" value="<?php echo $this->request['fields']['company']['address']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['address']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_city"><?php esc_html_e( 'City', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['city']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="12" id="company_city" name="company[city]" type="text" value="<?php echo $this->request['fields']['company']['city']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['city']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_country"><?php esc_html_e( 'Country', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['country']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="13" id="company_country" name="company[country]" type="text" value="<?php echo $this->request['fields']['company']['country']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['country']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_phone"><?php esc_html_e( 'Company Phone', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['phone']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="14" id="company_phone" name="company[phone]" type="text" class="optional" value="<?php echo $this->request['fields']['company']['phone']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['phone']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="company_website"><?php esc_html_e( 'Website', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $this->request['fields']['company']['website']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input tabindex="15" id="company_website" name="company[website]" type="text" value="<?php echo $this->request['fields']['company']['website']['value']; ?>" />
                                <div class="help-block"><?php echo $this->request['fields']['company']['website']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">

                            <?php $has_error = ! $this->request['fields']['captcha']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>" style="float: none; width: auto;">
                                <div class="g-recaptcha-outer">
                                    <div class="g-recaptcha-inner">
                                        <div class="g-recaptcha" data-sitekey="<?php echo Kanda_Config::get( 'google_site_key' ); ?>"></div>
                                    </div>
                                </div>
                                <div class="help-block"><?php echo $this->request['fields']['captcha']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label class="hidden-xss">&nbsp;</label>
                            <div class="input-holder">
                                <input type="hidden" name="kanda_nonce" value="<?php echo wp_create_nonce( 'kanda_register' ); ?>">
                                <input type="submit" class="btn" name="kanda_register" value="<?php esc_html_e( 'Register', 'kanda' ); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <?php
        if( (bool)$form_links ) {
            printf( '<div class="form-links text-center">%s</div>', implode( '<span class="devider">|</span>', $form_links ) );
        }
        ?>
    </div>
</section>