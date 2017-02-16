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
            buttonImage: kanda.theme_url + 'images/back/calendar.gif',
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

    /******************************************* Avatar functionality *******************************************/
    if( $( '#avatar-upload-ui').length > 0 ) {

        var avatar_uploader = new plupload.Uploader( avatar_uploader_config );

        avatar_uploader.init();

        /* a file was added in the queue */
        avatar_uploader.bind( 'FilesAdded', function( up, files ) {
            $.each( files, function( i, file ) {
                $('#filelist').append(
                    '<div class="file" id="' + file.id + '"><div class="filename"><b>' + file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' + '</div><div class="progress"></div></div>'
                );
            });
            up.refresh();
            up.start();
        });

        avatar_uploader.bind( 'UploadProgress', function( up, file ) {
            $( '#' + file.id + " .progress" ).width( file.percent + "%" );
            $( '#' + file.id + " span" ).html( plupload.formatSize( parseInt( file.size * file.percent / 100 ) ) );
        });

        // a file was uploaded
        avatar_uploader.bind( 'FileUploaded', function( up, file, response ) {
            response = $.parseJSON( response["response"] );

            if( response.success ) {
                var _uploader = $( '#' + avatar_uploader_config.container),
                    _cropper = $( '#cropper'),
                    _cropper_image = $( '#cropper-avatar' ),
                    _preview_image = $( '#preview-avatar' ),
                    _delete_avatar = $( '#avatar-delete' );

                $( '#' + file.id ).remove();

                _cropper_image.attr( 'src', response.data.full_url );
                _preview_image.attr( 'src', response.data.thumb_url );

                _uploader.remove();
                _cropper.removeClass( 'hidden' );
                _delete_avatar.removeClass( 'hidden' );

                init_cropper( _cropper_image, _preview_image );

            } else {
                $( '#' + file.id ).addClass( 'upload-error' ).append('<div class="error-msg help-block">' + response.data.message + '</div>');
            }
        });


    }

    if( $( '#cropper-avatar').length > 0 ) {

        /**
         * Set canvas functionality
         *
         * @param $object
         * @param $preview
         */
        function set_preview_canvas( $object, $preview ) {
            var cropped_canvas = $object.cropper('getCroppedCanvas', {
                    width   : 151,
                    height  : 151
                }),
                coordinates = $object.cropper( 'getData', true );

            $preview.attr( 'src', cropped_canvas.toDataURL() );
            $( '#coordinates').val( JSON.stringify( coordinates ) );
        }

        /**
         * Initialize cropper
         *
         * @param $object
         * @param $preview
         * @param $data
         */
        function init_cropper( $object, $preview, $data ) {
            var first_init = true;
            $object.cropper({
                aspectRatio: 1,
                zoomable : false,
                autoCropArea : 0.7,
                minCropBoxWidth : 151,
                minCropBoxHeight : 151,
                built: function(e) {
                    if( $data ) {
                        var $data_object = $.parseJSON( $data.replace(/'/g , '"') );
                        if (!$.isEmptyObject( $data_object ) ) {
                            $object.cropper('setData', $data_object );
                        }
                    }
                },
                crop: function(e) {
                    if( ! first_init ) {
                        set_preview_canvas($object, $preview);
                    } else {
                        first_init = false;
                    }
                }
            });

        }

        if( $( '#cropper.has-avatar').length ) {
            init_cropper($('#cropper-avatar'), $('#preview-avatar'), $('#coordinates').val());
        }
    }
    /***************************************** /end Avatar functionality *****************************************/
})(jQuery);