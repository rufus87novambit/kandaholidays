<?php kanda_show_notification(); ?>
<form class="form-block form-inline" enctype="multipart/form-data" method="post">
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'CHANGE PASSWORD', 'kanda' ); ?></legend>
        <div class="row">
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'old_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Old password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" name="old_password" class="form-control" value="<?php echo $this->old_password; ?>">
                        <?php if( $has_error && $this->errors[ 'old_password' ] ) { ?>
                            <div class="form-control-feedback"><small><?php echo $this->errors[ 'old_password' ]; ?></small></div>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'new_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'New password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" name="new_password" class="form-control" value="<?php echo $this->new_password; ?>">
                        <?php if( $has_error && $this->errors[ 'new_password' ] ) { ?>
                            <div class="form-control-feedback"><small><?php echo $this->errors[ 'new_password' ]; ?></small></div>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'confirm_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Confirm password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $this->confirm_password; ?>">
                        <?php if( $has_error && $this->errors[ 'confirm_password' ] ) { ?>
                            <div class="form-control-feedback"><small><?php echo $this->errors[ 'confirm_password' ]; ?></small></div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </fieldset>
    <div class="text-right">
        <?php wp_nonce_field( 'kanda_save_password', 'security' ); ?>
        <button type="submit" class="btn -primary" name="kanda_save"><?php _e( 'Update', 'kanda' ); ?></button>
        <a role="button" href="<?php echo kanda_url_to( 'profile', array( 'edit', 'password' ) ); ?>" class="btn -secondary"><?php esc_html_e( 'Cancel', 'kanda' ); ?></a>
    </div>
</form>