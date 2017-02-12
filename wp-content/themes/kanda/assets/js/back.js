(function($) {
    //currency
    $('.currency a').on('click', function(){
        $(this).parent().toggleClass( 'opened' );

        return false;
    });

    function close_currency_block( e ) {
        if( $( e.target).closest( '.currency' ).length === 0 ) {
            $('.currency.opened').removeClass('opened');
        }
    }

   /* $('#hotel-rating').barrating({
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
    });*/

    $('body').on('click', function( e ){
        close_currency_block( e );
    });




    //menu toggle
    $('#menuBtn').click(function(){
        $('body').toggleClass('menu-opened');
        return false;
    });
    //sub menu toggle
    $('.touchevents .sub-toggler').click(function(){
        $(this).parent().toggleClass('active');
        return false;
    });


    //customSelect
    if($(".custom-select").length>0) {
        $(".custom-select").customSelect();
    }

    //datepicker
    if($(".datepicker").length>0) {
        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: "../images/back/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date"
        });
    }

    //magnific popup example
   /* if($('.popup-block').length>0){
        $('.open-popup').magnificPopup({
            type:'inline',
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
    }*/

    //slider
    if($('.slider').length>0){
        $('.slider').slick({
            arrows: false,
            fade: true,
            autoplay: true,
            dots:true,
            dotsClass:'slick-dots container'
        });
    }

})(jQuery);