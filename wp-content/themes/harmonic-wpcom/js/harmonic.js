(function($) {

    $(document).ready(function() {
        function showSection() {

            var menu = $('#mobile-link');
            var menuShow = $('#mobile-block');

            function switchClass($myvar) {
                if ($myvar.hasClass('active')) {
                    $myvar.removeClass('active');
                } else {
                    $myvar.addClass('active');
                }
            }

            menu.on('click', function() {
                switchClass($(this));
                menuShow.slideToggle();
            });

        }

        $(window).on('load', showSection);
    });

        /**
    * Navigation sub menu show and hide
    *
    * Show sub menus with an arrow click to work across all devices
    * This switches classes and changes the genericon.
    * Note: Props Espied for the aria addition
    *
    */
    $( '.main-navigation .page_item_has_children > a, .main-navigation .menu-item-has-children > a' ).append( '<div class="showsub-toggle" aria-expanded="false"></div>' );

    $( '.showsub-toggle' ).click( function( e ) {
        e.preventDefault();
        $( this ).toggleClass( 'sub-on' );
        $( this ).parent().next( '.children, .sub-menu' ).toggleClass( 'sub-on' );
        $( this ).attr( 'aria-expanded', $( this ).attr( 'aria-expanded' ) == 'false' ? 'true' : 'false');
    } );

})(jQuery);

(function($) {
    var $window = $(window);
    var nav = $('#masthead');
    $window.scroll(function(){
    if ($window.scrollTop() >= 300) {
            nav.addClass('fixed-header');
        }
        else {
            nav.removeClass('fixed-header');
        }
    });

})(jQuery);