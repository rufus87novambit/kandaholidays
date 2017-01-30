<?php

get_header( 'front' );

if( ! is_user_logged_in() ) {
    ?>
    <section class="main clearfix">

        <div class="home-form-wrapper clearfix">

            <div class="form form-forgot-password">

                <h3 class="form-title"><?php esc_html_e( 'Forgot Password', 'kanda' ); ?></h3>

                <?php if( $kanda_request['message'] ) { ?>
                    <div class="message message-<?php echo $kanda_request['success'] ? 'success' : 'error'; ?>"><?php echo $kanda_request['message']; ?></div>
                <?php } ?>

                <p class="instructions"><?php esc_html_e( 'To reset your password please enter your username or email and click \'Submit\' button.', 'kanda' ); ?></p>

                <form method="post">
                    <?php $has_error = ! $kanda_request['fields']['username_email']['valid']; ?>
                    <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                        <label for="username_email"><?php esc_html_e( 'Username / Email', 'kanda' ); ?>:</label>
                        <div class="input-holder">
                            <input id="username_email" name="username_email" type="text" value="<?php echo $kanda_request['fields']['username_email']['value']; ?>" />
                            <?php if( $has_error ) { ?>
                                <p class="help-block"><?php echo $kanda_request['fields']['username_email']['msg']; ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <label class="hidden-xss">&nbsp;</label>
                        <div class="input-holder">
                            <?php wp_nonce_field( 'kanda_forgot', 'kanda_nonce' ); ?>
                            <input type="submit" name="kanda_forgot" value="<?php esc_html_e( 'Submit', 'kanda' ); ?>" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="form-links text-center">
                <a href="<?php echo site_url( '/' ); ?>"><?php esc_html_e( 'Login', 'kanda' ); ?></a>
                <span class="devider">|</span>
                <a href="<?php echo site_url( '/register' ); ?>"><?php esc_html_e( 'Register', 'kanda' ); ?></a>
            </div>
        </div>

    </section>
    <?php
}

get_footer( 'front' );
?>