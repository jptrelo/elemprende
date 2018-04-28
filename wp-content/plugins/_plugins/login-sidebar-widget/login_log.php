<?php
class afo_login_log{
	
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'login_log_afo_menu' ) );
	}
	
	public function login_log_afo_menu () {
		add_submenu_page( 'login_widget_afo', 'Login Logs', 'Login Logs', 'activate_plugins', 'login_log_afo', array( $this, 'login_log_afo_options' ));
	}
	
	public function  login_log_afo_options () {
		echo '<div class="wrap">';
		global $wpdb;
		$lmc = new login_message_class;
		$query = "SELECT `ip`,`msg`,`l_added`,`l_status` FROM `".$wpdb->base_prefix."login_log` ORDER BY `l_added` DESC";
		$ap = new afo_paginate(100);
		$data = $ap->initialize($query,@$_REQUEST['paged']);
		$lmc->show_message();
		$empty_log_url = wp_nonce_url( "admin.php?page=login_log_afo&action=empty_log", 'empty_login_log', 'trash_log' );
		?>
        <table width="100%" style="margin:5px 0px;">
		 <tr>
         	<td><h3><?php _e('Login Logs','login-sidebar-widget');?> </h3></td>
            <td align="right"><a href="<?php echo $empty_log_url;?>" class="page-title-action"><?php _e('Clear Login Log','login-sidebar-widget');?></a></td>
         </tr>
        </table>
        <div style="height:300px; overflow-y:scroll;background-color:#FFFFFF; border:1px solid #999999; margin-top:10px;">
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
		 <tr style="background-color:#F1F1F1;">
			<td width="30%"><strong><?php _e('IP','login-sidebar-widget');?></strong></td>
            <td width="30%"><strong><?php _e('Message','login-sidebar-widget');?></strong></td>
            <td width="20%"><strong><?php _e('Time','login-sidebar-widget');?></strong></td>
            <td width="20%"><strong><?php _e('Status','login-sidebar-widget');?></strong></td>
		  </tr>
          <?php foreach ( $data as $d ) { ?>
          <tr>
			<td><?php echo $d['ip'];?></td>
            <td><?php echo $d['msg'];?></td>
            <td><?php echo $d['l_added'];?></td>
            <td><?php echo $d['l_status'];?></td>
		  </tr>
          <?php } ?>
		</table>
        </div>
        <table width="100%">
		 <tr>
			<td><?php $ap->paginate();?></td>
		  </tr>
		</table>
		<table width="100%" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:5px 0px 5px 10px;">
            <tr>
            <td>
            Use <strong><a href="http://www.aviplugins.com/fb-login-widget-pro/" target="_blank">PRO</a></strong> version that has added security with <strong>Blocking IP</strong> after 5 wrong login attempts. <strong>Blocked IPs</strong> can be <strong>Whitelisted</strong> from admin panel or the <strong>Block</strong> gets automatically removed after <strong>1 Day</strong>.
            </td>
          </tr>
        </table>
        </div>
		<?php
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
		
	}
}