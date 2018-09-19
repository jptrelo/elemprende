jQuery(document).ready( function($) {
	var wcfm_marketplace_address_wrapper = $( '.store_address_wrap' );
	var wcfm_marketplace_address_select = {
			init: function () {
				wcfm_marketplace_address_wrapper.on( 'change', 'select#country', this.state_select );
				jQuery('select#country').change();
			},
			state_select: function () {
					var states_json = wc_country_select_params.countries.replace( /&quot;/g, '"' ),
							states = $.parseJSON( states_json ),
							$statebox = $( '#state' ),
							value = $statebox.val(),
							country = $( this ).val(),
							$state_required = $statebox.data('required');

					if ( states[ country ] ) {

							if ( $.isEmptyObject( states[ country ] ) ) {

								if ( $statebox.is( 'select' ) ) {
									if( typeof $state_required != 'undefined') {
										$( 'select#state' ).replaceWith( '<input type="text" class="wcfm-text wcfm_ele" name="address[state]" id="state" data-required="1" data-required_message="State/County: This field is required." />' );
									} else {
										$( 'select#state' ).replaceWith( '<input type="text" class="wcfm-text wcfm_ele" name="address[state]" id="state" />' );
									}
								}

								if( value ) {
									$( '#state' ).val( value );
								} else {
									$( '#state' ).val( 'N/A' );
								}

							} else {
									input_selected_state = '';

									var options = '',
											state = states[ country ];

									for ( var index in state ) {
											if ( state.hasOwnProperty( index ) ) {
													if ( selected_state ) {
															if ( selected_state == index ) {
																	var selected_value = 'selected="selected"';
															} else {
																	var selected_value = '';
															}
													}
													options = options + '<option value="' + index + '"' + selected_value + '>' + state[ index ] + '</option>';
											}
									}

									if ( $statebox.is( 'select' ) ) {
											$( 'select#state' ).html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option>' + options );
									}
									if ( $statebox.is( 'input' ) ) {
										if( typeof $state_required != 'undefined') {
											$( 'input#state' ).replaceWith( '<select class="wcfm-select wcfm_ele" name="address[state]" id="state" data-required="1" data-required_message="State/County: This field is required."></select>' );
										} else {
											$( 'input#state' ).replaceWith( '<select class="wcfm-select wcfm_ele" name="address[state]" id="state"></select>' );
										}
										$( 'select#state' ).html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option>' + options );
									}
									//$( '#wcmarketplace_address_state' ).removeClass( 'wcmarketplace-hide' );
									//$( 'div#wcmarketplace-states-box' ).slideDown();

							}
					} else {
						if ( $statebox.is( 'select' ) ) {
							if( typeof $state_required != 'undefined') {
								$( 'select#state' ).replaceWith( '<input type="text" class="wcfm-text wcfm_ele" name="address[state]" id="state" data-required="1" data-required_message="State/County: This field is required." />' );
							} else {
								$( 'select#state' ).replaceWith( '<input type="text" class="wcfm-text wcfm_ele" name="address[state]" id="state" />' );
							}
						}
						$( '#state' ).val(input_selected_state);

						if ( $( '#state' ).val() == 'N/A' ){
							$( '#state' ).val('');
						}
						//$( '#wcmarketplace_address_state' ).removeClass( 'wcmarketplace-hide' );
						//$( 'div#wcmarketplace-states-box' ).slideDown();
					}
			}
	}
	
	wcfm_marketplace_address_select.init();
		
		
	$store_lat = jQuery("#store_lat").val();
	$store_lng = jQuery("#store_lng").val();
  function initialize() {
		var latlng = new google.maps.LatLng( $store_lat, $store_lng );
		var map = new google.maps.Map(document.getElementById("wcfm-marketplace-map"), {
				center: latlng,
				blur : true,
				zoom: 15
		});
		var marker = new google.maps.Marker({
				map: map,
				position: latlng,
				draggable: true,
				anchorPoint: new google.maps.Point(0, -29)
		});
	
		var find_address_input = document.getElementById("find_address");
		//map.controls[google.maps.ControlPosition.TOP_LEFT].push(find_address_input);
		var geocoder = new google.maps.Geocoder();
		var autocomplete = new google.maps.places.Autocomplete(find_address_input);
		autocomplete.bindTo("bounds", map);
		var infowindow = new google.maps.InfoWindow();   
	
		autocomplete.addListener("place_changed", function() {
				infowindow.close();
				marker.setVisible(false);
				var place = autocomplete.getPlace();
				if (!place.geometry) {
						window.alert("Autocomplete returned place contains no geometry");
						return;
				}
	
				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
						map.fitBounds(place.geometry.viewport);
				} else {
						map.setCenter(place.geometry.location);
						map.setZoom(17);
				}
	
				marker.setPosition(place.geometry.location);
				marker.setVisible(true);
	
				bindDataToForm(place.formatted_address,place.geometry.location.lat(),place.geometry.location.lng());
				infowindow.setContent(place.formatted_address);
				infowindow.open(map, marker);
				showTooltip(infowindow,marker,place.formatted_address);
	
		});
		google.maps.event.addListener(marker, "dragend", function() {
				geocoder.geocode({"latLng": marker.getPosition()}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
								if (results[0]) {        
										bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
										infowindow.setContent(results[0].formatted_address);
										infowindow.open(map, marker);
										showTooltip(infowindow,marker,results[0].formatted_address);
										document.getElementById("searchStoreAddress");
								}
						}
				});
		});
	}
	
	function bindDataToForm(address,lat,lng){
		document.getElementById("store_location").value = address;
		document.getElementById("store_lat").value = lat;
		document.getElementById("store_lng").value = lng;
	}
	function showTooltip(infowindow,marker,address){
	 google.maps.event.addListener(marker, "click", function() { 
				infowindow.setContent(address);
				infowindow.open(map, marker);
		});
	}
	
	$is_initialize = false;
	$('#wcfm_settings_dashboard_head').click(function() {
		if( !$is_initialize && jQuery("#store_lat").length > 0 ) {
			setTimeout( function() {
				initialize();
				//google.maps.event.addDomListener(window, "load", initialize);
				$is_initialize = true;
			}, 1000 );
		}
	});
	
	// On Store Setup
	$page = GetURLParameterSetup( 'page' );
	if( $page = 'wcfmmp-store-setup' ) {
		if( !$is_initialize && jQuery("#store_lat").length > 0 ) {
			setTimeout( function() {
				initialize();
				//google.maps.event.addDomListener(window, "load", initialize);
				$is_initialize = true;
			}, 1000 );
		}
	}
	
	// WCfM Marketplace paymode settings options
	if( $('#payment_mode').length > 0 ) {
		$('#payment_mode').change(function() {
			$payment_mode = $(this).val();
			$('.paymode_field').hide();
			$('.paymode_' + $payment_mode).show();
			resetCollapsHeight($('#payment_mode').parent());
		}).change();
	}
	
	// WCfM Marketplace banner settings options
	if( $('#banner_type').length > 0 ) {
		$('#banner_type').change(function() {
			$banner_type = $(this).val();
			$('.banner_type_field').hide();
			$('.banner_type_' + $banner_type).show();
			$('input[type="text"].banner_type_upload').hide();
		}).change();
	}
	
	function GetURLParameterSetup(sParam) {
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) {
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) {
				return sParameterName[1];
			}
		}
	}
	
	// Profile Complete Progress Bar
	var progressbar = $( "#wcfmmp_profile_complete_progressbar" ),
		progressLabel = $( ".wcfmmp_profile_complete_progress_label" );
	if( progressbar.length > 0 ) {
		progressbar.progressbar({
			value: false,
			change: function() {
				progressLabel.text( progressbar.progressbar( "value" ) + "% " + $complete );
			},
			complete: function() {
				progressLabel.text( $profile_complete_percent + " " + $complete );
			}
		});
	
		function progress() {
			var val = progressbar.progressbar( "value" ) || 0;
	
			progressbar.progressbar( "value", val + 2 );
	
			if ( val < ( $profile_complete_percent - 1 ) ) {
				setTimeout( progress, 80 );
			}
		}
	
		setTimeout( progress, 200 );
	}
	
});

