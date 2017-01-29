(function($) {

    $('.currency a').on('click', function(){
        $(this).parent().toggleClass( 'opened' );

        return false;
    });

    function close_currency_block( e ) {
        if( $( e.target).closest( '.currency' ).length === 0 ) {
            $('.currency.opened').removeClass('opened');
        }
    }

    $('body').on('click', function( e ){
        close_currency_block( e );
    });

})(jQuery);