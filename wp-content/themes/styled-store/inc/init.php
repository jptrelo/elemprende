<?php
/**
 * File Name: init
 * @author StyledThemes 
 * @uses initilize file directory
 * @package styledstore
 * @version @since 1.1.0
*/

/**
 * Define Theme Directory
*/
define( 'styledstore_dir', get_template_directory() );


/*include category dropdown*/
include( styledstore_dir . '/inc/category-dropdown.php' );
//include styledstore recent widgets 
include( styledstore_dir . '/inc/widgets/styledstoreextend-recent-post.php' );
//include styledstore recent post with slider 
include( styledstore_dir . '/inc/widgets/styledstore-slider-recent-post.php' );

//check if woocommerce plugin is active: 
if ( styledstore_check_woocommerce_activation() ) {

	//include custom functios for woocomerce plugin
	include( styledstore_dir . '/woocommerce/wc-additional-function.php' );
	//include styledstore featured products
	include( styledstore_dir . '/woocommerce/widgets/widgets-featuredproducts.php' );
	//include styledstore show child category
	include( styledstore_dir . '/woocommerce/widgets/styledstore-show-child-category.php' );
	//include styledstore category list
	include( styledstore_dir . '/woocommerce/widgets/styledstore-woocommerce-category.php' );
	//include styledstore featured products with no slider
	include( styledstore_dir . '/woocommerce/widgets/styledstore-featured-products-none-slider.php' );
	//include styledstore featured products with no slider
	include( styledstore_dir . '/woocommerce/widgets/class-wc-widget-best-sale-product.php' );
	//include styledstore featured products with no slider
	include( styledstore_dir . '/woocommerce/widgets/class-wc-widget-new-arrival-product.php' );

	//include styledstore showcase widget
	include ( styledstore_dir. '/woocommerce/widgets/widget-styledstore-showcase-product.php' );
  
}

