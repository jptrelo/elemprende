<?php
/**
	* The Template for displaying product archives, including the main shop page which is a post type archive
	*
	* This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
	*
	* HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
	* will need to copy the new files to your theme to maintain compatibility. We try to do this.
	* as little as possible, but it does happen. When this occurs the version of the template file will.
	* be bumped and the readme will list any important changes.
	*
	* @see 	    http://docs.woothemes.com/document/template-structure/
	* @author 		WooThemes
	* @package 	WooCommerce/Templates
	* @version  3.5.0
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
<div class="st_product_listing">
	<div class="container">
		<div class="row">
			<?php $shop_layout = get_theme_mod( 'stwo_shop_layout', 'shopright' ); 
			switch( $shop_layout ) {
				case 'shopright' : ?>

					<div class="col-md-9 col-sm-8 col-xs-12">
						<?php do_action( 'styledstore-woo-archive-content' ); ?>
					</div>

					<div class="col-md-3 col-sm-4 col-xs-12 clearfix" id="st-woo-sidebar">
						<?php
						/**
						 * woocommerce_sidebar hook.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						do_action( 'woocommerce_sidebar' ); ?>
					</div>
					
				<?php break;
				case 'shopleft': ?>
					<div class="col-md-3 col-sm-4 col-xs-12 clearfix" id="st-woo-sidebar">
						<?php
						/**
						 * woocommerce_sidebar hook.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						do_action( 'woocommerce_sidebar' ); ?>
					</div>
					<div class="col-md-9 col-sm-8 col-xs-12">
						<?php do_action( 'styledstore-woo-archive-content' ); ?>
					</div>

				<?php break;
				case 'shopwide' : ?>

					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php do_action( 'styledstore-woo-archive-content' ); ?>
					</div>
				<?php break;

				default: ?>

					<div class="col-md-9 col-sm-8 col-xs-12">
						<?php do_action( 'styledstore-woo-archive-content' ); ?>
					</div>
					<div class="col-md-3 col-sm-4 col-xs-12 clearfix" id="st-woo-sidebar">
						<?php
							/**
							 * woocommerce_sidebar hook.
							 *
							 * @hooked woocommerce_get_sidebar - 10
							 */
							do_action( 'woocommerce_sidebar' );
						?>
					</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php get_footer( 'shop' ); ?>
