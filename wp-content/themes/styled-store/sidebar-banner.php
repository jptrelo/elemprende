<?php
/**
 * The sidebar contains banner and render its content just below the header areas
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-banner.php on child theme. Happy Customize
 * @package styledstore
 * @return render content just below category section
 */

if ( ! is_active_sidebar( 'styled_store_banner'  ) ) :

	return;
else :

	dynamic_sidebar( 'styled_store_banner' );

endif; 