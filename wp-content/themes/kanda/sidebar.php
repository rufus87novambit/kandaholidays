<aside class="sidebar col-md-3">
    <div class="box">
        <div class="slider">
            <div><img src="<?php echo KANDA_THEME_URL; ?>/images/delete/ad/ad1.jpg" alt="ad1"/></div>
            <div><img src="<?php echo KANDA_THEME_URL; ?>/images/delete/ad/ad2.jpg" alt="ad1"/></div>
        </div>
    </div>
    <div class="box">
    <?php if ( is_active_sidebar( 'default-sidebar' ) ) :
        dynamic_sidebar( 'default-sidebar' );
    endif; ?>
    </div>
</aside>