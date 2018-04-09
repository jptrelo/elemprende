// JavaScript Document

jQuery(document).ready(function($) {
    
	jQuery(window).scroll(function(){
	var e=jQuery(window).width();
	if(jQuery(this).scrollTop()>200)
	{	
		jQuery('.container-sony .container .margin-top-bottom-2').css({'margin':'10px 0px'});
	}
	if(jQuery(this).scrollTop()<200)
	{
		jQuery('.container-sony .container .margin-top-bottom-2').css({'margin':'20px 0px'});
	}
	});
	
});