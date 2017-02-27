(function($) {

    //menu toggle
    $('#menuBtn').on( 'click', function(){
        $('body').toggleClass('menu-opened');
        return false;
    });

    //sub menu toggle
    $('.touchevents .main-menu >li >a').on( 'click', function(){
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

    //if( $('.flash-message').length > 0 ) {
    //    var flash_message_timeout = setTimeout( function(){
    //        $('.flash-message').addClass( 'shown' );
    //        setTimeout( function(){
    //            $('.flash-message').remove()
    //        }, 500 );
    //    } , 5000);
    //}

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
    if( $('.datepicker-start-date').length > 0 && $('.datepicker-end-date').length > 0 ) {

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
        $( '.datepicker-start-date' ).datepicker({
            showOn: 'focus',
            dateFormat: 'dd MM, yy',
            minDate: checkin,
            onSelect: function(){
                checkin = new Date( this.value );
                checkout = new Date( checkin.getTime() + get_day_in_milliseconds( $( '#nights_count').val() ) );

                $( '.datepicker-end-date').datepicker( 'option', 'minDate', checkout );
                checkout = new Date( $( '.datepicker-end-date').datepicker( 'getDate' ) );
                $( '#nights_count' ).val( calculate_nights_count( checkin, checkout ) );
            }
        }).datepicker( 'setDate', checkin );

        /**
         * Checkout functionality
         */
        $( '.datepicker-end-date' ).datepicker({
            showOn: 'focus',
            dateFormat: 'dd MM, yy',
            minDate: checkout,
            onSelect: function(){
                checkin = $( '.datepicker-start-date' ).datepicker( 'getDate' );
                checkout = new Date( this.value );

                $( '#nights_count' ).val( calculate_nights_count( checkin, checkout ) );
            }
        }).datepicker( 'setDate', checkout );

        /**
         * Nights count functionality
         */
        $( '#nights_count').on( 'change keyup', function(){
            checkin = $( '.datepicker-start-date' ).datepicker( 'getDate' );
            if( checkin ) {
                checkout = new Date( checkin.getTime() + get_day_in_milliseconds( $( this ).val() ) );
            }
            $( '.datepicker-end-date' ).datepicker( 'setDate', checkout );
        } );
    }

    /**
     * Rooms count functionslity
     */
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
                append_box.append('<input type="number" name="room_occupants[' + block_index + '][child][age][' + i + ']" class="form-control" value="0" min="1" max="12">');
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

    /** tabs in popup */
    $('body').on( 'click', '.popup-tabs .tab-headings a', function(){
        var _this = $(this),
            _tabs = _this.closest( '.tabs' ),
            _headings = _this.siblings( '.tab-heading' ),
            _contents = _tabs.find( '.tab-contents .tab-content'),
            _target = _this.data('target'),
            _active_class = '-warning',
            _static_class = '-info';

        if( _this.hasClass( _active_class ) ) {
            return;
        }

        _headings.not(_this).removeClass(_active_class).addClass(_static_class);
        _this.removeClass(_static_class).addClass(_active_class);

        _contents.filter(':visible').addClass('hidden');
        _contents.filter(_target).removeClass('hidden');
    } );
    /*************************************** /end Currency functionality ***************************************/

    /******************************************* Avatar functionality *******************************************/
    if( $( '#avatar-upload-ui').length > 0 ) {

        var avatar_uploader = new plupload.Uploader( avatar_uploader_config );

        avatar_uploader.init();

        /* a file was added in the queue */
        avatar_uploader.bind( 'FilesAdded', function( up, files ) {
            $('#filelist').empty();
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
                $( '#' + file.id + " .progress").remove();
                $( '#' + file.id ).addClass( 'has-error' ).append('<div class="form-control-feedback"><small>' + response.data.message + '</small></div>');
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

    /*********************************************** Form Validation *********************************************/
    var kanda_back_form_validation_default_args = {
        onfocusout: function( element ) {
            if( this.element( element ) ) {
                $( element ).removeClass('error')
                    .parents( '.form-group' ).removeClass( 'has-error' )
                    .find( '.form-control-feedback').html( '' );
            }
        },
        errorPlacement : function( error, element ) {
            element.siblings( '.form-control-feedback').html( '<small>' + error.text() + '</small>' );
            element.parents('.form-group').addClass( 'has-error' );
        }
    };

    /**
     * loginRegex validation
     */
    $.validator.addMethod( 'loginRegex' , function( value, element ) {
        return /^[a-z0-9\_\-]+$/i.test( value );
    } );

    /**
     * Phone number validation
     */
    $.validator.addMethod( 'phone_number', function( value, element ) {
        var regex_result = /^[\+:]*\d{9,}$/.test( value );
        if( $( element).hasClass( 'optional' ) ) {
            return value ? regex_result : true;
        }
        return regex_result;
    } );

    /************************************************ Edit profile ***********************************************/

    /**
     * Edit profile general
     */
    if( $('#form_edit_profile').length > 0 ) {
        var kanda_back_form_edit_profile = $('#form_edit_profile'),
            kanda_back_form_edit_profile_validation_args = {
            rules : {
                user_email : {
                    required : true,
                    email : true
                },
                first_name : {
                    required : true
                },
                last_name : {
                    required : true
                },
                mobile : {
                    phone_number : true
                },
                company_phone : {
                    phone_number : true
                },
                company_website: {
                    url : true
                }
            },
            messages : {
                user_email : {
                    required : edit_profile.validation.user_email.required,
                    email : edit_profile.validation.user_email.email
                },
                first_name : {
                    required : edit_profile.validation.first_name.required,
                },
                last_name : {
                    required : edit_profile.validation.last_name.required,
                },
                mobile : {
                    phone_number : edit_profile.validation.mobile.phone_number,
                },
                company_phone : {
                    phone_number : edit_profile.validation.company_phone.phone_number,
                },
                company_website: {
                    url : edit_profile.validation.company_website.url,
                }
            }
        };

        kanda_back_form_edit_profile_validation_args = Object.assign(
            kanda_back_form_validation_default_args,
            kanda_back_form_edit_profile_validation_args
        );

        kanda_back_form_edit_profile.validate( kanda_back_form_edit_profile_validation_args );

        kanda_back_form_edit_profile.on( 'submit', function() {
            if ( ! $( this ).valid() ) {
                return false;
            }
        } );
    }

    /**
     * Edit password
     */
    if( $('#form_edit_password').length > 0 ) {
        var kanda_back_form_edit_password = $('#form_edit_password'),
            kanda_back_form_edit_password_validation_args = {
                rules : {
                    old_password : {
                        required : true
                    },
                    new_password : {
                        required : true,
                        rangelength : [ edit_password.validation_data.password_min_length, edit_password.validation_data.password_max_length ]
                    },
                    confirm_password : {
                        required : true,
                        equalTo : '#new_password'
                    }
                },
                messages : {
                    old_password : {
                        required : edit_password.validation.old_password.required
                    },
                    new_password : {
                        required : edit_password.validation.new_password.required,
                        rangelength : edit_password.validation.new_password.rangelength,
                    },
                    confirm_password : {
                        required : edit_password.validation.confirm_password.required,
                        equalTo : edit_password.validation.confirm_password.equalTo,
                    }
                }
            };

        kanda_back_form_edit_password_validation_args = Object.assign(
            kanda_back_form_validation_default_args,
            kanda_back_form_edit_password_validation_args
        );

        kanda_back_form_edit_password.validate( kanda_back_form_edit_password_validation_args );

        kanda_back_form_edit_password.on( 'submit', function() {
            if ( ! $( this ).valid() ) {
                return false;
            }
        } );
    }
    /********************************************** /end Edit profile ********************************************/

    /********************************************** Popups **********************************************/
    function loading_popup() {
        $.magnificPopup.open({
            items: {
                src: '#loading-popup',
            },
            type:'inline',
            showCloseBtn : false,
            closeOnBgClick : false,
            enableEscapeKey : false,
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
    }

    function error_popup( content ) {
        $.magnificPopup.close();

        $('#error-popup').html( content );
        $.magnificPopup.open({
            items: {
                src: '#error-popup',
            },
            type:'inline',
            showCloseBtn : true,
            closeOnBgClick : true,
            enableEscapeKey : true,
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
    }

    $('.maps-popup').magnificPopup({
        type: 'iframe',
        tLoading : '<img src="' + kanda.themeurl + '/images/back/ripple.svg" alt="loading" />',
        iframe: {
            markup: '<div class="mfp-iframe-scaler">'+
            '<div class="mfp-close"></div>'+
            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
            '</div>', // HTML markup of popup, `mfp-close` will be replaced by the close button

            patterns: {
                youtube: {
                    index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                    id: 'v=', // String that splits URL in a two parts, second part should be %id%
                    // Or null - full URL will be returned
                    // Or a function that should return %id%, for example:
                    // id: function(url) { return 'parsed id'; }

                    src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                },
                gmaps: {
                    index: '//maps.google.',
                    src: '%id%&output=embed'
                }

                // you may add here more sources

            },

            srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
        }
    });

    $('.ajax-popup').magnificPopup({
        type: 'ajax',
        midClick: true,
        closeBtnInside:true,
        tLoading : '<img src="' + kanda.themeurl + '/images/back/ripple.svg" alt="loading" />',
        callbacks : {
            parseAjax: function ( mfpResponse ) {
                if( ! mfpResponse.data ) {
                    mfpResponse.data = kanda.translatable.invalid_request
                }
                mfpResponse.data = '<div class="white-popup">' + mfpResponse.data + '</div>';
            },
            ajaxContentAdded : function(){
                $( this.content ).find( '.hotel-gallery').slick({
                    arrows      : false,
                    fade        : false,
                    autoplay    : true,
                    dots        : true,
                    dotsClass   : 'slick-dots container'
                });;
            }
        }

    });

    $('.open-popup').magnificPopup({
        type:'inline',
        midClick: true,
        tLoading : '<img src="' + kanda.themeurl + '/images/back/ripple.svg" alt="loading" />'
    });
    /********************************************** Popups **********************************************/

    /************************************************ Search hotels **********************************************/
    if( $('#hotel_search_form').length > 0 ) {

        $('#hotel_search_form').on( 'submit', function(){

            var _this = $(this),
                _criteria = _this.serialize();

            $.ajax({
                url : kanda.ajaxurl,
                type : 'POST',
                dataType : 'JSON',
                data: {
                    action : 'search_hotels',
                    criteria : _criteria
                },
                beforeSend: loading_popup(),
                success : function( response ){
                    if( response.success ) {
                        window.location = response.data.redirect_to
                    } else {
                        error_popup( response.data.message );
                    }
                },
                error : function(){
                    $.magnificPopup.close();
                }
            });

            return false;

        } );

    }
    /********************************************** /end Search hotels *******************************************/
})(jQuery);