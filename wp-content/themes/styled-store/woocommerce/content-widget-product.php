<?php
/**
 * The template for displaying product widget entries
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product; ?>

<li class="product-list-height">
	<div class="st_product_image col-xs-4">
		<?php echo $product->get_image(array(80, 100)); ?>
	</div>

	<div class="st_product_desc col-xs-8">
		<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
			<span class="product-title"><?php echo $product->get_title(); ?></span>
		</a>

		<?php echo get_the_term_list( $product->get_id(), 'product_cat',  '<span class="top-rated-woo-cateogry-list">', ' ', '</span>' ); ?>

		<?php if ( ! empty( $show_rating ) ) : ?>
			<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
		<?php endif; ?>
		<div class="st_product_price">
			<?php echo $product->get_price_html(); ?>
		</div>
	</div>
</li>
