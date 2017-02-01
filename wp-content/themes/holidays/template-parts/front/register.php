<?php get_header( 'guest' ); ?>

<?php
$form_links = array();
if( $login_page_id = kanda_fields()->get_option( 'kanda_auth_page_login' ) ) {
    $login_page = get_post( (int)$login_page_id );

    $form_links['login'] = sprintf(
        '<span>%1$s</span> <a href="%2$s">%3$s</a>',
        esc_html__( 'Back to', 'kanda' ),
        get_permalink( (int)$login_page_id ),
        apply_filters( 'the_title', $login_page->post_title, $login_page_id )
    );
}
?>

<div class="container-large">
    <section class="main clearfix">
        <div class="home-form-wrapper bordered-box clearfix">
            <div class="form form-register clearfix">

                <h1 class="page-title"><?php esc_html_e( 'Registration', 'kanda' ); ?></h1>

                <?php if( $kanda_request['message'] ) { ?>
                    <div class="message message-<?php echo $kanda_request['success'] ? 'success' : 'error'; ?>"><?php echo $kanda_request['message']; ?></div>
                <?php } ?>

                <form method="post">
                    <div class="clearfix">
                        <div class="column">
                            <?php $has_error = ! $kanda_request['fields']['personal']['username']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="username"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="1" id="username" name="personal[username]" type="text" value="<?php echo $kanda_request['fields']['personal']['username']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['username']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['email']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="email"><?php esc_html_e( 'Email', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="2" id="email" name="personal[email]" type="text" value="<?php echo $kanda_request['fields']['personal']['email']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['email']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['password']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="3" id="password" name="personal[password]" type="password" value="<?php echo $kanda_request['fields']['personal']['password']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['password']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['confirm_password']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="confirm_password"><?php esc_html_e( 'Confirm', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="4" id="confirm_password" name="personal[confirm_password]" type="password" value="<?php echo $kanda_request['fields']['personal']['confirm_password']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['confirm_password']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['first_name']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="first_name"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="5" id="first_name" name="personal[first_name]" type="text" value="<?php echo $kanda_request['fields']['personal']['first_name']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['first_name']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['last_name']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="last_name"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="6" id="last_name" name="personal[last_name]" type="text" value="<?php echo $kanda_request['fields']['personal']['last_name']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['last_name']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['mobile']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="mobile"><?php esc_html_e( 'Mobile', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="7" id="mobile" name="personal[mobile]" type="text" value="<?php echo $kanda_request['fields']['personal']['mobile']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['mobile']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['position']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="position"><?php esc_html_e( 'Position', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="11" id="position" name="personal[position]" type="text" value="<?php echo $kanda_request['fields']['personal']['position']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['personal']['position']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>

                        <div class="column">

                            <?php $has_error = ! $kanda_request['fields']['company']['name']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_name"><?php esc_html_e( 'Company Name', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="8" id="company_name" name="company[name]" type="text" value="<?php echo $kanda_request['fields']['company']['name']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['name']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['license']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_license"><?php esc_html_e( 'License ID', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="9" id="company_license" name="company[license]" type="text" value="<?php echo $kanda_request['fields']['company']['license']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['license']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['address']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_address"><?php esc_html_e( 'Address', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="10" id="company_address" name="company[address]" type="text" value="<?php echo $kanda_request['fields']['company']['address']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['address']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['city']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_city"><?php esc_html_e( 'City', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="12" id="company_city" name="company[city]" type="text" value="<?php echo $kanda_request['fields']['company']['city']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['city']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['country']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_country"><?php esc_html_e( 'Country', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="13" id="company_country" name="company[country]" type="text" value="<?php echo $kanda_request['fields']['company']['country']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['country']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['phone']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_phone"><?php esc_html_e( 'Company Phone', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="14" id="company_phone" name="company[phone]" type="text" value="<?php echo $kanda_request['fields']['company']['phone']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['phone']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['company']['website']['valid']; ?>
                            <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <label for="company_website"><?php esc_html_e( 'Website', 'kanda' ); ?>:</label>
                                <div class="input-holder">
                                    <input tabindex="15" id="company_website" name="company[website]" type="text" value="<?php echo $kanda_request['fields']['company']['website']['value']; ?>" />
                                    <?php if( $has_error ) { ?>
                                        <p class="help-block"><?php echo $kanda_request['fields']['company']['website']['msg']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $has_error = ! $kanda_request['fields']['personal']['mobile']['valid']; ?>
                            <div class="row text-center clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                                <div class="g-recaptcha-outer">
                                    <div class="g-recaptcha-inner">
                                        <div class="g-recaptcha" data-sitekey="6LdafxMUAAAAAP-E5ksEG5SVw-_PlR4rcJggUNpm"></div>
                                    </div>
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
</div>

<?php get_footer( 'guest' ); ?>