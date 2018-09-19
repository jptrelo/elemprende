<?php
function login_widget_afo_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$aplf = new ap_login_form;
	if($title){
		echo '<h2>'. esc_html( $title ) .'</h2>';
	}
	$aplf->login_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function forgot_password_afo_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$fpc = new afo_forgot_pass_class;
	if($title){
		echo '<h2>'. esc_html( $title ) .'</h2>';
	}
	$fpc->forgot_pass_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
