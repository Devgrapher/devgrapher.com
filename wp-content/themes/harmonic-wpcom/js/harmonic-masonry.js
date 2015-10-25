(function($) {
    $(document).ready(function() {
            var $blocks = $( "#archive-container" );

        $blocks.imagesLoaded( function(){
            $blocks.masonry({
                itemSelector: '.jetpack-portfolio'
            });
        });

        // Rearrange masonry on resize
        $( window ).resize( function() {
            $blocks.masonry();
        });

        // Infinite scroll
        infinite_count = 0;
        $( document.body ).on( "post-load", function () {
            infinite_count = infinite_count + 1;

            // Hide new posts so we can fade them in
            var $newItems = $( '#infinite-view-' + infinite_count + ' .jetpack-portfolio' ).css( { opacity: 0 } );

            // Once images are loaded, add the new posts via masonry
            $blocks.imagesLoaded( function() {
                $newItems.animate( { opacity: 1 } );
                $blocks.masonry( "appended", $newItems ).masonry( 'reloadItems' );
            });
        });
    });

})(jQuery);