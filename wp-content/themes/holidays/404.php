<?php get_header( 'guest' ); ?>

<div class="container error-404">
    <div class="page-wrapper bordered-box">

        <h1 class="page-title"><?php esc_html_e( 'Oops', 'boombox' ); ?></h1>

        <div class="page-content editor-content">
            <p class="text"><?php esc_html_e( 'We couldn\'t find the page you are looking for.', 'boombox' ); ?></p>

            <p class="text"><?php esc_html_e( 'It may have expired, or there could be a typo. Maybe you can find what you need from our homepage.', 'boombox' ); ?></p>
            <p class="text-center">
                <a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'boombox' ); ?></a>
                <a class="btn" href="<?php echo esc_url( home_url( '/login' ) ); ?>"><?php esc_html_e( 'Login', 'boombox' ); ?></a>
            </p>
        </div>
    </div>
</div>

<?php get_footer( 'guest' ); ?>