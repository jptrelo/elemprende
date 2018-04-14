<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$prefix = WP_AAS_META_PREFIX; // Metabox prefix

// Carousel Variables
$width 						= get_post_meta( $post->ID, $prefix.'width', true );
$height 					= get_post_meta( $post->ID, $prefix.'height', true );
$visible_panels 			= get_post_meta( $post->ID, $prefix.'visible_panels', true );
$orientation 				= get_post_meta( $post->ID, $prefix.'orientation', true );
$panel_distance 			= get_post_meta( $post->ID, $prefix.'panel_distance', true );
$max_openedaccordion_size 	= get_post_meta( $post->ID, $prefix.'max_openedaccordion_size', true );
$open_panel_on 				= get_post_meta( $post->ID, $prefix.'open_panel_on', true );
$shadow						= get_post_meta( $post->ID, $prefix.'shadow', true );
$autoplay 					= get_post_meta( $post->ID, $prefix.'autoplay', true );
$mouse_wheel 				= get_post_meta( $post->ID, $prefix.'mouse_wheel', true );
?>

<div class="wp-aas-mb-tabs-wrp">
	<div id="wp-aas-sdetails" class="wp-aas-sdetails wpaas-carousel">
		<table class="form-table wp-aas-sdetails-tbl">
		<h3><?php _e('Choose your Settings for Accordion', 'accordion-and-accordion-slider') ?></h3>
		<hr>	
			<tbody>
				<tr valign="top">					
					<td scope="row">
						<label><?php _e('Width', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
					<input type="number" min="100"  name="<?php echo $prefix; ?>width" value="<?php echo wp_aas_esc_attr($width); ?>"> px<br/>
					<em style="font-size:11px;"><?php _e('Enter width eg. 900','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Height', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="number" min="200" name="<?php echo $prefix; ?>height" value="<?php echo wp_aas_esc_attr($height); ?>"> px<br/>
						<em style="font-size:11px;"><?php _e('Enter height eg. 300','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">					
					<td scope="row">
						<label><?php _e('Visible Accordion Panels', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
					<input type="number" min="1" step="1" name="<?php echo $prefix; ?>visible_panels" value="<?php echo wp_aas_esc_attr($visible_panels); ?>"><br/>
					<em style="font-size:11px;"><?php _e('Enter Visible Accordion Panels at a time. eg. 5','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Orientation', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>orientation" value="horizontal" <?php checked( 'horizontal', $orientation ); ?>> Horizontal
						<input type="radio" name="<?php echo $prefix; ?>orientation" value="vertical" <?php checked( 'vertical', $orientation ); ?>> Vertical<br/>
						<em style="font-size:11px;"><?php _e('Select orientation for accordion','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Space Between Accordion', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="number"  name="<?php echo $prefix; ?>panel_distance" value="<?php echo wp_aas_esc_attr($panel_distance); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Distance between accordion. eg 10','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">					
					<td scope="row">
						<label><?php _e('Max Opened Accordion Size', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>						
						<input type="text"  name="<?php echo $prefix; ?>max_openedaccordion_size" value="<?php echo wp_aas_esc_attr($max_openedaccordion_size); ?>"> <br/>
						<em style="font-size:11px;"><?php _e('Enter opened accordion size eg. 80%','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Open Accordion Panel On', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>open_panel_on" value="hover" <?php checked( 'hover', $open_panel_on ); ?>>Hover
						<input type="radio" name="<?php echo $prefix; ?>open_panel_on" value="click" <?php checked( 'click', $open_panel_on ); ?>>Click<br/>
						<em style="font-size:11px;"><?php _e('Select accordion panel open option','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Shadow', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>shadow" value="true" <?php checked( 'true', $shadow ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>shadow" value="false" <?php checked( 'false', $shadow ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable shadow or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Autoplay', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>autoplay" value="true" <?php checked( 'true', $autoplay ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>autoplay" value="false" <?php checked( 'false', $autoplay ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable autoplay or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Mouse Wheel', 'accordion-and-accordion-slider'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>mouse_wheel" value="true" <?php checked( 'true', $mouse_wheel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>mouse_wheel" value="false" <?php checked( 'false', $mouse_wheel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable mouse wheel or not','accordion-and-accordion-slider'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>