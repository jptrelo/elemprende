<?php
/**
 * WCFM plugin views
 *
 * Plugin Custom Field Products Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfm/views
 * @version   2.3.7
 */
global $wp, $WCFM;

$product_id = 0;

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
}

?>

<!-- Start Product Custom Fields -->
<?php
$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
$wcfm_product_types = apply_filters( 'wcfm_product_types', array('simple' => __('Simple Product', 'wc-frontend-manager'), 'variable' => __('Variable Product', 'wc-frontend-manager'), 'grouped' => __('Grouped Product', 'wc-frontend-manager'), 'external' => __('External/Affiliate Product', 'wc-frontend-manager') ) );
$wpcf_icons = array( 'snowflake-o', 'ravelry', 'eercast', 'bullseye', 'cloud', 'certificate', 'crosshairs');
if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
	foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
		if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
		$block_name = !empty( $wcfm_product_custom_field['block_name'] ) ? $wcfm_product_custom_field['block_name'] : '';
		if( !$block_name ) continue;
		$exclude_product_types = isset( $wcfm_product_custom_field['exclude_product_types'] ) ? $wcfm_product_custom_field['exclude_product_types'] : array();
		$allowed_product_types = array_diff_key( $wcfm_product_types, array_flip( $exclude_product_types ) );
		$allowed_product_type_classes = implode( " ", array_keys( $allowed_product_types ) );
		$exclude_product_type_classes = "non-" . implode( " non-", $exclude_product_types );
		?>
		<div class="page_collapsible products_manage_<?php echo sanitize_title( $wcfm_product_custom_field['block_name'] ); ?> <?php echo $allowed_product_type_classes; ?> <?php echo $exclude_product_type_classes; ?>" id="wcfm_products_manage_form_<?php echo sanitize_title( $wcfm_product_custom_field['block_name'] ); ?>_head"><label class="fa fa-<?php echo ($wpcf_icons[$wpcf_index]) ? $wpcf_icons[$wpcf_index] : 'snowflake-o'; ?>"></label><?php _e( $wcfm_product_custom_field['block_name'], 'wc-frontend-manager'); ?><span></span></div>
		<div class="wcfm-container <?php echo $allowed_product_type_classes; ?> <?php echo $exclude_product_type_classes; ?>">
			<div id="wcfm_products_manage_form_<?php echo sanitize_title( $wcfm_product_custom_field['block_name'] ); ?>_expander" class="wcfm-content">
			  <h2><?php _e( $wcfm_product_custom_field['block_name'], 'wc-frontend-manager'); ?></h2>
				<div class="wcfm_clearfix"></div>
				<?php
				$is_group = !empty( $wcfm_product_custom_field['group_name'] ) ? 'yes' : 'no';
				$is_group = !empty( $wcfm_product_custom_field['is_group'] ) ? 'yes' : 'no';
				$group_name = $wcfm_product_custom_field['group_name'];
				$group_value = array();
				if( $product_id ) {
					$group_value = (array) get_post_meta( $product_id, $group_name, true );		
					$group_value = apply_filters( 'wcfm_custom_field_group_data_value', $group_value, $group_name );
				}
				$wcfm_product_custom_block_fields = $wcfm_product_custom_field['wcfm_product_custom_block_fields'];
				if( !empty( $wcfm_product_custom_block_fields ) ) {
					foreach( $wcfm_product_custom_block_fields as $wcfm_product_custom_block_field ) {
						if( !$wcfm_product_custom_block_field['name'] ) continue;
						$field_value = '';
						$field_name = $wcfm_product_custom_block_field['name'];
						if( $is_group == 'yes' ) {
							$field_name = $group_name . '[' . $wcfm_product_custom_block_field['name'] . ']';
							if( $product_id ) {
								if( $wcfm_product_custom_block_field['type'] == 'checkbox' ) {
									$field_value = isset( $group_value[$wcfm_product_custom_block_field['name']] ) ? 'yes' : 'no';
								} else {
									if( isset( $group_value[$wcfm_product_custom_block_field['name']] )) {
										$field_value = $group_value[$wcfm_product_custom_block_field['name']];
									}
								}
							}
						} else {
							if( $product_id ) {
								if( $wcfm_product_custom_block_field['type'] == 'checkbox' ) {
									$field_value = get_post_meta( $product_id, $field_name, true ) ? get_post_meta( $product_id, $field_name, true ) : 'no';
								} else {
									$field_value = get_post_meta( $product_id, $field_name, true );
								}
							}
						}
						
						// Is Required
						$custom_attributes = array();
						if( isset( $wcfm_product_custom_block_field['required'] ) && $wcfm_product_custom_block_field['required'] ) $custom_attributes = array( 'required' => 1 );
				  		
						switch( $wcfm_product_custom_block_field['type'] ) {
							case 'text':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'number':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager'), 'attributes' => array( 'min' => '', 'step'=> '0.1' ) ) ) );
							break;
							
							case 'textarea':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'editor':
								$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
								$wpeditor = apply_filters( 'wcfm_is_allow_product_wpeditor', 'wpeditor' );
								if( $wpeditor && $rich_editor ) {
									$rich_editor = 'wcfm_wpeditor';
								} else {
									$wpeditor = 'textarea';
								}
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking wcfm_custom_field_editor ' . $rich_editor, 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'datepicker':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele wcfm_datepicker simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'timepicker':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'checkbox':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'upload':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'select':
								$select_opt_vals = array();
								$select_options = explode( '|', $wcfm_product_custom_block_field['options'] );
								$is_first = true;
								if( !empty ( $select_options ) ) {
									foreach( $select_options as $select_option ) {
										if( $select_option ) {
											$select_opt_vals[$select_option] = __( ucfirst( str_replace( "-", " " , $select_option ) ), 'wc-frontend-manager' );
										} elseif( $is_first ) {
											$select_opt_vals[''] = __( "-Select-", "wc-frontend-manager" );
										}
										$is_first = false;
									}
								}
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_product_custom_block_field['name'] => array( 'label' => __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'select', 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __( $wcfm_product_custom_block_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
						}
					}
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>
<!-- End Product Custom Fields -->