<?php get_header( 'guest' ); ?>

<?php
$form_links = array();
if( $register_page_id = kanda_fields()->get_option( 'kanda_auth_page_register' ) ) {
    $register_page = get_post( (int)$register_page_id );

    $form_links['register'] = sprintf(
        '<a href="%1$s">%2$s</a>',
        get_permalink( (int)$register_page_id ),
        apply_filters( 'the_title', $register_page->post_title, $register_page_id )
    );
}
if( $forgot_password_page_id = kanda_fields()->get_option( 'kanda_auth_page_forgot' ) ) {
    $forgot_password_page = get_post( $forgot_password_page_id );

    $form_links['forgot_password'] = sprintf(
        '<a href="%1$s">%2$s</a>',
        get_permalink( (int)$forgot_password_page_id ),
        apply_filters( 'the_title', $forgot_password_page->post_title, $forgot_password_page_id )
    );
}
?>

<div class="container-large">
    <section class="main clearfix">
        <div class="home-form-wrapper bordered-box clearfix">
            <div class="form form-login">
                <h1 class="page-title"><?php esc_html_e( 'Login', 'kanda' ); ?></h1>

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
</div>

<?php get_footer( 'front' ); ?>