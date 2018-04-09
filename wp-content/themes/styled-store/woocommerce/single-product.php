<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<div class="entry-header">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-12">
			<?php
			/**
			 * styledstore-woocommerce-page-description hook.
			 *
			 * @hooked styledstore_show_woo_header_meta() - 
			 * @show the header meta information
			 */
			do_action( 'styledstore-woocommerce-page-description' );

			?>
			</div>
		</div>
	</div>
</div>
<div id="product-<?php the_ID(); ?>" class="st-woocommerce-single-product">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<?php
					/**
					 * woocommerce_before_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					if ( !function_exists( 'woocommerce_output_content_wrapper' ) ) { 
					    require_once '/includes/wc-template-functions.php'; 
					} 
					$result = woocommerce_output_content_wrapper();
				?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'single-product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php
					/**
					 * woocommerce_after_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );
				?>
			</div>
		</div>
	</div>
</div>
<?php get_footer( 'shop' ); ?>
