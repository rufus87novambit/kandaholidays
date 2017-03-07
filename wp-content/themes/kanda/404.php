<?php
$template_options = kanda_get_page_template_variables( 404 );
get_header( $template_options['header'] );

if( is_user_logged_in() ) { ?>
    <div class="row">
        <div class="primary col-md-9">
            <div class="box main-content">
                <h1 class="page-title"><?php echo apply_filters( 'the_title', $template_options['title'], 0 ); ?></h1>

                <?php echo apply_filters( 'the_content', $template_options['content'] ); ?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php } else { ?>

<div class="container error-404">
    <div class="page-wrapper bordered-box">
        <h1 class="page-title"><?php echo apply_filters( 'the_title', $template_options['title'], 0 ); ?></h1>

        <div class="page-content editor-content"><?php echo apply_filters( 'the_content', $template_options['content'] ); ?></div>
    </div>
</div>
<?php } ?>

<?php get_footer( $template_options['footer'] ); ?>