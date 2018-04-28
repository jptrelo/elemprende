jQuery(document).ready( function($) {
	if( $('#dropdown_vendor').length > 0 ) {
		$('#dropdown_vendor').on('change', function() {
			var data = {
				action                : 'vendor_manager_change_url',
				vendor_manager_change : $('#dropdown_vendor').val()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.redirect) {
						window.location = $response_json.redirect;
					}
				}
			});
		}).select2( $wcfm_vendor_select_args );
	}	
	
	// TinyMCE intialize - Description
	if( $('#wcfm_messages').length > 0 ) {
		var descTinyMCE = tinymce.init({
																	selector: '#wcfm_messages',
																	height: 75,
																	menubar: false,
																	plugins: [
																		'advlist autolink lists link charmap print preview anchor',
																		'searchreplace visualblocks code fullscreen',
																		'insertdatetime table contextmenu paste code directionality',
																		'autoresize'
																	],
																	toolbar: tinyMce_toolbar,
																	content_css: '//www.tinymce.com/css/codepen.min.css',
																	statusbar: false,
																	browser_spellcheck: true,
																});
	}
	
	// Direct Message Send
	$('#wcfm_messages_save_button').click(function(event) {
	  event.preventDefault();
	  
	  var wcfm_messages = '';
	  if( typeof tinymce != 'undefined' ) wcfm_messages = tinymce.get('wcfm_messages').getContent();
	  var direct_to = $('#direct_to').val();
	  
	  if( !wcfm_messages ) return false;
  
	  // Validations
	  $is_valid = true; //wcfm_coupons_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm_vendor_manage_form_message_expander').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action             : 'wcfm_ajax_controller',
				controller         : 'wcfm-message-sent',
				wcfm_messages      : wcfm_messages,
				direct_to          : direct_to
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						tinymce.get('wcfm_messages').setContent('');
						audio.play();
						$('#wcfm_vendor_manage_form_message_expander .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						audio.play();
						$('#wcfm_vendor_manage_form_message_expander .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_vendor_manage_form_message_expander').unblock();
				}
			});	
		}
	});
	
	// Profile Update
	$('#wcfm_profile_save_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
		$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		$wcfm_is_valid_form = true;
		$( document.body ).trigger( 'wcfm_form_validate' );
		$is_valid = $wcfm_is_valid_form;
	  
	  if($is_valid) {
			$('#wcfm_vendor_manage_form_profile_expander').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                          : 'wcfm_ajax_controller',
				controller                      : 'wcfm-vendors-manage-profile',
				wcfm_vendor_manage_profile_form : $('#wcfm_vendor_manage_profile_form').serialize(),
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_vendor_manage_form_profile_expander .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						audio.play();
						$('#wcfm_vendor_manage_form_profile_expander .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_vendor_manage_form_profile_expander').unblock();
				}
			});	
		}
	});
	
	// Badges Update
	$('#wcfm_vendor_badges_save_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
		$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		$wcfm_is_valid_form = true;
		$( document.body ).trigger( 'wcfm_form_validate' );
		$is_valid = $wcfm_is_valid_form;
	  
	  if($is_valid) {
			$('#wcfm_vendor_manage_form_badges_expander').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                          : 'wcfm_ajax_controller',
				controller                      : 'wcfm-vendors-manage-badges',
				wcfm_vendor_manage_badges_form  : $('#wcfm_vendor_manage_badges_form').serialize(),
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_vendor_manage_form_badges_expander .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						audio.play();
						$('#wcfm_vendor_manage_form_badges_expander .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_vendor_manage_form_badges_expander').unblock();
				}
			});	
		}
	});
	
	$('.wcfm_vendor_badges_manage_link').click(function(event) {
		event.preventDefault();
		$('.wcfm_vendor_badges_manage').slideDown();
		$(this).hide();
		return false;
	});
});