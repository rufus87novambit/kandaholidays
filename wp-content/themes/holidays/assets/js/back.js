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

    $('#hotel-rating').barrating({
        theme: 'kanda-stars',
        initialRating: '',
        allowEmpty : true,
        emptyValue : '',
        deselectable: true,
        hoverState: false,
        showValues: true,
        onSelect: function( value, text, event ) {
            var _helper = $( this.$elem.data( 'text-holder' )),
                _value = ( value && text ) ? $(text).text() : _helper.data( 'default' );
            _helper.html( _value );
        }
    });

    $('body').on('click', function( e ){
        close_currency_block( e );
    });

})(jQuery);