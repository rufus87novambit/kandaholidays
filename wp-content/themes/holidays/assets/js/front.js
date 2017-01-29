(function($){

    $( window ).on('load', function(){

        $('.slides').superslides({
            play : 7000,
            animation : 'fade',
            pagination : false,
            hashchange: false
        });

    });

})(jQuery)
