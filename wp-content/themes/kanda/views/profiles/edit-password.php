<?php kanda_show_notification(); ?>

<div class="secondary-menu">
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-user-tie"></i> <?php esc_html_e( 'Edit Profile', 'kanda' ); ?></a>
    <a href="javascript:void(0);" rel="button" class="btn -warning -sm"><i class="icon icon-key"></i> <?php esc_html_e( 'Change password', 'kanda' ); ?></a>
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'photo' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-image"></i> <?php esc_html_e( 'Change avatar', 'kanda' ); ?></a>
</div>

<form class="form-block form-inline" id="form_edit_password" method="post">
    <fieldset class="fieldset sep-btm">
        <div class="row">
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'old_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Old password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" id="old_password" name="old_password" class="form-control" value="<?php echo $this->old_password; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'old_password' ] : ''; ?></small></div>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'new_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'New password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" id="new_password" name="new_password" class="form-control" value="<?php echo $this->new_password; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'new_password' ] : ''; ?></small></div>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <?php $has_error = isset( $this->errors[ 'confirm_password' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Confirm password', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="<?php echo $this->confirm_password; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'confirm_password' ] : ''; ?></small></div>
                    </div>
                </div>

            </div>
        </div>
    </fieldset>
    <div class="text-right">
        <?php wp_nonce_field( 'kanda_save_password', 'security' ); ?>
        <button type="submit" class="btn -primary" name="kanda_save"><?php _e( 'Update', 'kanda' ); ?></button>
        <a role="button" href="<?php echo kanda_url_to( 'profile', array( 'edit', 'password' ) ); ?>" class="btn -danger"><?php esc_html_e( 'Cancel', 'kanda' ); ?></a>
    </div>
</form>