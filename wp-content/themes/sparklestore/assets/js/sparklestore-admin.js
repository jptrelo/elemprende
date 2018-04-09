jQuery(document).ready(function($){

	var sparklestore_upload;
	var sparklestore_selector;
    function sparklestore_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		sparklestore_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( sparklestore_upload ) {
			sparklestore_upload.open();
		} else {
			// Create the media frame.
			sparklestore_upload = wp.media.frames.sparklestore_upload =  wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			sparklestore_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = sparklestore_upload.state().get('selection').first();
				sparklestore_upload.close();
                sparklestore_selector.find('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					sparklestore_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">'+ sparklestore_remove.remove +'</a>').slideDown('fast');
				}
				sparklestore_selector.find('.upload-button-wdgt').unbind().addClass('remove-file').removeClass('upload-button-wdgt').val(sparklestore_remove.remove);
				sparklestore_selector.find('.of-background-properties').slideDown();
				sparklestore_selector.find('.remove-image, .remove-file').on('click', function() {
					sparklestore_remove_file( $(this).parents('.section') );
				});
			});
		}
		// Finally, open the modal.
		sparklestore_upload.open();
	}

	function sparklestore_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload').val('');
		selector.find('.of-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind().addClass('upload-button-wdgt').removeClass('remove-file').val(sparklestore_remove.upload);
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.upload-button-wdgt').remove();
		}
		selector.find('.upload-button-wdgt').on('click', function(event) {
			sparklestore_add_file(event, $(this).parents('.section'));
            
		});
	}

	$('body').on('click','.remove-image, .remove-file', function() {
		sparklestore_remove_file( $(this).parents('.section') );
    });

    $(document).on('click', '.upload-button-wdgt', function( event ) {
    	sparklestore_add_file(event, $(this).parents('.section'));
    });

    /**
     * Repeater Fields
    */
	function sparklestore_refresh_repeater_values(){
		$(".sparklestore-repeater-field-control-wrap").each(function(){			
			var values = []; 
			var $this = $(this);			
			$this.find(".sparklestore-repeater-field-control").each(function(){
			var valueToPush = {};
			$(this).find('[data-name]').each(function(){
				var dataName = $(this).attr('data-name');
				var dataValue = $(this).val();
				valueToPush[dataName] = dataValue;
			});
			values.push(valueToPush);
			});
			$this.next('.sparklestore-repeater-collector').val(JSON.stringify(values)).trigger('change');
		});
	}

    $('#customize-theme-controls').on('click','.sparklestore-repeater-field-title',function(){
        $(this).next().slideToggle();
        $(this).closest('.sparklestore-repeater-field-control').toggleClass('expanded');
    });
    $('#customize-theme-controls').on('click', '.sparklestore-repeater-field-close', function(){
    	$(this).closest('.sparklestore-repeater-fields').slideUp();;
    	$(this).closest('.sparklestore-repeater-field-control').toggleClass('expanded');
    });
    $("body").on("click",'.sparklestore-add-control-field', function(){
		var $this = $(this).parent();
		if(typeof $this != 'undefined') {
            var field = $this.find(".sparklestore-repeater-field-control:first").clone();
            if(typeof field != 'undefined'){                
                field.find("input[type='text'][data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });
                field.find("textarea[data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });
                field.find("select[data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });

				field.find('.sparklestore-fields').show();

				$this.find('.sparklestore-repeater-field-control-wrap').append(field);

                field.addClass('expanded').find('.sparklestore-repeater-fields').show(); 
                $('.accordion-section-content').animate({ scrollTop: $this.height() }, 1000);
                sparklestore_refresh_repeater_values();
            }

		}
		return false;
	 });
	
	$("#customize-theme-controls").on("click", ".sparklestore-repeater-field-remove",function(){
		if( typeof	$(this).parent() != 'undefined'){
			$(this).closest('.sparklestore-repeater-field-control').slideUp('normal', function(){
				$(this).remove();
				sparklestore_refresh_repeater_values();
			});			
		}
		return false;
	});

	$("#customize-theme-controls").on('keyup change', '[data-name]',function(){
		 sparklestore_refresh_repeater_values();
		 return false;
	});

	/*Drag and drop to change order*/
	$(".sparklestore-repeater-field-control-wrap").sortable({
		orientation: "vertical",
		update: function( event, ui ) {
			sparklestore_refresh_repeater_values();
		}
	});

});

(function ($) {
    jQuery(document).ready(function ($) {
        $('.sparkle-customizer').on( 'click', function( evt ){
            evt.preventDefault();
            section = $(this).data('section');
            if ( section ) {
                wp.customize.section( section ).focus();
            }
        });
    });
})(jQuery);

/**
 * ADD PRO BUTTON LINK
*/
( function( api ) {
	api.sectionConstructor['upsell'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );
} )( wp.customize );
