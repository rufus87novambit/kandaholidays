<?php
/**
 * Template name: Components
 */
?>
<?php get_header(); ?>


    <div class="row">
        <aside class="sidebar col-sm-4">
            <div class="box">
                <ul class="side-nav">
                    <li><a href="">Search Bookings</a></li>
                    <li><a href="">Confirmed Bookings</a></li>
                    <li><a href="">On Request Bookings</a></li>
                    <li><a href="">Cancelled Bookings</a></li>
                </ul>
                <ul class="side-nav">
                    <li><a href="">User List</a></li>
                    <li><a href="">Cancellation List</a></li>
                    <li><a href="">My Profile</a></li>
                    <li><a href="">User Guide</a></li>
                </ul>
                <ul class="side-nav">
                    <li><a href="">Customer Agreement</a></li>
                </ul>
                <ul class="side-nav">
                    <li><a href="">Logout</a></li>
                </ul>
            </div>
        </aside>
        <div class="primary col-sm-8">
            <div class="box">

                <form class="form-block">
                    <fieldset class="fieldset sep-btm">
                        <h4 class="form-title">Page title</h4>

                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- page template-->
    <br> <br>






    <div class="components box clearfix">

    <h2>Headings</h2>
    <br>
    <div class="box clearfix">
        <h1>h1 heading</h1>
        <h2>h2 heading</h2>
        <h3>h3 heading</h3>
        <h4>h4 heading</h4>
        <h5>h5 heading</h5>
        <h6>h6 heading</h6>
    </div>
    <br><br>



    <h2>Buttons</h2>
    <br>
    <div class="box clearfix">
        <!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
        <button type="button" class="btn -primary">Primary</button>
        <!-- Secondary, outline button -->
        <button type="button" class="btn -secondary">Secondary</button>
        <!-- Indicates a successful or positive action -->
        <button type="button" class="btn -success">Success</button>
        <!-- Contextual button for informational alert messages -->
        <button type="button" class="btn -info">Info</button>
        <!-- Indicates caution should be taken with this action -->
        <button type="button" class="btn -warning">Warning</button>
        <!-- Indicates a dangerous or potentially negative action -->
        <button type="button" class="btn -danger">Danger</button>
        <!-- Deemphasize a button by making it look like a link while maintaining button behavior -->
        <button type="button" class="btn -link">Link</button>
    </div>
    <br><br>



    <h2>Editor</h2>
    <br>
    <div class="box clearfix">
        <div class="editor-content">

            <?php the_post(); ?>

            <?php the_title( '<h1>', '</h1>' ); ?>

            <?php the_content(); ?>
        </div>
    </div>
    <br><br>

    <h2>Form</h2>
    <br>
    <div class="box clearfix">
        <form class="form-block">
            <fieldset class="fieldset sep-btm">
                <h4 class="form-title">Form title</h4>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <div class="select-wrap">
                            <select class="custom-select" name="name[]">
                                <option class="placeholder" selected disabled>Select label</option>
                                <option>value1</option>
                                <option>value2</option>
                                <option>value3</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="text" class="form-control" placeholder="Input text">
                    </div>
                </div>
                <div class="form-notice text-right">* Form notice example</div>
                <div class="ctrl-group">
                    <label class="ctrl-field -rbtn">
                        <input type='radio' class="ctrl-inp" name="radio_group3" checked>
                        <span class="ctrl-btn"></span>
                        <span class="ctrl-label">radio label</span>
                    </label>
                    <label class="ctrl-field -rbtn">
                        <input type='radio' class="ctrl-inp" name="radio_group3">
                        <span class="ctrl-btn"></span>
                        <span class="ctrl-label">radio label</span>
                    </label>
                </div>
                <div class="ctrl-group">
                    <label class="ctrl-field -chbox">
                        <input type='checkbox' class="ctrl-inp" name="" checked>
                        <span class="ctrl-btn"></span>
                        <span class="ctrl-label">Checkbox label</span>
                    </label>
                    <label class="ctrl-field -chbox">
                        <input type='checkbox' class="ctrl-inp" name="" checked>
                        <span class="ctrl-btn"></span>
                        <span class="ctrl-label">Checkbox label</span>
                    </label>
                    <label class="ctrl-field -chbox">
                        <input type='checkbox' class="ctrl-inp" name="">
                        <span class="ctrl-btn"></span>
                        <span class="ctrl-label">Checkbox label</span>
                    </label>
                </div>
            </fieldset>
        </form>
    </div>
    <br><br>

    <h2>Alerts</h2>
    <br>
    <div class="box clearfix">
        <div class="alert alert-success" role="alert">
            <strong>Well done!</strong> You successfully read this important alert message.
        </div>
        <div class="alert alert-info" role="alert">
            <strong>Heads up!</strong> This alert needs your attention, but it's not super important.
        </div>
        <div class="alert alert-warning" role="alert">
            <strong>Warning!</strong> Better check yourself, you're not looking too good.
        </div>
        <div class="alert alert-danger" role="alert">
            <strong>Oh snap!</strong> Change a few things up and try submitting again.
        </div>
    </div>
    <br><br>




</div>
<?php get_footer(); ?>