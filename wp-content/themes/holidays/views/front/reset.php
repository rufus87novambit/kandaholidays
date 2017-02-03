<?php get_header( 'guest' ); ?>

<?php
$form_links = array();
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
            <div class="form form-reset-password">

                <h1 class="page-title"><?php esc_html_e( 'Reset Password', 'kanda' ); ?></h1>

                <?php if( $kanda_request['has_error'] ) { ?>
                    <p class="instructions"><?php esc_html_e( 'Invalid Request', 'kanda' ); ?></p>
                <?php } else { ?>
                    <?php if( $kanda_request['message'] ) { ?>
                        <div class="message message-<?php echo $kanda_request['success'] ? 'success' : 'error'; ?>"><?php echo $kanda_request['message']; ?></div>
                    <?php } ?>

                    <form id="form_reset_password" method="post">

                        <div class="row clearfix">
                            <label for="password"><?php esc_html_e( 'Password', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $kanda_request['fields']['password']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input id="password" name="password" type="password" value="<?php echo $kanda_request['fields']['password']['value']; ?>" />
                                <div class="help-block"><?php echo $kanda_request['fields']['password']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label for="confirm_password"><?php esc_html_e( 'Confirm Password', 'kanda' ); ?>:</label>

                            <?php $has_error = ! $kanda_request['fields']['confirm_password']['valid']; ?>
                            <div class="input-holder <?php echo $has_error ? 'has-error' : ''; ?>">
                                <input id="confirm_password" name="confirm_password" type="password" value="<?php echo $kanda_request['fields']['confirm_password']['value']; ?>" />
                                <div class="help-block"><?php echo $kanda_request['fields']['confirm_password']['msg']; ?></div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <label class="hidden-xss">&nbsp;</label>
                            <div class="input-holder">
                                <?php wp_nonce_field( 'kanda_reset', 'kanda_nonce' ); ?>
                                <input type="hidden" name="user_id" value="<?php echo $kanda_request['fields']['user_id']['value']; ?>" />
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
</div>

<?php get_footer( 'guest' ); ?>