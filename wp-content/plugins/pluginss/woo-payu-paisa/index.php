<?php
/*
Plugin Name: PayU Paisa - Woocommerce
Plugin URI: 
Description: This is PayU Paisa payment gateway for WooCommerce. Allows you to use PayU Paisa payment gateway with currency conversion in WooCommerce plugin and empowering any business to collect money online within a minutes.
Version: 1.5
Author: Nilesh Chourasia
Author URI: 
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

add_action('plugins_loaded', 'woocommerce_payupaisa_init', 0);
define('payupaisa_imgdir', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/images/');

function woocommerce_payupaisa_init(){
	if(!class_exists('WC_Payment_Gateway')) return;

    if( isset($_GET['msg']) && !empty($_GET['msg']) ){
        add_action('the_content', 'payupaisa_showMessage');
    }
    function payupaisa_showMessage($content){
            return '<div class="'.htmlentities($_GET['type']).'">'.htmlentities(urldecode($_GET['msg'])).'</div>'.$content;
    }

    /**
     * Gateway class
     */
	class WC_payupaisa extends WC_Payment_Gateway{
		public function __construct(){
			$this->id 					= 'payupaisa';
			$this->method_title 		= 'PayU Paisa';
			$this->method_description	= "Redefining Payments, Simplifying Lives";
			$this->has_fields 			= false;
			$this->init_form_fields();
			$this->init_settings();
			if ( $this->settings['showlogo'] == "yes" ) {
				$this->icon 			= payupaisa_imgdir . 'logo.png';
			}			
			$this->title 			= $this->settings['title'];
			$this->redirect_page_id = $this->settings['redirect_page_id'];
			$this -> enable_currency_conversion      = $this -> settings['enable_currency_conversion'];
			if ( $this->settings['testmode'] == "yes" ) {
				$this->liveurl 			= 'https://test.payu.in/_payment';
				$this->merchant_id 		= "JBZaLc";
				$this->salt 			= "GQs7yium";
				$this->description 		= $this->settings['description'].
										"<br/><br/><u>Test Mode is <strong>ACTIVE</strong>, use following Credit Card details:-</u><br/>".
										"Test Card Name: <strong><em style='#999999;'>any name</em></strong><br/>".
										"Test Card Number: <strong>5123456789012346</strong><br/>".
										"Test Card CVV: <strong>123</strong><br/>".
										"Test Card Expiry: <strong>01/2020</strong><br/>";
			} else {
				$this->liveurl 			= 'https://secure.payu.in/_payment';
				$this->merchant_id 		= $this->settings['merchant_id'];
				$this->salt 			= $this->settings['salt'];
				$this->description 		= $this->settings['description'];
			}					
			$this->msg['message'] 	= "";
			$this->msg['class'] 	= "";
					
			add_action('init', array(&$this, 'check_payupaisa_response'));
			//update for woocommerce >2.0
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_payupaisa_response' ) );
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
				/* 2.0.0 */
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
			} else {
				/* 1.6.6 */
				add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
			}
			
			add_action('woocommerce_receipt_payupaisa', array(&$this, 'receipt_page'));
		}
    
		function init_form_fields(){
			$this->form_fields = array(
				'enabled' => array(
					'title' 		=> __('Enable/Disable', 'nilesh'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable PayU Paisa Payment Module.', 'nilesh'),
					'default' 		=> 'no',
					'description' 	=> 'Show in the Payment List as a payment option'
				),
      			'title' => array(
					'title' 		=> __('Title:', 'nilesh'),
					'type'			=> 'text',
					'default' 		=> __('Online Payment', 'nilesh'),
					'description' 	=> __('This controls the title which the user sees during checkout.', 'nilesh'),
					'desc_tip' 		=> true
				),
      			'description' => array(
					'title' 		=> __('Description:', 'nilesh'),
					'type' 			=> 'textarea',
					'default' 		=> __('Pay securely by Credit or Debit Card or Internet Banking through PayU Secure Servers.', 'nilesh'),
					'description' 	=> __('This controls the description which the user sees during checkout.', 'nilesh'),
					'desc_tip' 		=> true
				),
      			'merchant_id' => array(
					'title' 		=> __('Merchant KEY', 'nilesh'),
					'type' 			=> 'text',
					'description' 	=> __('Given to Merchant by PayU Money'),
					'desc_tip' 		=> true
				),
      			'salt' => array(
					'title' 		=> __('Merchant SALT', 'nilesh'),
					'type' 			=> 'text',
					'description' 	=>  __('Given to Merchant by PayU Money', 'nilesh'),
					'desc_tip' 		=> true
                ),
				'showlogo' => array(
					'title' 		=> __('Show Logo', 'nilesh'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Show the "PayU Paisa" logo in the Payment Method section for the user', 'nilesh'),
					'default' 		=> 'yes',
					'description' 	=> __('Tick to show "PayU Paisa" logo'),
					'desc_tip' 		=> true
                ),
				'enable_currency_conversion' => array(
                    'title' => __('Currency Conversion to INR?', 'nilesh'),
                    'type' => 'checkbox',
                    'label' => __('Enable Currency Conversion to INR.', 'nilesh'),
                    'default' => 'no',
					'description'=> __('converted to equivalent amount in INR for faster payment processing'),
					'desc_tip' 		=> true
				),
      			'testmode' => array(
					'title' 		=> __('TEST Mode', 'nilesh'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable PayU Paisa TEST Transactions.', 'nilesh'),
					'default' 		=> 'no',
					'description' 	=> __('Tick to run TEST Transaction on the PayU Paisa platform'),
					'desc_tip' 		=> true
                ),
      			'redirect_page_id' => array(
					'title' 		=> __('Return Page'),
					'type' 			=> 'select',
					'options' 		=> $this->payupaisa_get_pages('Select Page'),
					'description' 	=> __('URL of success page', 'nilesh'),
					'desc_tip' 		=> true
                )
			);
		}
        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         **/
		public function admin_options(){
			echo '<h3>'.__('PayU Paisa', 'nilesh').'</h3>';
			echo '<p>'.__('Redefining Payments, Simplifying Lives! Empowering any business to collect money online within minutes').'</p>';
			echo '<table class="form-table">';
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			echo '</table>';
		}
        /**
         *  There are no payment fields for techpro, but we want to show the description if set.
         **/
		function payment_fields(){
			if($this->description) echo wpautop(wptexturize($this->description));
		}
		/**
		* Receipt Page
		**/
		function receipt_page($order){
			echo '<p>'.__('Thank you for your order, please click the button below to pay with PayU.', 'nilesh').'</p>';
			echo $this->generate_payupaisa_form($order);
		}
		/*currency convertor API*/
		function currency_convert($currency_from,$currency_to,$currency_input)
		{
			if ($currency_from != $currency_to)
			{
				$yql_base_url = "http://query.yahooapis.com/v1/public/yql";
				$yql_query = 'select * from yahoo.finance.xchange where pair in ("'.$currency_from.$currency_to.'")';
				$yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
				$yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
				$yql_session = curl_init($yql_query_url);
				curl_setopt($yql_session, CURLOPT_RETURNTRANSFER,true);
				$yqlexec = curl_exec($yql_session);
				$yql_json =  json_decode($yqlexec,true);
				$currency_output = (float) $currency_input*$yql_json['query']['results']['rate']['Rate'];
		
				return $currency_output;
			}
			else
			{
				return $currency_input;
			}
		}		
		/**
		* Generate payu button link
		**/
		function generate_payupaisa_form($order_id){
			global $woocommerce;
			$order = new WC_Order( $order_id );
			$myTxnId = substr(str_shuffle(md5('abcdefghijklmnop9876543210')),0,8);
			
			if ( $this->redirect_page_id == "" || $this->redirect_page_id == 0 ) {
				$redirect_url = $order->get_checkout_order_received_url();
			} else {
				$redirect_url = get_permalink($this->redirect_page_id);
			}

			//For wooCoomerce 2.0
			if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
				$redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
			}

			$productinfo = "Order $order_id";
			/*check if currency converstion is enable*/
			$the_order_total = $order->order_total;
			if($this->enable_currency_conversion=='yes')
			{
				$the_order_total = $this->currency_convert($the_currency, 'INR', $the_order_total);
				$the_display_msg = "<small> $the_currency has been converted to equivalent amount in INR for faster payment processing.</small><br />";
			}
			/*-------------------------*/
			$str = $this->merchant_id."|".$myTxnId."|".$the_order_total."|".$productinfo."|".$order->billing_first_name."|".$order->billing_email."|||||||||||".$this->salt;

			$hash = hash('sha512', $str);

			$payupaisa_args = array(
				'key' 			=> $this->merchant_id,
				'hash' 			=> $hash,
				'txnid' 		=> $myTxnId,
				'amount' 		=> $the_order_total,
				'debug'			=>	1,
				'firstname'		=> $order->billing_first_name,
				'email' 		=> $order->billing_email,
				'phone' 		=> $order->billing_phone,
				'productinfo'	=> $productinfo,
				'surl' 			=> $redirect_url,
				'furl' 			=> $redirect_url,
				'lastname' 		=> $order->billing_last_name,
				'address1' 		=> $order->billing_address_1,
				'address2' 		=> $order->billing_address_2,
				'city' 			=> $order->billing_city,
				'state' 		=> $order->billing_state,
				'country' 		=> $order->billing_country,
				'zipcode' 		=> $order->billing_postcode,
				'curl'			=> $redirect_url,
				'pg' 			=> 'NB'
			);
			$payupaisa_args_array = array();
			foreach($payupaisa_args as $key => $value){
				$payupaisa_args_array[] = "<input type='hidden' name='$key' value='$value'/>";
			}
			
			return '	<form action="'.$this->liveurl.'" method="post" id="payupaisa_payment_form">
  				' . implode('', $payupaisa_args_array) . '
				<input type="submit" class="button-alt" id="submit_payupaisa_payment_form" value="'.__('Pay via PayU Paisa', 'nilesh').'" /> <a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Cancel order &amp; restore cart', 'nilesh').'</a>
					<script type="text/javascript">
					jQuery(function(){
					jQuery("body").block({
						message: "'.__('Thank you for your order. We are now redirecting you to Payment Gateway to make payment.', 'nilesh').'",
						overlayCSS: {
							background		: "#fff",
							opacity			: 0.6
						},
						css: {
							padding			: 20,
							textAlign		: "center",
							color			: "#555",
							border			: "3px solid #aaa",
							backgroundColor	: "#fff",
							cursor			: "wait",
							lineHeight		: "32px"
						}
					});
					jQuery("#submit_payupaisa_payment_form").click();});
					</script>
				</form>';
		}
		/**
		* Process the payment and return the result
		**/
		function process_payment($order_id){
			global $woocommerce;
			$order = new WC_Order( $order_id );
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) {
				/* 2.1.0 */
				$checkout_payment_url = $order->get_checkout_payment_url( true );
			} else {
				/* 2.0.0 */
				$checkout_payment_url = get_permalink( get_option ( 'woocommerce_pay_page_id' ) );
			}

			return array(
				'result' => 'success', 
				'redirect' => add_query_arg(
					'order', 
					$order->id, 
					add_query_arg(
						'key', 
						$order->order_key, 
						$checkout_payment_url						
					)
				)
        	);
		}
		/**
		* Check for valid payu server callback
		**/
		function check_payupaisa_response(){
			global $woocommerce;
			if( isset($_REQUEST['txnid']) && isset($_REQUEST['mihpayid']) ){
				$order_id = $_REQUEST['udf1'];
				if($order_id != ''){
					try{
						$order = new WC_Order( $order_id );
						$hash = $_REQUEST['hash'];
						$status = $_REQUEST['status'];
						$checkhash = hash('sha512', "$this->salt|||||||||||$_REQUEST[email]|$_REQUEST[firstname]|$_REQUEST[productinfo]|$_REQUEST[amount]|$_REQUEST[txnid]|$this->merchant_id");
						$transauthorised = false;
						
						if( $order->status !=='completed' ){
							if($hash == $checkhash){
								$status = strtolower($status);
								if($status=="success"){
									$transauthorised = true;
									$this->msg['message'] = "Thank you for shopping with us. Your account has been charged and your transaction is successful.";
									$this->msg['class'] = 'woocommerce-message';
									if($order->status == 'processing'){
										$order->add_order_note('PayU Paisa ID: '.$_REQUEST['mihpayid'].' ('.$_REQUEST['txnid'].')<br/>PG: '.$_REQUEST['PG_TYPE'].'<br/>Bank Ref: '.$_REQUEST['bank_ref_num']);
									}else{
										$order->payment_complete();
										$order->add_order_note('PayU Paisa payment successful.<br/>PayU Paisa ID: '.$_REQUEST['mihpayid'].' ('.$_REQUEST['txnid'].')<br/>PG: '.$_REQUEST['PG_TYPE'].'<br/>Bank Ref: '.$_REQUEST['bank_ref_num']);
										$woocommerce -> cart -> empty_cart();
									}
								}else if($status=="pending"){
									$this->msg['message'] = "Thank you for shopping with us. Right now your payment status is pending. We will keep you posted regarding the status of your order through eMail";
									$this->msg['class'] = 'woocommerce-info';
									$order->add_order_note('PayU Paisa payment status is pending<br/>PayU Paisa ID: '.$_REQUEST['mihpayid'].' ('.$_REQUEST['txnid'].')<br/>PG: '.$_REQUEST['PG_TYPE'].'<br/>Bank Ref: '.$_REQUEST['bank_ref_num']);
									$order->update_status('on-hold');
									$woocommerce -> cart -> empty_cart();
								}else{
									$this->msg['class'] = 'woocommerce-error';
									$this->msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";
									$order->add_order_note('Transaction ERROR: '.$_REQUEST['error'].'<br/>PayU Paisa ID: '.$_REQUEST['mihpayid'].' ('.$_REQUEST['txnid'].')');
								}
							}else{
								$this->msg['class'] = 'error';
								$this->msg['message'] = "Security Error. Illegal access detected.";
							}
							if($transauthorised==false){
								$order->update_status('failed');
							}
						}
					}catch(Exception $e){
                        $msg = "Error";
					}
				}

                $redirect_url = ($this->redirect_page_id=="" || $this->redirect_page_id==0)?get_site_url() . "/":get_permalink($this->redirect_page_id);
                //For wooCoomerce 2.0
                $redirect_url = add_query_arg( array('msg'=> urlencode($this->msg['message']), 'type'=>$this->msg['class']), $redirect_url );

                wp_redirect( $redirect_url );
                exit;
			}
		}
		// get all pages
		function payupaisa_get_pages($title = false, $indent = true) {
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array();
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
                	$has_parent = $page->post_parent;
                	while($has_parent) {
                    	$prefix .=  ' - ';
                    	$next_page = get_post($has_parent);
                    	$has_parent = $next_page->post_parent;
                	}
            	}
            	// add to page list array array
            	$page_list[$page->ID] = $prefix . $page->post_title;
        	}
        	return $page_list;
    		}
		}
		/**
		* Add the Gateway to WooCommerce
		**/
		function woocommerce_add_payupaisa_gateway($methods) {
			$methods[] = 'WC_payupaisa';
			return $methods;
		}
		add_filter('woocommerce_payment_gateways', 'woocommerce_add_payupaisa_gateway' );
	}