/* Nivo slider */
jQuery(document).ready(function() {
	if( jQuery( '#slider' ).length > 0 ){
        jQuery('.nivoSlider').nivoSlider({
            effect:'fade',
            animSpeed: 500,
            pauseTime: 3000,
            startSlide: 0,
			directionNav: true,
			controlNav: true,
			pauseOnHover:false,
		});
	}
});