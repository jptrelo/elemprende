;(function( $ ){
jQuery.fn.fixedNav = function( options ){

	var settings = $.extend({
            // These are the defaults.
            offset: 50,
        }, options );

	var menuFn = function(){

		var newClass = 'fixed-nav-active',
			menuHeight = jQuery( this.selector ).height() + settings.offset;

		var scrollPos = $(window).scrollTop();
		if( scrollPos >= menuHeight ){

			if( !$( 'body' ).hasClass( newClass ) ){
				$( 'body' ).addClass( newClass );
			}
		}else{

			if( $( 'body' ).hasClass( newClass ) ){

				$( 'body' ).removeClass( newClass );
			}
		}
	};

	jQuery( window ).scroll( function(){
		menuFn();
	});

	menuFn();

	return this;
};
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
+( function() {
	var container, button, menu, links, i, len;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	( function( container ) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		if ( 'ontouchstart' in window ) {
			touchStartFn = function( e ) {
				var menuItem = this.parentNode, i;

				if ( ! menuItem.classList.contains( 'focus' ) ) {
					e.preventDefault();
					for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
						if ( menuItem === menuItem.parentNode.children[i] ) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove( 'focus' );
					}
					menuItem.classList.add( 'focus' );
				} else {
					menuItem.classList.remove( 'focus' );
				}
			};

			for ( i = 0; i < parentLink.length; ++i ) {
				parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
			}
		}
	}( container ) );
} )();

jQuery.fn.scrollTo = function( offset ){

	jQuery( document ).on( 'click', this.selector, function( e ){
		e.preventDefault();
		var target = jQuery( this ).attr( 'href' );
		if( 'undefined' != typeof target ){
			if( !offset ){
				offset = 0;
			}
			var pos = jQuery( target ).offset().top - offset;
			jQuery("html, body").animate({ scrollTop: pos }, 800);
		}
	});

	return this;
};

