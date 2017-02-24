jQuery( document ).ready( function( $ ) {

	function navMenu() {

		var $navIcon = $('#toggle-nav');
		var $sidebarMenu = $('.navigation-main div > ul');
		var $header = $('.site-header');

		// Display/hide navigation
		$navIcon.unbind( 'click' ).click( function() {
			$('body').toggleClass('show-nav');
		});

		//Set nav menu position depending on header height
		var $sidebarMenuPosition = $header.height() + 32;

		$sidebarMenu.css('margin-top', $sidebarMenuPosition + 'px');

	};

	$(window).on( 'load', navMenu );

	var $timeout = false;

	if ( false !== $timeout ) {
		clearTimeout( $timeout );
	}

	$timeout = setTimeout( function() {
		$(window).on( 'resize', navMenu );
	}, 200 );

} );