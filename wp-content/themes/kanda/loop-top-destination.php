<li>
    <h4 class="article-title"><?php the_title(); ?></h4>
    <div class="row">
        <?php if( has_post_thumbnail() ) { ?>
        <div class="article-obj col-sm-4">
            <?php the_post_thumbnail( 'image315x152' ); ?>
        </div>
        <?php } ?>
        <div class="article-body col-sm-8">
            <div class="editor-content"><?php the_content(); ?></div>
        </div>
        <?php if( $excel_file_url = get_field( 'excel_url' ) ) { ?>
        <div class="col-lg-12">
            <div class="article-actions pull-right">
                <a href="<?php echo $excel_file_url; ?>" download class="btn -secondary -sm pull-right clearfix"><?php _e( 'Download', 'kanda' ); ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
</li>