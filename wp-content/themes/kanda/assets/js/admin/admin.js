(function($){

    $( function() {

        /**
         * Add background colors to list tables based on row specific column class
         */
        $('.wp-list-table .row-success').parents('tr').addClass( 'background-success-opacity' );
        $('.wp-list-table .row-danger').parents('tr').addClass( 'background-danger-opacity' );

        /**
         * Master data update request
         */
        $( '#iol-master-data-sync' ).on( 'click', '.button-update', function(){

            var _this = $(this),
                _url = $(this).attr( 'href' );

            if( ! _this.hasClass( 'processing' ) ) {

                $.ajax({
                    url: _url,
                    dataType: 'JSON',
                    type: 'POST',
                    beforeSend: function () {
                        _this.addClass('processing').data('static_text', _this.text()).text('Processing ...');
                    },
                    success: function (response) {
                        var _type,
                            _html;

                        _this.removeClass('processing').text(_this.data('static_text'));
                        if( response.success ) {
                            _type = 'updated';
                            _this.siblings( '.button-delete' ).removeClass( 'kanda_hidden' );
                            _this.closest( '.row' ).find( '.last-updated' ).html( response.data.last_updated );
                        } else {
                            _type = 'error';
                        }
                        _html = '<div id="message" class="' + _type + '"><p>' + response.data.message + '</p></div>';

                        $('#iol-master-data-sync').find('#message').remove().end().prepend($(_html));

                    },
                    error: function () {
                        alert('Internal server error');
                    }
                });

            }
            return false;
        } );

        /**
         * Master data delete request
         */
        $( '#iol-master-data-sync' ).on( 'click', '.button-delete', function(){
            var _this = $(this),
                _url = $(this).attr( 'href' );

            if( ! _this.hasClass( 'processing' ) ) {
                $.ajax({
                    url: _url,
                    dataType: 'JSON',
                    type: 'POST',
                    beforeSend: function () {
                        _this.addClass('processing').data('static_text', _this.text()).text('Processing ...');
                    },
                    success: function (response) {
                        var _type,
                            _html;

                        _this.removeClass('processing').text(_this.data('static_text'));
                        if( response.success ) {
                            _type = 'updated';
                            _this.addClass( 'kanda_hidden' );
                            _this.closest( '.row' ).find( '.last-updated').html( response.data.last_updated );
                        } else {
                            _type = 'error';
                            _this.removeClass( 'kanda_hidden' );
                        }
                        _html = '<div id="message" class="' + _type + '"><p>' + response.data.message + '</p></div>';

                        $('#iol-master-data-sync').find('#message').remove().end().prepend($(_html));

                    },
                    error: function () {
                        alert('Internal server error');
                    }
                });
            }

            return false;
        });

        if( $('.datepicker').length ) {
                $('.datepicker').datepicker({
                showOn: 'focus',
                defaultDate: new Date(),
                dateFormat: 'dd MM, yy',
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-10:c+10'
            });
        }
    });

})(jQuery);
