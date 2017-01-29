<?php
get_header( 'front' );

if( ! is_user_logged_in() ) {
    ?>
    <section class="main clearfix">

        <div class="home-form-wrapper clearfix">

            <div class="form form-login">

                <h3 class="form-title"><?php esc_html_e( 'Login', 'kanda' ); ?></h3>

                <?php if( $kanda_request['message'] ) { ?>
                    <div class="message message-<?php echo $kanda_request['success'] ? 'success' : 'error'; ?>"><?php echo $kanda_request['message']; ?></div>
                <?php } ?>

                <form method="post">
                    <?php $has_error = ! $kanda_request['fields']['username']['valid']; ?>
                    <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                        <label for="username"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>
                        <div class="input-holder">
                            <input id="username" name="username" type="text" value="<?php echo $kanda_request['fields']['username']['value']; ?>" />
                            <?php if( $has_error ) { ?>
                                <p class="help-block"><?php echo $kanda_request['fields']['username']['msg']; ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <?php $has_error = ! $kanda_request['fields']['password']['valid']; ?>
                    <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                        <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>
                        <div class="input-holder">
                            <input id="password" name="password" type="password" value="<?php echo $kanda_request['fields']['password']['value']; ?>" />
                            <?php if( $has_error ) { ?>
                                <p class="help-block"><?php echo $kanda_request['fields']['password']['msg']; ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <label class="hidden-xss">&nbsp;</label>
                        <div class="input-holder">
                            <input type="hidden" name="remember" value="0" />
                            <input type="checkbox" name="remember" value="1" <?php checked( $kanda_request['fields']['remember']['value'], 1 ); ?> /><span><?php esc_html_e( 'Remember', 'kanda' ); ?></span>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="hidden-xss">&nbsp;</label>
                        <div class="input-holder">
                            <?php wp_nonce_field( 'kanda_login', 'kanda_nonce' ); ?>
                            <input type="submit" name="kanda_login" value="<?php esc_html_e( 'Login', 'kanda' ); ?>" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="form-links text-center">
                <a href="<?php echo site_url( '/register' ); ?>"><?php esc_html_e( 'Register', 'kanda' ); ?></a>
                <span class="devider">|</span>
                <a href="<?php echo site_url( '/forgot-password' ); ?>"><?php esc_html_e( 'Forgot Password', 'kanda' ); ?></a>
            </div>
        </div>
    </section>
    <?php
}

get_footer( 'front' );
?>