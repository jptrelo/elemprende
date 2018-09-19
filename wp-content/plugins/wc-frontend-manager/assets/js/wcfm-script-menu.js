var $wcfm_product_select_args = {
			allowClear:  true,
			placeholder: wcfm_dashboard_messages.search_product_select2,
			minimumInputLength: '3',
			language: {
				inputTooShort: function ( args ) {
					var remainingChars = args.minimum - args.input.length;
          var message = wcfm_dashboard_messages.select2_minimum_input + remainingChars;
          return message;
				},
				noResults: function () {
          return wcfm_dashboard_messages.select2_no_result;
        },
        searching: function () {
          return wcfm_dashboard_messages.select2_searching;
        },
        loadingMore: function () {
          return wcfm_dashboard_messages.select2_loading_more;
        },
			},
			escapeMarkup: function( m ) {
				return m;
			},
			ajax: {
				url:         wcfm_params.ajax_url,
				dataType:    'json',
				delay:       250,
				data:        function( params ) {
					return {
						term:     params.term,
						action:   'wcfm_json_search_products_and_variations',
						exclude:  jQuery( this ).data( 'exclude' ),
						include:  jQuery( this ).data( 'include' ),
						limit:    jQuery( this ).data( 'limit' )
					};
				},
				processResults: function( data ) {
					var terms = [];
					if ( data ) {
						jQuery.each( data, function( id, text ) {
							terms.push( { id: id, text: text } );
						});
					}
					return {
						results: terms
					};
				},
				cache: true
			}
		};
		
var $wcfm_vendor_select_args = {
			allowClear:  true,
			placeholder: wcfm_dashboard_messages.choose_vendor_select2,
			minimumInputLength: '3',
			language: {
				inputTooShort: function ( args ) {
					var remainingChars = args.minimum - args.input.length;
          var message = wcfm_dashboard_messages.select2_minimum_input + remainingChars;
          return message;
				},
				noResults: function () {
          return wcfm_dashboard_messages.select2_no_result;
        },
        searching: function () {
          return wcfm_dashboard_messages.select2_searching;
        },
        loadingMore: function () {
          return wcfm_dashboard_messages.select2_loading_more;
        },
			},
			escapeMarkup: function( m ) {
				return m;
			},
			ajax: {
				url:         wcfm_params.ajax_url,
				dataType:    'json',
				delay:       250,
				data:        function( params ) {
					return {
						term:     params.term,
						action:   'wcfm_json_search_vendors',
						exclude:  jQuery( this ).data( 'exclude' ),
						include:  jQuery( this ).data( 'include' ),
						limit:    jQuery( this ).data( 'limit' )
					};
				},
				processResults: function( data ) {
					var terms = [];
					if ( data ) {
						jQuery.each( data, function( id, text ) {
							terms.push( { id: id, text: text } );
						});
					}
					return {
						results: terms
					};
				},
				cache: true
			}
		};
		
$wcfm_datatable_button_args = [
																{
																	extend: 'print',
																	exportOptions: {
																		columns: ':visible'
																	}
																},
																{
																	extend: 'pdfHtml5',
																	exportOptions: {
																		columns: ':visible'
																	}
																},
																{
																	extend: 'excelHtml5',
																	exportOptions: {
																		columns: ':visible'
																	}
																}, 
																{
																	extend: 'csv',
																	exportOptions: {
																		columns: ':visible'
																	}
																}
															];


