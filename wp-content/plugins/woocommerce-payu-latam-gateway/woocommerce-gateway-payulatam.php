<?php
/*
Plugin Name: WooCommerce - PayU Latam Gateway
Plugin URI: http://codeiseverywhere.com/wordpress-plugins/woocommerce-payu-latam-gateway-plugin/
Description: PayU Latinoamerica Payment Gateway for WooCommerce. Recibe pagos en internet en latinoamérica desde cualquier parte del mundo. ¡La forma más rápida, sencilla y segura para vender y recibir pagos por internet!
Version: 1.2.3
Author: Code is everywhere - Jairo Ivan Rondon Mejia
Author URI: http://www.codeiseverywhere.com/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

add_action('plugins_loaded', 'woocommerce_payulatam_init', 0);
define('PAYU_ASSETS', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets');

function woocommerce_payulatam_init(){
	if(!class_exists('WC_Payment_Gateway')) return;

    if( isset($_GET['msg']) && !empty($_GET['msg']) ){
        add_action('the_content', 'showPayuLatamMessage');
    }
    function showPayuLatamMessage($content){
            return '<div class="'.htmlentities($_GET['type']).'">'.htmlentities(urldecode($_GET['msg'])).'</div>'.$content;
    }

    /**
	 * PayU Gateway Class
     *
     * @access public
     * @param 
     * @return 
     */
	class WC_payulatam extends WC_Payment_Gateway{
		
		public function __construct(){
			global $woocommerce;
			$this->load_plugin_textdomain();
	        //add_action('init', array($this, 'load_plugin_textdomain'));

			$this->id 					= 'payulatam';
			$this->icon_default   		= $this->get_country_icon(false);
			$this->method_title 		= __('PayU Latam','payu-latam-woocommerce');
			$this->method_description	= __("The easiest way to sell and recive payments online in latinamerica",'payu-latam-woocommerce');
			$this->has_fields 			= false;
			
			$this->init_form_fields();
			$this->init_settings();
			$this->language 		= get_bloginfo('language');

			$this->testmode 		= $this->settings['testmode'];
			$this->testmerchant_id	= '508029';
			$this->testaccount_id	= '512321';
			$this->testapikey		= '4Vj8eK4rloUd272L48hsrarnUA';
			$this->debug = "no";

			$this->show_methods		= $this->settings['show_methods'];
			$this->icon_checkout 	= $this->settings['icon_checkout'];

			if($this->show_methods=='yes'&&trim($this->settings['icon_checkout'])=='') {
				$this->icon =  $this->icon_default;
			}elseif(trim($this->settings['icon_checkout'])!=''){
				$this->icon = $this->settings['icon_checkout'];
			}else{
				$this->icon = $this->get_country_icon();
			}

			$this->title 			= $this->settings['title'];
			$this->description 		= $this->settings['description'];
			$this->merchant_id 		= ($this->testmode=='yes')?$this->testmerchant_id:$this->settings['merchant_id'];
			$this->account_id 		= ($this->testmode=='yes')?$this->testaccount_id:$this->settings['account_id'];
			$this->apikey 			= ($this->testmode=='yes')?$this->testapikey:$this->settings['apikey'];
			$this->redirect_page_id = $this->settings['redirect_page_id'];
			$this->endpoint 		= $this->settings['endpoint'];
			$this->payu_language 	= $this->settings['payu_language'];
			$this->taxes 			= $this->settings['taxes'];
			$this->tax_return_base 	= $this->settings['tax_return_base'];
			$this->currency 		= ($this->is_valid_currency())?get_woocommerce_currency():'USD';
			$this->textactive 		= 0;
			$this->form_method 		= $this->settings['form_method'];
			$this->liveurl 			= 'https://gateway.payulatam.com/ppp-web-gateway/';
			$this->testurl 			= 'https://sandbox.gateway.payulatam.com/ppp-web-gateway/';

			/* mesagges */
			$this->msg_approved			= $this->settings['msg_approved'];
			$this->msg_declined			= $this->settings['msg_declined'];
			$this->msg_cancel 			= $this->settings['msg_cancel'];
			$this->msg_pending			= $this->settings['msg_pending'];

			if ($this->testmode == "yes")
				$this->debug = "yes";

			add_filter( 'woocommerce_currencies', 'add_all_currency' );
			add_filter( 'woocommerce_currency_symbol', 'add_all_symbol', 10, 2);

			$this->msg['message'] 	= "";
			$this->msg['class'] 	= "";
			// Logs
			
				if(version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')){
					$this->log = new WC_Logger();
				}else{
					$this->log = $woocommerce->logger();
				}
			
			
					
			add_action('payulatam_init', array( $this, 'pauylatam_successful_request'));
			add_action( 'woocommerce_receipt_payulatam', array( $this, 'receipt_page' ) );
			//update for woocommerce >2.0
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_payulatam_response' ) );
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
				/* 2.0.0 */
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
			} else {
				/* 1.6.6 */
				add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
			}
			
		}

	    public function load_plugin_textdomain()
	    {
			load_plugin_textdomain( 'payu-latam-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	    }

		/**
		 * Show payment metods by country
		 */
		
		public function get_country_icon($default=true){
			$country = '';
			if(!$default)
				$country = WC()->countries->get_base_country();

			$icon = PAYU_ASSETS.'/img/payulogo'.$country.'.png';
			return $icon;
		}
    	/**
		 * Check if Gateway can be display 
	     *
	     * @access public
	     * @return void
	     */
	    function is_available() {
			global $woocommerce;

			if ( $this->enabled=="yes" ) :
				
				if ( !$this->is_valid_currency()) return false;
				
				if ( $woocommerce->version < '1.5.8' ) return false;

				if ($this->testmode!='yes'&&(!$this->merchant_id || !$this->account_id || !$this->apikey )) return false;

				return true;
			endif;

			return false;
		}
		
    	/**
		 * Settings Options
	     *
	     * @access public
	     * @return void
	     */
		function init_form_fields(){
			$this->form_fields = array(
				'enabled' => array(
					'title' 		=> __('Enable/Disable', 'payu-latam-woocommerce'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable PayU Latam Payment Module.', 'payu-latam-woocommerce'),
					'default' 		=> 'no',
					'description' 	=> __('Show in the Payment List as a payment option', 'payu-latam-woocommerce')
				),
				'show_methods' => array(
					'title' 		=> __('Mostrar Metodos', 'payu-latam-woocommerce'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Mostrar metodos de pago por Pais.', 'payu-latam-woocommerce'),
					'default' 		=> 'no',
					'description' 	=> __('Mostrar imagen de los metodos de pago soportados por Pais.', 'payu-latam-woocommerce')
				),
      			'icon_checkout' => array(
					'title' 		=> __('Logo en el checkout:', 'payu-latam-woocommerce'),
					'type'			=> 'text',
					'default'		=> $this->get_country_icon(),
					'description' 	=> __('URL de la Imagen para mostrar en el carrro de compra.', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
				),
      			'title' => array(
					'title' 		=> __('Title:', 'payu-latam-woocommerce'),
					'type'			=> 'text',
					'default' 		=> __('PayU Latam Online Payments', 'payu-latam-woocommerce'),
					'description' 	=> __('This controls the title which the user sees during checkout.', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
				),
      			'description' => array(
					'title' 		=> __('Description:', 'payu-latam-woocommerce'),
					'type' 			=> 'textarea',
					'default' 		=> __('Pay securely by Credit or Debit Card or Internet Banking through PayU Latam Secure Servers.','payu-latam-woocommerce'),
					'description' 	=> __('This controls the description which the user sees during checkout.', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
				),
      			'merchant_id' => array(
					'title' 		=> __('Merchant ID', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'description' 	=> __('Given to Merchant by PayU Latam', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
				),
      			'account_id' => array(
					'title' 		=> __('Account ID', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'description' 	=> __('Some Countrys (Brasil, Mexico) require this ID, Gived to you by PayU Latam on regitration.', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
				),
      			'apikey' => array(
					'title' 		=> __('ApiKey', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'description' 	=>  __('Given to Merchant by PayU Latam', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'testmode' => array(
					'title' 		=> __('TEST Mode', 'payu-latam-woocommerce'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable PayU Latam TEST Transactions.', 'payu-latam-woocommerce'),
					'default' 		=> 'no',
					'description' 	=> __('Tick to run TEST Transaction on the PayU Latam platform', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
                'taxes' => array(
					'title' 		=> __('Tax Rate - Read', 'payu-latam-woocommerce').' <a target="_blank" href="http://docs.payulatam.com/manual-integracion-web-checkout/informacion-adicional/tablas-de-variables-complementarias/">PayU Documentacion</a>',
					'type' 			=> 'text',
					'default' 		=> '0',
					'description' 	=> __('Tax rates for Transactions (IVA).', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
		        ),
      			'tax_return_base' => array(
					'title' 		=> __('Tax Return Base', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					//'options' 		=> array('0' => 'None', '2' => '2% Credit Cards Payments Return (Colombia)'),
					'default' 		=> '0',
					'description' 	=> __('Tax base to calculate IVA ', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'payu_language' => array(
					'title' 		=> __('Gateway Language', 'payu-latam-woocommerce'),
					'type' 			=> 'select',
					'options' 		=> array('ES' => 'ES', 'EN' => 'EN', 'PT' => 'PT'),
					'description' 	=> __('PayU Latam Gateway Language ', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'form_method' => array(
					'title' 		=> __('Form Method', 'payu-latam-woocommerce'),
					'type' 			=> 'select',
					'default' 		=> 'POST',
					'options' 		=> array('POST' => 'POST', 'GET' => 'GET'),
					'description' 	=> __('Checkout form submition method ', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'redirect_page_id' => array(
					'title' 		=> __('Return Page', 'payu-latam-woocommerce'),
					'type' 			=> 'select',
					'options' 		=> $this->get_pages(__('Select Page', 'payu-latam-woocommerce')),
					'description' 	=> __('URL of success page', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
                'endpoint' => array(
					'title' 		=> __('Page End Point (Woo > 2.1)', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'default' 		=> '',
					'description' 	=> __('Return Page End Point.', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
		        ),
      			'msg_approved' => array(
					'title' 		=> __('Message for approved transaction', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'default' 		=> __('PayU Latam Payment Approved', 'payu-latam-woocommerce'),
					'description' 	=> __('Message for approved transaction', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'msg_pending' => array(
					'title' 		=> __('Message for pending transaction', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'default' 		=> __('Payment pending', 'payu-latam-woocommerce'),
					'description' 	=> __('Message for pending transaction', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'msg_cancel' => array(
					'title' 		=> __('Message for cancel transaction', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'default' 		=> __('Transaction Canceled.', 'payu-latam-woocommerce'),
					'description' 	=> __('Message for cancel transaction', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
      			'msg_declined' => array(
					'title' 		=> __('Message for declined transaction', 'payu-latam-woocommerce'),
					'type' 			=> 'text',
					'default' 		=> __('Payment rejected via PayU Latam.', 'payu-latam-woocommerce'),
					'description' 	=> __('Message for declined transaction ', 'payu-latam-woocommerce'),
					'desc_tip' 		=> true
                ),
			);
                
		} 

        /**
         * Generate Admin Panel Options
	     *
	     * @access public
	     * @return string
         **/
		public function admin_options(){
			echo '<img src="'.$this->get_country_icon().'" alt="PayU" width="80"><h3>'.__('PayU Latam', 'payu-latam-woocommerce').'</h3>';
			echo '<p>'.__('The easiest way to sell and recive payments online in latinamerica', 'payu-latam-woocommerce').'</p>';
			echo '<table class="form-table">';
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			echo '</table>';
		}
        /**
		 * Generate the PayU Latam Payment Fields
	     *
	     * @access public
	     * @return string
	     */
		function payment_fields(){
			if($this->description) echo wpautop(wptexturize($this->description));
		}
		/**
		 * Generate the PayU Latam Form for checkout
	     *
	     * @access public
	     * @param mixed $order
	     * @return string
		**/
		function receipt_page($order){
			echo '<p>'.__('Thank you for your order, please click the button below to pay with PayU Latam.', 'payu-latam-woocommerce').'</p>';
			echo $this->generate_payulatam_form($order);
		}
		/**
		 * Generate PayU POST arguments
	     *
	     * @access public
	     * @param mixed $order_id
	     * @return string
		**/
		function get_payulatam_args($order_id){
			global $woocommerce;
			$order = new WC_Order( $order_id );
			$txnid = $order->order_key.'-'.time();
			
			$redirect_url = ($this->redirect_page_id=="" || $this->redirect_page_id==0)?get_site_url() . "/":get_permalink($this->redirect_page_id);
			//For wooCoomerce 2.0
			$redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
			$redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
			$redirect_url = add_query_arg( '', $this->endpoint, $redirect_url );

			$productinfo = "Order $order_id";
			
				$str = "$this->apikey~$this->merchant_id~$txnid~$order->order_total~$this->currency";
				$hash = strtolower(md5($str));
			
			
			$payulatam_args = array(
				'merchantId' 		=> $this->merchant_id,
				'accountId' 		=> $this->account_id,
				'signature' 		=> $hash,
				'referenceCode' 	=> $txnid,
				'amount' 			=> $order->order_total,
				'currency' 			=> $this->currency,
				'payerFullName'		=> $order->billing_first_name .' '.$order->billing_last_name,
				'buyerEmail' 		=> $order->billing_email,
				'telephone' 		=> $order->billing_phone,
				'billingAddress' 	=> $order->billing_address_1.' '.$order->billing_address_2,
				'shippingAddress' 	=> $order->billing_address_1.' '.$order->billing_address_2,
				'billingCity' 		=> $order->billing_city,
				'shippingCity' 		=> $order->billing_city,
				'billingCountry' 	=> $order->billing_country,
				'shippingCountry' 	=> $order->billing_country,
				'zipCode' 			=> $order->billing_postcode,
				'lng'				=> $this->payu_language,
				'description'		=> $productinfo,
				'responseUrl' 		=> $redirect_url,
				'confirmationUrl'	=> $redirect_url,
				'tax' 				=> $this->taxes,
				'taxReturnBase'		=> $this->tax_return_base,
				'extra1'			=> $order->order_id,
				'discount' 			=> '0'
			);

			if ( $this->testmode == 'yes' ){
				$payulatam_args['ApiKey'] = $this->testapikey;
				$payulatam_args['test'] = '1';
			}else{
				$payulatam_args['ApiKey'] = $this->apikey;
			}

			return $payulatam_args;
		}

		/**
		 * Generate the PayU Latam button link
	     *
	     * @access public
	     * @param mixed $order_id
	     * @return string
	    */
	    function generate_payulatam_form( $order_id ) {
			global $woocommerce;

			$order = new WC_Order( $order_id );

			if ( $this->testmode == 'yes' )
				$payulatam_adr = $this->testurl;
			else 
				$payulatam_adr = $this->liveurl;
			

			$payulatam_args = $this->get_payulatam_args( $order_id );
			$payulatam_args_array = array();

			foreach ($payulatam_args as $key => $value) {
				$payulatam_args_array[] = '<input type="hidden" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
			}
			$code='jQuery("body").block({
						message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to PayU Latam to make payment.', 'payu-latam-woocommerce' ) ) . '",
						baseZ: 99999,
						overlayCSS:
						{
							background: "#fff",
							opacity: 0.6
						},
						css: {
					        padding:        "20px",
					        zindex:         "9999999",
					        textAlign:      "center",
					        color:          "#555",
					        border:         "3px solid #aaa",
					        backgroundColor:"#fff",
					        cursor:         "wait",
					        lineHeight:		"24px",
					    }
					});
				jQuery("#submit_payulatam_payment_form").click();';

			if (version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')) {
				 wc_enqueue_js($code);
			} else {
				$woocommerce->add_inline_js($code);
			}

			return '<form action="'.$payulatam_adr.'" method="POST" id="payulatam_payment_form" target="_top">
					' . implode( '', $payulatam_args_array) . '
					<input type="submit" class="button alt" id="submit_payulatam_payment_form" value="' . __( 'Pay via PayU Latam', 'payu-latam-woocommerce' ) . '" /> <a class="button cancel" href="'.esc_url( $order->get_cancel_order_url() ).'">'.__( 'Cancel order &amp; restore cart', 'woocommerce' ).'</a>
				</form>';
		}

		/**
	     * Process the payment and return the result
	     *
	     * @access public
	     * @param int $order_id
	     * @return array
	     */
		function process_payment( $order_id ) {
			$order = new WC_Order( $order_id );
			if ( $this->form_method == 'GET' ) {
				$payulatam_args = $this->get_payulatam_args( $order_id );
				$payulatam_args = http_build_query( $payulatam_args, '', '&' );
				if ( $this->testmode == 'yes' ):
					$payulatam_adr = $this->testurl . '&';
				else :
					$payulatam_adr = $this->liveurl . '?';
				endif;

				return array(
					'result' 	=> 'success',
					'redirect'	=> $payulatam_adr . $payulatam_args
				);
			} else {
				if (version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')) {
					return array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))
					);
				} else {
					return array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))
					);
				}

			}

		}
		/**
		 * Check for valid payu server callback
		 *
		 * @access public
		 * @return void
		**/
		function check_payulatam_response(){
			@ob_clean();
	    	if ( ! empty( $_REQUEST ) ) {
	    		header( 'HTTP/1.1 200 OK' );
	        	do_action( "payulatam_init", $_REQUEST );
			} else {
				wp_die( __("PayU Latam Request Failure", 'payu-latam-woocommerce') );
	   		}
		
		}

		/**
		 * Process Payu Response and update the order information
		 *
		 * @access public
		 * @param array $posted
		 * @return void
		 */
		function pauylatam_successful_request( $posted ) {
			global $woocommerce;
			
			if ( ! empty( $posted['transactionState'] ) && ! empty( $posted['referenceCode'] ) ) {
				$this->payulatam_return_process($posted);
			}
			if ( ! empty( $posted['state_pol'] ) && ! empty( $posted['reference_sale'] ) ) {
				$this->payulatam_confirmation_process($posted);
			}

			$redirect_url = $woocommerce->cart->get_checkout_url();
            //For wooCoomerce 2.0
            $redirect_url = add_query_arg( array('msg'=> urlencode(__( 'There was an error on the request. please contact the website administrator.', 'payulatam' )), 'type'=>$this->msg['class']), $redirect_url );

            wp_redirect( $redirect_url );
            exit;
		}

		/*
		* Procesar pagina de respuesta
		*
		* 
		 */
		function payulatam_return_process($posted)
		{
			global $woocommerce;

		    $order = $this->get_payulatam_order( $posted );

	        $codes=array('4' => 'APPROVED' ,'6' => 'DECLINED' ,'104' => 'ERROR' ,'5' => 'EXPIRED','7' => 'PENDING' );

		     if ( 'yes' == $this->debug )
	        	$this->log->add( 'payulatam', 'PAYULATAM Found order #' . $order->id );


	        if ( 'yes' == $this->debug )
	        	$this->log->add( 'payulatam', 'PAYULATAM Transaction state: ' . $posted['transactionState'] );
	        

	        	$state=$posted['transactionState'];
	        	// We are here so lets check status and do actions
		        switch ( $codes[$state] ) {
		            case 'APPROVED' :
		            case 'PENDING' :

		            	// Check order not already completed
		            	if ( $order->status == 'completed' ) {
		            		 if ( 'yes' == $this->debug )
		            		 	$this->log->add( 'payulatam', __('Aborting, Order #' . $order->id . ' is already complete.', 'payu-latam-woocommerce') );
		            		 exit;
		            	}

						// Validate Amount
					    if ( $order->get_total() != $posted['TX_VALUE'] ) {
					    	$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayU Latam amounts do not match (gross %s).', 'payu-latam-woocommerce'), $posted['TX_VALUE'] ) );

							$this->msg['message'] = sprintf( __( 'Validation error: PayU Latam amounts do not match (gross %s).', 'payu-latam-woocommerce'), $posted['TX_VALUE'] );
							$this->msg['class'] = 'woocommerce-error';	

					    }

					    // Validate Merchand id 
						if ( strcasecmp( trim( $posted['merchantId'] ), trim( $this->merchant_id ) ) != 0 ) {
					    	$order->update_status( 'on-hold', sprintf( __( 'Validation error: Payment in PayU Latam comes from another id (%s).', 'payu-latam-woocommerce'), $posted['merchantId'] ) );
							$this->msg['message'] = sprintf( __( 'Validation error: Payment in PayU Latam comes from another id (%s).', 'payu-latam-woocommerce'), $posted['merchantId'] );
							$this->msg['class'] = 'woocommerce-error';

						}

						 // Payment Details
		                if ( ! empty( $posted['buyerEmail'] ) )
		                	update_post_meta( $order->id, __('Payer PayU Latam email', 'payu-latam-woocommerce'), $posted['buyerEmail'] );
		                if ( ! empty( $posted['transactionId'] ) )
		                	update_post_meta( $order->id, __('Transaction ID', 'payu-latam-woocommerce'), $posted['transactionId'] );
		                if ( ! empty( $posted['trazabilityCode'] ) )
		                	update_post_meta( $order->id, __('Trasability Code', 'payu-latam-woocommerce'), $posted['trazabilityCode'] );
		                /*if ( ! empty( $posted['last_name'] ) )
		                	update_post_meta( $order->id, 'Payer last name', $posted['last_name'] );*/
		                if ( ! empty( $posted['lapPaymentMethodType'] ) )
		                	update_post_meta( $order->id, __('Payment type', 'payu-latam-woocommerce'), $posted['lapPaymentMethodType'] );

		                if ( $codes[$state] == 'APPROVED' ) {
		                	$order->add_order_note( __( 'PayU Latam payment approved', 'payu-latam-woocommerce') );
							$this->msg['message'] = $this->msg_approved;
							$this->msg['class'] = 'woocommerce-message';
		                	$order->payment_complete();
		                } else {
		                	$order->update_status( 'on-hold', sprintf( __( 'Payment pending: %s', 'payu-latam-woocommerce'), $codes[$state] ) );
							$this->msg['message'] = $this->msg_pending;
							$this->msg['class'] = 'woocommerce-info';
		                }

		            break;
		            case 'DECLINED' :
		            case 'EXPIRED' :
		            case 'ERROR' :
		                // Order failed
		                $order->update_status( 'failed', sprintf( __( 'Payment rejected via PayU Latam. Error type: %s', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );
							$this->msg['message'] = $this->msg_declined ;
							$this->msg['class'] = 'woocommerce-error';
		            break;
		            default :
		                $order->update_status( 'failed', sprintf( __( 'Payment rejected via PayU Latam.', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );
							$this->msg['message'] = $this->msg_cancel ;
							$this->msg['class'] = 'woocommerce-error';
		            break;
		        }

			$redirect_url = ($this->redirect_page_id=='default' || $this->redirect_page_id==""  || $this->redirect_page_id==0)?$order->get_checkout_order_received_url():get_permalink($this->redirect_page_id);
            //For wooCoomerce 2.0
            $redirect_url = add_query_arg( array('msg'=> urlencode($this->msg['message']), 'type'=>$this->msg['class']), $redirect_url );

            wp_redirect( $redirect_url );
            exit;
		}


		/*
		* Procesar pagina de confirmacion
		*
		* 
		 */
		function payulatam_confirmation_process($posted){
			global $woocommerce;
			    $order = $this->get_payulatam_order( $posted );

	        	$codes=array(
	        		'1' => 'CAPTURING_DATA' ,
	        		'2' => 'NEW' ,
	        		'101' => 'FX_CONVERTED' ,
	        		'102' => 'VERIFIED' ,
	        		'103' => 'SUBMITTED' ,
	        		'4' => 'APPROVED' ,
	        		'6' => 'DECLINED' ,
	        		'104' => 'ERROR' ,
	        		'7' => 'PENDING' ,
	        		'5' => 'EXPIRED'  
	        	);

			    if ( 'yes' == $this->debug )
		        	$this->log->add( 'payulatam', 'Found order #' . $order->id );

	        	$state=$posted['state_pol'];

		        if ( 'yes' == $this->debug )
		        	$this->log->add( 'payulatam', 'Payment status: ' . $codes[$state] );
	        
	        	// We are here so lets check status and do actions
		        switch ( $codes[$state] ) {
		            case 'APPROVED' :
		            case 'PENDING' :

		            	// Check order not already completed
		            	if ( $order->status == 'completed' ) {
		            		 if ( 'yes' == $this->debug )
		            		 	$this->log->add( 'payulatam', __('Aborting, Order #' . $order->id . ' is already complete.', 'payu-latam-woocommerce') );
		            		 exit;
		            	}

						// Validate Amount
					    if ( $order->get_total() != $posted['value'] ) {
					    	$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayU Latam amounts do not match (gross %s).', 'payu-latam-woocommerce'), $posted['value'] ) );

							$this->msg['message'] = sprintf( __( 'Validation error: PayU Latam amounts do not match (gross %s).', 'payu-latam-woocommerce'), $posted['value'] );
							$this->msg['class'] = 'woocommerce-error';	
					    }

					    // Validate Merchand id 
						if ( strcasecmp( trim( $posted['merchant_id'] ), trim( $this->merchant_id ) ) != 0 ) {

					    	$order->update_status( 'on-hold', sprintf( __( 'Validation error: Payment in PayU Latam comes from another id (%s).', 'payu-latam-woocommerce'), $posted['merchant_id'] ) );
							$this->msg['message'] = sprintf( __( 'Validation error: Payment in PayU Latam comes from another id (%s).', 'payu-latam-woocommerce'), $posted['merchant_id'] );
							$this->msg['class'] = 'woocommerce-error';
						}

						 // Payment details
		                if ( ! empty( $posted['email_buyer'] ) )
		                	update_post_meta( $order->id, __('PayU Latam Client email', 'payu-latam-woocommerce'), $posted['email_buyer'] );
		                if ( ! empty( $posted['transaction_id'] ) )
		                	update_post_meta( $order->id, __('Transaction ID', 'payu-latam-woocommerce'), $posted['transaction_id'] );
		                if ( ! empty( $posted['reference_pol'] ) )
		                	update_post_meta( $order->id, __('Trasability Code', 'payu-latam-woocommerce'), $posted['reference_pol'] );
		                if ( ! empty( $posted['sign'] ) )
		                	update_post_meta( $order->id, __('Tash Code', 'payu-latam-woocommerce'), $posted['sign'] );
		                if ( ! empty( $posted['ip'] ) )
		                	update_post_meta( $order->id, __('Transaction IP', 'payu-latam-woocommerce'), $posted['ip'] );

		               	update_post_meta( $order->id, __('Extra Data', 'payu-latam-woocommerce'), 'response_code_pol: '.$posted['response_code_pol'].' - '.'state_pol: '.$posted['state_pol'].' - '.'payment_method: '.$posted['payment_method'].' - '.'transaction_date: '.$posted['transaction_date'].' - '.'currency: '.$posted['currency'] );
		                

		                if ( ! empty( $posted['payment_method_type'] ) )
		                	update_post_meta( $order->id, __('Payment type', 'payu-latam-woocommerce'), $posted['payment_method_type'] );

		                if ( $codes[$state] == 'APPROVED' ) {
		                	$order->add_order_note( __( 'PayU Latam payment approved', 'payu-latam-woocommerce') );
							$this->msg['message'] =  $this->msg_approved;
							$this->msg['class'] = 'woocommerce-message';
		                	$order->payment_complete();

			            	if ( 'yes' == $this->debug ){ $this->log->add( 'payulatam', __('Payment complete.', 'payu-latam-woocommerce'));}

		                } else {
		                	$order->update_status( 'on-hold', sprintf( __( 'Payment pending: %s', 'payu-latam-woocommerce'), $codes[$state] ) );
							$this->msg['message'] = $this->msg_pending;
							$this->msg['class'] = 'woocommerce-info';
		                }


		            break;
		            case 'DECLINED' :
		            case 'EXPIRED' :
		            case 'ERROR' :
		            case 'ABANDONED_TRANSACTION':
		                // Order failed
		                $order->update_status( 'failed', sprintf( __( 'Payment rejected via PayU Latam. Error type: %s', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );
							$this->msg['message'] = $this->msg_declined ;
							$this->msg['class'] = 'woocommerce-error';
		            break;
		            default :
		                $order->update_status( 'failed', sprintf( __( 'Payment rejected via PayU Latam.', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );
							$this->msg['message'] = $this->msg_cancel ;
							$this->msg['class'] = 'woocommerce-error';
		            break;
		        }
		}
		

		/**
		 *  Get order information
		 *
		 * @access public
		 * @param mixed $posted
		 * @return void
		 */
		function get_payulatam_order( $posted ) {
			$custom =  $posted['order_id'];
			
	    	// Backwards comp for IPN requests
	    	
		    	$order_id = (int) $custom;
		    	$reference_code = ($posted['referenceCode'])?$posted['referenceCode']:$posted['reference_sale'];
	    		$order_key = explode('-', $reference_code);
				$order_key = ($order_key[0])?$order_key[0]:$order_key;

			$order = new WC_Order( $order_id );

			if ( ! isset( $order->id ) ) {
				$order_id 	= woocommerce_get_order_id_by_order_key( $order_key );
				$order 		= new WC_Order( $order_id );
			}

			// Validate key
			if ( $order->order_key !== $order_key ) {
	        	if ( $this->debug=='yes' )
	        		$this->log->add( 'payulatam', __('Error: Order Key does not match invoice.', 'payu-latam-woocommerce') );
	        	exit;
	        }

	        return $order;
		}


		/**
		 * Check if current currency is valid for PayU Latam
		 *
		 * @access public
		 * @return bool
		 */
		function is_valid_currency() {
			if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_payulatam_supported_currencies', array( 'CLP', 'ARS', 'BRL', 'COP', 'MXN', 'PEN', 'USD' ) ) ) ) return false;

			return true;
		}
		
		/**
		 * Get pages for return page setting
		 *
		 * @access public
		 * @return bool
		 */
		function get_pages($title = false, $indent = true) {
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array('default'=>__('Default Page','payu-latam-woocommerce'));
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
                	$has_parent = $page->post_parent;
                	while($has_parent) {
                    	$prefix .=  ' - ';
                    	$next_page = get_page($has_parent);
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
		 * Add all currencys supported by PayU Latem so it can be display 
		 * in the woocommerce settings
		 *
		 * @access public
		 * @return bool
		 */
		function add_all_currency( $currencies ) {
			$currencies['ARS'] = __( 'Argentine Peso', 'payu-latam-woocommerce');
			$currencies['BRL'] = __( 'Brasilian Real', 'payu-latam-woocommerce');
			$currencies['COP'] = __( 'Colombian Peso', 'payu-latam-woocommerce');
			$currencies['MXN'] = __( 'Mexican Peso', 'payu-latam-woocommerce');
			$currencies['CLP'] = __( 'Chile Peso', 'payu-latam-woocommerce');
			$currencies['PEN'] = __( 'Perubian New Sol', 'payu-latam-woocommerce');
			return $currencies;
		}
		/**
		 * Add simbols for all currencys in payu latam so it can be display 
		 * in the woocommerce settings
		 *
		 * @access public
		 * @return bool
		 */
		function add_all_symbol( $currency_symbol, $currency ) {
			switch( $currency ) {
			case 'ARS': $currency_symbol = '$'; break;
			case 'CLP': $currency_symbol = '$'; break;
			case 'BRL': $currency_symbol = 'R$'; break;
			case 'COP': $currency_symbol = '$'; break;
			case 'MXN': $currency_symbol = '$'; break;
			case 'PEN': $currency_symbol = 'S/.'; break;
			}
			return $currency_symbol;
		}
		/**
		* Add the Gateway to WooCommerce
		**/
		function woocommerce_add_payulatam_gateway($methods) {
			$methods[] = 'WC_payulatam';
			return $methods;
		}

		add_filter('woocommerce_payment_gateways', 'woocommerce_add_payulatam_gateway' );
	}

	/**
	 * Filter simbol for currency currently active so it can be display 
	 * in the front end
     *
     * @access public
     * @param (string) $currency_symbol, (string) $currency
     * @return (string) filtered currency simbol
     */
	function frontend_filter_currency_symbol( $currency_symbol, $currency ) {
		switch( $currency ) {
		case 'ARS': $currency_symbol = '$'; break;
		case 'CLP': $currency_symbol = '$'; break;
		case 'BRL': $currency_symbol = 'R$'; break;
		case 'COP': $currency_symbol = '$'; break;
		case 'MXN': $currency_symbol = '$'; break;
		case 'PEN': $currency_symbol = 'S/.'; break;
		}
		return $currency_symbol;
	}
	add_filter( 'woocommerce_currency_symbol', 'frontend_filter_currency_symbol', 1, 2);