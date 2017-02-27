<h3><?php echo sprintf( '%1$s - %2$s', $this->request['start_date'], $this->request['end_date'] ); ?></h3>
<!-- Media list -->
<ul class="articles-list">
    <?php foreach( $this->hotels as $hotel ) { ?>

    <li>
        <h4 class="article-title">
            <span><?php echo $hotel['hotelname'] ?></span>
            <span class="pull-right"><?php echo str_repeat( '<i class="icon icon-star"></i>', $hotel['starrating'] ); ?></span>
        </h4>

        <div class="row">
            <div class="article-obj col-sm-4">
                <a href="single_news.html">
                    <?php //the_post_thumbnail( 'full' ); ?>
                </a>
            </div>

            <div class="article-body col-sm-8">
                <div class="editor-content">Lorem ipsum ...</div>
                <div class="article-actions pull-right">

                    <a href="#<?php echo $hotel['hotelcode']; ?>" class="open-popup btn -info -sm  clearfix"><?php esc_html_e( 'Rooms', 'kanda' ); ?></a>

                    <a href="<?php echo $this->get_hotel_details_request_url( array( 'hotelcode' => $hotel['hotelcode'], 'start_date' => $this->request['start_date'], 'end_date' => $this->request['end_date'] ), $this->request ); ?>" class="ajax-popup btn -info -sm  clearfix"><?php esc_html_e( 'Hotel', 'kanda' ); ?></a>

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