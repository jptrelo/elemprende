if (!Array.prototype.find) {
  Object.defineProperty(Array.prototype, 'find', {
    value: function(predicate) {
     'use strict';
     if (this == null) {
       throw new TypeError('Array.prototype.find called on null or undefined');
     }
     if (typeof predicate !== 'function') {
       throw new TypeError('predicate must be a function');
     }
     var list = Object(this);
     var length = list.length >>> 0;
     var thisArg = arguments[1];
     var value;

     for (var i = 0; i < length; i++) {
       value = list[i];
       if (predicate.call(thisArg, value, i, list)) {
         return value;
       }
     }
     return undefined;
    }
  });
}

(function ($) {
    'use strict';

     function removeHTML(text) {
        return text
            .replace(/&/g, "")
            .replace(/</g, "")
            .replace(/>/g, "")
            .replace(/"/g, "")
            .replace(/'/g, "");
    }

    (function ($) {

        var mi_tab_defaults = {
            tabLinksSelector: '.mi-logo-slider-tabs-control',
            tabContentSelector: '.mi-logo-slider-tabs-content',
            activeLinkClass: 'tab-current',
            activeSectionClass: 'content-current'
        };

        var MI_Tab = function ($element, options) {
            this.$element = $element;
            this.config = $.extend({}, mi_tab_defaults, options);
            this.tabs = this.$element.find(this.config.tabLinksSelector).find('a');
            this.tabConWrap = this.$element.find(this.config.tabContentSelector);

            this.initialize();
        };

        MI_Tab.prototype.initialize = function () {

            var that = this;

            this.tabs.on('click', function (e) {

                if (e) {
                    e.preventDefault();
                }

                if ($(this).parent().hasClass(that.config.activeLinkClass)) {
                    return
                }

                $(this).parent().addClass(that.config.activeLinkClass).siblings().removeClass(that.config.activeLinkClass);

                that.tabConWrap.find($(this).attr('href')).addClass(that.config.activeSectionClass).siblings().removeClass(that.config.activeSectionClass);

            });

        };

        $.fn.miTab = function (options) {
            $(this).each(function () {
                new MI_Tab($(this), options);
            });
        }

    })(jQuery);


    jQuery(document).ready(function ($) {

        // add button script 
        $('#mi-plugin-add-more-slide').on('click',function(e){
            e.preventDefault();
            alert('It is a premium Feature')
        });


        var $sliderCat = $('#slider-cat'); //category hidden field value
        var $imageData = $('#cp_image_data'); // image hidden field value

        var categories = []; // categories array
        var logos = []; // image array
        var data;

        function get_the_categories() {
            var catList = $sliderCat.val();
            if (catList) {
                categories = JSON.parse(catList);
            }
            return categories;
        } // save all value in categories variable


        function get_the_image_data() {
            var imageList = $imageData.val();
            if (imageList) {
                logos = JSON.parse(imageList);
            }
            return logos;

        } // save all value in logos variable


        function get_cats_by_ids(idsArr) {

            if ( !idsArr || idsArr.length <= 0 ) {
                return [];
            }

            var cats = get_the_categories();
            var matchIds = [];

            idsArr.forEach(function(id){
                var found = cats.find(function(cat){
                    return cat.id === id;
                });
                if (found) {
                    matchIds.push( found );
                }
            });

            return matchIds;

        } // save all value in logos variable

        /* TO STORE DATA FIRST TIME*/
        get_the_categories();
        get_the_image_data();


        // Uploading files
        var file_frame;
        var wp_media_post_id = '';
        if (!wp.media)return;
        wp_media_post_id = wp.media.model.settings.post.id;

        // Store the old id
        //var set_to_post_id = <?php //echo $my_saved_attachment_post_id; ?>; // Set this

        jQuery('#upload_image_button').on('click', function (event) {

            event.preventDefault();


            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: true // Set to true to allow multiple files to be selected
            });

            file_frame.on('open',function() {
                var selection = file_frame.state().get('selection');
                var selectedLogos = get_the_image_data();

                selectedLogos.forEach(function(logo) {

                    var attachment = wp.media.attachment(logo.id);
                    attachment.fetch();

                    attachment.set({
                        'miLogoLink': logo.link,
                        'miLogoTitle': logo.title
                    });

                    selection.add( attachment ? [ attachment ] : [] );

                });
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {


                // We set multiple true
                var attachment = file_frame.state().get('selection').toJSON();

                var imgVal = [];
                var imageWrapper = "";
                attachment.forEach(function (image_object) {

                    var id, title, alt, link, url = "";

                    id          = image_object.id;
                    title       = image_object.miLogoTitle || image_object.title || "";
                    alt         = title || image_object.alt || "";
                    link        = image_object.miLogoLink || "";
                    url         = image_object.url || "";



                    imageWrapper += '<tr class="mi-logo-slider-single-logo logo-id-' + id + '" data-logo-id="' + id + '">';
                    imageWrapper += '<td class="column-thumb" data-colname="Title">';
                    imageWrapper += '<img width="40px" height="40px" src="' + url + '" alt="' + alt + '" />';
                    imageWrapper += '<input type="hidden" name="mi-logo-url" value="' + url + '">';
                    imageWrapper += '</td>';
                    imageWrapper += '<td class="column-title">';
                    imageWrapper += '<input class="logo-title" type="text" name="mi-logo-title" value="' + title + '" placeholder="Logo Title">';
                    imageWrapper += '</td>';
                    imageWrapper += '<td class="column-link">';
                    imageWrapper += '<input class="logo-link" type="text" name="mi-logo-link" placeholder="http://" value="' + link + '">';
                    imageWrapper += '</td>';
                    imageWrapper += '<td class="column-action" >';
                    imageWrapper += '<a href="#" class="mi-logo-remove mi-logo-color-danger "><span class="dashicons dashicons-no-alt"></span></a>';
                    imageWrapper += '</td>';
                    imageWrapper += '</tr>';

                    imgVal.push({
                        id: id,
                        url: url,
                        title: title,
                        link: link
                    });
                });

                var imgContainer = $('#image-list');
                imgContainer.empty().append(imageWrapper);


                var $data = $imageData.val(JSON.stringify(imgVal));
                data = JSON.parse($data.val());

                get_the_image_data();

                $('.image-preview').attr('height', '100');


                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();


        });

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: true // Set to true to allow multiple files to be selected
        });


        // Restore the main ID when the add media button is pressed
        jQuery('a.add_media').on('click', function () {
            wp.media.model.settings.post.id = wp_media_post_id;
        });


        $('.image-preview-wrapper').delegate('.mi-logo-slider-single-logo input,.mi-logo-slider-single-logo textarea, .mi-logo-slider-single-logo select', 'change', function () {


            var $this = $(this);

            if (typeof data == 'undefined') {
                data = $imageData.val();

                data = JSON.parse(data);
            }


            var id = $(this).closest('.mi-logo-slider-single-logo').data('logo-id');


            data.forEach(function (item) {
                if (item.id == id) {


                    if ($this.hasClass('logo-title')) {
                        item.title = $this.val();
                    }
                    if ($this.hasClass('logo-link')) {
                        item.link = $this.val();
                    }

                    if ($this.hasClass('select2-general')) {
                        item.cats = $this.val();
                    }

                    $imageData.val(JSON.stringify(data));
                }
            });


        });


        $('.image-preview-wrapper').delegate('.mi-logo-remove', 'click', function (e) {
            e.preventDefault();
           var itemResult = confirm("Are you sure you want to delete this item");
            if(!itemResult) return;

            var $this = $(this);


            if (typeof data == 'undefined') {
                data = $imageData.val();

                data = JSON.parse(data);
            }


            var id = $(this).closest('.mi-logo-slider-single-logo').remove().data('logo-id');


            data.forEach(function (item) {
                if (item.id == id) {
                    var index = data.indexOf(item);

                    console.log(item);
                    if (index > -1) {
                        data.splice(index, 1);
                    }

                    $imageData.val(JSON.stringify(data));
                }
            });


        });





        $(window).on('load', function () {
            $('.mi-tab').miTab();

        });



        /*end of update logo option*/

        /*dependancy function*/
        function input_field_swapping(inputClass, inputValue, targatedDiv, inverse) {

            var obtain_value;

            obtain_value = $(inputClass).val();


            var $tgDiv = $(targatedDiv);

            var valueTypeArray = Array.isArray(inputValue);

            $tgDiv.hide();

            if (inverse && obtain_value !== '') {
                $tgDiv.show();
            }

            function div_calculate() {
                obtain_value = $(inputClass).val();


                $tgDiv.hide();

                if (inverse) {
                    $tgDiv.show();
                }

                if (valueTypeArray) {

                    inputValue.forEach(function (element) {

                        if (obtain_value === element) {
                            if ($tgDiv.is(':hidden')) {
                                $tgDiv.show();
                            }

                            if (inverse) {
                                $tgDiv.hide();
                            }


                        }

                    })

                } else {

                    if (obtain_value === inputValue) {
                        $tgDiv.show();

                        if (inverse) {
                            $tgDiv.hide();
                        }
                    }

                }



            }


            $(inputClass).on('change', function () {

                div_calculate()
            });

            $(window).on('load', function () {
                div_calculate()
            })

        }

        $('.color-field').wpColorPicker();

        input_field_swapping('#display_mode', ['h-slider', 'v-slider'], '.slider-settings');
        input_field_swapping('#display_mode', 'h-slider', '.dot-position');
        input_field_swapping('#display_mode', 'h-slider', '.mi-logo-gutter',true);
        input_field_swapping('#display_mode', 'filter', '.filter-settings');
        input_field_swapping('#display_mode', ['list','v-slider', ''], '.responsive-design', true);
        input_field_swapping('#display_mode', 'h-slider', '.nav-position-horizontal');
        input_field_swapping('#display_mode', 'v-slider', '.nav-position-vertical');
        input_field_swapping('#display_mode', 'v-slider', '.vertical-slider-settings');
        input_field_swapping('#display_mode', 'list', '.list-settings');



        input_field_swapping('#layout_selection', ['simple', 'box-gray-style', ''], '.layout-background-color,.layout-border-color,.layout-design-option', true)
        input_field_swapping('#layout_selection', ['round-shadow-effect', 'box-hover-color'], '.layout-background-h-color,.layout-border-h-color');
        input_field_swapping('#display_mode', 'list', '.option-hidden',true);
        input_field_swapping('#display_mode', '', '.layout-settings', true);
        input_field_swapping('#display_mode', ['','list'], '.tooltip-settings', true);
        input_field_swapping('#display_mode', ['','list'], '.tooltip-settings', true);
        input_field_swapping('#display_mode','list', '.layout-text-color,.layout-text-hover-color');
        input_field_swapping('#nav-position-h',['both-side','right'],'.nav-inner');


        function list_special(){
            var displayValue = $('#display_mode').val();
            var layoutValue = $('#layout_selection').val();
            if(displayValue == "list"){
                
                if(layoutValue == 'round-shadow-effect' ||  layoutValue == 'box-hover-color'){
                    $('.layout-text-color,.layout-text-hover-color').show();
                }else{
                    $('.layout-text-color,.layout-text-hover-color').hide();
                }
                if(layoutValue == 'outer-shadow'){
                    $('.layout-text-color').show();
                }

            }
        }

        $(window).on('load', function(){
            list_special();

        });

        function list_layout_value_change(){

            var displayValue = $('#display_mode').val();
            if(displayValue == "list"){
                $('#layout_selection').val('simple');
                $('.layout-background-color,.layout-border-color,.layout-text-color,.layout-background-h-color,.layout-border-h-color,.layout-text-hover-color').hide();
            }
        }


        $('#display_mode').on('change', function(){
            list_layout_value_change();
        });
        $('#layout_selection').on('change', function(){
            list_special();
        });



    });

})(jQuery);