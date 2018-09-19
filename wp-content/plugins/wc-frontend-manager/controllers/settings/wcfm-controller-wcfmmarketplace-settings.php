<?php
/**
 * WCFM plugin controllers
 *
 * Plugin WCfM Marketplace Setings Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers
 * @version   5.0.0
 */

class WCFM_Settings_Marketplace_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST;
		
		$wcfm_settings_form_data = array();
	  parse_str($_POST['wcfm_settings_form'], $wcfm_settings_form);
	  
	  $has_error = false;
	  
	  if( wcfm_is_vendor() ) {
	  	$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
	  } else  {
	  	$user_id = absint( $wcfm_settings_form['vendor_id'] );
	  }
	  
		$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
		if( !is_array($vendor_data) ) $vendor_data = array();
	  
	  // WCFM form custom validation filter
		$custom_validation_results = apply_filters( 'wcfm_form_custom_validation', $wcfm_settings_form, 'vendor_setting_manage' );
		if(isset($custom_validation_results['has_error']) && !empty($custom_validation_results['has_error'])) {
			$custom_validation_error = __( 'There has some error in submitted data.', 'wc-frontend-manager' );
			if( isset( $custom_validation_results['message'] ) && !empty( $custom_validation_results['message'] ) ) { $custom_validation_error = $custom_validation_results['message']; }
			echo '{"status": false, "message": "' . $custom_validation_error . '"}';
			die;
		}
	  
	  // sanitize
		//$wcfm_settings_form = array_map( 'sanitize_text_field', $wcfm_settings_form );
		//$wcfm_settings_form = array_map( 'stripslashes', $wcfm_settings_form );
		
		// Set Gravatar
		if( apply_filters( 'wcfm_is_allow_store_logo', true ) ) {
			if(isset($wcfm_settings_form['gravatar']) && !empty($wcfm_settings_form['gravatar'])) {
				$wcfm_settings_form['gravatar'] = $WCFM->wcfm_get_attachment_id($wcfm_settings_form['gravatar']);
			} else {
				$wcfm_settings_form['gravatar'] = '';
			}
		}
		
		// Set List Banner
		if( apply_filters( 'wcfm_is_allow_store_banner', true ) ) {
			if(isset($wcfm_settings_form['list_banner']) && !empty($wcfm_settings_form['list_banner'])) {
				$wcfm_settings_form['list_banner'] = $WCFM->wcfm_get_attachment_id($wcfm_settings_form['list_banner']);
			} else {
				$wcfm_settings_form['list_banner'] = '';
			}
		}
		
		// Set Banner
		if( apply_filters( 'wcfm_is_allow_store_banner', true ) ) {
			if(isset($wcfm_settings_form['banner']) && !empty($wcfm_settings_form['banner'])) {
				$wcfm_settings_form['banner'] = $WCFM->wcfm_get_attachment_id($wcfm_settings_form['banner']);
			} else {
				$wcfm_settings_form['banner'] = '';
			}
		}
		
		// Set Vendor Store
		if( apply_filters( 'wcfm_is_allow_store_name', true ) ) {
			if(isset($wcfm_settings_form['store_name']) && !empty($wcfm_settings_form['store_name'])) {
				update_user_meta( $user_id, 'store_name', $wcfm_settings_form['store_name'] );
				update_user_meta( $user_id, 'wcfmmp_store_name', $wcfm_settings_form['store_name'] );
			}
			if( isset( $wcfm_settings_form['store_slug'] ) && !empty( $wcfm_settings_form['store_slug'] ) ) {
				$store_slug = sanitize_title( wc_clean( $wcfm_settings_form['store_slug'] ) );
				$the_user = get_user_by( 'id', $user_id );
				$user_login = sanitize_title( $the_user->user_login );
				$previous_store_slug     = $the_user->user_nicename;
				if( $previous_store_slug != $store_slug ) {
					if( ( $user_login == $store_slug ) || !username_exists( $store_slug ) ) {
						wp_update_user( array( 'ID' => $user_id, 'user_nicename' => wc_clean( $store_slug ) ) );
						if( apply_filters( 'wcfm_is_allow_reassociate_role', false ) ) {
							$member_user = new WP_User(absint($user_id));
							$member_user->set_role('wcfm_vendor');
						}
					} else {
						echo '{"status": false, "message": "' . __( 'Shop Slug already exists.', 'wc-frontend-manager' ) . '"}';
						$has_error = true;
					}
				}
			}
		}
		
		// sanitize html editor content
		if( apply_filters( 'wcfm_is_allow_store_description', true ) ) {
			$wcfm_settings_form['shop_description'] = ! empty( $_POST['profile'] ) ? stripslashes( html_entity_decode( $_POST['profile'], ENT_QUOTES, 'UTF-8' ) ) : '';
			update_user_meta( $user_id, '_store_description', apply_filters( 'wcfm_editor_content_before_save', $wcfm_settings_form['shop_description'] ) );
		}
		
		// Visibility
		if( apply_filters( 'wcfm_is_allow_store_visibility', true ) ) {
			if(isset($wcfm_settings_form['store_hide_email']) && !empty($wcfm_settings_form['store_hide_email'])) {
				$wcfm_settings_form['store_hide_email'] = 'yes';
			} else {
				$wcfm_settings_form['store_hide_email'] = 'no';
			}
			if(isset($wcfm_settings_form['store_hide_phone']) && !empty($wcfm_settings_form['store_hide_phone'])) {
				$wcfm_settings_form['store_hide_phone'] = 'yes';
			} else {
				$wcfm_settings_form['store_hide_phone'] = 'no';
			}
			if(isset($wcfm_settings_form['store_hide_address']) && !empty($wcfm_settings_form['store_hide_address'])) {
				$wcfm_settings_form['store_hide_address'] = 'yes';
			} else {
				$wcfm_settings_form['store_hide_address'] = 'no';
			}
		}
		
		if( apply_filters( 'wcfm_is_allow_vseo_settings', true ) ) {
			// Set Facebook Image
			if(isset($wcfm_settings_form['store_seo']) && !empty($wcfm_settings_form['store_seo']['wcfmmp-seo-og-image'])) {
				$wcfm_settings_form['store_seo']['wcfmmp-seo-og-image'] = $WCFM->wcfm_get_attachment_id($wcfm_settings_form['store_seo']['wcfmmp-seo-og-image']);
			} else {
				$wcfm_settings_form['store_seo']['wcfmmp-seo-og-image'] = '';
			}
			
			// Set Twitter Image
			if(isset($wcfm_settings_form['store_seo']) && !empty($wcfm_settings_form['store_seo']['wcfmmp-seo-twitter-image'])) {
				$wcfm_settings_form['store_seo']['wcfmmp-seo-twitter-image'] = $WCFM->wcfm_get_attachment_id($wcfm_settings_form['store_seo']['wcfmmp-seo-twitter-image']);
			} else {
				$wcfm_settings_form['store_seo']['wcfmmp-seo-twitter-image'] = '';
			}
		}
		
		// Vacation Settings
		if( wcfm_is_vendor() && apply_filters( 'wcfm_is_pref_vendor_vacation', true ) && apply_filters( 'wcfm_is_allow_vacation_settings', true ) ) {
			if( !isset( $wcfm_settings_form['wcfm_vacation_mode'] ) ) $wcfm_settings_form['wcfm_vacation_mode'] = 'no';
			if( !isset( $wcfm_settings_form['wcfm_disable_vacation_purchase'] ) ) $wcfm_settings_form['wcfm_disable_vacation_purchase'] = 'no';
		}
		
		// merge the changes with existing settings
		$wcfm_settings_form = array_merge( $vendor_data, $wcfm_settings_form );
		
		update_user_meta( $user_id, 'wcfmmp_profile_settings', $wcfm_settings_form );
		
		update_user_meta( $user_id, 'wcfm_register_member', 'yes' );
		
		do_action( 'wcfm_vendor_settings_update', $user_id, $wcfm_settings_form );
		do_action( 'wcfm_wcfmmp_settings_update', $user_id, $wcfm_settings_form );
		
		if( !$has_error ) {
			echo '{"status": true, "message": "' . __( 'Settings saved successfully', 'wc-frontend-manager' ) . '"}';
		}
		 
		die;
	}
}