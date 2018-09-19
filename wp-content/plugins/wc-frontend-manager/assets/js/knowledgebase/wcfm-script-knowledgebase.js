jQuery(document).ready(function($) {
	$knowledgebase_cat = '';
		
	$wcfm_knowledgebase_table = $('#wcfm-knowledgebase').DataTable( {
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"pageLength": dataTables_config.pageLength,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 2 },
										{ responsivePriority: 1 },
								],
		"columnDefs": [ { "targets": 0, "orderable" : false }, 
										{ "targets": 1, "orderable" : false },
										{ "targets": 2, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action              = 'wcfm_ajax_controller',
				d.controller          = 'wcfm-knowledgebase',
				d.knowledgebase_cat   = $knowledgebase_cat
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-groups table refresh complete
				$( document.body ).trigger( 'updated_wcfm-knowledgebase' );
			}
		}
	} );
	
	if( $('.dropdown_knowledgebase_cat').length > 0 ) {
		$('.dropdown_knowledgebase_cat').on('change', function() {
			$knowledgebase_cat = $('.dropdown_knowledgebase_cat').val();
			$wcfm_knowledgebase_table.ajax.reload();
		});
	}
	
	$knowledge_popup_width = '75%';
	if( jQuery(window).width() <= 768 ) {
		$knowledge_popup_width = '95%';
	}
	
	// View knowledgebase
	$( document.body ).on( 'updated_wcfm-knowledgebase', function() {
		$('.wcfm_knowledgebase_view').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				$knowledgebaseid = $(this).data('knowledgebaseid');
				var data = {
					action          : 'wcfm_knowledgebase_view',
					knowledgebaseid : $knowledgebaseid
				}	
				
				jQuery.ajax({
					type    :		'POST',
					url     : wcfm_params.ajax_url,
					data    : data,
					success :	function(response) {
						// Intialize colorbox
						jQuery.colorbox( { html: response, width: $knowledge_popup_width, height: '500' } );
					}
				});
				return false;
			});
		});
	});
	
	// Delete knowledgebase
	$( document.body ).on( 'updated_wcfm-knowledgebase', function() {
		$('.wcfm_knowledgebase_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Knowledgebase'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMKnowledgebase($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMKnowledgebase(item) {
		jQuery('#wcfm_knowledgebase_listing_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action          : 'delete_wcfm_knowledgebase',
			knowledgebaseid : item.data('knowledgebaseid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_knowledgebase_table) $wcfm_knowledgebase_table.ajax.reload();
				jQuery('#wcfm_knowledgebase_listing_expander').unblock();
			}
		});
	}
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
} );