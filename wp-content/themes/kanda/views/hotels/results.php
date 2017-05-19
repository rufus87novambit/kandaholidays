<?php
$start_date = date( 'Ymd', strtotime($this->response->request['start_date'] ) );
$end_date = date( 'Ymd', strtotime($this->response->request['end_date'] ) );
?>
<form class="form-block" method="get" action="<?php echo kanda_url_to( 'hotels', array( 'result', $this->response->request_id ) ); ?>">
    <fieldset class="clearfix">
        <div class="row">
            <div class="col-lg-2 col-md-12">
                <label class="form-label hidden-md-down">&nbsp;</label>
                <div>
                    <a href="#popup-criteria" class="btn -secondary -no-padding open-popup col-xs-12"><?php esc_html_e( 'Edit Search', 'kanda' ); ?></a>
                </div>
            </div>
            <div class="col-lg-10 col-md-12">
                <div class="row">
                    <div class="form-group col-lg-2 col-lg-offset-2 col-xs-3 clearfix">
                        <div class="select-wrap" name="order">
                            <label class="form-label"><?php esc_html_e( 'Currency', 'kanda' ); ?></label>
                            <div>
                                <?php
                                    if( $currencies = kanda_get_theme_option( 'exchange_active_currencies' ) ) { ?>
                                    <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="currency">
                                        <?php foreach( $currencies as $curr ) { ?>
                                            <option value="<?php echo $curr; ?>" <?php selected( $curr, $this->currency ); ?>><?php echo $curr; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-xs-3 clearfix">
                        <div class="select-wrap" name="order">
                            <label class="form-label"><?php esc_html_e( 'Per page', 'kanda' ); ?></label>
                            <div>
                                <input name="per_page" type="number" class="form-control -sm" min="0" value="<?php echo $this->response->per_page; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-xs-3 clearfix">
                        <div class="select-wrap" name="order">
                            <label class="form-label"><?php esc_html_e( 'Order', 'kanda' ); ?></label>
                            <div>
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="order_by">
                                    <option value="name" <?php selected( 'name', $this->order_by ) ?>><?php esc_html_e( 'Name', 'kanda' ); ?></option>
                                    <option value="rating" <?php selected( 'rating', $this->order_by ) ?>><?php esc_html_e( 'Rating', 'kanda' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-xs-3 clearfix">
                        <div class="select-wrap">
                            <label class="form-label"><?php esc_html_e( 'Order By', 'kanda' ); ?></label>
                            <div>
                                <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="order">
                                    <option value="asc" <?php selected( 'asc', $this->order ) ?>><?php esc_html_e( 'ASC', 'kanda' ); ?></option>
                                    <option value="desc" <?php selected( 'desc', $this->order ) ?>><?php esc_html_e( 'DESC', 'kanda' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-12 clearfix">
                        <label class="form-label hidden-md-down">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn -secondary -no-padding col-xs-12"><?php esc_html_e( 'Go', 'kanda' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>

<!-- Media list -->
<ul class="articles-list">
    <?php
        foreach( $this->response->data as $hotel ) {
            $hotel_permalink = kanda_get_single_hotel_url( array( 'hotelcode' => $hotel['hotelcode'], 'start_date' => $start_date, 'end_date' => $end_date ) ); ?>

    <li>
        <h4 class="article-title">
            <span><?php echo $hotel['hotelname'] ?> - <?php echo $hotel['propertytype'] ?></span>
            <span class="pull-right"><?php echo $hotel['starrating'] ? str_repeat( '<i class="icon icon-star-o"></i>', $hotel['starrating'] ) : 'N/A'; ?></span>
        </h4>

        <div class="row">
            <div class="article-obj col-sm-4">
                <a href="<?php echo $hotel_permalink; ?>" target="_blank">
                    <?php if( ! empty( $hotel['images'] ) ) { ?>
                    <img src="<?php echo kanda_get_cropped_image_src( $hotel['images'][0], array( 'width' => 315, 'height' => 152 ) ); ?>" alt="<?php echo $hotel['hotelname']; ?>">
                    <?php } else { ?>
                    <img src="<?php echo kanda_get_hotel_placeholder_image(); ?>" alt="<?php echo $hotel['hotelname']; ?>">
                    <?php } ?>
                </a>
            </div>

            <div class="article-body col-sm-8">
                <?php
                    if( $hotel[ 'hoteldescr' ] ) {
                        $description = $hotel[ 'hoteldescr' ];
                    } elseif( isset( $hotel['hotelfacilities'] ) && isset( $hotel['hotelfacilities']['facility'] ) ) {
                        $description = implode( ', ', (array)$hotel['hotelfacilities']['facility'] );
                    } else {
                        $description = __( 'Description is not available ...', 'kanda' );
                    }
                ?>
                <div class="editor-content"><?php echo $hotel[ 'hoteldescr' ] ? wp_trim_words( $hotel[ 'hoteldescr' ], 55 ) : $description; ?></div>
            </div>

            <div class="col-lg-12">
                <div class="article-actions pull-right">

                    <a href="#<?php echo $hotel['hotelcode']; ?>" class="btn -secondary -sm  clearfix show-booking-details">
                        <i class="icon icon-triangle-down"></i>
                        <?php esc_html_e( 'Book', 'kanda' ); ?>
                    </a>

                    <a href="<?php echo $hotel_permalink; ?>" class="btn -secondary -sm  clearfix" target="_blank">
                        <i class="icon icon-info2"></i>
                        <?php esc_html_e( 'Hotel details', 'kanda' ); ?>
                    </a>

                    <?php if( isset( $hotel['geolocation'] ) && $map_url = $this->get_hotel_google_map_url( $hotel['geolocation'] ) ) { ?>
                        <a href="<?php echo $map_url; ?>" class="btn -secondary -sm iframe-popup clearfix">
                            <i class="icon icon-location"></i>
                            <?php esc_html_e( 'View on map', 'kanda' ); ?>
                        </a>
                    <?php } ?>

                </div>

            </div>
        </div>

        <div class="booking-details-wrap">
            <div class="booking-details-box" id="<?php echo $hotel['hotelcode']; ?>">
                <?php
                    if(
                        array_key_exists( 'roomtypedetails', $hotel ) &&
                        array_key_exists( 'rooms', $hotel['roomtypedetails'] ) &&
                        array_key_exists( 'room', $hotel['roomtypedetails']['rooms'] )
                    ) {
                        $rooms = IOL_Helper::is_associative_array( $hotel['roomtypedetails']['rooms']['room'] ) ? array( $hotel['roomtypedetails']['rooms']['room'] ) : $hotel['roomtypedetails']['rooms']['room'];
                        usort( $rooms, "kanda_price_order" );

                        // Render as tabs as we have multiple rooms
                        if( $this->response->request['rooms_count'] > 1 ) {
                            ?>
                            <div class="tabs">
                                <div class="tab-headings">
                                <?php for( $i = 1; $i <= $this->response->request['rooms_count']; $i++ ) { ?>
                                <a href="javascript:void(0);" data-target=".hotel-<?php echo $hotel['hotelcode']; ?>-room-<?php echo $i; ?>" class="btn -sm <?php echo $i == 1 ? '-primary' : '-secondary'; ?> tab-heading"><?php printf( '%s %d', esc_html_e( 'Room', 'kanda' ), $i ); ?></a>
                                <?php } ?>
                                </div>
                                <div class="tab-contents editor-content">
                                    <?php for( $i = 1; $i <= $this->response->request['rooms_count']; $i++ ) { ?>
                                    <div class="tab-content hotel-<?php echo $hotel['hotelcode']; ?>-room-<?php echo $i; ?> <?php echo $i == 1 ? '' : 'hidden'; ?>">
                                        <?php
                                        $adults_count = $this->response->request['room_occupants'][$i]['adults'];
                                        $children_count = $this->response->request['room_occupants'][$i]['child'] ? count( $this->response->request['room_occupants'][$i]['child']['age'] ) : '';

                                        $message = array();
                                        if( $adults_count ) {
                                            $message[] = sprintf( '%1$d %2$s', $adults_count, _n( 'adult', 'adults', $adults_count, 'kanda' ) );
                                        }

                                        if( $children_count ) {
                                            $message[] = sprintf( '%1$d %2$s', $children_count, _n( 'child', 'children', $children_count, 'kanda' ) );
                                        }

                                        printf( '<p>%s</p>', implode( ' + ', $message ) );

                                        foreach (wp_list_filter( $rooms, array( 'roomnumber' => $i ) ) as $room) {
                                            IOL_Helper::render_room_details(
                                                $room,
                                                array(
                                                    'hotelcode' => $hotel['hotelcode'],
                                                    'start_date' => $start_date,
                                                    'end_date' => $end_date,
                                                    'currency' => $this->currency,
                                                    'roomnumber' => $i,
                                                    'request' => array_merge(
                                                        $this->response->request,
                                                        array( 'request_id' => $this->request_id )
                                                    ),
                                                    'requested_room_number' => $i
                                                )
                                            );
                                        }
                                        ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                        }
                        // render as single room
                        else {
                            $adults_count = $this->response->request['room_occupants'][1]['adults'];
                            $children_count = $this->response->request['room_occupants'][1]['child'] ? count( $this->response->request['room_occupants'][1]['child']['age'] ) : '';

                            $message = array();
                            if( $adults_count ) {
                                $message[] = sprintf( '%1$d %2$s', $adults_count, _n( 'adult', 'adults', $adults_count, 'kanda' ) );
                            }

                            if( $children_count ) {
                                $message[] = sprintf( '%1$d %2$s', $children_count, _n( 'child', 'children', $children_count, 'kanda' ) );
                            }

                            printf( '<p>%s</p>', implode( ' + ', $message ) );
                            foreach (wp_list_filter( $rooms, array( 'roomnumber' => 1 ) ) as $room) {
                                IOL_Helper::render_room_details(
                                    $room,
                                    array(
                                        'hotelcode' => $hotel['hotelcode'],
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'currency' => $this->currency,
                                        'roomnumber' => 1,
                                        'request' => array_merge(
                                            $this->response->request,
                                            array( 'request_id' => $this->request_id )
                                        ),
                                        'requested_room_number' => 1
                                    )
                                );
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>

<div class="pagination text-center">
<?php
    echo paginate_links( array(
        'base'               => kanda_url_to( 'hotels', array( 'result', $this->response->request_id, '%_%' ) ),
        'format'             => '%#%',
        'total'              => ceil( $this->response->total / $this->response->per_page ),
        'current'            => $this->page,
        'show_all'           => false,
        'end_size'           => 1,
        'mid_size'           => 1,
        'prev_next'          => true,
        'prev_text'          => '&laquo;',
        'next_text'          => '&raquo',
        'type'               => 'plain',
        'add_args'           => false,
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => ''
    ) )
?>
</div>

<div id="popup-criteria" class="static-popup mfp-hide">
    <?php
        add_filter( 'custom-select-classname', function(){ return 'kanda-select-late-init'; } );
        $args = array(
            'city' => $this->response->request['city'],
            'hotel_name' => $this->response->request['hotel_name'],
            'star_rating' => $this->response->request['star_rating'],
            'include_on_request' => $this->response->request['include_on_request'],
            'nationality' => $this->response->request['nationality'],
            'currency' => $this->currency,
            'start_date' => $this->response->request['start_date'],
            'end_date' => $this->response->request['end_date'],
            'nights_count' => $this->response->request['nights_count'],
            'rooms_count' => $this->response->request['rooms_count'],
            'room_occupants' => $this->response->request['room_occupants']
        );

        echo $this->partial( 'hotel-search-form', $args );
    ?>
</div>
<?php
    echo kanda_get_loading_popup();
    echo kanda_get_error_popup();
?>