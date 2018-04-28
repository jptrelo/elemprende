<?php

function login_validate(){
	if( isset($_POST['option']) and $_POST['option'] == "afo_user_login"){
		global $aperror;
		$lla = new login_log_adds;
		$aperror = new WP_Error;
		
		if($_POST['userusername'] != "" and $_POST['userpassword'] != ""){
			$creds = array();
			$creds['user_login'] = sanitize_text_field($_POST['userusername']);
			$creds['user_password'] = $_POST['userpassword'];
			
			if(isset($_POST['remember']) and $_POST['remember'] == "Yes"){
				$remember = true;
			} else {
				$remember = false;
			}
			$creds['remember'] = $remember;
			$user = wp_signon( $creds, true );
			if(isset($user->ID) and $user->ID != ''){
				wp_set_auth_cookie($user->ID, $remember);
				$lla->log_add($_SERVER['REMOTE_ADDR'], 'Login success', date("Y-m-d H:i:s"), 'success');
				wp_redirect( apply_filters( 'lwws_login_redirect', sanitize_text_field($_POST['redirect']), $user->ID ) );
				exit;
			} else{
				$aperror->add( "msg_class", "error_wid_login" );
				$aperror->add( "msg", __(get_login_error_message_text($user),'login-sidebar-widget') );								
				do_action('afo_login_log_front', $user);
			}
		} else {
			$aperror->add( "msg_class", "error_wid_login" );
			$aperror->add( "msg", __('Username or password is empty!','login-sidebar-widget') );
			$lla->log_add($_SERVER['REMOTE_ADDR'], 'Username or password is empty', date("Y-m-d H:i:s"), 'failed');
		}
	}
}