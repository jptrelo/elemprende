<?php
/**
 * WooCommerce Additional Widget Functions
 *
 * Extended woocommerce related functions and widget registration.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	WooCommerce/Functions
 */

/**
* @description display archive content of woocoommerce
* @author Styled Themes
* @package StyledStore
* @return archive products
*/
add_action( 'styledstore-woo-archive-content', 'styledstore_woo_archive_content' );

if( ! function_exists( 'styledstore_woo_archive_content' ) ) :

function styledstore_woo_archive_content()  {

	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	if ( !function_exists( 'woocommerce_output_content_wrapper' ) ) { 
	    require_once '/includes/wc-template-functions.php'; 
	} 
	 
	// NOTICE! Understand what this does before running. 
	$result = woocommerce_output_content_wrapper(); ?>


	<div class="catalog_odering clearfix">
		<?php
		/**
		 * woocommerce_before_shop_loop hook.
		 *
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' ); ?>
	</div>

	<?php if ( have_posts() ) : ?>

		<?php woocommerce_product_loop_start(); ?>

			<?php woocommerce_product_subcategories(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

		<?php
			/**
			 * woocommerce_after_shop_loop hook.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		?>

	<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

		<?php wc_get_template( 'loop/no-products-found.php' ); ?>

	<?php endif; ?>

	<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
}
endif;

/**
* @description Customize number of column on shop page
* @author Styled Themes
* @package StyledStore
* @return Number of coloumn on shop page
*/
add_filter( 'loop_shop_columns', 'styledstore_shop_page_column' );
function styledstore_shop_page_column() {

	return 3;
}

/**
* @description Customize title of products
* @author Styled Themes
* @package StyledStore
* @return edit woocommerce title
*/
function woocommerce_template_loop_product_title() {
	
	echo '<h3 class="st-woo-title">' . get_the_title() . '</h3>';
}


/**
* @description Customize the total number of related wooocommerce products
* @author Styled Themes
* @package StyledStore
* @return total number of related products
*/

add_filter( 'woocommerce_output_related_products_args', 'styledstore_related_products_argsS' );

function styledstore_related_products_argsS( $args ) {

	$args['posts_per_page'] = get_theme_mod( 'stwo_number_related_products', '5' );
	$args['rows'] = 1;

	return $args;
}

/**
* @description alter the product loop for featured products on the basis of product id given
* @author Styled Themes
* @package StyledStore
* @return manual products for featued products
*/

if( get_theme_mod( 'stwo_manual_featured_product' ) ) {

	add_filter( 'woocommerce_shortcode_products_query', 'styledstore_woo_static_featured_post', 10, 3 );

	function styledstore_woo_static_featured_post ( $query_args, $atts, $loop_name ) {

		$manual_feat_products_id = get_theme_mod( 'stwo_manual_featured_product' );
		$manual_feat_products_id = explode(',', $manual_feat_products_id );
		if ( $loop_name == 'featured_products' ) { 
			$st_feat_post_id = array( 'post__in' => $manual_feat_products_id );
			$query_args = array_merge ( $query_args,  $st_feat_post_id );
			return $query_args;
		}

	}
}


add_action( 'styledstore-woocommerce-page-description', 'styledstore_show_woo_header_meta' );

if( ! function_exists( 'styledstore_show_woo_header_meta' )) :
/**
 * @author StyledThemes 
 * @action_hook styledstore-woocommerce-page-description
 * @description you can overwrite this setting by copying this function on child theme functions.php
 * @uses woocommerce header meta 
*/
function styledstore_show_woo_header_meta() {
	if( get_theme_mod( 'stwo_hide_header_meta' ) == '' ) {
		if( is_cart() || is_checkout() || is_account_page() ) {
			the_title('<h2 class=" page-title woo-page-title">', '</h2>');
		} else {
			if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
				<h1 class="page-title woo-page-title"><?php woocommerce_page_title(); ?></h1>
			<?php
			endif;
		}

		/**
		 * @hooked woocommerce_breadcrumb - 20
		 */
		if ( !function_exists( 'woocommerce_breadcrumb' ) ) { 
		    require_once '/includes/wc-template-functions.php'; 
		} 
		  
		// The args. 
		$args = array(); 
		  
		// NOTICE! Understand what this does before running. 
		$result = woocommerce_breadcrumb($args);

	
		/**
		 * woocommerce_archive_description hook.
		 *
		 * @hooked woocommerce_taxonomy_archive_description - 10
		 * @hooked woocommerce_product_archive_description - 10
		 */
		do_action( 'woocommerce_archive_description' );
	}		
}
endif;



add_filter( 'woocommerce_sale_flash', 'styledstore_customize_sale_flash',10,3 );

if( !function_exists( 'styledstore_customize_sale_flash' ) ) :

/**
 * @author StyledThemes 
 * @action_hook woocommerce_sale_flash
 * @description you can overwrite this setting by copying this function on child theme functions.php
 * @uses customize the sale flash news
*/

function styledstore_customize_sale_flash( $html_data, $post, $product ) {
	
	$regular_price = $product->regular_price;
	if( ! empty( $regular_price ) ) {
		$price = $product->price;
		$sale_discount_pricing = intval( $regular_price - $price );
		$sale_percentage = intval( ( $regular_price - $price ) / $regular_price * 100 );

		if( get_theme_mod( 'stwo_show_onsale_on_pricing_form' ) ) :

			$sale_pricing_html = '<span class="sale-pricing"><span class="dollar-symbol">$</span>' . $sale_discount_pricing .'</span>';
		else :
			$sale_pricing_html = '<span class="sale-percentage">' . $sale_percentage . '%'.'</span>';
		endif;

	return '<span class="onsale"> ' . $sale_pricing_html . '
				<span class="sale-flash-text">' . __( 'OFF', 'styled-store' ) .' </span>
			</span>';
	} else {
		return;
	}
}
endif;


add_filter( 'get_product_search_form' , 'styledstore_woo_product_searchform' );

/**
 * customiza woocommerce search form
 *author Styled Themes
 * You can customize woocommerce search form by just overwriting this function on child theme. Happy Customize
 * @return woocommerce form
*/
if( !function_exists( 'styledstore_woo_product_searchform') ) :

function styledstore_woo_product_searchform( $form ) {
	
	$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div class="st-search">
			<label class="screen-reader-text" for="s">' . __( 'Search for:', 'styled-store' ) . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search Products', 'styled-store' ) . '" />
			<button class="btn" type="submit" value="'.esc_attr_x( 'Search', 'submit button', 'styled-store' ).'"><i class="fa fa-search"></i> </button>
			<input type="hidden" name="post_type" value="product" />
		</div>
	</form>';
	
	return $form;
	
}
endif;

