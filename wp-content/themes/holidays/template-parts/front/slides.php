<?php
    $gallery = kanda_fields()->get_option( 'kanda_front_background_slider_gallery', array() );
    if( $gallery ) {
?>
<div class="slides-wrapper">
    <div class="slides">
        <div class="slides-container">
            <?php foreach ( $gallery as $image_id ) {
                echo wp_get_attachment_image( $image_id, 'full' );
            } ?>
        </div>
    </div>
</div>
<?php } ?>