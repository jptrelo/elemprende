<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<div itemscope <?php post_class('clearfix'); ?>>
	<div class="col-sm-6 col-md-6 st-product-image">
		<?php wc_get_template( 'single-product/product-image.php' ); ?>
	</div>
	<div class="col-sm-6 col-md-6 st-product-detail">
			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			// remove woocommerce_template_single_meta hook
			/*
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', '40' ); */
			do_action( 'woocommerce_single_product_summary' );
		?>
		<?php
			/**
			 * Product Description
			 * woocommerce product review
			 *
			 * @hooked woocommerce_output_related_products - 20
			 */
		?>
			<!-- product description -->
			<div class="st-toggle-section st-product-description">
				<h3><?php esc_html_e( 'Description', 'styled-store' ); ?></h3>
				<i class="fa fa-chevron-down" aria-hidden="true"></i>
				<div class="detail-description"><?php the_content(); ?></div>
			</div>

			<!-- product review -->
			<div class="st-toggle-section st-product-description">
				<h3><?php esc_html_e( 'Reviews', 'styled-store' ); ?></h3>
				<i class="fa fa-chevron-down" aria-hidden="true"></i>
				<?php comments_template( 'woocommerce/single-product-reviews' ); ?>
			</div>

		<meta itemprop="url" content="<?php the_permalink(); ?>" />
	</div><!-- .summary -->

</div><!-- #product-<?php the_ID(); ?> -->

<div class="st-woocommerce-related-product">
	<?php
		/**
		* @return upsells products
		* @since @version 1.5.5
		*/
		woocommerce_upsell_display();

		// display related products
		if ( ! function_exists( 'woocommerce_output_related_products' ) ) { 
		    require_once '/includes/wc-template-functions.php'; 
		} 

		$result = woocommerce_output_related_products(); 
	?>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>