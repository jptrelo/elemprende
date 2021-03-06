<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
} else {
	global $qtyloop;
	$qtyloop ++;
	?>

	<div class="quantity">
		<button onclick="var result = document.getElementById('qty<?php echo esc_html($qtyloop) ;?>'); var qty = result.value; if( !isNaN( qty ) && qty > 1 ) { result.value--; } else{ result.value = 1;} jQuery('#qty<?php echo esc_html($qtyloop) ;?>').trigger('change'); return false;" class="reduced items-count" type="button"><i class="fa fa-minus">&nbsp;</i></button>
		<input type="text" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'sparklestore' ) ?>" class="input-text qty text sparklestore-qty-input" size="4" pattern="<?php echo esc_attr( $pattern ); ?>" inputmode="<?php echo esc_attr( $inputmode ); ?>" aria-labelledby="<?php echo ! empty( $args['product_name'] ) ? sprintf( esc_attr__( '%s quantity', 'sparklestore' ), $args['product_name'] ) : ''; ?>"  id="qty<?php echo esc_html($qtyloop) ;?>" />
		<button onclick="var result = document.getElementById('qty<?php echo esc_html($qtyloop) ;?>'); var qty = result.value; if( !isNaN( qty )) { result.value++;} else { result.value = 1 } jQuery('#qty<?php echo esc_html($qtyloop) ;?>').trigger('change'); return false;" class="increase items-count" type="button"><i class="fa fa-plus">&nbsp;</i></button>
	</div>
	<?php
}