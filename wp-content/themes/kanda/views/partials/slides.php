<?php
    $gallery = kanda_get_theme_option( 'front_pages_slider_gallery', array() );
    if( $gallery ) {
?>
<div class="slides-wrapper">
    <div class="slides">
        <div class="slides-container">
            <?php foreach ( $gallery as $gallery_item ) {
                echo wp_get_attachment_image( $gallery_item['image'], 'full' );
            } ?>
        </div>
    </div>
</div>
<?php } ?>