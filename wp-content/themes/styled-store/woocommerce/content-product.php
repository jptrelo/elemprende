<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php post_class('liHeight'); ?>>
	<?php
	

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 * @overlay on hover (product-overlay)
	 */
	echo '<div class="styledstore-woocomerce-product-thumb" style="position: relative"><div class="product-overlay"></div>';
		do_action( 'woocommerce_before_shop_loop_item_title' );
	    echo '<div class="add-to-cart-button"><div class="styledstore-product-buttons">';
			// add to cart button
			do_action('woocommerce_after_shop_loop_item');
		   // yith wish list
		   if (function_exists('YITH_WCWL')) {
                $url = add_query_arg('add_to_wishlist', $product->get_id() );
                ?>
                <a class="item-wishlist button" href="<?php echo esc_url( $url ); ?>"><i class="fa fa-heart"></i></a>
                <?php
            }
	    echo '</div></div>';
	echo '</div>';

	/**
        * woocommerce_before_shop_loop_item hook.
        *
        * @hooked woocommerce_template_loop_product_link_open - 10
    */
    do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
        * @hooked woocommerce_template_loop_product_link_close
    */
    if ( !function_exists( 'woocommerce_template_loop_product_link_close' ) ) { 
        require_once '/includes/wc-template-functions.php'; 
    } 
    $args = array(); 
    $result = woocommerce_template_loop_product_link_close($args);

	/**
	 * @description This renders short description of products
	 * @author Styled Themes
	 * @package StyledStore
	 */
	echo '<div class="short-description">';
		echo wp_trim_words ( apply_filters( 'woocommerce_short_description', $post->post_excerpt ) , get_theme_mod( 'stwo_excerpt_lenth', 3), '' ) ; 
	echo '</div>';

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

 
   ?>
	
</li>
