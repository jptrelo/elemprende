
(function($){
	window.yotu_wp = {

		parse_data : {},

		init : function (){
			
			jQuery('.yotu-tabs').each(function (indx){
				var tabs = jQuery(this),
					tabs_content = tabs.next();
					
				tabs.find('a').on('click', function (e){
					e.preventDefault();
					var that = jQuery(this),
						tab = that.data('tab'),
						yotu = that.data('yotu');
					tabs_content.find('.yotu-tab-content').css({display: 'none'});
					jQuery('#yotu-tab-' + tab).css({display: 'block'});
					tabs.find('li').removeClass('yotu-active');
					jQuery(this).closest('li').addClass('yotu-active');

					if(yotu === 'insert'){
						jQuery('.yotu_insert_popup').data('type', tab);
						jQuery('.yotu_insert_popup .yotu-info-res').html('');
						jQuery('.yotu_insert_popup .yotu-actions').addClass('yotu-hidden');
					}
				});
			});

			$('#shortcode_val').click(function () {
			   $(this).select();
			});
			
			
			//active tab with hash
			var hash = window.location.hash;

            if(hash && hash.indexOf('?')<0) {
                jQuery('.yotu-tabs a[data-tab='+hash.replace('#','')+']').trigger('click');
            }else{
            	jQuery('.yotu-tabs li:first-child a').trigger('click');
            }

			jQuery('.yotu_insert_popup .yotu-actions a').on('click', function (){
				var params = yotu_wp.get_params();
				wp.media.editor.insert('[yotuwp type="' + yotu_wp.parse_data.type + '" id="' + yotu_wp.parse_data.id + '" ' +params.join(' ') + ']');
			});

			jQuery('.yotu-insert-popup .yotu-tab-content').each(function(){

				var that = $(this),
					type = that.data('type');
			
			
				that.find('.yotu-search-action').on('click', function (e){
					e.preventDefault();

					var data = {}, val = that.find('.yotu-input-value').val().trim();
					
					if(['channel', 'playlist'].indexOf(type) > -1){
						data = yotu_wp.parse(val);
					}else if(['videos'].indexOf(type) > -1){
						var lines = val.split('\n'),
							ids = [];
						lines.map(function (url){
							data = yotu_wp.parse(url);
							
							if(data.type == 'video')
								ids.push(data.id);
						});
						
						data.type = 'videos';
						data.id = ids.join(',');
					}else if(['username', 'keyword'].indexOf(type) > -1){
						data.id = val;
						data.type = type;
						data.valid = true;
					}
					 
					if( 
						type == 'playlist' && 
						data.type === 'video'
					){
						if(typeof data.params.list !=='undefined'){
							data.id = data.params.list;
							data.type = 'playlist';
						}else data.valid = false;
					}

					yotu_wp.parse_data = data;

					if(
						data.valid && 
						data.type == type
					){
						yotu_wp.ajax(that, {type: type, data: data.id});
					}else alert('Please enter correct URL to start getting info.');
				});
				
			});
			
			jQuery('#yotu-settings-handler').on('click', yotu_wp.actions.show_settings);

			$('label.yotu-field-radios').on('click', function(e){
				var wrp = $(this).closest('.yotu-radios-img');
				wrp.find('.yotu-field-radios-selected').removeClass('yotu-field-radios-selected');
				$(this).addClass('yotu-field-radios-selected');
			});

			$('label.yotu-field-radios a').on('click', function(e){
				e.preventDefault();
			});
			var style_tag = $('#yotu-styling'),

			render_styling = function (elm, selector, value){

				var style_str = style_tag.html();

				if(style_str.indexOf('/*'+ elm.attr('id') +'*/') < 0){
					style_str += '/*'+ elm.attr('id') +'*/';
					style_str += selector + '{';
					style_str += value;
					style_str += '}';
					style_str += '/*end '+ elm.attr('id') +'*/';
					style_tag.html(style_str);
				}else{

					var patt = new RegExp('\/\\*' + elm.attr('id') + '(.+)'+elm.attr('id')+'\\*\/'),
						rep;

					rep = '/*'+ elm.attr('id') +'*/';
					rep += selector + '{';
					rep += value;
					rep += '}';
					rep += '/*end '+ elm.attr('id') +'*/';
					style_str = style_str.replace(patt, rep);
					style_tag.html(style_str);
				}
			},

			change_color = function (e, ui){
				let elm = $(e.target),
					refer = elm.data('css');
				if(refer !== ''){
					refer = refer.split('|');
					render_styling(elm, refer[0], refer[1] + ':' + ui.color.toString());
				}
				setTimeout(function (){
					elm.trigger('change');
				}, 200);
				
			};

			$('.yotu-colorpicker').wpColorPicker({
				change : change_color,
				clear : function(e){
					$(this).closest('.wp-picker-input-wrap').find('input.yotu-param').trigger('input').trigger('change');
					
				}
			}).on('input', function(e){
				var elm = $(this),
					refer = elm.data('css');

				if(refer !== ''){
					refer = refer.split('|');
					render_styling(elm, refer[0], refer[1] + ':' + elm.val());
				}
			});
			
		},

		get_params : function (){
			let type = jQuery('.yotu_insert_popup').data('type'),
				values = jQuery('.yotu-layout .yotu-param').serializeArray(),
				param_keys = ['settings', 'player'],
				data = {},
				option, params = [], options={}, player={}, player_params = [];
				
			for(let i in values){
				let param = values[i];
				param_keys.map(function (key){
					let patt = new RegExp('yotu-'+key+'\\[(.+)\\]');

					if(param.name.indexOf(key) > -1){
						if(typeof data[key] === 'undefined') data[key] = [];

						data[key][param.name.replace(patt,'$1')] = param.value;
					}
				});

				
			}

			params = yotu_wp.get_options_param(data['settings'], yotujs.options);

			param_keys.map(function (key){
				if(key === 'settings') return;
				let params_tab = (['player'].indexOf(key) > -1)? 
					yotu_wp.get_player_param(data[key], yotujs[key]) : 
					yotu_wp.get_options_param(data[key], yotujs[key]);

				if(params_tab.length > 0)
					params.push(key + '="' + params_tab.join('&') + '"');
			});

			delete yotujs.options['id'];
			delete yotujs.options['type'];
			delete yotujs.options['player'];
			delete yotujs.options['styling'];

			return params;
		},
		
		get_options_param : function (options, def_ops){
			var params = [];

			for(var i in def_ops){
				if(
					def_ops[i] === 'on' && 
					typeof options[i] === 'undefined'
				){
					params.push(i + '="off"');
					continue;
				}

				if(
					typeof def_ops[i] !== 'number' && 
					options[i] !== def_ops[i] && 
					typeof options[i] !== 'undefined' 
				){
					params.push(i + '="' + options[i] + '"');
				}
				
				if(
					typeof def_ops[i] === 'number' && 
					parseInt(options[i]) !== def_ops[i]
				){
					params.push(i + '="' + options[i] + '"');
				}
			}
			
			return params;
		},
		
		get_player_param : function (options, def_ops){
			let params = [],
				convert = function (val){
					switch(options[i]){
						case 'on':
							return 1;
							break;
						case 'off':
							return 0;
							break;
						default:
							return val;
							break;
					}
				};
			
			for(i in def_ops){
				if(
					def_ops[i] === 'on' && 
					typeof options[i] === 'undefined'
				){
					params.push(i + '=0');
					continue;
				}

				if(
					typeof def_ops[i] !== 'number' && 
					options[i] !== def_ops[i] && 
					typeof options[i] !== 'undefined' &&
					options[i] !== ''
				){
					params.push(i + '=' + convert(options[i]));
				}
				
				if(
					typeof def_ops[i] === 'number' && 
					parseInt(options[i]) !== def_ops[i]
				){
					params.push(i + '=' + convert(options[i]));
				}
			}
			
			return params;
		},

		ajax : function (wrp, obj){
			var error = function (code){
					var txt = '';
					switch(code) {
						case 403:
							text = yotujs.lang['01'];
							break;
						case 404:
							text = yotujs.lang['03'];
							break;
					}
					
					if(text !== '') alert(txt);

					load_more.removeClass('yotu-active');
				},
				is_shortcode_gen = wrp.closest('.shortcode_gen');

			obj.action = 'yotu_getinfo';

			jQuery('.yotu_insert_popup .yotu-info-res').addClass('yotu-active');

			$.ajax({

				url: yotujs.ajax_url + '?'+Math.random(),
				type: 'POST',
				dataType: 'json',
				data: obj,
				statusCode : {
					403: function (){
						error(403);
					},
					404: function(){
						error(404);
					},
					200: function(data) {
						var html = '<h4 class="light">'+yotujs.lang[4]+'</h4>';

						if(data.items.length > 0){
							data.items.map(function (item){
								html += '<div class="yotu-result">\
									<div class="yotu-item-thumb"><img src="' +item.snippet.thumbnails.default.url+ '"/></div>\
									<div class="yotu-item-title">' +item.snippet.title+ '</div>\
									<div class="yotu-item-desc">' +item.snippet.description+ '</div>\
								</div>';
							});

							jQuery('.yotu_insert_popup .yotu-actions').removeClass('yotu-hidden');

							yotu_wp.insert = {
								type : obj.type,
								id : obj.id
							}
						}else if(
							typeof data['error'] !== 'undefined' &&
							data.error === true &&
							data.msg !==''
						){
							html = '<p><strong>Error</strong>: '+data.msg+'</p>';
						}else{
							html = '<p>Items not found, please check your url again.</p>';
						}

						if(is_shortcode_gen.get(0)){
							var gen = function (){
									params = yotu_wp.get_params();
									jQuery('#shortcode_val').val('[yotuwp type="' + yotu_wp.parse_data.type + '" id="' + yotu_wp.parse_data.id + '" ' +params.join(' ') + ']');
								};
							gen();
							jQuery('.yotu-layout .yotu-param').off().on('change', function(){
								gen();
							})

						}
						jQuery('.yotu_insert_popup .yotu-info-res').html(html).removeClass('yotu-active');
					}
				}
			});
		},

		parse : function (url) {
			 
			if ( !/^http(s)?/i.test(url) ) url = "http://" + url;
			 
			var parser = document.createElement("a");
				parser.href = url,
				parsedUrl = {
					valid: false,
					params: {}
				},
				regexYouTubeUrl = /^((?:(?:m|www)\.)?youtu(?:be.com|.be|be.googleapis.com))/i;

			if ( regexYouTubeUrl.test(parser.hostname) ) {

				var regexYouTubeType = /^\/(channel|user|playlist|watch|v|video|embed)/i,
					typeCheck = regexYouTubeType.exec(parser.pathname);
				 
				if ( typeCheck ) {
					 
					if ( ["watch","v","video","embed"].indexOf(typeCheck[1]) > -1 ) {
						parsedUrl.type = "video";
					}
					else if ( ["channel","user"].indexOf(typeCheck[1]) > -1 ) {
						parsedUrl.type = "channel";
					}else if ( ["playlist"].indexOf(typeCheck[1]) > -1 ) {
						parsedUrl.type = "playlist";
					}
					 
					// If we got a valid type, get the ID
					if ( parsedUrl.type === "channel" ) {

						var regexYouTubeChannelId = /^\/[^\/]*\/([^\/]*)/i,
							channelCheck = regexYouTubeChannelId.exec(parser.pathname);

						parsedUrl.id = channelCheck[1];
					}

					else if ( parsedUrl.type === "video" || parsedUrl.type === "playlist" ) {
						var urlParamsStr = parser.search.substring(1),
							urlParamsPairs = urlParamsStr.split("&");
						 
						urlParamsPairs.forEach(function(pair){
							var pairKeyValue = pair.split("=");
							parsedUrl.params[pairKeyValue[0]] = pairKeyValue[1];
						});
						 
						parsedUrl.id = parsedUrl.params.v;

						if(parsedUrl.type === "playlist")
							parsedUrl.id = parsedUrl.params.list;
					}
					 
					// If we got the ID, then we can mark this as valid
					if ( parsedUrl.id ) {
						parsedUrl.valid = true;
						// Create a normalized YouTube URL
						parsedUrl.normalized = "http://youtube.com/" + parsedUrl.type + "/";

						if ( parsedUrl.type === "video" ) {
							parsedUrl.normalized += "?v=";
						}
						
						parsedUrl.normalized += parsedUrl.id;
					}
				}
			}
			 
			return parsedUrl;
		},
		
		actions : {
			
			show_settings : function (e){
				if(jQuery(e.target).is(':checked'))
					jQuery('.yotu-layout').addClass('yotu-hidden');
				else
					jQuery('.yotu-layout').removeClass('yotu-hidden');
			}
			
		}
	}
})(jQuery);

jQuery( document ).ready(function() {
    yotu_wp.init();
	
});