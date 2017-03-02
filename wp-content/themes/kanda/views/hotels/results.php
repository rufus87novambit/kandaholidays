<form class="form-block" method="get">
    <fieldset class="clearfix">
        <div class="row">
            <div class="col-sm-4">
                <label class="form-label">&nbsp;</label>
                <div>
                    <a href="#popup-criteria" class="btn -warning open-popup"><?php esc_html_e( 'Edit Search Criteria', 'kanda' ); ?></a>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group col-lg-3 col-sm-5 col-xs-5 clearfix">
                    <div class="select-wrap" name="order">
                        <label class="form-label"><?php esc_html_e( 'Per page', 'kanda' ); ?></label>
                        <div>
                            <input name="per_page" type="number" class="form-control -sm" min="0" value="<?php echo $this->response->per_page; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-sm-5 col-xs-5 clearfix">
                    <div class="select-wrap" name="order">
                        <label class="form-label"><?php esc_html_e( 'Order', 'kanda' ); ?></label>
                        <select class="custom-select" name="order_by">
                            <option value="name" selected="selected"><?php esc_html_e( 'Name', 'kanda' ); ?></option>
                            <option value="rating"><?php esc_html_e( 'Rating', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-sm-5 col-xs-5 clearfix">
                    <div class="select-wrap">
                        <label class="form-label"><?php esc_html_e( 'Order By', 'kanda' ); ?></label>
                        <select class="custom-select" name="order_by">
                            <option value="asc" selected="selected"><?php esc_html_e( 'ASC', 'kanda' ); ?></option>
                            <option value="desc"><?php esc_html_e( 'DESC', 'kanda' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn -primary col-lg-3 col-sm-5 col-xs-5"><?php esc_html_e( 'Go', 'kanda' ); ?></button>
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
            <span><?php echo $hotel['hotelname'] ?></span>
            <span class="pull-right"><?php echo str_repeat( '<i class="icon icon-star"></i>', $hotel['starrating'] ); ?></span>
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
                <div class="editor-content"><?php echo wp_trim_words( $hotel[ 'hoteldescr' ], 55 ); ?></div>
                <div class="article-actions pull-right">

                    <a href="#<?php echo $hotel['hotelcode']; ?>" class="open-popup btn -info -sm  clearfix"><?php esc_html_e( 'Rooms', 'kanda' ); ?></a>

                    <a href="<?php echo $this->get_hotel_details_request_url( array( 'hotelcode' => $hotel['hotelcode'], 'start_date' => $this->response->request['start_date'], 'end_date' => $this->response->request['end_date'] ), $this->response->request ); ?>" class="ajax-popup btn -info -sm  clearfix"><?php esc_html_e( 'Hotel', 'kanda' ); ?></a>

                    <?php if( isset( $hotel['geolocation'] ) && $map_url = $this->get_hotel_google_map_url( $hotel['geolocation'] ) ) { ?>
                    <a href="<?php echo $map_url; ?>" class="btn -warning -sm maps-popup clearfix"><?php esc_html_e( 'View on map', 'kanda' ); ?></a>
                    <?php } ?>

                </div>
                <?php /*
                    <div id="<?php echo $hotel['hotelcode']; ?>" class="white-popup mfp-hide">
                    ashdakjsdhkajshd
                </div>
                */ ?>
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
<div id="loading-popup" class="loading-popup mfp-hide"></div>
<div id="error-popup" class="white-popup text-center mfp-hide"></div>