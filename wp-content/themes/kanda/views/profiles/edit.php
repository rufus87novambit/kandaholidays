<?php kanda_show_notification( $this->notification ); ?>
<form class="form-block form-inline" method="post">
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'GENERAL', 'kanda' ); ?></legend>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?php echo $this->user_login; ?>" readonly disabled>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Company Name', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?php echo $this->company_name; ?>" readonly disabled>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'License ID', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?php echo $this->company_license; ?>" readonly disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <img style="max-width: 150px; border-radius: 50%;" src="<?php echo KANDA_THEME_URL; ?>images/delete/profile.jpg" alt="john doe" />
            </div>
        </div>
    </fieldset>
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'PROFILE', 'kanda' ); ?></legend>
        <div class="row">
            <div class="col-sm-6">

                <?php $has_error = isset( $this->errors[ 'user_email' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Email', 'kanda' ); ?>: *</label>
                    <div class="col-sm-8">
                        <input type="email" name="user_email" class="form-control" value="<?php echo $this->user_email; ?>">
                        <?php if( $has_error && $this->errors[ 'user_email' ] ) { ?>
                        <div class="error-msg"><?php echo $this->errors[ 'user_email' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'first_name' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'First Name', 'kanda' ); ?>: *</label>
                    <div class="col-sm-8">
                        <input type="text" name="first_name" class="form-control" value="<?php echo $this->first_name; ?>">
                        <?php if( $has_error && $this->errors[ 'first_name' ] ) { ?>
                            <div class="error-msg"><?php echo $this->errors[ 'first_name' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'last_name' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Last Name', 'kanda' ); ?>: *</label>
                    <div class="col-sm-8">
                        <input type="text" name="last_name" class="form-control" value="<?php echo $this->last_name; ?>">
                        <?php if( $has_error && $this->errors[ 'last_name' ] ) { ?>
                            <div class="error-msg"><?php echo $this->errors[ 'last_name' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'mobile' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Mobile', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="mobile" class="form-control" value="<?php echo $this->mobile; ?>">
                        <?php if( $has_error && $this->errors[ 'mobile' ] ) { ?>
                            <div class="error-msg"><?php echo $this->errors[ 'mobile' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Position', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="position" class="form-control" value="<?php echo $this->position; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">

                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Address', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company_address" class="form-control" value="<?php echo $this->company_address; ?>">
                    </div>
                </div>

                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'City', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company_city" class="form-control" value="<?php echo $this->company_city; ?>">
                    </div>
                </div>

                <div class="form-group row clearfix">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Country', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company_country" class="form-control" value="<?php echo $this->company_country; ?>">
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_phone' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Company Phone', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company_phone" class="form-control" value="<?php echo $this->company_phone; ?>">
                        <?php if( $has_error && $this->errors[ 'company_phone' ] ) { ?>
                            <div class="error-msg"><?php echo $this->errors[ 'company_phone' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_website' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Website', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" name="company_website" class="form-control" value="<?php echo $this->company_website; ?>">
                        <?php if( $has_error && $this->errors[ 'company_website' ] ) { ?>
                            <div class="error-msg"><?php echo $this->errors[ 'company_website' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
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
                            <div class="error-msg"><?php echo $this->errors[ 'old_password' ]; ?></div>
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
                            <div class="error-msg"><?php echo $this->errors[ 'new_password' ]; ?></div>
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
                            <div class="error-msg"><?php echo $this->errors[ 'confirm_password' ]; ?></div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </fieldset>
    <div class="text-right">
        <?php wp_nonce_field( 'kanda_save_profile', 'security' ); ?>
        <button type="submit" class="btn -primary" name="kanda_save"><?php _e( 'Update', 'kanda' ); ?></button>
        <a role="button" href="<?php echo kanda_url_to( 'profile' ); ?>" class="btn -secondary"><?php esc_html_e( 'Cancel', 'kanda' ); ?></a>
    </div>
</form>