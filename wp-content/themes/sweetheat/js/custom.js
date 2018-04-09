
jQuery(function($) {
	/* ---------------------------------------------------------------------- */
	/*  Full width for Page Builder rows
	/* ---------------------------------------------------------------------- */
	var width = $(window).width();
	var rowWidth = $('.panel-grid').width();
	var margin = (width - rowWidth)/2;
	var contactWidth = $('.row').width();
	var contactMargin = (width - contactWidth)/2;
	$('.panel-row-style-full').css('margin', -margin);
	$('.panel-row-style-full').css('padding', margin);
	$('.panel-row-style-full .widget_sweetheat_latest_news').css('margin', -margin-15);
	$('.panel-row-style-full .widget_sweetheat_portfolio').css('margin', -margin-15);
	$('.work-page').css('margin', -contactMargin);
	$('#google-map').css('margin', -contactMargin);
	$(window).resize(function(){
		var width = $(window).width();
		var rowWidth = $('.panel-grid').width();
		var margin = (width - rowWidth)/2;
		$('.panel-row-style-full').css('margin', -margin);
		$('.panel-row-style-full').css('padding', margin);
		$('.panel-row-style-full .widget_sweetheat_latest_news').css('margin', -margin-15);
		$('.panel-row-style-full .widget_sweetheat_portfolio').css('margin', -margin-15);		
		var contactWidth = $('.row').width();
		var contactMargin = (width - contactWidth)/2;
		$('.work-page').css('margin', -contactMargin);
		$('#google-map').css('margin', -contactMargin);		
	});
});	


	/* ---------------------------------------------------------------------- */
	/*  Mobile Navigation
	/* ---------------------------------------------------------------------- */
jQuery(function($) {
	$('.main-nav .menu').slicknav({
		label: '<i class="fa fa-bars"></i>',
		prependTo: '.mobile-nav',
		closedSymbol: '&#43;',
		openedSymbol: '&#45;'
	});


	$('.info-close').click(function(){
		$(this).parent().fadeOut();
		return false;
	});
});	


    /* ---------------------------------------------------------------------- */
    /*  Testimonials Slider
    /* ---------------------------------------------------------------------- */
jQuery(function($) {
    $('.testimonials-wrapper').flexslider({
		selector: ".testimonials > li",
		animation: "fade",
		controlNav: false,
		slideshow: true,
		smoothHeight: true,
    	nextText: "",
    	prevText: "",
	});
});	

	/* ---------------------------------------------------------------------- */
	/*  Testimonials View All
	/* ---------------------------------------------------------------------- */
jQuery(function($) {
	$('.view-more').on('click', function(){
		$('.testimonials-all').addClass('is-visible');

		return false;
	});

	$('.testimonials-all .close-btn').on('click', function(){
		$('.testimonials-all').removeClass('is-visible');

		return false;
	});

	$(document).keyup(function(event){
		if(event.which=='27'){
    		$('.testimonials-all').removeClass('is-visible');	
	    }
    });

    $('.testimonials-all-wrapper').children('ul').masonry({
  		itemSelector: '.testimonials-item'
	});
});	

	/* ---------------------------------------------------------------------- */
	/*  Fit Vids
	/* ---------------------------------------------------------------------- */
jQuery(function($) {
  
    $("body").fitVids();
  
});
	
	/* ---------------------------------------------------------------------- */
	/*  Back to top
	/* ---------------------------------------------------------------------- */
jQuery(function($) {
	$('a[href="#top"]').click(function() {

    	$('#top').animate({scrollTop: 0},600);

    	return false;
  	});
});

