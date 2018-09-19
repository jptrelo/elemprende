<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Vendors New Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   5.0.2
 */

class WCFM_Vendors_New_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMmp;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_vendor_form_data;
		
		$wcfm_vendor_form_data = array();
	  parse_str($_POST['wcfm_vendors_new_form'], $wcfm_vendor_form_data);
	  
	  $wcfm_vendor_messages = get_wcfm_vendors_new_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_vendor_form_data['user_name']) && !empty($wcfm_vendor_form_data['user_name'])) {
	  	if(isset($wcfm_vendor_form_data['user_email']) && !empty($wcfm_vendor_form_data['user_email'])) {
	  		if(isset($wcfm_vendor_form_data['store_name']) && !empty($wcfm_vendor_form_data['store_name'])) {
					$vendor_id = 0;
					$is_update = false;
					
					if ( ! is_email( $wcfm_vendor_form_data['user_email'] ) ) {
						echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
						die;
					}
					
					if ( ! validate_username( $wcfm_vendor_form_data['user_name'] ) ) {
						echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
						die;
					}
					
					// WCFM form custom validation filter
					$custom_validation_results = apply_filters( 'wcfm_form_custom_validation', $wcfm_vendor_form_data, 'vendor_manage' );
					if(isset($custom_validation_results['has_error']) && !empty($custom_validation_results['has_error'])) {
						$custom_validation_error = __( 'There has some error in submitted data.', 'wc-frontend-manager' );
						if( isset( $custom_validation_results['message'] ) && !empty( $custom_validation_results['message'] ) ) { $custom_validation_error = $custom_validation_results['message']; }
						echo '{"status": false, "message": "' . $custom_validation_error . '"}';
						die;
					}
					
					if( username_exists( $wcfm_vendor_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_vendor_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_vendor_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_vendor_messages['email_exists'] . '"}';
						}
					}
					
					$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
					if( !$has_error ) {
						$user_data = array( 'user_login'     => $wcfm_vendor_form_data['user_name'],
																'user_email'     => $wcfm_vendor_form_data['user_email'],
																'display_name'   => $wcfm_vendor_form_data['user_name'],
																'nickname'       => $wcfm_vendor_form_data['user_name'],
																'first_name'     => $wcfm_vendor_form_data['first_name'],
																'last_name'      => $wcfm_vendor_form_data['last_name'],
																'user_pass'      => $password,
																'role'           => 'wcfm_vendor',
																'ID'             => $vendor_id
																);
						if( $is_update ) {
							unset( $user_data['user_login'] );
							unset( $user_data['display_name'] );
							unset( $user_data['nickname'] );
							unset( $user_data['user_pass'] );
							unset( $user_data['role'] );
							$vendor_id = wp_update_user( $user_data ) ;
						} else {
							$vendor_id = wp_insert_user( $user_data ) ;
						}
							
						if( !$vendor_id ) {
							$has_error = true;
						} else {
							// Store Setting
							$vendor_data = array();
							// Set Gravatar
							if(isset($wcfm_vendor_form_data['gravatar']) && !empty($wcfm_vendor_form_data['gravatar'])) {
								$wcfm_vendor_form_data['gravatar'] = $WCFM->wcfm_get_attachment_id($wcfm_vendor_form_data['gravatar']);
							} else {
								$wcfm_vendor_form_data['gravatar'] = '';
							}
							
							// Set Banner
							if(isset($wcfm_vendor_form_data['banner']) && !empty($wcfm_vendor_form_data['banner'])) {
								$wcfm_vendor_form_data['banner'] = $WCFM->wcfm_get_attachment_id($wcfm_vendor_form_data['banner']);
							} else {
								$wcfm_vendor_form_data['banner'] = '';
							}
							
							update_user_meta( $vendor_id, 'wcfmmp_profile_settings', $wcfm_vendor_form_data );
							update_user_meta( $vendor_id, 'store_name', strip_tags( $wcfm_vendor_form_data['store_name'] ) );
							update_user_meta( $vendor_id, 'wcfmmp_store_name', strip_tags( $wcfm_vendor_form_data['store_name'] ) );
							
							// Set Vendor Shipping
							$wcfmmp_shipping = array ( '_wcfmmp_user_shipping_enable' => 'yes', '_wcfmmp_user_shipping_type' => 'by_zone' );
							update_user_meta( $vendor_id, '_wcfmmp_shipping', $wcfmmp_shipping );
							
							do_action( 'wcfmmp_new_store_created', $vendor_id, $wcfm_vendor_form_data );
							
							$wcfm_vendor_billing_fields = array( 
																						'billing_first_name'  => 'bfirst_name',
																						'billing_last_name'   => 'blast_name',
																						'billing_phone'       => 'bphone',
																						'billing_address_1'   => 'baddr_1',
																						'billing_address_2'   => 'baddr_2',
																						'billing_country'     => 'bcountry',
																						'billing_city'        => 'bcity',
																						'billing_state'       => 'bstate',
																						'billing_postcode'    => 'bzip'
																					);
							foreach( $wcfm_vendor_billing_fields as $wcfm_vendor_default_key => $wcfm_vendor_default_field ) {
								update_user_meta( $vendor_id, $wcfm_vendor_default_key, $wcfm_vendor_form_data[$wcfm_vendor_default_field] );
							}
							
							$wcfm_vendor_shipping_fields = array( 
																						'shipping_first_name'  => 'sfirst_name',
																						'shipping_last_name'   => 'slast_name',
																						'shipping_address_1'   => 'saddr_1',
																						'shipping_address_2'   => 'saddr_2',
																						'shipping_country'     => 'scountry',
																						'shipping_city'        => 'scity',
																						'shipping_state'       => 'sstate',
																						'shipping_postcode'    => 'szip'
																					);
							foreach( $wcfm_vendor_shipping_fields as $wcfm_vendor_shipping_key => $wcfm_vendor_shipping_field ) {
								update_user_meta( $vendor_id, $wcfm_vendor_shipping_key, $wcfm_vendor_form_data[$wcfm_vendor_shipping_field] );
							}
							
							if( !defined( 'DOING_WCFM_EMAIL' ) ) 
								define( 'DOING_WCFM_EMAIL', true );
							
							// Sending Mail to new user
							$mail_to = $wcfm_vendor_form_data['user_email'];
							$new_account_mail_subject = "{site_name}: New Account Created";
							$new_account_mail_body = '<br/>' . __( 'Dear', 'wc-frontend-manager' ) . ' {first_name}' .
																			 ',<br/><br/>' . 
																			 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-manager' ) .
																			 '<br/><br/>' . 
																			 __( 'Store Name', 'wc-frontend-manager' ) . ': {store_name}' . 
																			 '<br/><br/>' . 
																			 __( 'Store Manager', 'wc-frontend-manager' ) . ': <a href="{site_url}">' . __( 'Click here ...', 'wc-frontend-manager' ) . '</a>' . 
																			 '<br/>' .
																			 __( 'Username', 'wc-frontend-manager' ) . ': {username}' .
																			 '<br/>' . 
																			 __( 'Password', 'wc-frontend-manager' ) . ': {password}' .
																			 '<br /><br/>Thank You' .
																			 '<br/><br/>';
																			 
							$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
							$message = str_replace( '{site_url}', get_wcfm_url(), $new_account_mail_body );
							$message = str_replace( '{first_name}', $wcfm_vendor_form_data['first_name'], $message );
							$message = str_replace( '{store_name}', $wcfm_vendor_form_data['store_name'], $message );
							$message = str_replace( '{username}', $wcfm_vendor_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', 'Vendor', $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
						
							update_user_meta( $vendor_id, 'show_admin_bar_front', false );
							
							// Desktop notification message for new_vendor
							$wcfm_messages = sprintf( __( 'A new vendor <b>%s</b> added .', 'wc-frontend-manager' ), '<a class="wcfm_dashboard_item_title" href="' . get_wcfm_vendors_manage_url( $vendor_id ) . '">' . $wcfm_vendor_form_data['store_name'] . '</a>' );
							$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'registration' );
							
							update_user_meta( $vendor_id, 'wcfm_register_member', 'yes' );
							
							do_action( 'wcfm_vendors_new', $vendor_id, $wcfm_vendor_form_data );
						}
						
						if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_vendor_messages['vendor_saved'] . '", "redirect": "' . apply_filters( 'wcfm_vendors_new_redirect', get_wcfm_vendors_manage_url( $vendor_id ), $vendor_id ) . '"}'; }
						else { echo '{"status": false, "message": "' . $wcfm_vendor_messages['vendor_failed'] . '"}'; }
					}
				} else {
					echo '{"status": false, "message": "' . $wcfm_vendor_messages['no_store_name'] . '"}';
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_vendor_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_vendor_messages['no_username'] . '"}';
		}
		
		die;
	}
}