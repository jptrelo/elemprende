<?php

class afo_forgot_pass_class {
	
	public function ForgotPassForm(){
		$this->load_script();
		if(!is_user_logged_in()){
		?>
        <div class="forgot-pass-form">
        
		<?php do_action('lwws_before_forgot_password_form_start');?>
        
        <?php $this->error_message();?>
        
		<form name="forgot" id="forgot" method="post" action="" autocomplete="off" <?php do_action( 'lwws_forgot_password_form_tag' );?>>
		<input type="hidden" name="option" value="afo_forgot_pass" />
			<div class="forgot-pass-form-group">
			<label for="email"><?php _e('Email','login-sidebar-widget');?> </label>
			<input type="email" name="userusername" required <?php do_action( 'lwws_fp_userusername_field' );?>/>
			</div>
			
			<div class="forgot-pass-form-group"><input name="forgot" type="submit" value="<?php _e('Submit','login-sidebar-widget');?>" <?php do_action( 'lwws_forgot_password_form_submit_tag' );?>/></div>
			
			<div class="forgot-pass-form-group">
				<div class="forgot-text">
					<?php _e('Please enter your email. The password reset link will be provided in your email.','login-sidebar-widget');?>
				</div>
			</div>
		</form>
        
        <?php do_action('lwws_after_forgot_password_form_end');?>
        
        </div>
		<?php 
		}
	}
	
	public function load_script(){?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#forgot').validate();
			});
		</script>
	<?php }
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].'</div>';
		}
	}
} 


function forgot_pass_validate(){
	if (!function_exists('set_html_content_type')) {
		function set_html_content_type() {
			return 'text/html';
		}
	}
	
	if(isset($_GET['key']) && sanitize_text_field($_GET['action']) == "reset_pwd") {
		global $wpdb;
		$reset_key = sanitize_text_field($_GET['key']);
		$user_login = sanitize_text_field($_GET['login']);
		$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));
		
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		
		$login_sidebar_widget_from_email = get_option('login_sidebar_widget_from_email');
		if($login_sidebar_widget_from_email == ''){
			$login_sidebar_widget_from_email = 'no-reply@wordpress.com';
		}
		
		if(!empty($reset_key) && !empty($user_data)) {
			$new_password = wp_generate_password(7, false);
			wp_set_password( $new_password, $user_data->ID );
			//mailing reset details to the user
			$headers = 'From: '.get_bloginfo('name').' <'.$login_sidebar_widget_from_email.'>' . "\r\n";
			$message = nl2br(get_option('new_password_mail_body'));
			$message = str_replace(array('#site_url#','#user_name#','#user_password#'), array(site_url(),$user_login,$new_password), $message);
			$message = html_entity_decode($message);
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			if ( $message && !wp_mail($user_email, get_option('new_password_mail_subject'), $message, $headers) ) {
				wp_die(__('Email failed to send for some unknown reason.','login-sidebar-widget'));
				exit;
			}
			else {
				wp_die(__('New Password successfully sent to your mail address.','login-sidebar-widget'));
				exit;
			}
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		} 
		else {
			wp_die(__('Not a Valid Key.','login-sidebar-widget'));
			exit;
		}
	}

	if(isset($_POST['option']) and sanitize_text_field($_POST['option']) == "afo_forgot_pass"){
		global $aperror;
		global $wpdb;
		$aperror = new WP_Error;
		
		$user_login = '';
		$user_email = '';
		$msg = '';
		
		if(empty($_POST['userusername'])) {
			$msg .= __('Email is empty!','login-sidebar-widget');
		}
		
		$user_username = esc_sql(trim(sanitize_text_field($_POST['userusername'])));
		
		$user_data = get_user_by('email', $user_username);
		if(empty($user_data)) { 
			$msg .= __('Invalid E-mail address!','login-sidebar-widget');
		}
		
		if( isset($user_data->data->user_login) and isset($user_data->data->user_email) ){
			$user_login = $user_data->data->user_login;
			$user_email = $user_data->data->user_email;
		}
		
		if($user_email){
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			if(empty($key)) {
				$key = wp_generate_password(10, false);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));	
			}
			
			$login_sidebar_widget_from_email = get_option('login_sidebar_widget_from_email');
			if($login_sidebar_widget_from_email == ''){
				$login_sidebar_widget_from_email = 'no-reply@wordpress.com';
			}
			
			//mailing reset details to the user
			$headers = 'From: '.get_bloginfo('name').' <'.$login_sidebar_widget_from_email.'>' . "\r\n";
			$resetlink = site_url() . "?action=reset_pwd&key=$key&login=" . rawurlencode($user_login);
			$message = nl2br(get_option('forgot_password_link_mail_body'));
			$message = str_replace(array('#site_url#','#user_name#','#resetlink#'), array(site_url(),$user_login,$resetlink), $message);
			$message = html_entity_decode($message);
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
						
			if ( !wp_mail($user_email, get_option('forgot_password_link_mail_subject'), $message, $headers) ) {
				$aperror->add( "reg_msg_class", "error_wid_login" );
				$aperror->add( "reg_error_msg", __('Email failed to send for some unknown reason.','login-sidebar-widget') );	
			}
			else {
				$aperror->add( "reg_msg_class", "error_wid_login" );
				$aperror->add( "reg_error_msg", __('We have just sent you an email with Password reset instructions.','login-sidebar-widget') );	
			}
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		} else {
			$aperror->add( "reg_msg_class", "error_wid_login" );
			$aperror->add( "reg_error_msg", $msg );	
		}
	}
}
