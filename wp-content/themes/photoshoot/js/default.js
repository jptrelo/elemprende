// JavaScript Document
jQuery(document).ready(function(e) {
 	  jQuery("#owl-demo").owlCarousel({
        items : 3,
        lazyLoad : true,
        navigation : true,
		navigationText : ["",""],
		pagination : false,
		itemsCustom : false,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,3],
		itemsTablet: [768,2],
		itemsTabletSmall: true,
		itemsMobile : [479,1]
      });
	  jQuery("#latest-photos").owlCarousel({
        items : 3,
        lazyLoad : true,
        navigation : true,
		navigationText : ["",""],
		pagination : false,
		itemsCustom : false,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,3],
		itemsTablet: [768,2],
		itemsTabletSmall: true,
		itemsMobile : [479,1]
      });
      
});