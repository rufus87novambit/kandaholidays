<div class="slides-wrapper">
    <div class="slides">
        <div class="slides-container">
            <?php
                $range = range( 1, 10 );
                shuffle( $range );
                foreach( $range as $image ) { ?>
                <img src="<?php echo KH_THEME_URL; ?>images/delete/slideshow/<?php echo $image; ?>.jpg" width="100%" alt="slideshow" />
            <?php } ?>
        </div>
    </div>
</div>