/**
 * customiza woocommerce search form
 *author Styled Themes
 * You can customize woocommerce search form by just overwriting this function on child theme. Happy Customize
 *@return // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'styledstore_woocommerce_header_add_to_cart_fragment' );

function styledstore_woocommerce_header_add_to_cart_fragment( $fragments ) {

ob_start();
?>
	<a class="st-cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'styled-store' ); ?>"><?php esc_html( 'cart /', 'styled-store' ); ?> <?php echo WC()->cart->get_cart_total(); ?><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i>
		<span class="mini-cart-count-item"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'styled-store' ), WC()->cart->get_cart_contents_count() ); ?>
		</span>
	</a>
<?php

$fragments['a.cart-contents'] = ob_get_clean();
return $fragments;

}

add_filter( 'loop_shop_per_page', 'styledstore_change_number_of_shop_product',20 );
if( ! function_exists( 'styledstore_change_number_of_shop_product' ) ) :
/**
 * customize number of product on shop page
 * @author Styled Themes
 * @return number of product at shop page
 * @since @version 1.5.3
*/
function styledstore_change_number_of_shop_product() {
	$styledstore_number_of_product_at_shop_page = get_theme_mod( 'stwo_number_product_at_shop_page', 10 );
	return $styledstore_number_of_product_at_shop_page;
}
endif;

add_filter( 'post_class', 'styledstore_add_image_flipper_class' );
if( ! function_exists( 'styledstore_add_image_flipper_class' ) ) :
/**
 * Add st-product-flipper-container' on product li
 * @author Styled Themes
 * @return this will add class - 'st-product-flipper-container' on li of shop products
 * @since @version 1.5.4
*/
function styledstore_add_image_flipper_class( $classes ) {
	global $product;
	$post_type = get_post_type( get_the_ID() );

	if ( ! is_admin() ) {

		if ( $post_type == 'product'  && ( is_shop() && get_theme_mod( 'stwo_disable_fipper_image' ) == '' )  ) {

			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids ) {
				$classes[] = 'st-product-flipper-container';
			}
		}

	}
	return $classes;
}
endif;

add_action( 'woocommerce_before_shop_loop_item_title', 'styledstore_add_first_gallery_image_below_the_featured_image' );
if( ! function_exists( 'styledstore_add_first_gallery_image_below_the_featured_image' ) ) :
/**
 * Add st-product-flipper-container' on product li
 * @author Styled Themes
 * @return first image of gallery below the product featured image
 * @since @version 1.5.4
*/
function styledstore_add_first_gallery_image_below_the_featured_image() {
	global $product, $woocommerce;
	if ( get_theme_mod( 'stwo_custom_featured_image' ) )  {
		$size = 'styledstore-pro-woo-custom-thumbnail'; 
	} else {
		$size = 'shop_catalog';
	}

	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids && ( is_shop() && get_theme_mod( 'stwo_disable_fipper_image' ) == '' )  ) {
		$secondary_image_id = $attachment_ids['0'];
		echo wp_get_attachment_image( $secondary_image_id, $size, '', $attr = array( 'class' => 'styledstore-flipper-image attachment-shop-catalog' ) );
	}

}
endif;