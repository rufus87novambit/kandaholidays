<?php
/**
 * Template name: Components
 */
?>
<?php get_header(); ?>

<!--Popup-->
<!--<div class="popup-wrap">
    <div class="popup-container">
        <div class="popup-content">
            <div class="popup-block white-popup-block">
                <button title="Close (Esc)" type="button" class="popup-close">×</button>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Ad assumenda atque commodi ea est iure iusto nobis numquam pariatur,
                praesentium quasi quia, sapiente sit totam voluptas. A at consequuntur deserunt dolor dolore,
                dolorum eum facere minima modi provident repellat ullam.
            </div>

           <div class="popup-block text-center text-light">
                <button title="Close (Esc)" type="button" class="popup-close">×</button>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Ad assumenda atque commodi ea est iure iusto nobis numquam pariatur,
                praesentium quasi quia, sapiente sit totam voluptas. A at consequuntur deserunt dolor dolore,
                dolorum eum facere minima modi provident repellat ullam.
            </div>
        </div>
    </div>
</div>
<div class="popup-overlay"></div>-->
<!--End Popup-->




    <div class="row">
        <aside class="sidebar col-sm-3 pull-right">
            <div class="box">
                <ul class="side-nav">
                    <li><a href="#headingsSection">Headings</a></li>
                    <li><a href="#buttonsSection">Buttons</a></li>
                    <li><a href="#formSection">Form</a></li>
                    <li><a href="#editorSection">Editor</a></li>
                    <li><a href="#alertsSection">Alerts</a></li>
                </ul>
                <br>   <br> <br>
                <ul class="side-nav">
                    <li><a href="">Search Bookings</a></li>
                    <li><a href="">Confirmed Bookings</a></li>
                    <li><a href="">On Request Bookings</a></li>
                    <li class="sep"><a href="">Cancelled Bookings</a></li>
                    <li><a href="">User List</a></li>
                    <li><a href="">Cancellation List</a></li>
                    <li><a href="">My Profile</a></li>
                    <li class="sep"><a href="">User Guide</a></li>
                    <li class="sep"><a href="">Customer Agreement</a></li>
                    <li><a href="">Logout</a></li>
                </ul>
            </div>
        </aside><!-- .sidebar -->
        <div class="primary col-sm-9">
            <div class="components box clearfix">

                <section id="headingsSection">
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
                </section>
                <br><br>

                <section id="buttonsSection">
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
                        <br><br>
                        <button type="button" class="btn -primary -lg">Large button</button>
                        <button type="button" class="btn -secondary -lg">Large button</button>
                        <br><br>
                        <button type="button" class="btn -primary -sm">Small button</button>
                        <button type="button" class="btn -secondary -sm">Small button</button>
                        <br><br>
                        <button type="button" class="btn -primary -lg -block">Block level button</button>
                    </div>
                </section>
                <br><br>


                <section id="formSection">
                    <h2>Form</h2>
                    <br>
                    <div class="box clearfix">
                        <form class="form-block">
                            <fieldset class="fieldset sep-btm">
                                <h4 class="form-title">Form title</h4>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Input text">
                                </div>

                                <div class="form-group">
                                    <div class="select-wrap">
                                        <select class="custom-select" name="name[]">
                                            <option class="placeholder" selected disabled>Select label</option>
                                            <option>value1</option>
                                            <option>value2</option>
                                            <option>value3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Date</label>
                                    <div class="calendar-field">
                                        <input type="text" class="form-control datepicker">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <input type="text" class="form-control" placeholder="Input text">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <input type="text" class="form-control" placeholder="Input text">
                                    </div>
                                </div>

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
                                <br> <br>
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

                            <fieldset class="fieldset">
                                <h3>Defining states</h3>
                                <div class="form-group has-error">
                                    <label class="form-label" for="inputDanger1">Input with danger</label>
                                    <input type="text" class="form-control" id="inputDanger1">
                                    <div class="form-control-feedback">Sorry, that username's taken. Try another?</div>
                                    <small class="form-text text-muted">Example help text that remains unchanged.</small>
                                </div>
                                <div class="form-group has-success">
                                    <label class="form-label" for="inputSuccess1">Input with success</label>
                                    <input type="text" class="form-control" id="inputSuccess1">
                                    <div class="form-control-feedback">Success! You've done it.</div>
                                    <small class="form-text text-muted">Example help text that remains unchanged.</small>
                                </div>
                                <div class="form-group has-warning">
                                    <label class="form-label" for="inputWarning1">Input with warning</label>
                                    <input type="text" class="form-control" id="inputWarning1">
                                    <div class="form-control-feedback">Shucks, check the formatting of that and try again.</div>
                                    <small class="form-text text-muted">Example help text that remains unchanged.</small>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </section>
                <br><br>

                <section id="editorSection">
                    <h2>Editor</h2>
                    <br>
                    <div class="box clearfix">
                        <div class="editor-content">

                            <?php the_post(); ?>

                            <?php the_title( '<h1>', '</h1>' ); ?>

                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
                <br><br>


                <section id="alertsSection">
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
                </section>
                <br><br>




            </div>

        </div><!-- .primary -->
    </div><!-- page template-->
    <br> <br>






<?php get_footer(); ?>