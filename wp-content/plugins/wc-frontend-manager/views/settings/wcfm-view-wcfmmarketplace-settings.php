<?php
/**
 * WCFM plugin view
 *
 * WCFM Marketplace Settings View
 *
 * @author 		WC Lovers
 * @package 	wcfm/view
 * @version   5.0.0
 */

global $WCFM, $WCFMmp;

$wcfm_is_allow_manage_settings = apply_filters( 'wcfm_is_allow_manage_settings', true );
if( !$wcfm_is_allow_manage_settings ) {
	wcfm_restriction_message_show( "Settings" );
	return;
}

$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );

$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );

$the_user = get_user_by( 'id', $user_id );
$user_email = $the_user->user_email;

// Store Genral
$gravatar       = isset( $vendor_data['gravatar'] ) ? absint( $vendor_data['gravatar'] ) : 0;
$banner_type    = isset( $vendor_data['banner_type'] ) ? $vendor_data['banner_type'] : 'single_img';
$list_banner    = isset( $vendor_data['list_banner'] ) ? absint( $vendor_data['list_banner'] ) : 0;
$banner         = isset( $vendor_data['banner'] ) ? absint( $vendor_data['banner'] ) : 0;
$banner_video   = isset( $vendor_data['banner_video'] ) ? $vendor_data['banner_video'] : '';
$banner_slider  = isset( $vendor_data['banner_slider'] ) ? $vendor_data['banner_slider'] : array();
$store_name     = isset( $vendor_data['store_name'] ) ? esc_attr( $vendor_data['store_name'] ) : '';
$store_name     = empty( $store_name ) ? $the_user->display_name : $store_name;
$store_slug     = $the_user->user_nicename;
$phone          = isset( $vendor_data['phone'] ) ? esc_attr( $vendor_data['phone'] ) : '';

// Store Description
$shop_description = get_user_meta( $user_id, '_store_description', true );

$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
if( $wpeditor && $rich_editor ) {
	$rich_editor = 'wcfm_wpeditor';
} else {
	$wpeditor = 'textarea';
}
if( !$rich_editor ) {
	$breaks = array("<br />","<br>","<br/>"); 
	
	$shop_description = str_ireplace( $breaks, "\r\n", $shop_description );
	$shop_description = strip_tags( $shop_description );
}

// Address
$address         = isset( $vendor_data['address'] ) ? $vendor_data['address'] : '';
$street_1 = isset( $vendor_data['address']['street_1'] ) ? $vendor_data['address']['street_1'] : '';
$street_2 = isset( $vendor_data['address']['street_2'] ) ? $vendor_data['address']['street_2'] : '';
$city    = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
$zip     = isset( $vendor_data['address']['zip'] ) ? $vendor_data['address']['zip'] : '';
$country = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
$state   = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';

// Location
$store_location   = isset( $vendor_data['store_location'] ) ? esc_attr( $vendor_data['store_location'] ) : '';
$map_address    = isset( $vendor_data['find_address'] ) ? esc_attr( $vendor_data['find_address'] ) : '';
$store_lat    = isset( $vendor_data['store_lat'] ) ? esc_attr( $vendor_data['store_lat'] ) : 0;
$store_lng    = isset( $vendor_data['store_lng'] ) ? esc_attr( $vendor_data['store_lng'] ) : 0;

// Country -> States
$country_obj   = new WC_Countries();
$countries     = $country_obj->countries;
$states        = $country_obj->states;
$state_options = array();
if( $state && isset( $states[$country] ) && is_array( $states[$country] ) ) {
	$state_options = $states[$country];
}
if( $state ) $state_options[$state] = $state;

// Gravatar image
$gravatar_url = $gravatar ? wp_get_attachment_url( $gravatar ) : '';

// List Banner URL
$list_banner_url = $list_banner ? wp_get_attachment_url( $list_banner ) : '';

// Banner URL
$banner_url = $banner ? wp_get_attachment_url( $banner ) : '';

