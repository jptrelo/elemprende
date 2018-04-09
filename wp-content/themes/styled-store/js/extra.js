/**
    * File extra.js
    * contains js to extend functionality of themes like to initilize slider, and add custom script needed for theme
    *
*/
function styledstore_checkwidth(){
    var styledstore_width = 1280; 
    var windowWidth = jQuery(window).width();
    if(windowWidth > styledstore_width ){
        jQuery('.sameheight').matchHeight();
        var navigationDivHeight = jQuery('.sameheight').height();
        var liHeight = jQuery('.sameheight li').height();
        jQuery('.sameheight #main-menu').css({"padding-top" : (navigationDivHeight/2)-(liHeight/2) });
    }else {
        jQuery('.sameheight').matchHeight({remove: true});
    }
}
jQuery(document).ready(function($) {
    // product li height
    jQuery('.liHeight').matchHeight();

    //initialize smart js for large screens device
    $('.header-menu').smartmenus();

    // initilize slider in homepage
    $('.st_slider').bxSlider({
        mode: 'fade',
        pager: false,
        onSliderLoad: function() { 
            $(".st_slider").css("visibility", "visible");
        }
    });

    var productSlider = $('.st-gallery-main-images').bxSlider({
        speed:1000,
        pager:false,
        nextText:'',
        prevText:'',
        infiniteLoop:false,
        hideControlOnEnd:true,
        onSliderLoad: function() { 
            $(".st-gallery-main-images").css("visibility", "visible");
        },
        onSlideBefore:function($slideElement, oldIndex, newIndex){
            changeproductThumb(productThumbSlider,newIndex);
        }
    });

    if( jQuery( 'ol.flex-control-thumbs li').length >= 1 ) {
        var productThumbSlider = jQuery('.flex-control-thumbs').bxSlider({
            minSlides: 4,
            maxSlides: 4,
            slideWidth: 140,
            slideMargin: 10,
            moveSlides: 1,
            pager:false,
            speed:1000,
            infiniteLoop:false,
            hideControlOnEnd:true,
            nextText:'<span></span>',
            prevText:'<span></span>',
            onSlideBefore:function($slideElement, oldIndex, newIndex){ }
        });
    }

    linkProductSlider(productSlider,productThumbSlider);
    
    if($("#st-product-gallery-thumb li").length<5){
        $("#st-product-gallery-thumb .bx-next").hide();
    }

    // sincronizza sliders realizzazioni
    function linkProductSlider(bigS,thumbS){

        $("ul#st-product-gallery-thumb").on("click","a",function(event){
            event.preventDefault();
            var newIndex = $(this).parent().attr("data-slide-index");
            bigS.goToSlide(newIndex);
        });
    }

    //slider!=$thumbSlider. slider is the productSlider
    function changeproductThumb(slider,newIndex){
        var $thumbS = $("#st-product-gallery-thumb");
        $thumbS.find('.active').removeClass("active");
        $thumbS.find('li[data-slide-index="'+newIndex+'"]').addClass("active");

        if(slider.getSlideCount()-newIndex>=4)slider.goToSlide(newIndex);
        else slider.goToSlide(slider.getSlideCount()-4);
    }

    //featured slider on blog page
    $('.st-bxslider').bxSlider({
        slideWidth: '270',
        minSlides: 2,
        maxSlides: 3,
        slideMargin: 10,
        pager: false,
        onSliderLoad: function(){ 
            $(".st-bxslider").css("visibility", "visible");
        }
    });

    $('.st_wc_featured_product, .st_cat_slider, .st_latest_article, .st-woocommerce-related-product .related ul.products').bxSlider({
            slideWidth: 270,
            minSlides: 2,
            maxSlides: 4,
            moveSlides: 1,
            slideMargin: 20,
            pager: false,
            onSliderLoad: function() { 
                $(".st_wc_featured_product, .st_cat_slider, .st_latest_article, .st-woocommerce-related-product .related ul.products").css("visibility", "visible");
            }
        });


    /*hover effect on for cart link*/
    $( '.woocommerce-page ul.products li.product').hover( function() {
        $( '.add-to-cart-button', this).css({
                "display":"block",
                "opacity":"1",
                "z-index":"9999"
            });
    }, function() {
        $( '.add-to-cart-button').hide();
        });

    // decrease value in quantity input single product page
    $( document ).on( 'click', '.qty-minus', function(e){
        var val = parseInt( $( this ).next( 'input' ).val());
        if(val !== 1){
            $( this ).next( 'input' ).val( val-1 );
            $( 'input[name="update_cart"]' ).prop( 'disabled', false );
        }
    });

    //increase the quantity value on cart page
    $( document ).on('click', '.qty-plus', function(e){
        var val = parseInt($(this).prev('input').val());
        $(this).prev('input').val( val+1 );
        $( 'input[name="update_cart"] ').prop( 'disabled', false );
    });

    // hide all the div at first
    $('.st-product-description > div').hide();
    // if a icon is clicked
    $('.st-product-description > i').on('click', function(){
        // toggle the div right after it
        $(this).next('div').slideToggle();
        // hide another open div
        $('.st-product-description > div').not( $(this).next('div') ).hide();
    });


    //Scroll To Top
    var window_height = $(window).height();
    var window_height = (window_height) + (50);
    $(window).scroll(function() {
        var scroll_top = $(window).scrollTop();
        if (scroll_top > window_height) {
            $('.styledstore_move_to_top').show();
        }
        else {
            $('.styledstore_move_to_top').hide();   
        }
    });

    $('.styledstore_move_to_top').click(function(){
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
    }); 

    jQuery(function() {
        var $mainMenuState = jQuery( '#main-menu-state' );
        if ($mainMenuState.length) {
            // animate mobile menu
            $mainMenuState.change( function(e) {
                var $menu = jQuery('.header-menu');
                if (this.checked) {
                    $menu.hide().slideDown(250, function() { $menu.css('display', ''); });
                } else {
                    $menu.show().slideUp(250, function() { $menu.css('display', ''); });
                }
            });
            // hide mobile menu beforeunload
            jQuery(window).bind('beforeunload unload', function() {
                if ( $mainMenuState[0].checked ) {
                    $mainMenuState[0].click();
                 }
            });
        }

        // submenu collaspible for mobile menu
        $('.header-menu').bind('click.smapi', function(e, item) {
            var obj = $(this).data('smartmenus');
            if (obj.isCollapsible()) {
                var $item = $(item),
                    $sub = $item.dataSM('sub');
                if ($sub && !$sub.is(':visible')) {
                    obj.itemActivate($item, true);
                    return false;
                }
            }
        });
    });

    /**
     *variale product support
     * @since version 1.1.4
    */
    $( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
        $( '.st-product-image-gallery ul.st-gallery-main-images' ).first().css( "transform", "translate3d(0px, 0px, 0px)" );
    } );

    $( window ).resize(function() {
        styledstore_checkwidth();
    });
    styledstore_checkwidth();

    jQuery('.product-list-height').matchHeight();
    /*
    *
    *js for flipping first gallery image with product thumbnail
    * since version 1.5.4
    */
    jQuery( 'ul.products ' ).find( 'li.st-product-flipper-container .styledstore-woocomerce-product-thumb:first-child' ).hover( function() {
        jQuery( this ).children( '.wp-post-image' ).removeClass( 'fadeInDown' ).addClass( 'animated fadeOutUp' );
        jQuery( this ).children( '.styledstore-flipper-image' ).removeClass( 'fadeOutUp' ).addClass( 'animated fadeInDown' );
    }, function() {
        jQuery( this ).children( '.wp-post-image' ).removeClass( 'fadeOutUp' ).addClass( 'fadeInDown' );
        jQuery( this ).children( '.styledstore-flipper-image' ).removeClass( 'fadeInDown' ).addClass( 'fadeOutUp' );
    });

});//end of ready function