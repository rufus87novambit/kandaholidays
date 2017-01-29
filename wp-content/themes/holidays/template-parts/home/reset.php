<?php

get_header( 'front' );

if( ! is_user_logged_in() ) {
    ?>
    <section class="main clearfix">

        <div class="home-form-wrapper clearfix">

            <div class="form form-reset-password">

                <h3 class="form-title"><?php esc_html_e( 'Reset Password', 'kanda' ); ?></h3>

                <?php if( $kanda_request['has_error'] ) { ?>
                    <p class="instructions"><?php esc_html_e( 'Invalid Request', 'kanda' ); ?></p>
                <?php } else { ?>
                    <?php if( $kanda_request['message'] ) { ?>
                        <div class="message message-<?php echo $kanda_request['success'] ? 'success' : 'error'; ?>"><?php echo $kanda_request['message']; ?></div>
                    <?php } ?>

                    <form method="post">
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

                        <?php $has_error = ! $kanda_request['fields']['confirm_password']['valid']; ?>
                        <div class="row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                            <label for="confirm_password"><?php esc_html_e( 'Confirm Password', 'kanda' ); ?>:</label>
                            <div class="input-holder">
                                <input id="confirm_password" name="confirm_password" type="password" value="<?php echo $kanda_request['fields']['confirm_password']['value']; ?>" />
                                <?php if( $has_error ) { ?>
                                    <p class="help-block"><?php echo $kanda_request['fields']['confirm_password']['msg']; ?></p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label class="hidden-xss">&nbsp;</label>
                            <div class="input-holder">
                                <?php wp_nonce_field( 'kanda_reset', 'kanda_nonce' ); ?>
                                <input type="hidden" name="user_id" value="<?php echo $kanda_request['fields']['user_id']['value']; ?>" />
                                <input type="submit" name="kanda_reset" value="<?php esc_html_e( 'Reset', 'kanda' ); ?>" />
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
            <div class="form-links text-center">
                <a href="<?php echo site_url( '/forgot-password' ); ?>"><?php esc_html_e( 'Forgot Password', 'kanda' ); ?></a>
            </div>
        </div>
    </section>
    <?php
}

get_footer( 'front' );
?>