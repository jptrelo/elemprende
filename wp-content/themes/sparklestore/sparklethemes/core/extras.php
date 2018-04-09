<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Sparkle_Store
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sparklestore_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}


	if(is_singular(array( 'post','page' ))){
		global $post;
		$post_sidebar = esc_attr( get_post_meta($post->ID, 'sparklestore_page_layouts', true) );
		if(!$post_sidebar){
			$post_sidebar = 'rightsidebar';
		}
		$classes[] = $post_sidebar;
	}

  	if( is_home() || is_search() || is_category() || is_tag() || is_attachment() ){
		$classes[] = 'rightsidebar';
	}

	if ( sparklestore_is_woocommerce_activated() ) {
	    
	    if( is_product_category() || is_shop() ) {
	        $woo_page_layout = esc_attr( get_theme_mod( 'sparklestore_woocommerce_products_page_layout','rightsidebar' ) );
	        if(!$woo_page_layout){
	            $woo_page_layout = 'rightsidebar';
	        }
	        $classes[] = $woo_page_layout;
	    }

	    if( is_singular('product') ) {
	        $woo_page_layout = esc_attr( get_theme_mod( 'sparklestore_woocommerce_single_products_page_layout','rightsidebar' ) );
	        if(!$woo_page_layout){
	            $woo_page_layout = 'rightsidebar';
	        }
	        $classes[] = $woo_page_layout;
	    }

        $classes[] = 'woocommerce';
	}

	$web_layout = get_theme_mod( 'sparklestore_web_page_layout_options', 'disable' );
	if($web_layout == 'enable'){
		$classes[] = 'boxed';
	}else{
		$classes[] = 'fulllayout';
	}

	return $classes;
}
add_filter( 'body_class', 'sparklestore_body_classes' );