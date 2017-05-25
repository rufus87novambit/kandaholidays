<form class="form-block" id="form_hotel_search" method="post">

    <?php $city = isset( $city ) ? $city : 'AUH'; ?>
    <fieldset class="fieldset sep-btm">
        <legend><?php esc_html_e( 'SELECT DESTINATION', 'kanda' ); ?></legend>
        <ul class="block-sm-3 clearfix">
            <?php foreach( IOL_Config::get( 'cities' ) as $city_code => $city_name ) { ?>
            <li>
                <label class="ctrl-field -rbtn">
                    <input type='radio' class="ctrl-inp" name="city" value="<?php echo $city_code; ?>" <?php checked( $city_code, $city ); ?>>
                    <span class="ctrl-btn"></span>
                    <span class="ctrl-label"><?php echo $city_name; ?></span>
                </label>
            </li>
            <?php } ?>
        </ul>
    </fieldset>

    <fieldset class="fieldset clearfix sep-btm">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-lg-11">
                        <legend><?php esc_html_e( 'SEARCH CRITERIA', 'kanda' ); ?></legend>

                        <?php $hotel_name = isset( $hotel_name ) ? $hotel_name : ''; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Hotel Name', 'kanda' ); ?></label>
                            <div class="col-lg-7">
                                <input type="text" id="hotel_name" name="hotel_name" class="form-control" value="<?php echo $hotel_name; ?>">
                                <div id="autocomplete-wrap"></div>
                            </div>
                        </div>

                        <?php $star_rating = isset( $star_rating ) ? $star_rating : ''; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Rating', 'kanda' ); ?></label>
                            <div class="select-wrap col-lg-7">
                                <select class="rating" name="star_rating">
                                    <option value="" <?php selected( "", $star_rating ); ?>></option>
                                    <option value="2" <?php selected( 2, $star_rating ); ?>></option>
                                    <option value="2" <?php selected( 2, $star_rating ); ?>></option>
                                    <option value="3" <?php selected( 3, $star_rating ); ?>></option>
                                    <option value="4" <?php selected( 4, $star_rating ); ?>></option>
                                    <option value="5" <?php selected( 5, $star_rating ); ?>></option>
                                </select>
                            </div>
                        </div>

                        <?php $include_on_request = isset( $include_on_request ) ? $include_on_request : 1; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Hotels In Request', 'kanda' ); ?></label>
                            <div class="select-wrap col-lg-7">
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="include_on_request">
                                    <option value="1" <?php selected( 1, $include_on_request ); ?>><?php esc_html_e( 'Available & On Request', 'kanda' ); ?></option>
                                    <option value="0" <?php selected( 0, $include_on_request ); ?>><?php esc_html_e( 'Only Available', 'kanda' ); ?></option>
                                </select>
                            </div>
                        </div>

                        <?php $nationality = isset( $nationality ) ? $nationality : 'AM'; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Nationality', 'kanda' ); ?></label>
                            <div class="select-wrap col-lg-7">
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="nationality">
                                    <?php foreach( kanda_get_nationality_choices() as $iso => $nat_name ) { ?>
                                    <option value="<?php echo $iso; ?>" <?php selected( $iso, $nationality ); ?>><?php echo $nat_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <?php $currency = isset( $currency ) ? $currency : 'USD'; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Currency', 'kanda' ); ?></label>
                            <?php if( $currencies = kanda_get_theme_option( 'exchange_active_currencies' ) ) { ?>
                                <div class="select-wrap col-lg-7">
                                    <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="currency">
                                        <?php foreach( $currencies as $curr ) { ?>
                                            <option value="<?php echo $curr; ?>" <?php selected( $curr, $currency ); ?>><?php echo $curr; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-lg-11 col-lg-offset-1">
                        <legend><?php esc_html_e( 'SELECT YOUR TRAVEL DATES', 'kanda' ); ?></legend>

                        <?php
                        $start_date = isset( $start_date ) ? $start_date : '';
                        $has_error = isset( $this->errors[ 'start_date' ] );
                        ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Check In date', 'kanda' ); ?></label>
                            <div class="calendar-field col-lg-7">
                                <input type="text" name="start_date" class="form-control datepicker-start-date deny-typing" value="<?php echo $start_date; ?>">
                                <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'start_date' ] : ''; ?></small></div>
                            </div>
                        </div>

                        <?php
                        $end_date = isset( $end_date ) ? $end_date : '';
                        $has_error = isset( $this->errors[ 'end_date' ] );
                        ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Check Out date', 'kanda' ); ?></label>
                            <div class="calendar-field col-lg-7">
                                <input type="text" name="end_date" class="form-control datepicker-end-date deny-typing" value="<?php echo $end_date; ?>">
                                <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'end_date' ] : ''; ?></small></div>
                            </div>
                        </div>

                        <?php
                        $nights_count = isset( $nights_count ) ? $nights_count : '1';
                        $has_error = isset( $this->errors[ 'nights_count' ] );
                        ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Number Of Nights', 'kanda' ); ?></label>
                            <div class="select-wrap col-lg-7">
                                <input id="nights_count" name="nights_count" type="number" class="form-control -sm" min="1" max="30" value="<?php echo $nights_count; ?>">
                                <div class="form-control-feedback"><small><?php echo $has_error ? $this->errors[ 'nights_count' ] : ''; ?></small></div>
                            </div>
                        </div>

                        <legend><?php esc_html_e( 'SELECT YOUR ROOM/S', 'kanda' ); ?></legend>

                        <?php $rooms_count = isset( $rooms_count ) ? $rooms_count : 1; ?>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'How Many Rooms Do You Require?', 'kanda' ); ?></label>
                            <div class="select-wrap col-lg-7">
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="rooms_count" id="rooms_count">
                                    <option value="1" <?php selected( 1, $rooms_count ); ?>>1</option>
                                    <option value="2" <?php selected( 2, $rooms_count ); ?>>2</option>
                                    <option value="3" <?php selected( 3, $rooms_count ); ?>>3</option>
                                    <option value="4" <?php selected( 4, $rooms_count ); ?>>4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>


    <fieldset class="fieldset row">
        <?php
            for( $i = 1; $i <= $rooms_count; $i++ ) {
                $room_occupants = isset( $room_occupants ) ? $room_occupants : array( 1 => array( 'adults' => 1, 'child' => 0 ) );
                $adults = $room_occupants[ $i ][ 'adults' ];
                $children = $room_occupants[ $i ][ 'child' ];
                $has_children = is_array( $children );
                $children_ages = $has_children ? $children[ 'age' ] : array();
                $children_count = $has_children ? count( $room_occupants[ $i ][ 'child' ][ 'age' ] ) : 0; ?>
        <div class="col-md-6 occupants <?php echo $i == 1 ? 'occupants-cloneable' : ''; ?>" data-index="<?php echo $i; ?>">
            <div class="row">
                <div class="col-lg-11">
                    <div class="box body-bg">
                        <legend><?php printf( __( 'ROOM <span>%d</span> OCCUPANTS', 'kanda' ), $i ); ?></legend>
                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Adults', 'kanda' ); ?>:</label>
                            <div class="select-wrap col-lg-7">
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="room_occupants[<?php echo $i; ?>][adults]">
                                    <option value="1" <?php selected( $adults, 1 ); ?>><?php esc_html_e( '1 Adult - Single', 'kanda' ); ?></option>
                                    <option value="2" <?php selected( $adults, 2 ); ?>><?php esc_html_e( '2 Adults - Double', 'kanda' ); ?></option>
                                    <option value="3" <?php selected( $adults, 3 ); ?>><?php esc_html_e( '3 Adults - Triple', 'kanda' ); ?></option>
                                    <option value="4" <?php selected( $adults, 4 ); ?>><?php esc_html_e( '4 Adults - Quad', 'kanda' ); ?></option>
                                    <option value="5" <?php selected( $adults, 5 ); ?>><?php esc_html_e( '5 Adults', 'kanda' ); ?></option>
                                    <option value="6" <?php selected( $adults, 6 ); ?>><?php esc_html_e( '6 Adults', 'kanda' ); ?></option>
                                    <option value="7" <?php selected( $adults, 7 ); ?>><?php esc_html_e( '7 Adults', 'kanda' ); ?></option>
                                    <option value="8" <?php selected( $adults, 8 ); ?>><?php esc_html_e( '8 Adults', 'kanda' ); ?></option>
                                    <option value="9" <?php selected( $adults, 9 ); ?>><?php esc_html_e( '9 Adults', 'kanda' ); ?></option>
                                    <option value="10" <?php selected( $adults, 10 ); ?>><?php esc_html_e( '10 Adults', 'kanda' ); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row clearfix">
                            <label class="form-label col-lg-5"><?php esc_html_e( 'Children', 'kanda' ); ?>:</label>
                            <div class="select-wrap col-lg-7">
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?> children-presence" name="room_occupants[<?php echo $i; ?>][child]">
                                    <option value="0" <?php selected( $children_count, 0 ); ?>><?php esc_html_e( 'No Child', 'kanda' ); ?></option>
                                    <option value="1" <?php selected( $children_count, 1 ); ?>><?php esc_html_e( '1 Child', 'kanda' ); ?></option>
                                    <option value="2" <?php selected( $children_count, 2 ); ?>><?php esc_html_e( '2 Children', 'kanda' ); ?></option>
                                    <option value="3" <?php selected( $children_count, 3 ); ?>><?php esc_html_e( '3 Children', 'kanda' ); ?></option>
                                    <option value="4" <?php selected( $children_count, 4 ); ?>><?php esc_html_e( '4 Children', 'kanda' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row clearfix text-center children-age-box <?php echo $has_children ? '' : 'hidden'; ?>">
                            <small class="form-text text-muted"><?php esc_html_e( 'Please Specify Ages Of Children', 'kanda' ); ?></small>
                            <div class="children-ages">
                                <?php for( $j = 0; $j < $children_count; $j++ ) { ?>
                                    <input type="number" name="room_occupants[ <?php echo $i; ?> ][ child ][ age ][ <?php echo $j; ?> ]" class="form-control" value="<?php echo $children_ages[ $j ]; ?>" min="0" max="12">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </fieldset>

    <footer class="form-footer clearfix">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce( 'kanda-hotel-search' ); ?>" />
        <input type="submit" name="kanda_search" value="<?php _e( 'Search hotel', 'kanda' ); ?>" class="btn -secondary pull-right">
    </footer>
</form>