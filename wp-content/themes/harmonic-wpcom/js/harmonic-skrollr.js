( function( $ ) {

    // Setup variables
    $window = $(window);
    $slide = $('.slide');
    $body = $('body');

    //FadeIn all sections
    $body.imagesLoaded( function() {
        setTimeout(function() {

              // Resize sections
              adjustWindow();

              // Fade in sections
              $body.removeClass('loading').addClass('loaded');

        }, 800);
    });

    function adjustWindow(){

        // Get window size
        winH = $window.height();
        winW = $window.width();

        // Keep minimum height 550
        if(winH <= 550) {
            winH = 550;
        }

        // Init Skrollr for 768 and up
        if( winW >= 768) {

            // Init Skrollr
            var s = skrollr.init({
                forceHeight: false,
            });

            // Resize our slides
            $slide.height(winH);

            s.refresh($('.slide'));

            //The options (second parameter) are all optional. The values shown are the default values.
            skrollr.menu.init(s, {} );

        } else {

            // Init Skrollr
            var s = skrollr.init();
            s.destroy();
        }

    }

    function initAdjustWindow() {
        return {
            match : function() {
                adjustWindow();
            },
            unmatch : function() {
                adjustWindow();
            }
        };
    }

    enquire.register("screen and (min-width : 768x)", initAdjustWindow(), false);

} )( jQuery );
