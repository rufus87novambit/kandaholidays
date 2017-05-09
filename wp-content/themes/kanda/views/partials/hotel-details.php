<div class="row">
    <div class="col-sm-6 hotel-detailed-information">
        <ul>
            <li>
                <a href="callto:<?php echo $hotel['hotelphone']; ?>" class="btn -sm -primary -block">
                    <i class="icon icon-phone"></i>
                    <?php echo $hotel['hotelphone']; ?>
                </a>
            </li>
            <li>
                <a href="mailto:<?php echo $hotel['hotelemail']; ?>" class="btn -sm -primary -block">
                    <i class="icon icon-envelope"></i>
                    <?php echo $hotel['hotelemail']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo 'http://' . strtr( $hotel['hotelweb'], array( 'http://' => '', 'https://' => '', 'www.' => '' ) ); ?>" class="btn -sm -primary -block" target="_blank">
                    <i class="icon icon-globe"></i>
                    <?php echo $hotel['hotelweb']; ?>
                </a>
            </li>
            <li>
                <p><a href="javascript:void(0);" class="btn -sm -primary -block -normal-wrap"><?php echo $hotel['hoteladdress']; ?></a></p>
                <?php
                    $map_url = ( $cached_hotel && isset( $cached_hotel['geolocation'] ) ) ? $this->get_hotel_google_map_url( $cached_hotel['geolocation'] ) : false;
                    if( $map_url ) {
                ?>
                <a href="<?php echo $map_url ? $map_url : 'javascript:void(0)'; ?>" class="btn -sm -primary -block <?php echo $map_url ? 'iframe-popup' : ''; ?>">
                    <i class="icon icon-location"></i>
                    <?php esc_html_e( 'View on map', 'kanda' ); ?>
                </a>
                <?php } ?>
            </li>
        </ul>
    </div>
    <div class="col-sm-6">
        <div class="hotel-gallery">
            <?php foreach( $hotel['images'][ 'img' ] as $img ) { ?>
            <div><img src="<?php echo kanda_get_cropped_image_src( $img, array( 'width' => 500, 'height' => 300 ) ); ?>" alt="<?php esc_html_e( 'gallery', 'kanda' ); ?>" /></div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
    $has_description = isset( $hotel['description'] );
    $has_hotel_facilities = ( isset( $hotel['hotelfacilities']['facility'] ) && ! empty( $hotel['hotelfacilities']['facility'] ) );
    $has_room_facilities = ( isset( $hotel['roomfacilities']['facility'] ) && ! empty( $hotel['roomfacilities']['facility'] ) );
    if( $has_description || $has_hotel_facilities || $has_room_facilities ) {
?>
<div class="tabs popup-tabs">
    <div class="tab-headings">
        <?php if( $has_description ) { ?>
        <a href="javascript:void(0);" data-target=".hoteloverview" class="btn -sm -primary tab-heading">
            <i class="icon icon-tags"></i>
            <?php esc_html_e( 'Overview', 'kanda' ); ?>
        </a>
        <?php } ?>

        <?php if( $has_hotel_facilities ) { ?>
        <a href="javascript:void(0);" data-target=".hotelfacilities" class="btn -sm -secondary tab-heading">
            <i class="icon icon-hotel"></i>
            <?php esc_html_e( 'Hotel facilities', 'kanda' ); ?>
        </a>
        <?php } ?>

        <?php if( $has_room_facilities ) { ?>
        <a href="javascript:void(0);" data-target=".roomfacilities" class="btn -sm -secondary tab-heading">
            <i class="icon icon-room"></i>
            <?php esc_html_e( 'Room facilities', 'kanda' ); ?>
        </a>
        <?php } ?>
    </div>
    <div class="tab-contents editor-content">
        <?php if( $has_description ) { ?>
        <div class="tab-content hoteloverview">
            <?php
                $hotel_description = preg_replace( '#</?(html|head|body)[^>]*>#i', '', $hotel['description'] );
                $hotel_description = preg_replace( '/style=(["\'])[^\1]*?\1/i', '', $hotel_description, -1 );

                echo $hotel_description;
            ?>
        </div>
        <?php } ?>

        <?php if( $has_hotel_facilities ) { ?>
        <div class="tab-content hotelfacilities hidden">
            <ul>
                <?php foreach( $hotel['hotelfacilities']['facility'] as $facility ) { ?>
                <li><?php echo $facility; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>

        <?php if( $has_room_facilities ) { ?>
        <div class="tab-content roomfacilities hidden">
            <ul>
                <?php foreach( $hotel['roomfacilities']['facility'] as $facility ) { ?>
                    <li><?php echo $facility; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
