<?php kanda_show_notification(); ?>

<div class="secondary-menu">
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-user-tie"></i> <?php esc_html_e( 'Edit Profile', 'kanda' ); ?></a>
    <a href="<?php echo kanda_url_to( 'profile', array( 'edit', 'password' ) ); ?>" rel="button" class="btn -info -sm"><i class="icon icon-key"></i> <?php esc_html_e( 'Change password', 'kanda' ); ?></a>
    <a href="javascript:void(0);" rel="button" class="btn -warning -sm"><i class="icon icon-image"></i> <?php esc_html_e( 'Change avatar', 'kanda' ); ?></a>
</div>

<form class="form-block form-inline" enctype="multipart/form-data" method="post">
    <?php $has_avatar = kanda_user_has_avatar(); ?>
    <fieldset class="fieldset sep-btm">
        <div class="row">
            <div class="col-sm-3 text-center">
                <div class="avatar-box">
                    <div class="avatar-wrapper text-center">
                        <?php echo kanda_get_user_avatar( false, 'user-avatar' ); ?>
                    </div>
                    <div class="<?php echo $has_avatar ? '' : 'hidden'; ?>" id="avatar-delete" method="post">
                        <input type="hidden" name="avatar-delete-security" value="<?php echo wp_create_nonce( 'kanda-delete-avatar' ); ?>" />
                        <button type="submit" class="btn -danger -sm" name="kanda-delete-avatar"><?php esc_html_e( 'Delete', 'kanda' ); ?></button>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div id="cropper" class="<?php echo $has_avatar ? 'has-avatar' : 'hidden'; ?>">
                    <?php echo kanda_get_user_avatar( false, 'full', array( 'id' => 'cropper-avatar' ) ); ?>
                </div>

                <div id="uploader" class="<?php echo $has_avatar ? 'hidden' : ''; ?>">
                    <div id="avatar-upload-ui" class="text-center">
                        <div><?php esc_html_e( 'Drag and drop files here', 'kanda' ); ?></div>
                        <div><?php esc_html_e( 'or', 'kanda' ); ?></div>
                        <button class="btn -info" id="avatar-upload-browse"><?php esc_html_e( 'Select', 'kanda' ); ?></button>
                    </div>
                    <div id="avatar-upload-helper">
                        <div id="filelist"></div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="text-right">
        <input type="hidden" name="coordinates" id="coordinates" />
        <?php wp_nonce_field( 'kanda-save-avatar', 'avatar-save-security' ); ?>
        <button type="submit" class="btn -primary" name="kanda-save-avatar"><?php _e( 'Save', 'kanda' ); ?></button>
        <a role="button" href="<?php echo kanda_url_to( 'profile', array( 'edit' ) ); ?>" class="btn -danger"><?php esc_html_e( 'Cancel', 'kanda' ); ?></a>
    </div>
</form>