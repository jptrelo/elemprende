<?php
/**
 * The sidebar contains homepage category widgets
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-category-list.php on child theme. Happy Customize
 * @package styledstore
 * @return render content just below the featured products
 */

if ( ! is_active_sidebar( 'styledstore-category-section'  ) ) :
	return;
else:
	dynamic_sidebar( 'styledstore-category-section' );
endif;