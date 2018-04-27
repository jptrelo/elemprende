var resizeTimer;

(function($) {

	$(document).ready(function(){

		(function($){

			var defaults = {
				itemClass: 'mi-logo__item',
				leftBorderRemoveClass: 'mi-logo-remove-left-border',
				bottomBorderRemoveClass: 'mi-logo-remove-bottom-border'
			};

			var BorderFix = function( $wrapper, options ) {

				this.$wrapper = $wrapper;
				this.config = $.extend({}, defaults, options);

				this.init();

				return this;

			};

			BorderFix.prototype.init = function() {

				this.items = this.$wrapper.find('.'+this.config.itemClass+':visible');
				this.initLeft;
				this.gridItems = 1;
				this.stopExecution = false;
				this.leftBNIndex = 0;

				this.setGrid();
				this.setLayout();

			}

			BorderFix.prototype.setGrid = function() {


				this.items.each(function(index, item){

					var $current = $(item);
					var currentOffset = $current.offset().left;

					if ( !this.initLeft ) {
						this.initLeft = currentOffset;
						return;
					}

					if( currentOffset !== this.initLeft && !this.stopExecution ) {
						this.gridItems++;
					} else {
						this.stopExecution = true;
					}

				}.bind(this));
			}

			BorderFix.prototype.setLayout = function() {

				this.items.each(function(index, item){

					var $item = $(item);
					var leftRemove = true;
					var bottomRemove = true;
					var classes = "";

					if ( this.leftBNIndex !== false && index == this.leftBNIndex ) {
						leftRemove = false;
						this.leftBNIndex = index + this.gridItems;
						if( this.items.eq(this.leftBNIndex).length < 1 ) {
							this.leftBNIndex = false;
						}
					}

					var nextIndex = index + this.gridItems;

					if ( this.items.eq(nextIndex).length < 1 ) {
						bottomRemove = false;
					}

					classes += ( leftRemove ) ? " " + this.config.leftBorderRemoveClass : "";
					classes += ( bottomRemove ) ? " " + this.config.bottomBorderRemoveClass : "";

					$item.addClass(classes);

				}.bind(this));

			}

			BorderFix.prototype.update = function() {

				this.items = this.$wrapper.find('.'+this.config.itemClass+':visible');
				this.initLeft = undefined;
				this.stopExecution = false;
				this.leftBNIndex = 0;
				this.gridItems = 1;

				this.items.removeClass( this.config.leftBorderRemoveClass +' '+ this.config.bottomBorderRemoveClass );

				this.setGrid();
				this.setLayout();

			}

			$.fn.borderFix = function(options) {

				$(this).each(function(){
					var borderFixData =  new BorderFix( $(this), options );
					return $(this).data( 'BorderFix', borderFixData );
				});

			}

		})(jQuery);

		// FIXED HEADER TOP
		$(window).on('scroll', function () {
			if ($(window).scrollTop() > 324) {
				$('.mi-logo-header-top').addClass('mi-logo-header-top--fixed');
			} else {
				$('.mi-logo-header-top').removeClass('mi-logo-header-top--fixed');
			}
		});



		// BIND FILTER BUTTON ON CLICK
		$('.mi-logo-filter-trigger').on('click', 'a', function () {
			var filterValue = $(this).attr('data-filter');
			var $parent = $(this).closest('.mi-logo-filter-wrapper');
			$parent.find('.mi-logo-grid').isotope({ filter: filterValue });
			$(this).addClass('mi-logo-btn--is-checked').siblings('.mi-logo-btn').removeClass('mi-logo-btn--is-checked');

			// Fix Border;
			$bFixableWrapper = $parent.find('.mi-logo-row-0');
			if( $bFixableWrapper.length > 0 ) {

				var borderFixData = $bFixableWrapper.data('BorderFix');

				var handler = setTimeout(function(){
					borderFixData.update();
				}, 800);

			}

		});




		var global_owl_options = {
			themeClass: 'mi-owl-theme',
			baseClass: 'mi-owl-carousel',
			itemClass: 'mi-owl-item',
			navContainerClass: 'mi-owl-nav',
			controlsClass: 'mi-owl-controls',
			dotClass: 'mi-owl-dot',
			dotsClass: 'mi-owl-dots',
			autoHeightClass: 'mi-owl-height',
			navClass: ['mi-owl-prev', 'mi-owl-next'],
			navText: ['<i class="mi-logo-slider-icon-left-open"></i>', '<i class="mi-logo-slider-icon-right-open"></i>'],
		};

		var global_slick_options = {
			vertical: true,
			verticalSwiping: true,
			dotsClass: 'mi-logo-slick-pagination',
			prevArrow: '<i class="mi-logo-slider-icon-down-open mi-logo-slick-prev-left"></i>',
			nextArrow: '<i class="mi-logo-slider-icon-up-open mi-logo-slick-next-right"></i>'
		}

		var carousels = $(".mi-logo-slider-plugin .mi-owl-carousel");
		if( carousels.length > 0 ) {
			carousels.each(function(){
				var options = $(this).data('owl-options');
				options = (options) ? options : {};
				var config = $.extend({}, global_owl_options, options);

				var owlCar = $(this).miOwlCarousel( config );
				owlCar.find('.owl-stage-outer').wrapAll("<div class='mi-owl-carousel-wrapper' />");
			});
		}

		var miSlickCarousel = $(".mi-logo-slick-carousel");
		if (miSlickCarousel.length > 0) {
			miSlickCarousel.each(function() {
				var options = $(this).data('slick-options');
				options = (options) ? options : {};
				var config = $.extend({}, global_slick_options, options);
				var miSlickCarouselInit = $(this).slick(config);
			})
		}

		// LOGO ITEM HEIGHT
		$('.mi-logo__item').each(function(){
			var width = $(this).width();
			var height = width * 0.8888888888888888;

			if( $(this).hasClass('mi-logo__item--equal-width-height') ) {
				height = width*1;
			}

			$(this).height(height);
		});

		// LOGO BORDER FIX
		$('.mi-logo-row-0').borderFix({
			itemClass: 'mi-logo__item'
		});


		$(window).on('resize', function(e) {

			clearTimeout(resizeTimer);

			resizeTimer = setTimeout(function() {

				$('.mi-logo--margin').each(function(i) {
					var borderFixData = $(this).data('BorderFix');
					borderFixData.update();
				});

			}, 250);

		});
		
	});

}(jQuery));