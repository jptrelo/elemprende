/**
 * Reviews JS
 */
if (typeof (jQuery) != 'undefined') {

    jQuery.noConflict(); // Reverts '$' variable back to other JS libraries

    (function ($) {
        "use strict";

        $(function () {


            var LAE_Frontend = {

                init: function () {
                    this.output_custom_css();
                },

                output_custom_css: function () {

                    var custom_css = lae_settings['custom_css'];
                    if (custom_css !== undefined && custom_css != '') {
                        custom_css = '<style type="text/css">' + custom_css + '</style>';
                        $('head').append(custom_css);
                    }
                },

                isMobile: function () {
                    "use strict";
                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                        return true;
                    }
                    return false;
                },

                vendor_prefix: function () {

                    var prefix;

                    function prefix() {
                        var styles = window.getComputedStyle(document.documentElement, '');
                        prefix = (Array.prototype.slice.call(styles).join('').match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o']))[1];

                        return prefix;
                    }

                    prefix();

                    return prefix;
                },
            }

            LAE_Frontend.init();

        });

    }(jQuery));

}