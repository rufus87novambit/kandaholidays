<div class="row">
    <div class="col-sm-6">
        <div>
            <i class="icon icon-phone"></i>
            <?php echo $hotel['hotelphone']; ?>
        </div>
        <div>
            <i class="icon icon-email"></i>
            <?php echo $hotel['hotelemail']; ?>
        </div>
        <div>
            <i class="icon icon-website"></i>
            <?php echo $hotel['hotelweb']; ?>
        </div>
        <div>
            <i class="icon icon-address"></i>
            <?php echo $hotel['hoteladdress']; ?>
        </div>
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
        <a href="javascript:void(0);" data-target=".hoteloverview" class="btn -sm -warning tab-heading"><?php esc_html_e( 'Overview', 'kanda' ); ?></a>
        <?php } ?>

        <?php if( $has_hotel_facilities ) { ?>
        <a href="javascript:void(0);" data-target=".hotelfacilities" class="btn -sm -info tab-heading"><?php esc_html_e( 'Hotel facilities', 'kanda' ); ?></a>
        <?php } ?>

        <?php if( $has_room_facilities ) { ?>
        <a href="javascript:void(0);" data-target=".roomfacilities" class="btn -sm -info tab-heading"><?php esc_html_e( 'Room facilities', 'kanda' ); ?></a>
        <?php } ?>
    </div>
    <div class="tab-contents editor-content">
        <?php if( $has_description ) { ?>
        <div class="tab-content hoteloverview">
            <?php echo preg_replace( '#</?(html|head|body)[^>]*>#i', '', $hotel['description'] ); ?>
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
