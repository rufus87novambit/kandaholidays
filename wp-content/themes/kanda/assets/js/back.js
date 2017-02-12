(function($) {


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




    //menu toggle
    $('#menuBtn').on( 'click', function(){
        $('body').toggleClass('menu-opened');
        return false;
    });

    //sub menu toggle
    $('.touchevents .sub-toggler').on( 'click', function(){
        $(this).parent().toggleClass('active');
        return false;
    });

    /************************************************ Helpers **************************************************/
    /**
     * Custom Select
     */
    if($('.custom-select').length > 0) {
        $(".custom-select").customSelect();
    }

    /**
     * Date picker
     */
    if( $( '.datepicker' ).length > 0 ) {
        $( '.datepicker' ).datepicker({
            showOn: 'button',
            buttonImage: '../images/back/calendar.gif',
            buttonImageOnly: true,
            buttonText: 'Select date'
        });
    }

    /**
     * Slider
     */
    if( $('.slider').length > 0 ){
        $('.slider').slick({
            arrows      : false,
            fade        : true,
            autoplay    : true,
            dots        :true,
            dotsClass   : 'slick-dots container'
        });
    }
    /********************************************** /end Helpers ***********************************************/

    /***************************************** Currency functionality ******************************************/
    /**
     * Open currency block on node event (click)
     */
    $('.currency a').on('click', function(){
        $(this).parent().toggleClass( 'opened' );

        return false;
    });

    /**
     * Close currency block on body event (click) except currency block
     */
    $('body').on('click', function( e ){
        if( $( e.target ).closest( '.currency' ).length === 0 ) {
            $( '.currency.opened' ).removeClass('opened');
        }
    });
    /*************************************** /end Currency functionality ***************************************/

    /******************************************* Popup functionality *******************************************/
    /**
     * Open popup on node event (click)
     */
    $('.popup-block').on('click', function(){
        var popup_id = $(this).data('target');
        if( popup_id ) {
            open_popup( popup_id );
        }
    });

    /**
     * Close popup on close button event (click)
     */
    $('.popup-close').on('click', function(){
        close_popup( $(this) );
    });

    /**
     * Open popup by popup id
     *
     * @param popup_id
     * @param callbacks
     */
    function open_popup( popup_id, callbacks ) {
        var default_callbacks = { before : null, before_show : null, after : null };
        $.extend( default_callbacks, callbacks );

        if( typeof default_callbacks.before == 'function' ) {
            default_callbacks.before();
        }

        $('body').addClass('popup-open');

        if( typeof default_callbacks.before_show == 'function' ) {
            default_callbacks.before_show();
        }

        $('#'+ popup_id ).addClass('open');

        if( typeof default_callbacks.after == 'function' ) {
            setTimeout( default_callbacks.after, 10 );
        }
    }

    /**
     * Close popup
     *
     * @param btn
     * @param callbacks
     */
    function close_popup( btn, callbacks ) {
        var default_callbacks = { before : null, after_close : null, after : null };
        $.extend( default_callbacks, callbacks );

        if( typeof default_callbacks.before == 'function' ) {
            default_callbacks.before();
        }

        btn.closest( 'popup-wrap').removeClass('open');

        if( typeof default_callbacks.after_close == 'function' ) {
            default_callbacks.after_close();
        }

        $('body').removeClass('popup-open');

        if( typeof default_callbacks.after == 'function' ) {
            setTimeout( default_callbacks.after, 10 );
        }
    }
    /***************************************** /end Popup functionality *****************************************/

})(jQuery);