// Visiblity
$global_store_name_position = isset( $WCFMmp->wcfmmp_marketplace_options['store_name_position'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_name_position'] : 'on_banner';
$store_name_position = isset( $vendor_data['store_name_position'] ) ? esc_attr( $vendor_data['store_name_position'] ) : $global_store_name_position;
$global_store_ppp = isset( $WCFMmp->wcfmmp_marketplace_options['store_ppp'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_ppp'] : get_option('posts_per_page');
$store_ppp = isset( $vendor_data['store_ppp'] ) ? absint( $vendor_data['store_ppp'] ) : $global_store_ppp;
$store_hide_email = isset( $vendor_data['store_hide_email'] ) ? esc_attr( $vendor_data['store_hide_email'] ) : 'no';
$store_hide_phone = isset( $vendor_data['store_hide_phone'] ) ? esc_attr( $vendor_data['store_hide_phone'] ) : 'no';
$store_hide_address = isset( $vendor_data['store_hide_address'] ) ? esc_attr( $vendor_data['store_hide_address'] ) : 'no';

// Payment
$payment_mode = isset( $vendor_data['payment']['method'] ) ? esc_attr( $vendor_data['payment']['method'] ) : '' ;
if( isset($_GET['code']) ) {
	$payment_mode = 'stripe';
}
$paypal = isset( $vendor_data['payment']['paypal']['email'] ) ? esc_attr( $vendor_data['payment']['paypal']['email'] ) : '' ;
$skrill = isset( $vendor_data['payment']['skrill']['email'] ) ? esc_attr( $vendor_data['payment']['skrill']['email'] ) : '' ;
$ac_name   = isset( $vendor_data['payment']['bank']['ac_name'] ) ? esc_attr( $vendor_data['payment']['bank']['ac_name'] ) : '';
$ac_number = isset( $vendor_data['payment']['bank']['ac_number'] ) ? esc_attr( $vendor_data['payment']['bank']['ac_number'] ) : '';
$bank_name      = isset( $vendor_data['payment']['bank']['bank_name'] ) ? esc_attr( $vendor_data['payment']['bank']['bank_name'] ) : '';
$bank_addr      = isset( $vendor_data['payment']['bank']['bank_addr'] ) ? esc_textarea( $vendor_data['payment']['bank']['bank_addr'] ) : '';
$routing_number = isset( $vendor_data['payment']['bank']['routing_number'] ) ? esc_attr( $vendor_data['payment']['bank']['routing_number'] ) : '';
$iban           = isset( $vendor_data['payment']['bank']['iban'] ) ? esc_attr( $vendor_data['payment']['bank']['iban'] ) : '';
$swift     = isset( $vendor_data['payment']['bank']['swift'] ) ? esc_attr( $vendor_data['payment']['bank']['swift'] ) : '';
$ifsc     = isset( $vendor_data['payment']['bank']['ifsc'] ) ? esc_attr( $vendor_data['payment']['bank']['ifsc'] ) : '';

// SEO
$wcfmmp_seo_meta_title = isset( $vendor_data['store_seo']['wcfmmp-seo-meta-title'] ) ? $vendor_data['store_seo']['wcfmmp-seo-meta-title'] : '';
$wcfmmp_seo_meta_desc = isset( $vendor_data['store_seo']['wcfmmp-seo-meta-desc'] ) ? $vendor_data['store_seo']['wcfmmp-seo-meta-desc'] : '';
$wcfmmp_seo_meta_keywords    = isset( $vendor_data['store_seo']['wcfmmp-seo-meta-keywords'] ) ? $vendor_data['store_seo']['wcfmmp-seo-meta-keywords'] : '';
$wcfmmp_seo_og_title     = isset( $vendor_data['store_seo']['wcfmmp-seo-og-title'] ) ? $vendor_data['store_seo']['wcfmmp-seo-og-title'] : '';
$wcfmmp_seo_og_desc = isset( $vendor_data['store_seo']['wcfmmp-seo-og-desc'] ) ? $vendor_data['store_seo']['wcfmmp-seo-og-desc'] : '';
$wcfmmp_seo_og_image   = isset( $vendor_data['store_seo']['wcfmmp-seo-og-image'] ) ? $vendor_data['store_seo']['wcfmmp-seo-og-image'] : 0;
$wcfmmp_seo_twitter_title     = isset( $vendor_data['store_seo']['wcfmmp-seo-twitter-title'] ) ? $vendor_data['store_seo']['wcfmmp-seo-twitter-title'] : '';
$wcfmmp_seo_twitter_desc = isset( $vendor_data['store_seo']['wcfmmp-seo-twitter-desc'] ) ? $vendor_data['store_seo']['wcfmmp-seo-twitter-desc'] : '';
$wcfmmp_seo_twitter_image   = isset( $vendor_data['store_seo']['wcfmmp-seo-twitter-image'] ) ? $vendor_data['store_seo']['wcfmmp-seo-twitter-image'] : 0;

// Facebook image
$wcfmmp_seo_og_image_url = $wcfmmp_seo_og_image ? wp_get_attachment_thumb_url( $wcfmmp_seo_og_image ) : '';

// Twitter URL
$wcfmmp_seo_twitter_image_url = $wcfmmp_seo_twitter_image ? wp_get_attachment_thumb_url( $wcfmmp_seo_twitter_image ) : '';

// Customer Support
$vendor_customer_phone = isset( $vendor_data['customer_support']['phone'] ) ? $vendor_data['customer_support']['phone'] : '';
$vendor_customer_email = isset( $vendor_data['customer_support']['email'] ) ? $vendor_data['customer_support']['email'] : '';
$vendor_csd_return_address1 = isset( $vendor_data['customer_support']['address1'] ) ? $vendor_data['customer_support']['address1'] : '';
$vendor_csd_return_address2 = isset( $vendor_data['customer_support']['address2'] ) ? $vendor_data['customer_support']['address2'] : '';
$vendor_csd_return_country = isset( $vendor_data['customer_support']['country'] ) ? $vendor_data['customer_support']['country'] : '';
$vendor_csd_return_city = isset( $vendor_data['customer_support']['city'] ) ? $vendor_data['customer_support']['city'] : '';
$vendor_csd_return_state = isset( $vendor_data['customer_support']['state'] ) ? $vendor_data['customer_support']['state'] : '';
$vendor_csd_return_zip = isset( $vendor_data['customer_support']['zip'] ) ? $vendor_data['customer_support']['zip'] : '';

// Vacation Mode
$wcfm_vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
$wcfm_disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
$wcfm_vacation_mode_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';

$store_banner_width = isset( $WCFMmp->wcfmmp_marketplace_options['store_banner_width'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_banner_width'] : '1650';
$store_banner_height = isset( $WCFMmp->wcfmmp_marketplace_options['store_banner_height'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_banner_height'] : '350';
$store_banner_mwidth = isset( $WCFMmp->wcfmmp_marketplace_options['store_banner_mwidth'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_banner_mwidth'] : '520';
$store_banner_mheight = isset( $WCFMmp->wcfmmp_marketplace_options['store_banner_mheight'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_banner_mheight'] : '150';

$banner_help_text = sprintf(
		__('Upload a banner for your store. Banner size is (%sx%s) pixels.', 'wc-frontend-manager' ),
		$store_banner_width, $store_banner_height
);

$is_marketplace = wcfm_is_marketplace();
?>

<div class="collapse wcfm-collapse" id="">
  <div class="wcfm-page-headig">
		<span class="fa fa-cogs"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Settings', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	  	<h2><?php _e('Store Settings', 'wc-frontend-manager' ); ?></h2>
	  	
	  	<?php 
	  	do_action( 'wcfm_vendor_setting_header_before', $user_id );
			if( apply_filters( 'wcfm_is_allow_social_profile', true ) ) {
				echo '<a id="wcfm_social_settings" class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_profile_url().'#wcfm_profile_form_social_head" data-tip="' . __( 'Social', 'wc-frontend-manager' ) . '"><span class="fa fa-users"></span><span class="text">' . __( 'Social', 'wc-frontend-manager' ) . '</span></a>';
			}
			do_action( 'wcfm_vendor_setting_header_after', $user_id );
			?>
	  	<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
		
	  <?php do_action( 'before_wcfm_marketplace_settings', $user_id ); ?>
		
	  <form id="wcfm_settings_form" class="wcfm">
	
			<?php do_action( 'begin_wcfm_marketplace_settings_form', $user_id ); ?>
			
			<div class="wcfm-tabWrap">
				<!-- collapsible -->
				<div class="page_collapsible" id="wcfm_settings_dashboard_head">
					<label class="fa fa-shopping-bag"></label>
					<?php _e('Store', 'wc-frontend-manager'); ?><span></span>
				</div>
				<div class="wcfm-container wcfm_marketplace_store_settings">
					<div id="wcfm_settings_form_store_expander" class="wcfm-content">
						<?php
							$settings_fields_general = apply_filters( 'wcfm_marketplace_settings_fields_general', array(
																																																"gravatar" => array('label' => __('Store Logo', 'wc-frontend-manager') , 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'prwidth' => 150, 'value' => $gravatar_url ),
																																																"list_banner" => array('label' => __('Store List Banner', 'wc-frontend-manager') , 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele wcfm-banner-uploads', 'label_class' => 'wcfm_title', 'prwidth' => 250, 'value' => $list_banner_url, 'hints' => __( 'This Banner will be visible at Store List Page.', 'wc-frontend-manager' ) ),
																																																"banner_type" => array('label' => __('Store Banner Type', 'wc-frontend-manager') , 'type' => 'select', 'options' => array( 'single_img' => __( 'Static Image', 'wc-frontend-manager' ), 'slider' => __( 'Slider', 'wc-frontend-manager' ), 'video' => __( 'Video', 'wc-frontend-manager' ) ), 'class' => 'wcfm-select wcfm_ele wcfm-banner-uploads', 'label_class' => 'wcfm_title', 'value' => $banner_type ),
																																																"banner" => array('label' => __('Store Banner', 'wc-frontend-manager') , 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele banner_type_upload banner_type_field banner_type_single_img wcfm-banner-uploads', 'label_class' => 'wcfm_title banner_type_field banner_type_single_img', 'prwidth' => 250, 'value' => $banner_url, 'hints' => $banner_help_text ),
																																																"banner_video" => array('label' => __('Video Banner', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele banner_type_field banner_type_video', 'label_class' => 'wcfm_title banner_type_field banner_type_video','value' => $banner_video, 'hints' => __( 'Insert YouTube video URL.', 'wc-frontend-manager' ) ),
																																																"banner_slider"  => array( 'label' => __('Slider', 'wc-frontend-manager'), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele banner_type_upload banner_type_field banner_type_slider wcfm_non_sortable', 'label_class' => 'wcfm_title banner_type_field banner_type_slider', 'value' => $banner_slider, 'hints' => $banner_help_text, 'options' => array(
																																																																									"image" => array( 'type' => 'upload', 'class' => 'wcfm_gallery_upload banner_type_upload wcfm-banner-uploads', 'prwidth' => 75),
																																																																								) ),
																																																"slider_break" => array( 'type' => 'html', 'value' => '<div class="wcfm_clearfix"></div>' ),
																																																"store_name" => array('label' => __('Store Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'custom_attributes' => array( 'required' => true ), 'value' => $store_name ),
																																																"store_slug" => array('label' => __('Store Slug', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'custom_attributes' => array( 'required' => true ), 'value' => $store_slug ),
																																																"phone" => array('label' => __('Store Phone', 'wc-frontend-manager') , 'type' => 'text', 'placeholder' => '+123456..', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $phone ),
																																																"shop_description" => array('label' => __('Shop Description', 'wc-frontend-manager') , 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title', 'value' => $shop_description, 'hints' => __( 'This is displayed on your shop page.', 'wc-frontend-manager' ) ),
																																																), $user_id );
							
							if( !apply_filters( 'wcfm_is_allow_store_logo', true ) ) {
								if( isset( $settings_fields_general['gravatar'] ) ) { unset( $settings_fields_general['gravatar'] ); }
							}
							
							if( !apply_filters( 'wcfm_is_allow_store_name', true ) ) {
								if( isset( $settings_fields_general['store_name'] ) ) { unset( $settings_fields_general['store_name'] ); }
								if( isset( $settings_fields_general['store_slug'] ) ) { unset( $settings_fields_general['store_slug'] ); }
							}
							
							if( !apply_filters( 'wcfm_is_allow_store_banner', true ) ) {
								if( isset( $settings_fields_general['list_banner'] ) ) { unset( $settings_fields_general['list_banner'] ); }
								if( isset( $settings_fields_general['banner_type'] ) ) { unset( $settings_fields_general['banner_type'] ); }
								if( isset( $settings_fields_general['banner'] ) ) { unset( $settings_fields_general['banner'] ); }
								if( isset( $settings_fields_general['banner_video'] ) ) { unset( $settings_fields_general['banner_video'] ); }
								if( isset( $settings_fields_general['banner_slider'] ) ) { unset( $settings_fields_general['banner_slider'] ); }
							}
							
							if( !apply_filters( 'wcfm_is_allow_store_phone', true ) ) {
								if( isset( $settings_fields_general['phone'] ) ) { unset( $settings_fields_general['phone'] ); }
							}
							
							if( !apply_filters( 'wcfm_is_allow_store_description', true ) ) {
								if( isset( $settings_fields_general['shop_description'] ) ) { unset( $settings_fields_general['shop_description'] ); }
							}
										
							$WCFM->wcfm_fields->wcfm_generate_form_field( $settings_fields_general );	
							
							if( apply_filters( 'wcfm_is_allow_store_address', true ) ) {
						  ?>
						
							<div class="wcfm_clearfix"></div><br/>
							<div class="wcfm_vendor_settings_heading"><h3><?php _e( 'Store Address', 'wc-frontend-manager' ); ?></h3></div>
							<div class="store_address store_address_wrap">
								<?php
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_address', array(
																																																		"street_1" => array('label' => __('Street', 'wc-frontend-manager'), 'placeholder' => __('Street adress', 'wc-frontend-manager'), 'name' => 'address[street_1]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $street_1 ),
																																																		"street_2" => array('label' => __('Street 2', 'wc-frontend-manager'), 'placeholder' => __('Apartment, suit, unit etc. (optional)', 'wc-frontend-manager'), 'name' => 'address[street_2]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $street_2 ),
																																																		"city" => array('label' => __('City/Town', 'wc-frontend-manager'), 'placeholder' => __('Town / City', 'wc-frontend-manager'), 'name' => 'address[city]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $city ),
																																																		"zip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager'), 'placeholder' => __('Postcode / Zip', 'wc-frontend-manager'), 'name' => 'address[zip]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $zip ),
																																																		"country" => array('label' => __('Country', 'wc-frontend-manager'), 'name' => 'address[country]', 'type' => 'country', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $country ),
																																																		"state" => array('label' => __('State/County', 'wc-frontend-manager'), 'name' => 'address[state]', 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => $state_options, 'value' => $state ),
																																																		), $user_id ) );
								?>
							</div>
						
							<?php 
							$api_key = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] : '';
							if ( $api_key ) {
								?>
								<div class="wcfm_clearfix"></div>
								<div class="wcfm_vendor_settings_heading"><h3><?php _e( 'Store Location', 'wc-frontend-manager' ); ?></h3></div>
								<div class="store_address store_location_wrap">
									<?php
										$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_location', array(
																																																			"find_address" => array( 'label' => __( 'Find Location', 'wc-frontend-manager' ), 'placeholder' => __( 'Type an address to find', 'wc-frontend-manager' ), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $map_address ),
																																																			"store_location" => array( 'type' => 'hidden', 'value' => $store_location ),
																																																			"store_lat" => array( 'type' => 'hidden', 'value' => $store_lat ),
																																																			"store_lng" => array( 'type' => 'hidden', 'value' => $store_lng ),
																																																			), $user_id ) );
									?>
									<div class="wcfm_clearfix"></div><br />
									<div class="wcfm-marketplace-google-map" id="wcfm-marketplace-map"></div>
									<div class="wcfm_clearfix"></div><br />
								</div>
							<?php
							}
							?>
						
						<?php
							}
						?>
						
						<?php if( apply_filters( 'wcfm_is_allow_store_visibility', true ) ) { ?>
							<div class="wcfm_clearfix"></div>
							<div class="wcfm_vendor_settings_heading"><h3><?php _e( 'Store Visibility', 'wc-frontend-manager' ); ?></h3></div>
							<div class="store_address store_visibility_wrap">
								<?php
								  $settings_fields_visibility = apply_filters( 'wcfm_marketplace_settings_fields_visibility', array(
																															"store_name_position" => array( 'label' => __('Store Name Position', 'wc-multivendor-marketplace'), 'type' => 'select', 'options' => array( 'on_banner' => __( 'On Banner', 'wc-multivendor-marketplace' ), 'on_header' => __( 'At Header', 'wc-multivendor-marketplace' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $store_name_position, 'hints' => __( 'Store name position at you Store Page.', 'wc-frontend-manager' ) ),
																															"store_ppp" => array( 'label' => __('Products per page', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $store_ppp, 'attributes' => array( 'min'=> 1, 'step' => 1 ), 'hints' => __( 'No of products at you Store Page.', 'wc-frontend-manager' ) ),
																															"store_hide_email" => array('label' => __( 'Hide Email from Store', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $store_hide_email ),
																															"store_hide_phone" => array('label' => __( 'Hide Phone from Store', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $store_hide_phone ),
																															"store_hide_address" => array('label' => __( 'Hide Address from Store', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $store_hide_address ),
																															) );
									$WCFM->wcfm_fields->wcfm_generate_form_field( $settings_fields_visibility );
									
									if( !apply_filters( 'wcfm_is_allow_show_email', true ) ) {
										if( isset( $settings_fields_visibility['store_hide_email'] ) ) { unset( $settings_fields_visibility['store_hide_email'] ); }
									}
									
									if( !apply_filters( 'wcfm_is_allow_show_phone', true ) ) {
										if( isset( $settings_fields_visibility['store_hide_phone'] ) ) { unset( $settings_fields_visibility['store_hide_phone'] ); }
									}
									
									if( !apply_filters( 'wcfm_is_allow_show_address', true ) ) {
										if( isset( $settings_fields_visibility['store_hide_address'] ) ) { unset( $settings_fields_visibility['store_hide_address'] ); }
									}
								?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				<!-- end collapsible -->
				
			  <!-- collapsible -->
				<?php if( $wcfm_is_allow_billing_settings = apply_filters( 'wcfm_is_allow_billing_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_payment_head">
						<label class="fa fa-money"></label>
						<?php _e('Payment', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_payment_expander" class="wcfm-content">
							<?php
							$wcfm_marketplace_withdrwal_payment_methods = get_wcfm_marketplace_active_withdrwal_payment_methods();
							$wcfmmp_settings_fields_billing = apply_filters( 'wcfm_marketplace_settings_fields_billing', array(
																																															"payment_mode" => array('label' => __('Prefered Payment Method', 'wc-frontend-manager'), 'name' => 'payment[method]', 'type' => 'select', 'options' => $wcfm_marketplace_withdrwal_payment_methods, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $payment_mode ),
																																															"paypal" => array('label' => __('PayPal Email', 'wc-frontend-manager'), 'name' => 'payment[paypal][email]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_paypal', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_paypal', 'value' => $paypal ),
																																															"skrill" => array('label' => __('Skrill Email', 'wc-frontend-manager'), 'name' => 'payment[skrill][email]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_skrill', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_skrill', 'value' => $skrill ),
																																															), $user_id );
							
							$WCFM->wcfm_fields->wcfm_generate_form_field( $wcfmmp_settings_fields_billing );
							?>
							
							<?php if( in_array( 'bank_transfer', array_keys( $wcfm_marketplace_withdrwal_payment_methods ) ) ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="wcfm_vendor_settings_heading wcfm_marketplace_bank paymode_field paymode_bank_transfer"><h3><?php _e( 'Bank Details', 'wc-frontend-manager' ); ?></h3></div>
								<div class="store_address">
									<?php
										$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_billing_bank', array(
																																				"ac_name" => array('label' => __('Account Name', 'wc-frontend-manager'), 'placeholder' => __('Your bank account name', 'wc-frontend-manager'), 'name' => 'payment[bank][ac_name]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $ac_name ),
																																				"ac_number" => array('label' => __('Account Number', 'wc-frontend-manager'), 'placeholder' => __('Your bank account number', 'wc-frontend-manager'), 'name' => 'payment[bank][ac_number]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $ac_number ),
																																				"bank_name" => array('label' => __('Bank Name', 'wc-frontend-manager'), 'placeholder' => __('Name of bank', 'wc-frontend-manager'), 'name' => 'payment[bank][bank_name]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $bank_name ),
																																				"bank_addr" => array('label' => __('Bank Address', 'wc-frontend-manager'), 'placeholder' => __('Address of your bank', 'wc-frontend-manager'), 'name' => 'payment[bank][bank_addr]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $bank_addr ),
																																				"routing_number" => array('label' => __('Routing Number', 'wc-frontend-manager'), 'placeholder' => __( 'Routing number', 'wc-frontend-manager' ), 'name' => 'payment[bank][routing_number]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $routing_number ),
																																				"iban" => array('label' => __('IBAN', 'wc-frontend-manager'), 'placeholder' => __('IBAN', 'wc-frontend-manager'), 'name' => 'payment[bank][iban]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $iban ),
																																				"swift" => array('label' => __('Swift Code', 'wc-frontend-manager'), 'placeholder' => __('Swift code', 'wc-frontend-manager'), 'name' => 'payment[bank][swift]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $swift ),
																																				"ifsc" => array('label' => __('IFSC Code', 'wc-frontend-manager'), 'placeholder' => __('IFSC code', 'wc-frontend-manager'), 'name' => 'payment[bank][ifsc]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_bank_transfer', 'value' => $ifsc ),
																																				), $user_id ) );
									?>
								</div>
							<?php } ?>
							
							<?php if( array_key_exists( 'stripe', $wcfm_marketplace_withdrwal_payment_methods ) && apply_filters( 'wcfm_is_allow_billing_stripe', true ) ) { ?>
						    <div class="paymode_field paymode_stripe">
									<?php
									$testmode = isset( $WCFMmp->wcfmmp_withdrawal_options['test_mode'] ) ? true : false;
									$client_id = $testmode ? $WCFMmp->wcfmmp_withdrawal_options['stripe_test_client_id'] : $WCFMmp->wcfmmp_withdrawal_options['stripe_client_id'];
									$secret_key = $testmode ? $WCFMmp->wcfmmp_withdrawal_options['stripe_test_secret_key'] : $WCFMmp->wcfmmp_withdrawal_options['stripe_secret_key'];
									if (isset($client_id) && isset($secret_key)) {
										if (isset($_GET['code'])) {
											$code = $_GET['code'];
											if (!is_user_logged_in()) {
												if (isset($_GET['state'])) {
													$user_id = $_GET['state'];
												}
											}
											$token_request_body = array(
												'grant_type' => 'authorization_code',
												'client_id' => $client_id,
												'code' => $code,
												'client_secret' => $secret_key
											);
											$req = curl_init('https://connect.stripe.com/oauth/token');
											curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($req, CURLOPT_POST, true);
											curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
											curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
											curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
											curl_setopt($req, CURLOPT_VERBOSE, true);
											// TODO: Additional error handling
											$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
											$resp = json_decode(curl_exec($req), true);
											curl_close($req);
											if (!isset($resp['error'])) {
												update_user_meta( $user_id, 'vendor_connected', 1);
												update_user_meta( $user_id, 'admin_client_id', $client_id);
												update_user_meta( $user_id, 'access_token', $resp['access_token']);
												update_user_meta( $user_id, 'refresh_token', $resp['refresh_token']);
												update_user_meta( $user_id, 'stripe_publishable_key', $resp['stripe_publishable_key']);
												update_user_meta( $user_id, 'stripe_user_id', $resp['stripe_user_id']);
												$vendor_data['payment']['method'] = 'stripe';
												update_user_meta( $user_id, 'wcfmmp_profile_settings', $vendor_data );
												?>
												<script>
													window.location =  '<?php echo get_wcfm_settings_url() . '#wcfm_settings_form_payment_head'; ?>';
												</script>
												<?php
											}
											if (isset($resp['access_token']) || get_user_meta($user_id, 'vendor_connected', true) == 1) {
												update_user_meta($user_id, 'vendor_connected', 1);
												?>
												<div class="clear"></div>
												<div class="wcfmmp_stripe_connect">
													<form action="" method="POST">
														<table class="form-table">
															<tbody>
																<tr>
																	<th style="width: 35%;">
																		<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
																	</th>
																	<td>
																		<label><?php _e('You are connected with Stripe', 'wc-frontend-manager'); ?></label>
																	</td>
																</tr>
																<tr>
																	<th></th>
																	<td>
																		<input type="submit" class="button" name="disconnect_stripe" value="<?php _e( 'Disconnect Stripe Account', 'wc-frontend-manager' ); ?>" />
																	</td>
																</tr>
															</tbody>
														</table>
													</form>
												</div>
												<?php
											} else {
												update_user_meta($user_id, 'vendor_connected', 0);
												?>
												<div class="clear"></div>
												<div class="wcfmmp_stripe_connect">
													<form action="" method="POST">
														<table class="form-table">
															<tbody>
																<tr>
																	<th style="width: 35%;">
																		<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
																	</th>
																	<td>
																		<label><?php _e('Please Retry!!!', 'wc-frontend-manager'); ?></label>
																	</td>
																</tr>
															</tbody>
														</table>
													</form>
												</div>
												<?php
										}
									} else if (isset($_GET['error'])) { // Error
										update_user_meta($user_id, 'vendor_connected', 0);
										?>
										<div class="clear"></div>
										<div class="wcfmmp_stripe_connect">
											<table class="form-table">
												<tbody>
													<tr>
														<th style="width: 35%;">
															<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
														</th>
														<td>
															<label><?php _e('Please Retry!!!', 'wc-frontend-manager'); ?></label>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<?php
									} else {
										$stripe_user_id = get_user_meta($user_id, 'stripe_user_id', true);
										if( isset( $_GET['disconnect_stripe'] ) && !empty($stripe_user_id) ) {
											$testmode = isset( $WCFMmp->wcfmmp_withdrawal_options['test_mode'] ) ? true : false;
											$client_id = $testmode ? $WCFMmp->wcfmmp_withdrawal_options['stripe_test_client_id'] : $WCFMmp->wcfmmp_withdrawal_options['stripe_client_id'];
											$secret_key = $testmode ? $WCFMmp->wcfmmp_withdrawal_options['stripe_test_secret_key'] : $WCFMmp->wcfmmp_withdrawal_options['stripe_secret_key'];
											$token_request_body = array(
													'client_id' => $client_id,
													'stripe_user_id' => $stripe_user_id,
													'client_secret' => $secret_key
											);
											$req = curl_init('https://connect.stripe.com/oauth/deauthorize');
											curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($req, CURLOPT_POST, true);
											curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
											curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
											curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
											curl_setopt($req, CURLOPT_VERBOSE, true);
											// TODO: Additional error handling
											$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
											$resp = json_decode(curl_exec($req), true);
											print_r($resp);
											curl_close($req);
											if ( isset( $resp['stripe_user_id'] ) ) {
												delete_user_meta( $user_id, 'vendor_connected');
												delete_user_meta( $user_id, 'admin_client_id');
												delete_user_meta( $user_id, 'access_token');
												delete_user_meta( $user_id, 'refresh_token');
												delete_user_meta( $user_id, 'stripe_publishable_key');
												delete_user_meta( $user_id, 'stripe_user_id');
												$vendor_data['payment']['method'] = '';
												update_user_meta( $user_id, 'wcfmmp_profile_settings', $vendor_data );
												?>
												<script>
													window.location =  '<?php echo get_wcfm_settings_url() . '#wcfm_settings_form_payment_head'; ?>';
												</script>
												<?php
											} else {
												_e( 'Unable to disconnect your account pleease try again', 'wc-frontend-manager');
											}
										}
										
										$vendor_connected = get_user_meta( $user_id, 'vendor_connected', true );
										$connected = true;

										if (isset($vendor_connected) && $vendor_connected == 1) {
											$admin_client_id = get_user_meta( $user_id, 'admin_client_id', true );

											if ($admin_client_id == $client_id) {
												?>
												<div class="clear"></div>
												<div class="wcfmmp_stripe_connect">
													<table class="form-table">
														<tbody>
															<tr>
																<th style="width: 35%;">
																		<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
																</th>
																<td>
																		<label><?php _e('You are connected with Stripe', 'wc-frontend-manager'); ?></label>
																</td>
															</tr>
															<tr>
																<th></th>
																<td>
																		<input type="submit" class="button" name="disconnect_stripe" value="<?php _e('Disconnect Stripe Account', 'wc-frontend-manager'); ?>" />
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<?php
											} else {
												$connected = false;
											}
										} else {
												$connected = false;
										}

										if (!$connected) {

											$status = delete_user_meta($user_id, 'vendor_connected');
											$status = delete_user_meta($user_id, 'admin_client_id');

											// Show OAuth link
											$authorize_request_body = array(
												'response_type' => 'code',
												'scope' => 'read_write',
												'client_id' => $client_id,
												'redirect_uri' => get_wcfm_settings_url(),
												'state' => $user_id,
												'stripe_user' => array( 
													                    'email' => $user_email,
													                    'url'   => wcfmmp_get_store_url( $user_id )  
													                    )
											);
											$url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query($authorize_request_body);
											$stripe_connect_url = $WCFM->plugin_url . 'assets/images/blue-on-light.png';

											if (!$status) {
												?>
												<div class="clear"></div>
												<div class="wcfmmp_stripe_connect">
													<table class="form-table">
														<tbody>
															<tr>
																<th style="width: 35%;">
																	<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
																</th>
																<td><?php _e('You are not connected with stripe.', 'wc-frontend-manager'); ?></td>
															</tr>
															<tr>
																<th></th>
																<td>
																	<a href=<?php echo $url; ?> target="_self"><img src="<?php echo $stripe_connect_url; ?>" /></a>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<?php
											} else {
													?>
												<div class="clear"></div>
													<div class="wcfmmp_stripe_connect">
														<table class="form-table">
															<tbody>
																<tr>
																	<th style="width: 35%;">
																		<label><?php _e('Stripe', 'wc-frontend-manager'); ?></label>
																	</th>
																	<td><?php _e('Please connected with stripe again.', 'wc-frontend-manager'); ?></td>
																</tr>
																<tr>
																	<th></th>
																	<td>
																			<a href=<?php echo $url; ?> target="_self"><img src="<?php echo $stripe_connect_url; ?>" /></a>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
													<?php
												}
											}
										}
									} else {
										_e('Stripe not setup properly, please contact your site admin.', 'wc-frontend-manager');
									}
									?>
								</div>
							<?php } ?>
							
						</div>
					</div>
					<div class="wcfm_clearfix"></div>
				<?php } ?>
				<!-- end collapsible -->
			
				<!-- collapsible -->
				<?php if( apply_filters( 'wcfm_is_allow_vshipping_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_shipping_head">
						<label class="fa fa-truck"></label>
						<?php _e('Shipping', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_shipping_expander" class="wcfm-content">
							<?php
							// WCfM Marketplace Shipping Setting
              do_action('wcfm_marketplace_shipping');
							?>
						</div>
					</div>
				<?php } ?>
			
				<?php if( apply_filters( 'wcfm_is_allow_vseo_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_seo_head">
						<label class="fa fa-globe"></label>
						<?php _e('SEO', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_shipping_expander" class="wcfm-content">
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendors_settings_fields_shipping', array(
																																						"wcfmmp-seo-meta-title" => array('label' => __('SEO Title', 'wc-frontend-manager') , 'name' => 'store_seo[wcfmmp-seo-meta-title]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_meta_title, 'hints' => __('SEO Title is shown as the title of your store page', 'wc-frontend-manager') ),
																																						"wcfmmp-seo-meta-desc" => array('label' => __('Meta Description', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-meta-desc]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_meta_desc, 'hints' => __('The meta description is often shown as the black text under the title in a search result. For this to work it has to contain the keyword that was searched for and should be less than 156 chars.', 'wc-frontend-manager') ),
																																						"wcfmmp-seo-meta-keywords" => array('label' => __('Meta Keywords', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-meta-keywords]', 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_meta_keywords, 'hints' => __('Insert some comma separated keywords for better ranking of your store page.', 'wc-frontend-manager') ),
																																						"wcfmmp-seo-og-title" => array('label' => __('Facebook Title', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-og-title]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_og_title ),
																																						"wcfmmp-seo-og-desc" => array('label' => __('Facebook Description', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-og-desc]', 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_og_desc ),
																																						"wcfmmp-seo-og-image" => array('label' => __('Facebook Image', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-og-image]', 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_og_image_url ),
																																						"wcfmmp-seo-twitter-title" => array('label' => __('Twitter Title', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-twitter-title]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_twitter_title ),
																																						"wcfmmp-seo-twitter-desc" => array('label' => __('Twitter Description', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-twitter-desc]', 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_twitter_desc ),
																																						"wcfmmp-seo-twitter-image" => array('label' => __('Twitter Image', 'wc-frontend-manager'), 'name' => 'store_seo[wcfmmp-seo-twitter-image]', 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfmmp_seo_twitter_image_url ),
																																					 ), $user_id ) );
								
							?>
						</div>
					</div>
				<?php } ?>
				<!-- end collapsible -->
				
				<!-- collapsible - Customer Support -->
				<?php if( apply_filters( 'wcfm_is_allow_customer_support_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_customer_support_head">
						<label class="fa fa-thumbs-o-up"></label>
						<?php _e('Customer Support', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_customer_support_expander" class="wcfm-content">
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcmarketplace_settings_fields_customer_support', array(
																																																	"vendor_customer_phone" => array('label' => __('Phone', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[phone]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_customer_phone ),
																																																	"vendor_customer_email" => array('label' => __('Email', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[email]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_customer_email ),
																																																	"vendor_csd_return_address1" => array('label' => __('Address 1', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[address1]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_address1 ),
																																																	"vendor_csd_return_address2" => array('label' => __('Address 2', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[address2]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_address2 ),
																																																	"vendor_csd_return_country" => array('label' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'name' => 'customer_support[country]', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_country ),
																																																	"vendor_csd_return_city" => array('label' => __('City/Town', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[city]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_city ),
																																																	"vendor_csd_return_state" => array('label' => __('State/County', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[state]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_state ),
																																																	"vendor_csd_return_zip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'customer_support[zip]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_csd_return_zip )
																																																	), $user_id ) );
							?>
						</div>
					</div>
					<div class="wcfm_clearfix"></div>
				<?php } ?>
				<!-- end collapsible -->
				
				<?php do_action( 'end_wcfm_vendor_settings', $user_id ); ?>
				
				<!-- collapsible -->
				<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
					<?php if( apply_filters( 'wcfm_is_pref_vendor_vacation', true ) && apply_filters( 'wcfm_is_allow_vacation_settings', true ) ) { ?>
						<div class="page_collapsible" id="wcfm_settings_form_vacation_head">
							<label class="fa fa-tripadvisor"></label>
							<?php _e('Vacation Mode', 'wc-frontend-manager'); ?><span></span>
						</div>
						<div class="wcfm-container">
							<div id="wcfm_settings_form_vacation_expander" class="wcfm-content">
								<?php
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendors_settings_fields_vacation', array(
																																																														"wcfm_vacation_mode" => array('label' => __('Enable Vacation Mode', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $wcfm_vacation_mode ),
																																																														"wcfm_disable_vacation_purchase" => array('label' => __('Disable Purchase During Vacation', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $wcfm_disable_vacation_purchase ),
																																																														"wcfm_vacation_mode_type" => array('label' => __('Vacation Type', 'wc-frontend-manager') , 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'instant' => __( 'Instantly Close', 'wc-frontend-manager' ), 'date_wise' => __( 'Date wise close', 'wc-frontend-manager' ) ), 'value' => $wcfm_vacation_mode_type ),
																																																														"wcfm_vacation_start_date" => array('label' => __('From', 'wc-frontend-manager'), 'type' => 'text', 'placeholder' => 'From... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele date_wise_vacation_ele', 'label_class' => 'wcfm_title wcfm_ele date_wise_vacation_ele', 'value' => $wcfm_vacation_start_date),
																																																														"wcfm_vacation_end_date" => array('label' => __('Upto', 'wc-frontend-manager'), 'type' => 'text', 'placeholder' => 'To... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele date_wise_vacation_ele', 'label_class' => 'wcfm_title wcfm_ele date_wise_vacation_ele', 'value' => $wcfm_vacation_end_date),
																																																														"wcfm_vacation_mode_msg" => array('label' => __('Vacation Message', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfm_vacation_mode_msg )
																																																													 ) ) );
								?>
							</div>
						</div>
						<div class="wcfm_clearfix"></div>
					<?php } ?>
				<?php
				}
				?>
				<!-- end collapsible -->
				
			  <?php do_action( 'end_wcfm_marketplace_settings', $user_id ); ?>
			  
			</div>
			
			<div id="wcfm_settings_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="save-data" value="<?php _e( 'Save', 'wc-frontend-manager' ); ?>" id="wcfm_settings_save_button" class="wcfm_submit_button" />
			</div>
			
		</form>
		<?php
		do_action( 'after_wcfm_marketplace_settings', $user_id );
		?>
	</div>
</div>

<script type="text/javascript">
	var selected_state = '<?php echo $state; ?>';
	var input_selected_state = '<?php echo $state; ?>';
</script>