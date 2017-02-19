(function($) {

    //menu toggle
    $('#menuBtn').on( 'click', function(){
        $('body').toggleClass('menu-opened');
        return false;
    });

    //sub menu toggle
    $('.sub-toggler').on( 'click', function(){
        $(this).parent().toggleClass('active');
        return false;
    });

    /************************************************ Helpers **************************************************/
    /**
     * Rating
     */
    $('.rating').barrating({
        theme: 'kanda-stars',
        initialRating: '',
        allowEmpty : true,
        emptyValue : '',
        deselectable: true,
        hoverState: false,
        showValues: true,
    });

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
            showOn: 'focus',
            minDate: new Date()
        });
    }

    /**
     * Checkin / Checkout datepickers
     */
    if( $('.datepicker-checkin').length > 0 && $('.datepicker-checkout').length > 0 ) {

        var checkin = new Date();
        var checkout = new Date( checkin.getTime() + get_day_in_milliseconds( $( '#nights_count').val() ) );

        /**
         * Get date in milliseconds
         * @returns {number}
         */
        function get_day_in_milliseconds( $days ) {
            return $days * 24 * 60 * 60 * 1000;
        }

        /**
         * Calculate nights count
         * @param $first
         * @param $second
         * @returns {number}
         */
        function calculate_nights_count( $first, $second ) {
            return Math.round( Math.abs( ( $first.getTime() - $second.getTime() ) / get_day_in_milliseconds( 1 ) ) );
        }

        /**
         * Checkin functionality
         */
        $( '.datepicker-checkin' ).datepicker({
            showOn: 'focus',
            dateFormat: 'dd MM, yy',
            minDate: checkin,
            onSelect: function(){
                checkin = new Date( this.value );
                checkout = new Date( checkin.getTime() + get_day_in_milliseconds( $( '#nights_count').val() ) );

                $( '.datepicker-checkout').datepicker( 'option', 'minDate', checkout );
                checkout = new Date( $( '.datepicker-checkout').datepicker( 'getDate' ) );
                $( '#nights_count' ).val( calculate_nights_count( checkin, checkout ) );
            }
        }).datepicker( 'setDate', checkin );

        /**
         * Checkout functionality
         */
        $( '.datepicker-checkout' ).datepicker({
            showOn: 'focus',
            dateFormat: 'dd MM, yy',
            minDate: checkout,
            onSelect: function(){
                checkin = $( '.datepicker-checkin' ).datepicker( 'getDate' );
                checkout = new Date( this.value );

                $( '#nights_count' ).val( calculate_nights_count( checkin, checkout ) );
            }
        }).datepicker( 'setDate', checkout );

        /**
         * Nights count functionality
         */
        $( '#nights_count').on( 'change keyup', function(){
            checkin = $( '.datepicker-checkin' ).datepicker( 'getDate' );
            if( checkin ) {
                checkout = new Date( checkin.getTime() + get_day_in_milliseconds( $( this ).val() ) );
            }
            $( '.datepicker-checkout' ).datepicker( 'setDate', checkout );
        } );
    }

    if( $( '#rooms_count').length > 0 ) {
        $( '#rooms_count' ).on( 'change', function(){
            var count = $(this).val(),
                clone = null,
                existing_count = $('.occupants').length;

            if( count > existing_count ) {
                for( var i = 0; i < ( count - existing_count ); i++ ) {
                    var block_index = existing_count + i + 1;
                    clone = $( '.occupants-cloneable' ).clone( true );

                    clone
                        .data( 'index', block_index )
                        .removeClass( 'occupants-cloneable' )
                        .find( '.children-age-box').addClass('hidden')
                            .find('.children-ages').empty().end().end()
                        .find('legend span').text( block_index ).end()
                        .find('input, select').each( function(){
                            this.name = this.name.replace('[1]', '[' + block_index + ']');
                        }).end()
                        .find( 'span.customSelect').remove().end()
                        .find( 'select.hasCustomSelect' ).removeAttr( 'style' )

                    clone.insertAfter( $('.occupants:last') );
                    $('.occupants:last .custom-select').customSelect();
                }
            } else if( count < existing_count ) {
                $('.occupants').slice( count - existing_count).remove();
            }
        } );

        $('.children-presence').on( 'change', function(){
            var value = $(this).val(),
                wrap = $(this).closest( '.occupants'),
                block_index = wrap.data('index'),
                children_box = wrap.find( '.children-age-box'),
                append_box = children_box.find( '.children-ages' );

            append_box.empty();
            for( var i = 0; i < value; i++ ) {
                append_box.append('<input type="number" name="room_occupants[' + block_index + '][children_age][]" class="form-control" value="0" min="1" max="12">');
            }
            if( value > 0 ) {
                children_box.removeClass('hidden');
            } else {
                children_box.addClass('hidden');
            }
        } );
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
                    _cropper_image = 'cropper-avatar',
                    _preview_image = 'preview-avatar',
                    _delete_avatar = $( '#avatar-delete' );

                $( '#' + file.id ).remove();

                $( '#' + _cropper_image ).attr( 'src', response.data.full_url );
                $( '#' + _preview_image ).attr( 'src', response.data.thumb_url );

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
         * Initialize cropper
         *
         * @param $object
         * @param $preview
         * @param $data
         */
        function init_cropper( $image, $preview ) {
            var cropper = new Cropper( document.getElementById( $image ), {
                aspectRatio: 1,
                zoomable : false,
                autoCropArea : 0.8,
                responsive : true,
                minCropBoxWidth : 151,
                minCropBoxHeight : 151,
                preview: '.avatar-wrapper',
                crop: function(e) {
                    $( '#coordinates').val( JSON.stringify( cropper.getData( true ) ) );
                }
            } );
            }

        if( $( '#cropper.has-avatar').length ) {
            init_cropper( 'cropper-avatar', 'preview-avatar' );
        }
    }
    /***************************************** /end Avatar functionality *****************************************/
})(jQuery);