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
    $('#menuBtn').on( 'click', function(){
        $('body').toggleClass('menu-opened');
        return false;
    });
    //sub menu toggle
    $('.touchevents .sub-toggler').on( 'click', function(){
        $(this).parent().toggleClass('active');
        return false;
    });


    //customSelect
    if($(".custom-select").length>0) {
        $(".custom-select").customSelect();
    }


    //magnific popup example
    if($('.popup-block').length>0){
        $('.open-popup').magnificPopup({
            type:'inline',
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
    }

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

    //trigger upload
    if( $('.avatar-actions .upload').length>0 ) {

        function init_cropper_popup( image_src ) {
            var popup = $( '#image-crop' ),
                image = $( '#image-crop img' );

            image.attr( 'src', image_src );
            popup.addClass( 'opened' );

            image.cropper( {
                autoCrop: true,
                zoomable: false,
                aspectRatio: 1,
                ready: function(){},
                crop: function( e ) {
                    var cropped_url = $(this).cropper('getCroppedCanvas', {
                        width: 151,
                        height: 151
                    }).toDataURL();
                    $('.user-avatar').attr( 'src', cropped_url );
                }
            } );
        }


        $('.avatar-actions .upload').on( 'click', function(){
            var _target = $(this).data( 'target' );
            $( _target).trigger( 'click' );

            return false;
        } );

        $( '.upload_field').on( 'change', function(){

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    init_cropper_popup( e.target.result );
                }

                reader.readAsDataURL( this.files[0] );
            }
        } );
    }

})(jQuery);