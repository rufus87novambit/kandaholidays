<?php
$form_links = array(
    'register' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'register' ), esc_html__( 'Register', 'kanda' ) ),
    'forgot_password' => sprintf( '<a href="%1$s">%2$s</a>', kanda_url_to( 'forgot-password' ), esc_html__( 'Forgot Password', 'kanda' ) ),
);
?>

<section class="main clearfix">
    <div class="home-form-wrapper bordered-box clearfix">
        <div class="form form-login">
            <h1 class="page-title"><?php esc_html_e( 'Login', 'kanda' ); ?></h1>

            <?php if( $this->request['message'] ) { ?>
                <div class="message message-<?php echo $this->request['success'] ? 'success' : 'error'; ?>"><?php echo $this->request['message']; ?></div>
            <?php } ?>

            <form id="form_login" method="post">

                <div class="row clearfix">
                    <label for="username"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>

                    <?php $has_error = ! $this->request['fields']['username']['valid']; ?>
                    <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                        <input id="username" name="username" type="text" value="<?php echo $this->request['fields']['username']['value']; ?>" />
                        <div class="help-block"><?php echo $this->request['fields']['username']['msg']; ?></div>
                    </div>
                </div>

                <div class="row clearfix">
                    <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>

                    <?php $has_error = ! $this->request['fields']['password']['valid']; ?>
                    <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                        <input id="password" name="password" type="password" value="<?php echo $this->request['fields']['password']['value']; ?>" />
                        <div class="help-block"><?php echo $this->request['fields']['password']['msg']; ?></div>
                    </div>
                </div>

                <div class="row clearfix">
                    <label class="hidden-xss">&nbsp;</label>
                    <div class="input-holder">
                        <input type="hidden" name="remember" value="0" />
                        <input type="checkbox" name="remember" value="1" <?php checked( $this->request['fields']['remember']['value'], 1 ); ?> /><span><?php esc_html_e( 'Remember', 'kanda' ); ?></span>
                    </div>
                </div>
                <div class="row clearfix">
                    <label class="hidden-xss">&nbsp;</label>
                    <div class="input-holder">
                        <?php wp_nonce_field( 'kanda_login', 'kanda_nonce' ); ?>
                        <input type="submit" class="btn" name="kanda_login" value="<?php esc_html_e( 'Login', 'kanda' ); ?>" />
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