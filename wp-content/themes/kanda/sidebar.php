<aside class="sidebar col-sm-3">
    <div class="box">
    <?php if ( is_active_sidebar( 'default-sidebar' ) ) :
        dynamic_sidebar( 'default-sidebar' );
    endif; ?>
    </div>
</aside>