jQuery( document ).ready( function( $ ) {
	// Removing loader slowly
	/*if( wcfm_noloader == 'yes' ) {
		$('#wcfm_page_load').remove();
		$('.wcfm-collapse-content').css( 'opacity', '1' );
	} else {
		$opacity = 9;
		$content_opaticy = 1;
		function removingLoader() {
			if( $opacity == 0 ) {
				$('#wcfm_page_load').fadeOut("slow", function() {  $('#wcfm_page_load').remove(); $('.wcfm-collapse-content').css( 'opacity', '1' ); } );
			} else {
				setTimeout( function() { 
					$('#wcfm_page_load').css( 'opacity', '0.' + $opacity );
					$('.wcfm-collapse-content').css( 'opacity', '0.' + $content_opaticy );
					$opacity -= 1;
					$content_opaticy += 1;
					removingLoader();
				}, 250);
			}
		}
		removingLoader();
	}*/
	
	
	// Responsive
	// WCFM Responsive Menu Toggler
	if( $(window).width() <= 768 ) {
		if( $('#wcfm-main-contentainer .wcfm_responsive_menu_toggler').length > 0 ) {
			$('#wcfm_menu').removeClass('wcfm_menu_toggle');
			$('#wcfm-main-contentainer .wcfm_responsive_menu_toggler').click(function() {
				$('#wcfm_menu').addClass('wcfm_responsive_menu_toggle');
				if( $('#wcfm_menu').hasClass('wcfm_menu_toggle') ) {
					$('#wcfm_menu').removeClass('wcfm_menu_toggle');
				} else {
					$('#wcfm_menu').addClass('wcfm_menu_toggle');
				}
			}).click();
			
			/*$('#wcfm-main-contentainer .wcfm_responsive_menu_toggler').hover(function() {
				$('#wcfm_menu').addClass('wcfm_responsive_menu_toggle');
				if( $('#wcfm_menu').hasClass('wcfm_menu_toggle') ) {
					$('#wcfm_menu').removeClass('wcfm_menu_toggle');
				} else {
					$('#wcfm_menu').addClass('wcfm_menu_toggle');
				}
			});*/
		}
	}
	
	if( $(window).width() <= 768 ) {
		if( !$('#wcfm_menu').hasClass('wcfm_responsive_menu_toggle') ) {
			$('.wcfm_form_simple_submit_wrapper').css( 'bottom', $('#wcfm_menu').height() );
			$('.wcfm-message').css( 'bottom', ($('#wcfm_menu').height() + 60) );
		}
	}
	if ($(window).width() <= 640) {
		$('#wcfm-main-contentainer').css( 'max-width', $(window).width() );
		$('#wcfm-main-contentainer').parents().each(function() {
		  $(this).addClass('no-margin');
		});
		//$container_width = $(window).width() - 10;
		//$('.wcfm-container').css( 'width', $container_width );
		//$('.wcfm-content').css( 'width', $container_width );
		/*if ($(window).width() > 414 ) {
			$container_width = $(window).width() - 145;
			$('.wcfm-container').css( 'width', $container_width );
			$('.wcfm-content').css( 'width', $container_width );
		} else if ($(window).width() <= 414 ) {
			$container_width = $(window).width() - 102;
			$('.wcfm-container').css( 'width', $container_width );
			$('.wcfm-content').css( 'max-width', $container_width );
		}*/
	}
	
	// Select wrapper fix
	function unwrapSelect() {
		$('#wcfm-main-contentainer').find('select').each(function() {
			if ( $(this).parent().is( "span" ) ) {
			  $(this).unwrap( "span" );
			}
			if ( $(this).parent().hasClass( "select-option" ) || $(this).parent().hasClass( "buddyboss-select-inner" ) || $(this).parent().hasClass( "buddyboss-select" ) ) {
				$(this).parent().find('.ti-angle-down').remove();
				$(this).parent().find('span').remove();
			  $(this).unwrap( "div" );
			}
		});
		setTimeout( function() {  unwrapSelect(); }, 500 );
	}
	
	setTimeout( function() { 
		$('#wcfm-main-contentainer').find('select').each(function() {
			if ( $(this).parent().is( "span" ) ) {
			 $(this).css( 'padding', '5px' ).css( 'min-width', '15px' ).css( 'min-height', '35px' ).css( 'padding-top', '5px' ).css( 'padding-right', '5px' ); //.change();
			}
		});
		unwrapSelect();
	}, 500 );
	
	// Menu Tip
  jQuery('.menu_tip').each(function() {                                                  
		jQuery(this).qtip({
			content: jQuery(this).attr('data-tip'),
			position: {
				my: 'center right',
				at: 'center left',
				viewport: jQuery(window)
			},
			show: {
				event: 'mouseover',
				solo: true
			},
			hide: {
				inactive: 6000,
				fixed: true
			},
			style: {
				classes: 'qtip-dark qtip-shadow qtip-rounded qtip-wcfm-menu-css'
			}
		});
	});
	
	$( '#wcfm_menu .wcfm_menu_item' ).each( function() {
		$(this).mouseover( function() {
			var hideTime;
			$hover_block = $(this).find( '.wcfm_sub_menu_items' );
			clearTimeout(hideTime);
			$hover_block.show( 'slow', function() {
				hideTime = setTimeout(function() {
					$( '.wcfm_sub_menu_items' ).hide( 'slow' );
					$hover_block.removeClass( 'moz_class' );
				}, 30000);  
			} );
		} );
	} );
	
	// WCFM Menu Toggler
	$('#wcfm-main-contentainer .wcfm_menu_toggler').click(function() {
		$toggle_state = 'no';
	  if( $('#wcfm_menu').hasClass('wcfm_menu_toggle') ) {
	  	$('#wcfm_menu').removeClass('wcfm_menu_toggle');
	  } else {
	  	$toggle_state = 'yes';
	  	$('#wcfm_menu').addClass('wcfm_menu_toggle');
	  }
	  var data = {
			action       : 'wcfm_menu_toggler',
			toggle_state : $toggle_state
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				
			}
		});
	});
} );