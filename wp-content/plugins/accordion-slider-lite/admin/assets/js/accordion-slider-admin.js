/*
 * ======================================================================
 * Accordion Slider Admin
 * ======================================================================
 */
(function( $ ) {

	var AccordionSliderAdmin = {

		/**
		 * Stores the data for all panels in the accordion.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Array}
		 */
		panels: [],

		/**
		 * Keeps a count for the panels in the accordion.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Int}
		 */
		panelCounter: 0,

		/**
		 * Stores all posts names and their taxonomies.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Object}
		 */
		postsData: {},

		/**
		 * Indicates if the preview images from the panels
		 * can be resized.
		 * This prevents resizing the images too often.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Boolean}
		 */
		allowPanelImageResize: true,

		/**
		 * Initializes the functionality for a single accordion page
		 * or for the page that contains all the accordions.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			if ( as_js_vars.page === 'single' ) {
				this.initSingleAccordionPage();
			} else if ( as_js_vars.page === 'all' ) {
				this.initAllAccordionsPage();
			}
		},

		/*
		 * ======================================================================
		 * Accordion functions
		 * ======================================================================
		 */
		
		/**
		 * Initializes the functionality for a single accordion page
		 * by adding all the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		initSingleAccordionPage: function() {
			var that = this;

			this.initPanels();

			if ( parseInt( as_js_vars.id, 10 ) !== -1 ) {
				this.loadAccordionData();
			}

			$( 'form' ).on( 'submit', function( event ) {
				event.preventDefault();
				that.saveAccordion();
			});

			$( '.preview-accordion' ).on( 'click', function( event ) {
				event.preventDefault();
				that.previewAccordion();
			});

			$( '.add-panel' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addImagePanels();
			});

			$( '.postbox .hndle, .postbox .handlediv' ).on( 'click', function() {
				var postbox = $( this ).parent( '.postbox' );
				
				if ( postbox.hasClass( 'closed' ) === true ) {
					postbox.removeClass( 'closed' );
				} else {
					postbox.addClass( 'closed' );
				}
			});

			$( '.sidebar-settings' ).on( 'mouseover', 'label', function() {
				that.showInfo( $( this ) );
			});

			$( window ).resize(function() {
				if ( that.allowPanelImageResize === true ) {
					that.resizePanelImages();
					that.allowPanelImageResize = false;

					setTimeout( function() {
						that.resizePanelImages();
						that.allowPanelImageResize = true;
					}, 250 );
				}
			});
		},

		/**
		 * Initializes the functionality for the page that contains
		 * all the accordions by adding all the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		initAllAccordionsPage: function() {
			var that = this;

			$( '.accordions-list' ).on( 'click', '.preview-accordion', function( event ) {
				event.preventDefault();
				that.previewAccordionAll( $( this ) );
			});

			$( '.accordions-list' ).on( 'click', '.delete-accordion', function( event ) {
				event.preventDefault();
				that.deleteAccordion( $( this ) );
			});

			$( '.accordions-list' ).on( 'click', '.duplicate-accordion', function( event ) {
				event.preventDefault();
				that.duplicateAccordion( $( this ) );
			});

			$( '.clear-all-cache' ).on( 'click', function( event ) {
				event.preventDefault();

				$( '.clear-cache-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

				var nonce = $( this ).attr( 'data-nonce' );

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_lite_clear_all_cache', nonce: nonce },
					complete: function( data ) {
						$( '.clear-cache-spinner' ).css( { 'display': '', 'visibility': '' } );
					}
				});
			});
		},

		/**
		 * Load the accordion accordion data.
		 * 
		 * Send an AJAX request with the accordion id and the nonce, and
		 * retrieve all the accordion's database data. Then, assign the
		 * data to the panels.
		 *
		 * @since 1.0.0
		 */
		loadAccordionData: function() {
			var that = this;

			$( '.panel-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_lite_get_accordion_data', id: as_js_vars.id, nonce: as_js_vars.lad_nonce },
				complete: function( data ) {
					var accordionData = $.parseJSON( data.responseText );

					$.each( accordionData.panels, function( index, panel ) {
						var panelData = {
							background: {},
							layers: panel.layers,
							html: panel.html,
							settings: $.isArray( panel.settings ) ? {} : panel.settings
						};

						$.each( panel, function( settingName, settingValue ) {
							if ( settingName.indexOf( 'background' ) !== -1 ) {
								panelData.background[ settingName ] = settingValue;
							}
						});

						that.getPanel( index ).setData( 'all', panelData );
					});

					$( '.panel-spinner' ).css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Save the accordion's data.
		 * 
		 * Get the accordion's data and send it to the server with AJAX. If
		 * a new accordion was created, redirect to the accordion's edit page.
		 *
		 * @since 1.0.0
		 */
		saveAccordion: function() {
			var accordionData = this.getAccordionData();
			accordionData[ 'nonce' ] = as_js_vars.sa_nonce;
			accordionData[ 'action' ] = 'save';

			var accordionDataString = JSON.stringify( accordionData );

			var spinner = $( '.update-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_lite_save_accordion', data: accordionDataString },
				complete: function( data ) {
					spinner.css( { 'display': '', 'visibility': '' } );

					if ( parseInt( as_js_vars.id, 10 ) === -1 && isNaN( data.responseText ) === false ) {
						$( 'h2' ).after( '<div class="updated"><p>' + as_js_vars.accordion_create + '</p></div>' );

						window.location = as_js_vars.admin + '?page=accordion-slider-lite&id=' + data.responseText + '&action=edit';
					} else if ( $( '.updated' ).length === 0 ) {
						$( 'h2' ).after( '<div class="updated"><p>' + as_js_vars.accordion_update + '</p></div>' );
					}
				}
			});
		},

		/**
		 * Get the accordion's data.
		 * 
		 * Read the value of the sidebar settings, including the breakpoints,
		 * the panels state, the name of the accordion, the id, and get the
		 * data for each panel.
		 *
		 * @since 1.0.0
		 * 
		 * @return {Object} The accordion data.
		 */
		getAccordionData: function() {
			var that = this,
				accordionData = {
					'id': as_js_vars.id,
					'name': $( 'input#title' ).val(),
					'settings': {},
					'panels': [],
					'panels_state': {}
				},
				breakpoints = [];

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				var $panel = $( this ),
					panelData = that.getPanel( parseInt( $panel.attr('data-id'), 10 ) ).getData( 'all' );
				
				panelData.position = parseInt( $panel.attr( 'data-position' ), 10 );

				accordionData.panels[ index ] = panelData;
			});

			$( '.sidebar-settings' ).find( '.setting' ).each(function() {
				var setting = $( this );
				accordionData.settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
			});

			$( '.sidebar-settings' ).find( '.postbox' ).each(function() {
				var panel = $( this );
				accordionData.panels_state[ panel.attr( 'data-name' ) ] = panel.hasClass( 'closed' ) ? 'closed' : '';
			});

			return accordionData;
		},

		/**
		 * Preview the accordion in the accordion's edit page.
		 *
		 * @since 1.0.0
		 */
		previewAccordion: function() {
			PreviewWindow.open( this.getAccordionData() );
		},

		/**
		 * Preview the accordion in the accordions' list page.
		 *
		 * @since 1.0.0
		 */
		previewAccordionAll: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.lad_nonce,
				id = parseInt( url.id, 10 );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_lite_get_accordion_data', id: id, nonce: nonce },
				complete: function( data ) {
					var accordionData = $.parseJSON( data.responseText );

					PreviewWindow.open( accordionData );
				}
			});
		},

		/**
		 * Delete an accordion.
		 *
		 * This is called in the accordions' list page upon clicking
		 * the 'Delete' link.
		 *
		 * It displays a confirmation dialog before sending the request
		 * for deletion to the server.
		 *
		 * The accordion's row is removed after the accordion is deleted
		 * server-side.
		 * 
		 * @since 1.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Delete' link.
		 */
		deleteAccordion: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.da_nonce,
				id = parseInt( url.id, 10 ),
				row = target.parents( 'tr' );

			var dialog = $(
				'<div class="modal-overlay"></div>' +
				'<div class="modal-window-container">' +
				'	<div class="modal-window delete-accordion-dialog">' +
				'		<p class="dialog-question">' + as_js_vars.accordion_delete + '</p>' +
				'		<div class="dialog-buttons">' +
				'			<a class="button dialog-ok" href="#">' + as_js_vars.yes + '</a>' +
				'			<a class="button dialog-cancel" href="#">' + as_js_vars.cancel + '</a>' +
				'		</div>' +
				'	</div>' +
				'</div>'
			).appendTo( 'body' );

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_lite_delete_accordion', id: id, nonce: nonce },
					complete: function( data ) {
						if ( id === parseInt( data.responseText, 10 ) ) {
							row.fadeOut( 300, function() {
								row.remove();
							});
						}
					}
				});

				dialog.remove();
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Duplicate an accordion.
		 *
		 * This is called in the accordions' list page upon clicking
		 * the 'Duplicate' link.
		 *
		 * A new row is added in the list for the newly created
		 * accordion.
		 * 
		 * @since 1.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Duplicate' link.
		 */
		duplicateAccordion: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.dua_nonce,
				id = parseInt( url.id, 10 );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_lite_duplicate_accordion', id: id, nonce: nonce },
				complete: function( data ) {
					var row = $( data.responseText ).appendTo( $( '.accordions-list tbody' ) );
					
					row.hide().fadeIn();
				}
			});
		},

		/*
		 * ======================================================================
		 * Panel functions executed by the accordion
		 * ======================================================================
		 */
		
		/**
		 * Initialize all the existing panels when the page loads.
		 * 
		 * @since 1.0.0
		 */
		initPanels: function() {
			var that = this;

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				that.initPanel( $( this ) );
			});

			$( '.panels-container' ).lightSortable( {
				children: '.panel',
				placeholder: 'panel panel-placeholder',
				sortEnd: function( event ) {
					$( '.panel' ).each(function( index ) {
						$( this ).attr( 'data-position', index );
					});
				}
			} );
		},

		/**
		 * Initialize an individual panel.
		 *
		 * Creates a new instance of the Panel object and adds it 
		 * to the array of panels.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {jQuery Object} element The panel element.
		 * @param  {Object}        data    The panel's data.
		 */
		initPanel: function( element, data ) {
			var that = this,
				$panel = element,
				panel = new Panel( $panel, this.panelCounter, data );

			this.panels.push( panel );

			panel.on( 'duplicatePanel', function( event ) {
				that.duplicatePanel( event.panelData );
			});

			panel.on( 'deletePanel', function( event ) {
				that.deletePanel( event.id );
			});

			$panel.attr( 'data-id', this.panelCounter );
			$panel.attr( 'data-position', this.panelCounter );

			this.panelCounter++;
		},

		/**
		 * Return the panel data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Int}    id The id of the panel to retrieve.
		 * @return {Object}    The data of the retrieved panel.
		 */
		getPanel: function( id ) {
			var that = this,
				selectedPanel;

			$.each( that.panels, function( index, panel ) {
				if ( panel.id === id ) {
					selectedPanel = panel;
					return false;
				}
			});

			return selectedPanel;
		},

		/**
		 * Duplicate an individual panel.
		 *
		 * The background image is sent to the server for the purpose
		 * of adding it to the panel preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Object} panelData The data of the object to be duplicated.
		 */
		duplicatePanel: function( panelData ) {
			var that = this,
				newPanelData = $.extend( true, {}, panelData ),
				data = [{
					settings: {
						content_type: newPanelData.settings.content_type
					},
					background_source: newPanelData.background.background_source
				}];

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_lite_add_panels', data: JSON.stringify( data ) },
				complete: function( data ) {
					var panel = $( data.responseText ).appendTo( $( '.panels-container' ) );

					that.initPanel( panel, newPanelData );
				}
			});
		},

		/**
		 * Delete an individual panel.
		 *
		 * The background image is sent to the server for the purpose
		 * of adding it to the panel preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Int} id The id of the panel to be deleted.
		 */
		deletePanel: function( id ) {
			var that = this,
				panel = that.getPanel( id ),
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-panel-dialog">' +
					'		<p class="dialog-question">' + as_js_vars.panel_delete + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + as_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + as_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>').appendTo( 'body' );

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				panel.off( 'duplicatePanel' );
				panel.off( 'deletePanel' );
				panel.remove();
				dialog.remove();

				that.panels.splice( $.inArray( panel, that.panels ), 1 );
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Add image panel(s).
		 *
		 * Add one or multiple panels pre-populated with image data.
		 *
		 * @since 1.0.0
		 */
		addImagePanels: function() {
			var that = this;
			
			MediaLoader.open(function( selection ) {
				var images = [];

				$.each( selection, function( index, image ) {
					images.push({
						background_source: image.url,
						background_alt: image.alt,
						background_title: image.title,
						background_width: image.width,
						background_height: image.height,
						background_link: image.link
					});
				});

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_lite_add_panels', data: JSON.stringify( images ) },
					complete: function( data ) {
						var lastIndex = $( '.panels-container' ).find( '.panel' ).length - 1,
							panels = $( '.panels-container' ).append( data.responseText ),
							indexes = lastIndex === -1 ? '' : ':gt(' + lastIndex + ')';

						panels.find( '.panel' + indexes ).each(function( index ) {
							var panel = $( this );

							that.initPanel( panel, { background: images[ index ], layers: {}, html: '', settings: {} } );
						});
					}
				});
			});
		},

		/*
		 * ======================================================================
		 * More accordion functions
		 * ======================================================================
		 */

		/**
		 * Display the informative tooltip.
		 * 
		 * @since 1.0.0
		 * 
		 * @param  {jQuery Object} target The setting label which is hovered.
		 */
		showInfo: function( target ) {
			var label = target,
				info = label.attr( 'data-info' ),
				infoTooltip = null;

			if ( typeof info !== 'undefined' ) {
				infoTooltip = $( '<div class="info-tooltip">' + info + '</div>' ).appendTo( label.parent() );
				infoTooltip.css( { 'left': - infoTooltip.outerWidth( true ) ,'marginTop': - infoTooltip.outerHeight( true ) * 0.5 - 9 } );
			}

			label.on( 'mouseout', function() {
				if ( infoTooltip !== null ) {
					infoTooltip.remove();
				}
			});
		},

		/**
		 * Iterate through all panels and resizes the preview
		 * images based on their aspect ratio and the panel's
		 * current aspect ratio.
		 *
		 * @since 1.0.0
		 */
		resizePanelImages: function() {
			var panelRatio = $( '.panel-preview' ).width() / $( '.panel-preview' ).height();

			$( '.panel-preview > img' ).each(function() {
				var image = $( this );

				if ( image.width() / image.height() > panelRatio ) {
					image.css( { width: 'auto', height: '100%' } );
				} else {
					image.css( { width: '100%', height: 'auto' } );
				}
			});
		}
	};

	/*
	 * ======================================================================
	 * Panel functions
	 * ======================================================================
	 */
	
	/**
	 * Panel object.
	 *
	 * @since 1.0.0
	 * 
	 * @param {jQuery Object} element The jQuery element.
	 * @param {Int}           id      The id of the panel.
	 * @param {Object}        data    The data of the panel.
	 */
	var Panel = function( element, id, data ) {
		this.$panel = element;
		this.id = id;
		this.data = data;
		this.events = $( {} );

		if ( typeof this.data === 'undefined' ) {
			this.data = { background: {}, layers: {}, html: '', settings: {} };
		}

		this.init();
	};

	Panel.prototype = {

		/**
		 * Initialize the panel.
		 * 
		 * Add the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			var that = this;

			this.$panel.find( '.panel-preview' ).on( 'click', function( event ) {
				MediaLoader.open(function( selection ) {
					var image = selection[ 0 ];

					that.setData( 'background', { background_source: image.url, background_alt: image.alt, background_title: image.title, background_width: image.width, background_height: image.height, background_link: image.link } );
					that.updatePanelPreview();
				});
			});

			this.$panel.find( '.delete-panel' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'deletePanel', id: that.id } );
			});

			this.$panel.find( '.duplicate-panel' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'duplicatePanel', panelData: that.data } );
			});

			this.resizeImage();
		},

		/**
		 * Return the panel's data.
		 *
		 * It can return the background data, or the layers
		 * data, or the HTML data, or the settings data, or
		 * all the data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} target The type of data to return.
		 * @return {Object}        The requested data.
		 */
		getData: function( target ) {
			if ( target === 'all' ) {
				var allData = {};

				$.each( this.data.background, function( settingName, settingValue ) {
					allData[ settingName ] = settingValue;
				});

				allData[ 'layers' ] = this.data.layers;
				allData[ 'html' ] = this.data.html;
				allData[ 'settings' ] = this.data.settings;

				return allData;
			} else if ( target === 'background' ) {
				return this.data.background;
			}
		},

		/**
		 * Set the panel's data.
		 *
		 * It can set a specific data type, like the background, 
		 * layers, html, settings, or it can set all the data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} target The type of data to set.
		 * @param  {Object} data   The data to attribute to the panel.
		 */
		setData: function( target, data ) {
			var that = this;

			if ( target === 'all' ) {
				this.data = data;
			} else if ( target === 'background' ) {
				$.each( data, function( name, value ) {
					that.data.background[ name ] = value;
				});
			}
		},

		/**
		 * Remove the panel.
		 * 
		 * @since 1.0.0
		 */
		remove: function() {
			this.$panel.find( '.panel-preview' ).off( 'click' );
			this.$panel.find( '.delete-panel' ).off( 'click' );
			this.$panel.find( '.duplicate-panel' ).off( 'click' );

			this.$panel.fadeOut( 500, function() {
				$( this ).remove();
			});
		},

		/**
		 * Update the panel's preview.
		 *
		 * If the content type is custom, the preview will consist
		 * of an image. If the content is dynamic, a text will be 
		 * displayed that indicates the type of content (i.e., posts).
		 *
		 * This is called when the background image is changed or
		 * when the content type is changed.
		 * 
		 * @since 1.0.0
		 */
		updatePanelPreview: function() {
			var panelPreview = this.$panel.find( '.panel-preview' ),
				contentType = this.data.settings[ 'content_type' ];
			
			panelPreview.empty();

			if ( typeof contentType === 'undefined' || contentType === 'custom' ) {
				var backgroundSource = this.data.background[ 'background_source' ];

				if ( typeof backgroundSource !== 'undefined' && backgroundSource !== '' ) {
					$( '<img src="' + backgroundSource + '" />' ).appendTo( panelPreview );
					this.resizeImage();
				} else {
					$( '<p class="no-image">' + as_js_vars.no_image + '</p>' ).appendTo( panelPreview );
				}

				this.$panel.removeClass( 'dynamic-panel' );
			}
		},

		/**
		 * Resize the preview image, after it has loaded.
		 *
		 * @since 1.0.0
		 */
		resizeImage: function() {
			var panelPreview = this.$panel.find( '.panel-preview' ),
				panelImage = this.$panel.find( '.panel-preview > img' );

			if ( panelImage.length ) {
				var checkImage = setInterval(function() {
					if ( panelImage[0].complete === true ) {
						clearInterval( checkImage );

						if ( panelImage.width() / panelImage.height() > panelPreview.width() / panelPreview.height() ) {
							panelImage.css( { width: 'auto', height: '100%' } );
						} else {
							panelImage.css( { width: '100%', height: 'auto' } );
						}
					}
				}, 100 );
			}
		},

		/**
		 * Add an event listener to the panel.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String}   type    The event name.
		 * @param  {Function} handler The callback function.
		 */
		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		/**
		 * Remove an event listener from the panel.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		off: function( type ) {
			this.events.off( type );
		},

		/**
		 * Triggers an event.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		trigger: function( type ) {
			this.events.triggerHandler( type );
		}
	};

	/*
	 * ======================================================================
	 * Media loader
	 * ======================================================================
	 */

	var MediaLoader = {

		/**
		 * Open the WordPress media loader and pass the
		 * information of the selected images to the 
		 * callback function.
		 *
		 * The passed that is the image's url, alt, title,
		 * width and height.
		 * 
		 * @since 1.0.0
		 */
		open: function( callback ) {
			var selection = [],
				insertReference = wp.media.editor.insert;
			
			wp.media.editor.send.attachment = function( props, attachment ) {
				var image = typeof attachment.sizes[ props.size ] !== 'undefined' ? attachment.sizes[ props.size ] : attachment.sizes[ 'full' ],
					url = image.url,
					width = image.width,
					height = image.height,
					alt = attachment.alt,
					title = attachment.title,
					link = props.link === 'custom' ? props.linkUrl : '';

				selection.push( { url: url, alt: alt, title: title, width: width, height: height, link: link } );
			};

			wp.media.editor.insert = function( prop ) {
				callback.call( this, selection );

				wp.media.editor.insert = insertReference;
			};

			wp.media.editor.open( 'media-loader' );
		}
	};

	/*
	 * ======================================================================
	 * Preview window
	 * ======================================================================
	 */
	
	var PreviewWindow = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 1.0.0
		 * 
		 * @type {jQuery Object}
		 */
		previewWindow: null,

		/**
		 * Reference to the accordion slider instance.
		 *
		 * @since 1.0.0
		 * 
		 * @type {jQuery Object}
		 */
		accordion: null,

		/**
		 * The accordion's data.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Object}
		 */
		accordionData: null,

		/**
		 * Open the preview window and pass the accordion's data,
		 * which consists of accordion settings and each panel's
		 * settings and content.
		 *
		 * Send an AJAX request with the data and receive the 
		 * accordion's HTML markup and inline JavaScript.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Object} data The data of the accordion
		 */
		open: function( data ) {
			this.accordionData = data;

			var that = this,
				spinner = $( '.preview-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_lite_preview_accordion', data: JSON.stringify( data ) },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the preview.
		 *
		 * Detect when the window is resized and resize the preview
		 * window accordingly, and also based on the accordion's set
		 * width.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			var that = this;

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			this.previewWindow = $( '.preview-window .modal-window' );
			this.accordion = this.previewWindow.find( '.accordion-slider' );

			this.previewWindow.find( '.close-x' ).on( 'click', function( event ) {
				that.close();
			});

			var accordionWidth = this.accordionData[ 'settings' ][ 'width' ],
				accordionHeight = this.accordionData[ 'settings' ][ 'height' ],
				isPercetageWidth = accordionWidth.indexOf( '%' ) !== -1,
				isPercetageHeight = accordionHeight.indexOf( '%' ) !== -1;

			if ( isPercetageWidth === true ) {
				this.accordion.accordionSlider('width', '100%');
			} else {
				accordionWidth = parseInt( accordionWidth, 10 );
			}

			if ( isPercetageHeight === true ) {
				this.accordion.accordionSlider('height', '100%');
			}

			$( window ).on( 'resize.accordionSlider', function() {
				if ( isPercetageWidth === true ) {
					that.previewWindow.css( 'width', $( window ).width() * ( parseInt( accordionWidth, 10 ) / 100 ) - 100 );
				} else if ( accordionWidth >= $( window ).width() - 100 ) {
					that.previewWindow.css( 'width', $( window ).width() - 100 );
				} else {
					that.previewWindow.css( 'width', accordionWidth );
				}

				if ( isPercetageHeight === true ) {
					that.previewWindow.css( 'height', $( window ).height() * ( parseInt( accordionHeight, 10 ) / 100 ) - 200 );
				}
			});

			$( window ).trigger( 'resize' );
			$( window ).trigger( 'resize' );
		},

		/**
		 * Close the preview window.
		 *
		 * Remove event listeners and elements.
		 *
		 * @since 1.0.0
		 */
		close: function() {
			this.previewWindow.find( '.close-x' ).off( 'click' );
			$( window ).off( 'resize.accordionSlider' );

			this.accordion.accordionSlider( 'destroy' );
			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	$( document ).ready(function() {
		AccordionSliderAdmin.init();
	});

})( jQuery );

