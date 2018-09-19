<?php
class afo_login_log{
	
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'login_log_afo_menu' ) );
	}
	
	public function login_log_afo_menu () {
		add_submenu_page( 'login_widget_afo', 'Login Logs', 'Login Logs', 'activate_plugins', 'login_log_afo', array( $this, 'login_log_afo_options' ));
	}
	
	public function  login_log_afo_options () {
		global $wpdb;
		$lmc = new login_message_class;
		$query = "SELECT `ip`,`msg`,`l_added`,`l_status` FROM `".$wpdb->base_prefix."login_log` ORDER BY `l_added` DESC";
		$ap = new afo_paginate(100);
		$data = $ap->initialize($query,@$_REQUEST['paged']);
		$empty_log_url = wp_nonce_url( "admin.php?page=login_log_afo&action=empty_log", 'empty_login_log', 'trash_log' );
		
		echo '<div class="wrap">';
		$lmc->show_message();
		login_settings::help_support();
        include( LSW_DIR_PATH . '/view/admin/login_log.php');
		login_settings::donate();
		echo '</div>';
	}
}


function login_log_ip_data(){
	if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == "empty_log"){
		if ( ! isset( $_REQUEST['trash_log'] ) || ! wp_verify_nonce( $_REQUEST['trash_log'], 'empty_login_log' ) ) {
		   wp_die( 'Sorry, your nonce did not verify.');
		} 
			
		global $wpdb;
		$lmc = new login_message_class;
		$wpdb->query("TRUNCATE TABLE ".$wpdb->base_prefix."login_log");
		$lmc->add_message('Log successfully cleared.','updated');
		return;
	}
}