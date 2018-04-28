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

$gallery_imgs 	= get_post_meta( $post->ID, $prefix.'gallery_id', true );
$no_img_cls		= !empty($gallery_imgs) ? 'wp-aas-hide' : '';
?>

<table class="form-table wp-aas-post-sett-table">
	<tbody>
		<tr valign="top">
			<th scope="row">
				<label for="wp-aas-gallery-imgs"><?php _e('Choose Gallery Images', 'accordion-and-accordion-slider'); ?></label>
			</th>
			<td>
				<button type="button" class="button button-secondary wp-aas-img-uploader" id="wp-aas-gallery-imgs" data-multiple="true" data-button-text="<?php _e('Add to Gallery', 'accordion-and-accordion-slider'); ?>" data-title="<?php _e('Add Images to Gallery', 'accordion-and-accordion-slider'); ?>"><i class="dashicons dashicons-format-gallery"></i> <?php _e('Gallery Images', 'accordion-and-accordion-slider'); ?></button>
				<button type="button" class="button button-secondary wp-aas-del-gallery-imgs"><i class="dashicons dashicons-trash"></i> <?php _e('Remove Gallery Images', 'accordion-and-accordion-slider'); ?></button><br/>
				
				<div class="wp-aas-gallery-imgs-prev wp-aas-imgs-preview wp-aas-gallery-imgs-wrp">
					<?php if( !empty($gallery_imgs) ) {
						foreach ($gallery_imgs as $img_key => $img_data) {

							$attachment_url 		= wp_get_attachment_thumb_url( $img_data );
							$attachment_edit_link	= get_edit_post_link( $img_data );
					?>
							<div class="wp-aas-img-wrp">
								<div class="wp-aas-img-tools wp-aas-hide">
									<span class="wp-aas-tool-icon wp-aas-edit-img dashicons dashicons-edit" title="<?php _e('Edit Image in Popup', 'accordion-and-accordion-slider'); ?>"></span>
									<a href="<?php echo $attachment_edit_link; ?>" target="_blank" title="<?php _e('Edit Image', 'accordion-and-accordion-slider'); ?>"><span class="wp-aas-tool-icon wp-aas-edit-attachment dashicons dashicons-visibility"></span></a>
									<span class="wp-aas-tool-icon wp-aas-del-tool wp-aas-del-img dashicons dashicons-no" title="<?php _e('Remove Image', 'accordion-and-accordion-slider'); ?>"></span>
								</div>
								<img class="wp-aas-img" src="<?php echo $attachment_url; ?>" alt="" />
								<input type="hidden" class="wp-aas-attachment-no" name="wp_aas_img[]" value="<?php echo $img_data; ?>" />
							</div><!-- end .wp-aas-img-wrp -->
					<?php }
					} ?>
					
					<p class="wp-aas-img-placeholder <?php echo $no_img_cls; ?>"><?php _e('No Gallery Images', 'accordion-and-accordion-slider'); ?></p>

				</div><!-- end .wp-aas-imgs-preview -->
				<span class="description"><?php _e('Choose your desired images for gallery. Hold Ctrl key to select multiple images at a time.', 'accordion-and-accordion-slider'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wtwp-tstmnl-table -->