/*
 * ======================================================================
 * LightSortable
 * ======================================================================
 */

;(function( $ ) {

	var LightSortable = function( instance, options ) {

		this.options = options;
		this.$container = $( instance );
		this.$selectedChild = null;
		this.$placeholder = null;

		this.currentMouseX = 0;
		this.currentMouseY = 0;
		this.panelInitialX = 0;
		this.panelInitialY = 0;
		this.initialMouseX = 0;
		this.initialMouseY = 0;
		this.isDragging = false;
		
		this.checkHover = 0;

		this.uid = new Date().valueOf();

		this.events = $( {} );
		this.startPosition = 0;
		this.endPosition = 0;

		this.init();
	};

	LightSortable.prototype = {

		init: function() {
			this.settings = $.extend( {}, this.defaults, this.options );

			this.$container.on( 'mousedown.lightSortable' + this.uid, $.proxy( this._onDragStart, this ) );
			$( document ).on( 'mousemove.lightSortable.' + this.uid, $.proxy( this._onDragging, this ) );
			$( document ).on( 'mouseup.lightSortable.' + this.uid, $.proxy( this._onDragEnd, this ) );
		},

		_onDragStart: function( event ) {
			if ( event.which !== 1 || $( event.target ).is( 'select' ) || $( event.target ).is( 'input' ) ) {
				return;
			}

			this.$selectedChild = $( event.target ).is( this.settings.children ) ? $( event.target ) : $( event.target ).parents( this.settings.children );

			if ( this.$selectedChild.length === 1 ) {
				this.initialMouseX = event.pageX;
				this.initialMouseY = event.pageY;
				this.panelInitialX = this.$selectedChild.position().left;
				this.panelInitialY = this.$selectedChild.position().top;

				this.startPosition = this.$selectedChild.index();

				event.preventDefault();
			}
		},

		_onDragging: function( event ) {
			if ( this.$selectedChild === null || this.$selectedChild.length === 0 )
				return;

			event.preventDefault();

			this.currentMouseX = event.pageX;
			this.currentMouseY = event.pageY;

			if ( ! this.isDragging ) {
				this.isDragging = true;

				this.trigger( { type: 'sortStart' } );
				if ( $.isFunction( this.settings.sortStart ) ) {
					this.settings.sortStart.call( this, { type: 'sortStart' } );
				}

				var tag = this.$container.is( 'ul' ) || this.$container.is( 'ol' ) ? 'li' : 'div';

				this.$placeholder = $( '<' + tag + '>' ).addClass( 'ls-ignore ' + this.settings.placeholder )
					.insertAfter( this.$selectedChild );

				if ( this.$placeholder.width() === 0 ) {
					this.$placeholder.css( 'width', this.$selectedChild.outerWidth() );
				}

				if ( this.$placeholder.height() === 0 ) {
					this.$placeholder.css( 'height', this.$selectedChild.outerHeight() );
				}

				this.$selectedChild.css( {
						'pointer-events': 'none',
						'position': 'absolute',
						left: this.$selectedChild.position().left,
						top: this.$selectedChild.position().top,
						width: this.$selectedChild.width(),
						height: this.$selectedChild.height()
					} )
					.addClass( 'ls-ignore' );

				this.$container.append( this.$selectedChild );

				$( 'body' ).css( 'user-select', 'none' );

				var that = this;

				this.checkHover = setInterval( function() {

					that.$container.find( that.settings.children ).not( '.ls-ignore' ).each( function() {
						var $currentChild = $( this );

						if ( that.currentMouseX > $currentChild.offset().left &&
							that.currentMouseX < $currentChild.offset().left + $currentChild.width() &&
							that.currentMouseY > $currentChild.offset().top &&
							that.currentMouseY < $currentChild.offset().top + $currentChild.height() ) {

							if ( $currentChild.index() >= that.$placeholder.index() )
								that.$placeholder.insertAfter( $currentChild );
							else
								that.$placeholder.insertBefore( $currentChild );
						}
					});
				}, 200 );
			}

			this.$selectedChild.css( { 'left': this.currentMouseX - this.initialMouseX + this.panelInitialX, 'top': this.currentMouseY - this.initialMouseY + this.panelInitialY } );
		},

		_onDragEnd: function() {
			if ( this.isDragging ) {
				this.isDragging = false;

				$( 'body' ).css( 'user-select', '');

				this.$selectedChild.css( { 'position': '', left: '', top: '', width: '', height: '', 'pointer-events': '' } )
									.removeClass( 'ls-ignore' )
									.insertAfter( this.$placeholder );

				this.$placeholder.remove();

				clearInterval( this.checkHover );

				this.endPosition = this.$selectedChild.index();

				this.trigger( { type: 'sortEnd' } );
				if ( $.isFunction( this.settings.sortEnd ) ) {
					this.settings.sortEnd.call( this, { type: 'sortEnd', startPosition: this.startPosition, endPosition: this.endPosition } );
				}
			}

			this.$selectedChild = null;
		},

		destroy: function() {
			this.$container.removeData( 'lightSortable' );

			if ( this.isDragging ) {
				this._onDragEnd();
			}

			this.$container.off( 'mousedown.lightSortable.' + this.uid );
			$( document ).off( 'mousemove.lightSortable.' + this.uid );
			$( document ).off( 'mouseup.lightSortable.' + this.uid );
		},

		on: function( type, callback ) {
			return this.events.on( type, callback );
		},
		
		off: function( type ) {
			return this.events.off( type );
		},

		trigger: function( data ) {
			return this.events.triggerHandler( data );
		},

		defaults: {
			placeholder: '',
			sortStart: function() {},
			sortEnd: function() {}
		}

	};

	$.fn.lightSortable = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );

		return this.each(function() {
			if ( typeof $( this ).data( 'lightSortable' ) === 'undefined' ) {
				var newInstance = new LightSortable( this, options );

				$( this ).data( 'lightSortable', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'lightSortable' );

				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in lightSortable.' );
				}
			}
		});
	};

})( jQuery );

/*
 * ======================================================================
 * lightURLParse
 * ======================================================================
 */

;(function( $ ) {

	$.lightURLParse = function( url ) {
		var urlArray = url.split( '?' )[1].split( '&' ),
			result = [];

		$.each( urlArray, function( index, element ) {
			var elementArray = element.split( '=' );
			result[ elementArray[ 0 ] ] = elementArray[ 1 ];
		});

		return result;
	};

})( jQuery );