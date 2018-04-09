( function( $ ) {

	$( document ).ready(function( $ ) {

		// Featured carousel.
		if ( $( '.main-product-carousel' ).length > 0 ) {
			$( '.main-product-carousel' ).slick();
		}

		// Search icon.
		if ( $( '.search-icon' ).length > 0 ) {
			$( '.search-icon' ).on('click', function( e ) {
				e.preventDefault();
				$( '.search-box-wrap' ).slideToggle();
			});
		}

		// Mobile menu.
		$( '#mobile-trigger' ).sidr({
			timing: 'ease-in-out',
			speed: 500,
			source: '#mob-menu',
			name: 'sidr-main'
		});

		$( '#mobile-trigger2' ).sidr({
			timing: 'ease-in-out',
			speed: 500,
			source: '#category-list',
			side: 'right',
			name: 'sidr-category'
		});

		// Implement go to top.
		var $scrollup_object = $( '#btn-scrollup' );
		if ( $scrollup_object.length > 0 ) {
			$( window ).scroll( function() {
				if ( $( this ).scrollTop() > 100 ) {
					$scrollup_object.fadeIn();
				} else {
					$scrollup_object.fadeOut();
				}
			});

			$scrollup_object.on('click', function() {
				$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
				return false;
			});
		}

	});

} )( jQuery );
