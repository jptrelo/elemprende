/**
 * Base js functions
 */

jQuery(document).ready(function(){
    var $container = jQuery('.masonry-container');

    var gutter = 30;
    var min_width = 200;
    $container.imagesLoaded( function(){
        $container.masonry({
            itemSelector : '.box',
            gutterWidth: gutter,
            isAnimated: true,
              columnWidth: function( containerWidth ) {
                var box_width = (((containerWidth - 2*gutter)/3) | 0) ;

                if (box_width < min_width) {
                    box_width = (((containerWidth - gutter)/2) | 0);
                }

                if (box_width < min_width) {
                    box_width = containerWidth;
                }

                jQuery('.box').width(box_width);

                return box_width;
              }
        });
    });
});
