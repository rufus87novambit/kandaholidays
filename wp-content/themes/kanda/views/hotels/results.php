<?php
$start_date = date( 'Ymd', strtotime($this->response->request['start_date'] ) );
$end_date = date( 'Ymd', strtotime($this->response->request['end_date'] ) );
?>
<form class="form-block" method="get" action="<?php echo kanda_url_to( 'hotels', array( 'result', $this->response->request_id ) ); ?>">
    <fieldset class="clearfix">
        <div class="row">
            <div class="col-lg-4 col-sm-3 col-xs-12">
                <label class="form-label">&nbsp;</label>
                <div>
                    <a href="#popup-criteria" class="btn -primary open-popup col-xl-6 col-xs-12"><?php esc_html_e( 'Edit Search', 'kanda' ); ?></a>
                </div>
            </div>
            <div class="col-lg-8 col-sm-9">
                <div class="form-group col-lg-3 col-sm-3 col-xs-4 clearfix">
                    <div class="select-wrap" name="order">
                        <label class="form-label"><?php esc_html_e( 'Per page', 'kanda' ); ?></label>
                        <div>
                            <input name="per_page" type="number" class="form-control -sm" min="0" value="<?php echo $this->response->per_page; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-sm-3 col-xs-4 clearfix">
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
                <div class="form-group col-lg-3 col-sm-3 col-xs-4 clearfix">
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
                <div class="form-group col-lg-3 col-sm-3 col-xs-12">
                    <label class="form-label hidden-xs-down">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn -secondary col-xs-12"><?php esc_html_e( 'Go', 'kanda' ); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>

<!-- Media list -->
<ul class="articles-list">
    <?php foreach( $this->response->data as $hotel ) { ?>

    <li>
        <h4 class="article-title">
            <span><?php echo $hotel['hotelname'] ?> - <?php echo $hotel['propertytype'] ?></span>
            <span class="pull-right"><?php echo str_repeat( '<i class="icon icon-star-o"></i>', $hotel['starrating'] ); ?></span>
        </h4>

        <div class="row">
            <div class="article-obj col-sm-4">
                <a href="single_news.html">
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
                        <i class="icon icon-hand-pointer-o"></i>
                        <?php esc_html_e( 'Book', 'kanda' ); ?>
                    </a>

                    <a href="<?php echo $this->get_single_hotel_url( array( 'hotelcode' => $hotel['hotelcode'], 'start_date' => $start_date, 'end_date' => $end_date, $this->response->request ) ); ?>" class="btn -secondary -sm  clearfix" target="_blank">
                        <i class="icon icon-info2"></i>
                        <?php esc_html_e( 'Hotel details', 'kanda' ); ?>
                    </a>

                    <?php if( isset( $hotel['geolocation'] ) && $map_url = $this->get_hotel_google_map_url( $hotel['geolocation'] ) ) { ?>
                        <a href="<?php echo $map_url; ?>" class="btn -primary -sm iframe-popup clearfix">
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
                    $rooms = $hotel['roomtypedetails']['rooms']['room'];
                    foreach( $rooms as $room ) { ?>
                    <div class="users-table table">
                        <header class="thead">
                            <div class="th" style="width: 25%"><?php esc_html_e( 'Property type', 'kanda' ); ?></div>
                            <div class="th">
                                <?php esc_html_e( 'Property value', 'kanda' ); ?>
                                <div class="actions pull-right">
                                    <a href="javascript:void(0);" class="btn -sm -secondary book-btn"><?php esc_html_e( 'Book', 'kanda' ); ?></a>
                                    <a href="<?php echo $this->get_cancellation_policy_url( $hotel['hotelcode'], $room['roomtypecode'], $room['contracttokenid'], $start_date, $end_date ); ?>" class="btn -sm -primary ajax-popup" data-popup="confirmation"><?php esc_html_e( 'Cancellation policy', 'kanda' ); ?></a>
                                </div>
                            </div>
                        </header>
                        <div class="tbody">
                            <?php
                            $properties = array(
                                'roomtype' => esc_html__( 'Room Type', 'kanda' ),
                                'mealplan' => esc_html__( 'Meal Plan', 'kanda' ),
                                'rate' => esc_html__( 'Rate', 'kanda' ),
                            );
                            foreach( $properties as $property => $property_label ) {
                                if( isset( $room[ $property ] ) && $room[ $property ] ) { ?>
                            <div class="tr">
                                <div class="td"><?php echo $property_label; ?></div>
                                <div class="td"><?php echo $room[ $property ]; ?></div>
                            </div>
                                <?php }
                            }

                            if( (bool)$room['discountdetails'] ) {
                                $room_discounts = $room['discountdetails']['discount'];
                                foreach( $room_discounts as $discount ) {
                                    $properties = array(
                                        'discountname' => esc_html__( 'Discount Name', 'kanda' ),
                                        'discounttype' => esc_html__( 'Discount Type', 'kanda' ),
                                        'discountnotes' => esc_html__( 'Discount Notes', 'kanda' ),
                                        'totaldiscountrate' => esc_html__( 'Discount Total Rate', 'kanda' ),
                                    );
                                    foreach( $properties as $property => $property_label ) {
                                        if( isset( $discount[ $property ] ) && $discount[ $property ] ) { ?>
                                    <div class="tr">
                                        <div class="td"><?php echo $property_label; ?></div>
                                        <div class="td"><?php echo $discount[ $property ]; ?></div>
                                    </div>
                                        <?php }
                                    }
                                }
                            } ?>
                        </div>
                    </div>
                <?php } ?>
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

<div id="popup-criteria" class="white-popup mfp-hide">
    <?php
        add_filter( 'custom-select-classname', function(){ return 'kanda-select-late-init'; } );
        $args = array(
            'city' => $this->response->request['city'],
            'hotel_name' => $this->response->request['hotel_name'],
            'star_rating' => $this->response->request['star_rating'],
            'include_on_request' => $this->response->request['include_on_request'],
            'nationality' => $this->response->request['nationality'],
            'currency' => $this->response->request['currency'],
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