// Shipping by Country

(function($){
  $('#wcfmmp_shipping_type').change(function() {
    $('.shipping_type').addClass('shipping_hide');
    //console.log($( '.' + $(this).val() ));
    if($(this).val()) { 
      $( '.' + $(this).val() ).removeClass('shipping_hide');
    }
    resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_country'));
  }).change();
  $('#wcfmmp_shipping_enable').change(function() {
    console.log($(this).is(':checked'));
	  if( $(this).is(':checked') ) {
	  	$('.hide_if_shipping_disabled').show();
	  } else {
	  	$('.hide_if_shipping_disabled').hide();
	  }
	  resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_country'));
    
	}).change();
  
  
  // Shipping rates country change
	function setStateBoxforCountry( countryBox ) {
		var states_json = wc_country_select_params.countries.replace( /&quot;/g, '"' ),
				states = $.parseJSON( states_json ),
				country = countryBox.val();

		if ( states[ country ] ) {
			if ( $.isEmptyObject( states[ country ] ) ) {
				countryBox.parent().find('.wcfmmp_state_to_select').each(function() {
					$statebox = $(this);
					$statebox_id = $statebox.attr('id');
					$statebox_name = $statebox.attr('name');
					$statebox_val = $statebox.val();
					if( $statebox_val === null ) $statebox_val = '';
					$statebox_dataname = $statebox.data('name');
					
					if ( $statebox.is( 'select' ) ) {
						$statebox.replaceWith( '<input type="text" name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" value="'+$statebox_val+'" class="wcfm-text wcfmmp_state_to_select multi_input_block_element" />' );
					}
				});
			} else {
				input_selected_state = '';
				var options = '',
						state = states[ country ];

				countryBox.parent().find('.wcfmmp_state_to_select').each(function() {
					$statebox = $(this);
					$statebox_id = $statebox.attr('id');
					$statebox_name = $statebox.attr('name');
					$statebox_val = $statebox.val();
					if( $statebox_val === null ) $statebox_val = '';
					$statebox_dataname = $statebox.data('name');
					
					for ( var index in state ) {
						if ( state.hasOwnProperty( index ) ) {
							if ( $statebox_val ) {
								if ( $statebox_val == index ) {
									var selected_value = 'selected="selected"';
								} else {
									var selected_value = '';
								}
							}
							options = options + '<option value="' + index + '"' + selected_value + '>' + state[ index ] + '</option>';
						}
					}
					
					if ( $statebox.is( 'select' ) ) {
						$statebox.html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option><optgroup label="-------------------------------------"><option value="everywhere">'+wcfm_dashboard_messages.everywhere+'</option></optgroup><optgroup label="-------------------------------------">' + options + '</optgroup>' );
					}
					if ( $statebox.is( 'input' ) ) {
						$statebox.replaceWith( '<select name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" class="wcfm-select wcfmmp_state_to_select multi_input_block_element"></select>' );
						$statebox = $('#'+$statebox_id);
						$statebox.html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option><optgroup label="-------------------------------------"><option value="everywhere">'+wcfm_dashboard_messages.everywhere+'</option></optgroup><optgroup label="-------------------------------------">' + options + '</optgroup>' );
					}
					$statebox.val( $statebox_val );
				});
			}
		} else {
			countryBox.parent().find('.wcfmmp_state_to_select').each(function() {
				$statebox = $(this);
				$statebox_id = $statebox.attr('id');
				$statebox_name = $statebox.attr('name');
				$statebox_val = $statebox.val();
				if( $statebox_val === null ) $statebox_val = '';
				$statebox_dataname = $statebox.data('name');
				
				if ( $statebox.is( 'select' ) ) {
					$statebox.replaceWith( '<input type="text" name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" value="'+$statebox_val+'" class="wcfm-text wcfmmp_state_to_select multi_input_block_element" />' );
				}
			});
		}
		
		if( country == 'everywhere' ) {
			countryBox.parent().find('.wcfmmp_shipping_state_rates_label').addClass('wcfm_custom_hide');
			countryBox.parent().find('.multi_input_holder').addClass('wcfm_custom_hide');
		} else {
			countryBox.parent().find('.wcfmmp_shipping_state_rates_label').removeClass('wcfm_custom_hide');
			countryBox.parent().find('.multi_input_holder').removeClass('wcfm_custom_hide');
		}
	}
	
	$('.wcfmmp_country_to_select').each(function() {
	  $(this).change(function() {
	    setStateBoxforCountry( $(this) );
	  }).change();
	});
	
	setTimeout(function() {
    
		$('#wcfmmp_shipping_rates').children('.multi_input_block').children('.add_multi_input_block').click(function() {
      
			$('#wcfmmp_shipping_rates').children('.multi_input_block:last').find('.wcfmmp_country_to_select').select2();
			$('#wcfmmp_shipping_rates').children('.multi_input_block:last').find('.wcfmmp_country_to_select').change(function() {
				setStateBoxforCountry( $(this) );
			}).change();
		});
    
	}, 2000 );
  
})(jQuery);


