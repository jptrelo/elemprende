<?php
/**
 * The sidebar contains featured sidebar content
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-footer-top.php on child theme. Happy Customize
 * @package styledstore
 * @return render content just below the slider.
 */

if ( ! is_active_sidebar( 'styledstore-homepage-featured'  ) ) :
	return;
else:
	dynamic_sidebar( 'styledstore-homepage-featured' );
endif;

