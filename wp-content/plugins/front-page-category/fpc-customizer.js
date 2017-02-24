
;(function( $ ){

	var api = wp.customize;

	$( document ).ready( function() {

		//var $parent = $( '#customize-control-fpc_categories' );
		var $parent = $( '#customize-control-hide_categories' );

		$parent.find( '.fpc-category' ).on( 'click', function() {

			fpc_update_category_list();

		} );

		var fpc_update_category_list = function() {

			var categories = '';

			$parent.find( '.fpc-category').not( ':checked' ).each( function() {

				if ( '' !== categories ) {

					categories += ',';

				}

				categories += $( this ).val();

			} );

	        var id = $parent.find( '.fpc-hidden-categories' ).prop( 'id' );

            api.instance( id ).set( categories );

		};

	} );

})( jQuery );

