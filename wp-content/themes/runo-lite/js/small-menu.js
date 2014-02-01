/**
 * Handles toggling the main navigation menu for small screens.
 */
jQuery( document ).ready( function( $ ) {
	var $mainheader = $( '#main-header' ),
	    timeout = false;

	$.fn.smallMenu = function() {
		$mainheader.find( '.site-navigation' ).removeClass( 'menu' ).addClass( 'small-menu' );
		$mainheader.find( '#main-nav h3' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );

		$( '.menu-toggle' ).unbind( 'click' ).click( function() {
			$mainheader.find( '.site-navigation' ).toggle();
			$( this ).toggleClass( 'toggled-on' );
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() < 600 )
		$.fn.smallMenu();

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( browserWidth < 600 ) {
				$.fn.smallMenu();
			} else {
				$mainheader.find( '.site-navigation' ).removeClass( 'small-menu' ).addClass( 'menu' );
				$mainheader.find( '#main-nav h3' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
				$mainheader.find( '.menu' ).removeAttr( 'style' );
			}
		}, 200 );
	} );
} );