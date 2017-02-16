<?php
$form_links = array(
    'login' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'login' ), esc_html__( 'Login', 'kanda' ) ),
    'register' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'register' ), esc_html__( 'Register', 'kanda' ) )
);
?>

<section class="main clearfix">
    <div class="home-form-wrapper bordered-box clearfix">
        <div class="form form-forgot-password">

            <h1 class="page-title"><?php esc_html_e( 'Forgot Password', 'kanda' ); ?></h1>

            <?php kanda_show_notification(); ?>

            <?php if( $this->show_form ) { ?>
            <p class="instructions"><?php esc_html_e( 'To reset your password please enter your username or email and click \'Submit\' button.', 'kanda' ); ?></p>

            <form id="form_forgot_password" method="post">

                <div class="row clearfix">
                    <label for="username_email"><?php esc_html_e( 'Username / Email', 'kanda' ); ?>:</label>

                    <?php $has_error = isset( $this->errors['username_email'] ); ?>
                    <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                        <input id="username_email" name="username_email" type="text" value="<?php echo $this->username_email; ?>" />
                        <div class="help-block"><?php echo $this->errors['username_email']; ?></div>
                    </div>
                </div>

                <div class="row clearfix">
                    <label class="hidden-xss">&nbsp;</label>
                    <div class="input-holder">
                        <?php wp_nonce_field( 'kanda_security_forgot', 'security' ); ?>
                        <input type="submit" class="btn" name="kanda_forgot" value="<?php esc_html_e( 'Submit', 'kanda' ); ?>" />
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