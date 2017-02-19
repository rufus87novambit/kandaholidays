<?php kanda_show_notification(); ?>

<div class="secondary-menu">
    <a href="javascript:void(0);" rel="button" class="btn -warning -sm"><i class="icon icon-user-tie"></i> <?php esc_html_e( 'Edit Profile', 'kanda' ); ?></a>
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'password' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-key"></i> <?php esc_html_e( 'Change password', 'kanda' ); ?></a>
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'photo' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-image"></i> <?php esc_html_e( 'Change avatar', 'kanda' ); ?></a>
</div>

<form class="form-block form-inline" id="form_edit_profile" method="post">
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'GENERAL', 'kanda' ); ?></legend>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Username', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" value="<?php echo $this->user_login; ?>" readonly disabled>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'Company Name', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" value="<?php echo $this->company_name; ?>" readonly disabled>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group row clearfix">
                    <label class="form-label col-sm-12"><?php esc_html_e( 'License ID', 'kanda' ); ?>:</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" value="<?php echo $this->company_license; ?>" readonly disabled>
                    </div>
                </div>
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
                        <input type="email" id="user_email" name="user_email" class="form-control" value="<?php echo $this->user_email; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'user_email' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'first_name' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'First Name', 'kanda' ); ?>: *</label>
                    <div class="col-sm-8">
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $this->first_name; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'first_name' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'last_name' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Last Name', 'kanda' ); ?>: *</label>
                    <div class="col-sm-8">
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $this->last_name; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'last_name' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'mobile' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Mobile', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo $this->mobile; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'mobile' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'position' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Position', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="position" name="position" class="form-control" value="<?php echo $this->position; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'position' ] : ''; ?></small></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">

                <?php $has_error = isset( $this->errors[ 'company_address' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Address', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="company_address" name="company_address" class="form-control" value="<?php echo $this->company_address; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'company_address' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_city' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'City', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="company_city" name="company_city" class="form-control" value="<?php echo $this->company_city; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'company_city' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_country' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Country', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="company_country" name="company_country" class="form-control" value="<?php echo $this->company_country; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'company_country' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_phone' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Company Phone', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="company_phone" name="company_phone" class="form-control" value="<?php echo $this->company_phone; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'company_phone' ] : ''; ?></small></div>
                    </div>
                </div>

                <?php $has_error = isset( $this->errors[ 'company_website' ] ); ?>
                <div class="form-group row clearfix <?php echo $has_error ? 'has-error' : ''; ?>">
                    <label class="form-label col-sm-4"><?php esc_html_e( 'Website', 'kanda' ); ?>:</label>
                    <div class="col-sm-8">
                        <input type="text" id="company_website" name="company_website" class="form-control" value="<?php echo $this->company_website; ?>">
                        <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'company_website' ] : ''; ?></small></div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="text-right">
        <?php wp_nonce_field( 'kanda_save_profile', 'security' ); ?>
        <button type="submit" class="btn -primary" name="kanda_save"><?php _e( 'Update', 'kanda' ); ?></button>
        <a role="button" href="<?php echo kanda_url_to( 'profile', array( 'edit' ) ); ?>" class="btn -danger"><?php esc_html_e( 'Cancel', 'kanda' ); ?></a>
    </div>
</form>