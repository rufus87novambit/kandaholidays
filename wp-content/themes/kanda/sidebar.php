<aside class="sidebar col-md-3">
    <?php
    $show_banners = get_field( 'show_sidebar_banner' );
    $show_banners = is_null( $show_banners ) ? true : $show_banners;
    $show_banners = apply_filters( 'kanda/show_sidebar_banner', $show_banners );
    if( $show_banners ) { ?>
        <div class="box">
            <?php kanda_render_banners( 'sidebar' ); ?>
        </div>
    <?php } ?>
    <?php if ( !is_page_template( 'page-top-destinations.php' ) && is_active_sidebar( 'default-sidebar' ) ) { ?>
        <div class="box">
            <?php dynamic_sidebar( 'default-sidebar' ); ?>
        </div>
    <?php } ?>
</aside>