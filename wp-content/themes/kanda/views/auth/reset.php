<?php
$form_links = array(
    'forgot_password' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'forgot-password' ), esc_html__( 'Forgot Password', 'kanda' ) )
);
?>

<section class="main clearfix">
    <div class="home-form-wrapper bordered-box clearfix">
        <div class="form form-reset-password">

            <h1 class="page-title"><?php esc_html_e( 'Reset Password', 'kanda' ); ?></h1>

            <?php if( $this->request['has_error'] ) { ?>
                <p class="instructions"><?php echo $this->request['message']; ?></p>
            <?php } else { ?>
                <?php kanda_show_notification( $this->notification ); ?>

                <form id="form_reset_password" method="post">

                    <div class="row clearfix">
                        <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>

                        <?php $has_error = isset( $this->errors['password'] ); ?>
                        <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                            <input id="password" name="password" type="password" value="<?php echo $this->password; ?>" />
                            <div class="help-block"><?php echo $this->errors['password']; ?></div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <label for="confirm_password"><?php esc_html_e( 'Confirm Password', 'kanda' ); ?>:</label>

                        <?php $has_error = isset( $this->errors['confirm_password'] ); ?>
                        <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                            <input id="confirm_password" name="confirm_password" type="password" value="<?php echo $this->confirm_password; ?>" />
                            <div class="help-block"><?php echo $this->errors['confirm_password']; ?></div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <label class="hidden-xss">&nbsp;</label>
                        <div class="input-holder">
                            <?php wp_nonce_field( 'kanda_security_reset', 'security' ); ?>
                            <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" />
                            <input type="submit" class="btn" name="kanda_reset" value="<?php esc_html_e( 'Reset', 'kanda' ); ?>" />
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>

        <?php
        if( (bool)$form_links ) {
            printf( '<div class="form-links text-center">%s</div>', implode( '<span class="devider">|</span>', $form_links ) );
        }
        ?>
    </div>
</section>