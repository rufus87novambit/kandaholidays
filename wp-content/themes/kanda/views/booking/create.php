<form class="form-block" id="form_create_booking" method="post">
    <h4><?php echo _n( 'Adult', 'Adults', $this->adults, 'kanda' ); ?></h4>

    <fieldset class="fieldset row">
        <?php for( $i = 0; $i < $this->adults; $i++ ) { ?>
        <div class="col-md-6">
            <div class="box body-bg">
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Title', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="adults[<?php echo $i; ?>][title]">
                            <option value="mr"><?php _e( 'Mr', 'kanda' ); ?></option>
                            <option value="mrs"><?php _e( 'Mrs', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][first_name]" class="form-control" value="">
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][last_name]" class="form-control" value="">
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Date of birth', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][date_of_birth]" class="form-control" value="">
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </fieldset>

    <?php if( $this->children ) { ?>
    <h4><?php echo _n( 'Child', 'Children', $this->children, 'kanda' ); ?></h4>
    <fieldset class="fieldset row">
        <?php for( $i = 0; $i < $this->children; $i++ ) { ?>
            <div class="col-md-6">
                <div class="box body-bg">
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Title', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="children[<?php echo $i; ?>][title]">
                                <option value="mr"><?php _e( 'Mr', 'kanda' ); ?></option>
                                <option value="mrs"><?php _e( 'Mrs', 'kanda' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][first_name]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][last_name]" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Date of birth', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][date_of_birth]" class="form-control" value="">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </fieldset>
    <?php } ?>

</form>