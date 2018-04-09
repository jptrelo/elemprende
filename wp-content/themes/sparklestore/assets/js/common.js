jQuery(document).ready(function($) {    
    "use strict"; 
    
    /**
     * Wishlist count ajax update 
    */
    jQuery( document ).ready( function($){
        $('body').on( 'added_to_wishlist', function(){
            $('.top-wishlist .count').load( yith_wcwl_l10n.ajax_url + '.top-wishlist span', { action: 'yith_wcwl_update_single_product_list'} );
        });
    });

    /**
     * sparklestore pro banner slider
    */
    if( $('.sparklestore-slider').length > 0 ) {
        $('.sparklestore-slider').flexslider({
            animation: "fade",
            animationSpeed: 500,
            animationLoop: true,
            prevText: '',
            nextText: '',
            before: function(slider) {
                $('.sparklestore-caption').fadeOut().animate({top:'35%'},{queue:false, easing: 'swing', duration: 700});
                slider.slides.eq(slider.currentSlide).delay(500);
                slider.slides.eq(slider.animatingTo).delay(500);
            },
            after: function(slider) {
                $('.sparklestore-caption').fadeIn().animate({top:'50%'},{queue:false, easing: 'swing', duration: 700});
            },
            useCSS: true
        });
    }


    /**
     * Widget Sticky sidebar
    */
    $('.content-area').theiaStickySidebar({
        additionalMarginTop: 30
    });

    $('.widget-area').theiaStickySidebar({
        additionalMarginTop: 30
    });


    /**
     * Sparkle Store Category MobileMenu 
    */
    jQuery('.toggle-wrap').click(function(){
        jQuery('.main-menu-links').slideToggle();
    });

    /**
     * Sparkle Store Sub Menu
    */
    $('.main-menu-links .menu-item-has-children').append('<span class="sub-toggle"> <i class="fa fa-plus"></i> </span>');
    $('.main-menu-links .page_item_has_children').append('<span class="sub-toggle-children"> <i class="fa fa-plus"></i> </span>');

    $('.main-menu-links .sub-toggle').click(function() {
        $(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
        $(this).children('.fa-plus').first().toggleClass('fa-minus');
    });

    $('.main-menu-links .sub-toggle-children').click(function() {
        $(this).parent('.page_item_has_children').children('ul.children').first().slideToggle('1000');
        $(this).children('.fa-plus').first().toggleClass('fa-minus');
    });

    
    /**
     * Sparkle Store MobileMenu 
    */
    jQuery('.category-menu-title').click(function(){
        jQuery('.menu-category').slideToggle();
    });

    $('.menu-category .menu-item-has-children').append('<span class="sub-toggle-cat"> <i class="fa fa-plus"></i> </span>');
  
    $('.menu-category .sub-toggle-cat').click(function() {
        $(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
        $(this).children('.fa-plus').first().toggleClass('fa-minus');
    });

    /**
     * Sparkle Tabs Category Product
    */
    $('.sparkletablinks li').first('li').addClass('active');

    $('.sparkletabs .sparkletablinks a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
        var product_num = $(this).parents('ul').attr('data-id');
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
        $(this).parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.tabscontentwrap').hide();

            // Ajax Function
            $(this).parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.preloader').show();

            $.ajax({
                url : sparklestore_tabs_ajax_action.ajaxurl,                
                data:{
                        action        : 'sparklestore_tabs_ajax_action',
                        category_slug : currentAttrValue,
                        product_num   : product_num
                    },
                type:'post',
                    success: function(res){                                        
                        $('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.tabscontentwrap').html(res);
                        $('.tabscontentwrap').show();
                        sparklestore_ajax_cat_tabs();
                        $('.preloader').hide();
                    }
            });
    });
    
    /**
     * WooCommerce Tabs Category Products Functions Area
    */
    function sparklestore_ajax_cat_tabs(){
        $('.widget_sparklestore_cat_collection_tabs_widget_area').each(function(){
            
            var Id = $(this).attr('id');
            var NewId = Id; 

            NewId = $('#'+Id+" .tabsproduct").lightSlider({
                item:4,
                pager:false,
                loop:true,
                speed:600,
                controls:false,
                slideMargin:20,
                onSliderLoad: function() {
                    $('.tabsproduct').removeClass('cS-hidden');
                },
                responsive : [
                    {
                        breakpoint:800,
                        settings: {
                            item:2,
                            slideMove:1,
                            slideMargin:6,
                          }
                    },
                    {
                        breakpoint:480,
                        settings: {
                            item:1,
                            slideMove:1,
                          }
                    }
                ]
            });

            $('#'+Id+' .sparkle-lSPrev').click(function(){
                NewId.goToPrevSlide(); 
            });
            $('#'+Id+' .sparkle-lSNext').click(function(){
                NewId.goToNextSlide(); 
            });

        });
    }

    sparklestore_ajax_cat_tabs();

    /**
     * WooCommerce Category With Products 
    */
    $('.widget_sparklestore_cat_with_product_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .catwithproduct").lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            slideMargin:20,
            onSliderLoad: function() {
                $('.catwithproduct').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                      }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });

    /**
     * WooCommerce Products Area 
    */
    $('.widget_sparklestore_product_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .productarea").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            slideMargin:20,
            onSliderLoad: function() {
                $('.productarea').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                      }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });


    /**
     * WooCommerce Category Collection Area 
    */
    $('.widget_sparklestore_cat_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .categoryslider").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            slideMargin:20,
            onSliderLoad: function() {
                $('.categoryslider').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                      }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });

    /**
     * Posts Display Area 
    */
    $('.widget_sparklestore_blog_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .blogspostarea").lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            slideMargin:20,
            onSliderLoad: function() {
                $('.blogspostarea').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                      }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });
   
    /**
     * ScrollUp
    */
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 1000) {
            jQuery('.scrollup').fadeIn();
        } else {
            jQuery('.scrollup').fadeOut();
        }
    });

    jQuery('.scrollup').click(function() {
        jQuery("html, body").animate({
            scrollTop: 0
        }, 2000);
        return false;
    });    

});