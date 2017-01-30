(function($){

    /**
     * Add background colors to list tables based on row specific column class
     */
    $( document ).on( 'ready', function(){
        $('.wp-list-table .row-success').parents('tr').addClass( 'background-success-opacity' );
        $('.wp-list-table .row-danger').parents('tr').addClass( 'background-danger-opacity' );
    } );



})(jQuery);