function scrollToTop ( param ){

	this.markup   = null,
	this.selector = null;
	this.fixed    = true;
	this.visible  = false;

	this.init = function(){

		if( this.valid() ){

			if( typeof param != 'undefined' && typeof param.fixed != 'undefined' ){
				this.fixed = param.fixed;
			}

			this.selector = ( param && param.selector ) ? param.selector : '#go-top';

			this.getMarkup();
			var that = this;

			jQuery( 'body' ).append( this.markup );

			if( this.fixed ){

				jQuery( this.selector ).hide();

				var windowHeight = jQuery( window ).height();

				jQuery( window ).scroll(function(){

					var scrollPos     = jQuery( window ).scrollTop();

					if(  ( scrollPos > ( windowHeight - 100 ) ) ){

						if( false == that.visible ){
							jQuery( that.selector ).fadeIn();
							that.visible = true;
						}
						
					}else{

						if( true == that.visible ){
							jQuery( that.selector ).fadeOut();
							that.visible = false;
						}
					}
				});

				jQuery( this.selector ).scrollTo();
			}
		}
	}

	this.getMarkup = function(){

		var position = this.fixed ? 'fixed':'absolute';

		var wrapperStyle = 'style="position: '+position+'; z-index:999999; bottom: 20px; right: 20px;"';

		var buttonStyle  = 'style="cursor:pointer;display: inline-block;padding: 10px 20px;background: #f15151;color: #fff;border-radius: 2px;"';

		var markup = '<div ' + wrapperStyle + ' id="go-top"><span '+buttonStyle+'>Scroll To Top</span></div>';

		this.markup   = ( param && param.markup ) ? param.markup : markup;
	}

	this.valid = function(){

		if( param && param.markup && !param.selector ){
			alert( 'Please provide selector. eg. { markup: "<div id=\'scroll-top\'></div>", selector: "#scroll-top"}' );
			return false;
		}

		return true;
	}
};
/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
+(function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();

/**
* Setting up functionality for alternative menu
* @since Bizplan 1.0
*/
function wpMenuAccordion( selector ){

	var $ele = selector + ' .menu-item-has-children > a';
	$( $ele ).each( function(){
		var text = $( this ).text();
		text = text + '<span class="kfi kfi-arrow-carrot-down-alt2 triangle"></span>';
		$( this ).html( text );
	});

	$( document ).on( 'click', $ele + ' span.triangle', function( e ){
		e.preventDefault();
		e.stopPropagation();

		$parentLi = $( this ).parent().parent( 'li' );
		$childLi = $parentLi.find( 'li' );

		if( $parentLi.hasClass( 'open' ) ){
			/**
			* Closing all the ul inside and 
			* removing open class for all the li's
			*/
			$parentLi.removeClass( 'open' );
			$childLi.removeClass( 'open' );

			$( this ).parent( 'a' ).next().slideUp();
			$( this ).parent( 'a' ).next().find( 'ul' ).slideUp();
		}else{

			$parentLi.addClass( 'open' );
			$( this ).parent( 'a' ).next().slideDown();
		}
	});
};

/**
* Increase cart count when product is added by ajax 
* @uses Woocommerce
* @since Bizplan 0.1
*/
jQuery( document ).on( 'added_to_cart', function(){
	$ele = $( '.cart-icon .count' );
	var count = $ele.text();
	$ele.text( parseInt( count ) + 1 );
});

/**
* Show or Hide Search field on clicking search icon
* @since Bizplan 0.1
*/
$( document ).on( 'click', '.search-icon a', function( e ){
	e.preventDefault();
	$( '#search-form' ).slideToggle(100).find( '.search-field' ).focus();
});

/**
* Fire slider for homepage
* @link https://owlcarousel2.github.io/OwlCarousel2/docs/started-welcome.html
* @since Bizplan 0.1
*/
function homeSlider(){
	var item_count = parseInt($( '.block-slider .slide-item').length);
	$(".home-slider").owlCarousel({
		items: 1,
		autoHeight: false,
		nav: true,
		autoHeightClass: 'name',
		animateOut: 'fadeOut',
    	navContainer: '.block-slider .controls',
    	dotsContainer: '#kt-slide-pager',
    	autoplay : BIZPLAN.home_slider.autoplay,
    	autoplayTimeout : parseInt( BIZPLAN.home_slider.timeout ),
    	loop : ( item_count > 1 ) ? true : false,
    	rtl: ( BIZPLAN.is_rtl == '1' ) ? true : false ,
    	onInitialize: function(event) {
		    if (item_count <= 1) {
		        $( '.block-slider .controls' ).hide();
		    }
		}
	});
};

/**
* Fire Slider for Testimonials
* @link https://owlcarousel2.github.io/OwlCarousel2/docs/started-welcome.html
* @since Bizplan 0.1
*/
function testimonialSlider(){
	
	jQuery(".testimonial-carousel").owlCarousel({
		items: 2,
		animateOut: 'fadeOut',
		navContainer: '.block-testimonial .controls',
		dotsContainer: '#testimonial-pager',
		responsiveClass:true,
	    responsive:{
	        0:{
	            items:1,
	            nav:true
	        },
	        992:{
	            items:2,
	            nav:true
	        }
	    },
	    rtl: ( BIZPLAN.is_rtl == '1' ) ? true : false ,
		loop : false,
		dots: true,
	});
};

/**
* Animate contact form fields when they are focused
* @since Bizplan 0.1
*/
jQuery( '.kt-contact-form-area input, .kt-contact-form-area textarea' ).on( 'focus',function(){
	var target = jQuery( this ).attr( 'id' );
	jQuery('label[for="'+target+'"]').addClass( 'move' );
});

jQuery( '.kt-contact-form-area input, .kt-contact-form-area textarea' ).on( 'blur',function(){
	var target = jQuery( this ).attr( 'id' );
	jQuery('label[for="'+target+'"]').removeClass( 'move' );
});

jQuery( document ).ready( function(){

	var menuHeight = jQuery( '#masthead' ).outerHeight();
	if( jQuery( window ).width() <= 767 ){
		menuHeight = 0;
	}
	$( '.scroll-to' ).scrollTo( menuHeight );

	$( '#masthead' ).fixedNav();

	homeSlider();

	testimonialSlider();

	/**
	* Initializing scroll top js
	*/
	new scrollToTop({
		markup   : '<a href="#page" class="scroll-to '+ ( BIZPLAN.enable_scroll_top_in_mobile == 0 ? "hidden-xs" : "" )+'" id="go-top"><span class="kfi kfi-arrow-up"></span></a>',
		selector : '#go-top'
	}).init();

	wpMenuAccordion( '#offcanvas-menu' );
	
	$( document ).on( 'click', '.offcanvas-menu-toggler, .close-offcanvas-menu, .kt-offcanvas-overlay', function( e ){
		e.preventDefault();
		$( 'body' ).toggleClass( 'offcanvas-menu-open' );
	});
	jQuery( 'body' ).append( '<div class="kt-offcanvas-overlay"></div>' );

	/**
	* Make sure if the masonry wrapper exists
	*/
	if( jQuery( '.masonry-wrapper' ).length > 0 ){
		$grid = jQuery( '.masonry-wrapper' ).masonry({
			itemSelector: '.masonry-grid',
			percentPosition: true,
		});	
	}

	/**
	* Make support for Jetpack's infinite scroll on masonry layout
	*/
	infinite_count = 0;
    $( document.body ).on( 'post-load', function () {

		infinite_count = infinite_count + 1;
		var container = '#infinite-view-' + infinite_count;
		$( container ).hide();

		$( $( container + ' .masonry-grid' ) ).each( function(){
			$items = $( this );
		  	$grid.append( $items ).masonry( 'appended', $items );
		});

		setTimeout( function(){
			$grid.masonry('layout');
		},500);
    });

    /**
    * Modify default search placeholder
    */
    $( '#masthead #s' ).attr( 'placeholder', BIZPLAN.search_placeholder );
});

jQuery( window ).load( function(){
	if( 'undefined' !== typeof $grid ){
		$grid.masonry('reloadItems');
		$grid.masonry('layout');
	}
});
})( jQuery );