//Shipping by Zone

(function($) {
  var wcfmmp_shipping_zone_object = {
    init: function() {
      this.bindEvents();
    },
    bindEvents: function() {
      var that = this; 
      $('.vendor_edit_zone').on('click', that.vendorEditZone);
      $('body').on('zone_settings_loaded', '#wcfm_settings_form' , that.zoneAfterLoaded);
      $('body').on('select2:select', '.select_zone_country_select', that.zoneCountrySelected );
      $('body').on('select2:unselect', '.select_zone_country_select', that.zoneCountryUnselected );
      $('body').on('select2:select', '.select_zone_states_select', that.zoneStateChange );
      $('body').on('select2:unselect', '.select_zone_states_select', that.zoneStateChange );
      $('body').on('change', '#limit_zone_location', that.limitZoneLocation);
      $('body').on('click', '.return-to-zone-list a', that.returnToZoneList);
      $('body').on('click', '.wcfmmp-zone-method-add-btn', that.loadAddMethodPopup );
      $('body').on('click', '#wcfmmp_shipping_method_add_button', that.addShippingMethod );
      $('body').on('change', '.method_status', that.toggleShippingMethod );
      $('body').on('click', '.delete_shipping_method', that.deleteShippingMethod );
      $('body').on('click', '.edit_shipping_method', that.loadEditMethodPopup );
      $('body').on('click', '#wcfmmp_shipping_method_edit_button', that.editShippingMethod );
           
    },
    zoneStateChange: function() {
      resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_zone'));
    },
    
    vendorEditZone: function(e, zid = 0) {
      //e.preventDefault();
      var zoneID;
      //console.log(e);
      if( typeof e === "undefined" ) {
        zoneID = zid;
      } else {
        zoneID = $(this).attr('data-zone-id');
      }
      var data = {
                action: 'wcfmmp-get-shipping-zone',
                zoneID: zoneID,
            };
      $('.wcfm-container').block({
        message: null,
        overlayCSS: {
          background: '#fff',
          opacity: 0.6
        }
      });
      
      $.post(wcfm_params.ajax_url, data, function (resp) {
        if( resp && resp.success ) {
          $('#vendor_edit_zone').html(resp.data.html).show();
          $('.wcfmmp-table.shipping-zone-table, #wcfmmp_settings_form_shipping_expander').hide();
          $('.wcfm-container').unblock();
        }
        $('#wcfm_settings_form').trigger('zone_settings_loaded');
        $('.select_zone_states_select').val($.parseJSON(resp.data.states)).trigger('change');
        resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_zone'));
      });
      
    },
    
    zoneAfterLoaded: function(e) {
      $(".select_zone_country_select, .select_zone_states_select").select2({
        placeholder: wcfm_dashboard_messages.choose_select2 + ' ...',
        containerCssClass: 'hide_if_zone_not_limited'
      });
      wcfmmp_shipping_zone_object.zoneCountrySelected();
      wcfmmp_shipping_zone_object.limitZoneLocation();
    },
    
    zoneCountrySelected: function (e) {
      var seletedCountries = $('.select_zone_country_select').val();
      wcfmmp_shipping_zone_object.generateState(seletedCountries);  
      resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_zone'));
    },
    
    zoneCountryUnselected: function(e) {
      var unseletedCountry = e.params.data;
      wcfmmp_shipping_zone_object.removeStates(unseletedCountry);
      resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_zone'));
    },
    
    generateState: function(seletedCountries) {
      //$("#select_zone_states option").remove().trigger('change');
      var states_json = wc_country_select_params.countries.replace( /&quot;/g, '"' ),
            states = $.parseJSON( states_json );
      $.each(seletedCountries, function(index, value){
        statesOfSelectedCountry = states[value];
        $.each(statesOfSelectedCountry, function(i, v){
          if ($('#select_zone_states').find("option[value='" + value + ":" + i + "']").length) {
              //$('#select_zone_states').val(i).trigger('change');
          } else { 
            // Create a DOM Option and pre-select by default
            var newOption = new Option(v, value + ":" + i, false, false);
            // Append it to the select
            $('#select_zone_states').append(newOption).trigger('change');
          }
        });

      });
    },
    
    removeStates: function(unseletedCountry) {
      var states_json = wc_country_select_params.countries.replace( /&quot;/g, '"' ),
            states = $.parseJSON( states_json );
      statesOfSelectedCountry = states[unseletedCountry.id];
      console.log(statesOfSelectedCountry);
      $.each(statesOfSelectedCountry, function(i, v){
        $("#select_zone_states option[value='" + unseletedCountry.id + ":" + i + "']").remove().trigger('change');
      });
    },
    
    limitZoneLocation: function() {
      //console.log($('#limit_zone_location').is(':checked'));
      if( $('#limit_zone_location').is(':checked') ) {
        $('.hide_if_zone_not_limited').show();
      } else {
        $('.hide_if_zone_not_limited').hide();
      }
      resetCollapsHeight($('#wcfmmp_settings_form_shipping_by_zone'));
    },
    
    returnToZoneList: function(e) {
      e.preventDefault();
      $('#vendor_edit_zone').html('').hide();
      $('.wcfmmp-table.shipping-zone-table, #wcfmmp_settings_form_shipping_expander').show();
    },
    
    loadAddMethodPopup: function(e) {
      e.preventDefault();      
      var $product_popup_width = '60%';
      
      if( $(window).width() <= 960 ) {
        $product_popup_width = '75%';
      }
      
      jQuery.colorbox( { 
        inline:true, 
        href: "#wcfmmp_shipping_method_add_container", 
        width: $product_popup_width,
        onComplete:function() {
          $('#wcfmmp_shipping_method_add_container').find('.wcfmmp-collapse-content').attr('id', 'wcfmmp_shipping_method_add-main-contentainer');
        }
      });
    },
    
    addShippingMethod: function(e) {
      e.preventDefault();
      var zoneId = $('#zone_id').val(),
          shippingMethod = $('#shipping_method option:selected').val();
      if(zoneId == '') { 
        alert(wcfm_dashboard_messages.shiping_zone_not_found);
      } else if(shippingMethod == '') {
        alert(wcfm_dashboard_messages.shiping_method_not_selected);
      } else { 
        
        var data = {
                  action: 'wcfmmp-add-shipping-method',
                  zoneID: zoneId,
                  method: shippingMethod
              };
              
        $('#wcfmmp_shipping_method_add_button').block({
          message: null,
          overlayCSS: {
            background: '#fff',
            opacity: 0.6
          }
        });
        $('#wcfm_settings_save_button').click();
        $.post(wcfm_params.ajax_url, data, function (resp) {
          
          if (resp.success) {
            $.colorbox.close();
            $('#wcfmmp_shipping_method_add_button').unblock();
            wcfmmp_shipping_zone_object.vendorEditZone(undefined, zoneId);
          } else {
            $('#wcfmmp_shipping_method_add_button').unblock();
            alert(resp.data);
          }
        });      
      }      
    },
    
    toggleShippingMethod: function(){
      var checked = $(this).is(':checked'),
          value = $(this).val(),
          zoneId = $('#zone_id').val();
      console.log(checked,value);
      var data = {
            action: 'wcfmmp-toggle-shipping-method',
            zoneID: zoneId,
            instance_id: value,
            checked: checked,
          };
      
      if( zoneId == '' ) { 
        alert( wcfm_dashboard_messages.shiping_zone_not_found );
      } else if( value == '' ) {
        alert( wcfm_dashboard_messages.shiping_method_not_found );
      } else { 
        $('.wcfm-container').block({
          message: null,
          overlayCSS: {
            background: '#fff',
            opacity: 0.6
          }
        });
        $.post( wcfm_params.ajax_url, data, function (resp) {
          if (resp.success) {
            $('.wcfm-container').unblock();
          } else {
            $('.wcfm-container').unblock();
            alert(resp.data);
          }
        });
      }
      
    },
    
    deleteShippingMethod: function(e) {
      e.preventDefault();
      if (confirm(wcfm_dashboard_messages.shipping_method_del_confirm)) {
        var instance_id = $(this).parents('.edit_del_actions').attr('data-instance_id'),
            zoneId = $('#zone_id').val();
        var data = data = {
                  action: 'wcfmmp-delete-shipping-method',
                  zoneID: zoneId,
                  instance_id: instance_id
              };
        $('.wcfm-container').block({
          message: null,
          overlayCSS: {
            background: '#fff',
            opacity: 0.6
          }
        });
        if( zoneId == '' ) { 
          alert( wcfm_dashboard_messages.shiping_zone_not_found );
        } else if( instance_id == '' ) {
          alert( wcfm_dashboard_messages.shiping_method_not_found );
        } else { 
          $('#wcfm_settings_save_button').click();
          $.post( wcfm_params.ajax_url, data, function (resp) {
            if (resp.success) {
              //$('.wcfm-container').unblock();

              wcfmmp_shipping_zone_object.vendorEditZone(undefined, zoneId);
            } else {
              $('.wcfm-container').unblock();
              alert(resp.data);
            }
          });
        }
      }
    },
    
    loadEditMethodPopup: function(e) {
      e.preventDefault();
      var $product_popup_width = '60%',
          $parents = $(this).parents('.edit_del_actions');
      var instanceId = $parents.attr('data-instance_id'),
          zoneId = $('#zone_id').val(),
          methodId = $parents.attr('data-method_id'),
          methodSettings = $.parseJSON($parents.attr('data-method-settings'));
          
      //console.log(instanceId,zoneId,methodId,methodSettings);
      
      if( $(window).width() <= 960 ) {
        $product_popup_width = '75%';
      }
      $('#wcfmmp_shipping_method_edit_container #method_id_selected').val(methodId);
      $('#wcfmmp_shipping_method_edit_container #instance_id_selected').val(instanceId);
      
      jQuery.colorbox( { 
        inline:true, 
        href: "#wcfmmp_shipping_method_edit_container", 
        width: $product_popup_width,
        onLoad:function() {
          //console.log(methodSettings.settings, methodSettings.settings.title);
          $('.shipping_form').hide();
          $('#' + methodId ).show();
          $.colorbox.resize();
          if( methodId == 'free_shipping' ) {
            $('#free_shipping #method_title_fs').val(methodSettings.settings.title);
            methodSettings.settings.hasOwnProperty('min_amount') 
            ? $('#free_shipping #minimum_order_amount_fs').val(methodSettings.settings.min_amount) 
            : $('#free_shipping #minimum_order_amount_fs').val('0'); 
            $('#free_shipping #method_description_fs').val(methodSettings.settings.description);
          }
          if( methodId == 'local_pickup' ) {
            $('#local_pickup #method_title_lp').val(methodSettings.settings.title);
            $('#local_pickup #method_cost_lp').val(methodSettings.settings.cost);
            $('#local_pickup #method_tax_status_lp option[value='+methodSettings.settings.tax_status+']').attr('selected','selected');
            $('#local_pickup #method_description_lp').val(methodSettings.settings.description);
          }
          if( methodId == 'flat_rate' ) {
            $('#flat_rate #method_title_fr').val(methodSettings.settings.title);
            $('#flat_rate #method_cost_fr').val(methodSettings.settings.cost);
            $('#flat_rate #method_tax_status_fr option[value='+methodSettings.settings.tax_status+']').attr('selected','selected');
            $('#flat_rate #method_description_fr').val(methodSettings.settings.description);
          }
        }
      });
    },
    
    editShippingMethod: function() {
      var methodID = $('#wcfmmp_shipping_method_edit_container #method_id_selected').val(),
          instanceId = $('#wcfmmp_shipping_method_edit_container #instance_id_selected').val(),
          zoneId = $('#zone_id').val();
      var data = {
        action: 'wcfmmp-update-shipping-method',
        zoneID: zoneId,
        args: {
          instance_id: instanceId,
          zone_id: zoneId,
          method_id: methodID,
          settings: {}
        }
      };
      if( methodID == 'free_shipping' ) {
        data.args.settings.title = $('#free_shipping #method_title_fs').val();
        data.args.settings.description = $('#free_shipping #method_description_fs').val();
        data.args.settings.cost = 0;
        data.args.settings.tax_status = 'none';
        data.args.settings.min_amount = $('#free_shipping #minimum_order_amount_fs').val();
      }
      if( methodID == 'local_pickup' ) {
        data.args.settings.title = $('#local_pickup #method_title_lp').val();
        data.args.settings.description = $('#local_pickup #method_description_lp').val();
        data.args.settings.cost = $('#local_pickup #method_cost_lp').val();
        data.args.settings.tax_status = $('#local_pickup #method_tax_status_lp option:selected').val();
        
      }
      if( methodID == 'flat_rate' ) {
        data.args.settings.title = $('#flat_rate #method_title_fr').val();
        data.args.settings.description = $('#flat_rate #method_description_fr').val();
        data.args.settings.cost = $('#flat_rate #method_cost_fr').val();
        data.args.settings.tax_status = $('#flat_rate #method_tax_status_fr option:selected').val();
      }
      $('#wcfmmp_shipping_method_edit_button').block({
        message: null,
        overlayCSS: {
          background: '#fff',
          opacity: 0.6
        }
      });
      $('#wcfm_settings_save_button').click();
      $.post( wcfm_params.ajax_url, data, function (resp) {
        //console.log(resp);
        if (resp.success) {
            $.colorbox.close();
            $('#wcfmmp_shipping_method_edit_button').unblock();
            wcfmmp_shipping_zone_object.vendorEditZone(undefined, zoneId);
          } else {
            $('#wcfmmp_shipping_method_edit_button').unblock();
            alert(resp.data);
          }
      });
    }
  };
  wcfmmp_shipping_zone_object.init();
}) (jQuery);