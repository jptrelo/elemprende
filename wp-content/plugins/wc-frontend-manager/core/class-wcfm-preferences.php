<?php
/**
 * WCFM plugin core
 *
 * Plugin WCFM Preferences Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   3.2.8
 */
 
class WCFM_Preferences {
	
	private $wcfm_module_options = array();
	private $wcfm_options = array();

	public function __construct() {
		global $WCFM;
		
		$this->wcfm_options = get_option( 'wcfm_options', array() );
		$this->wcfm_module_options = isset( $this->wcfm_options['module_options'] ) ? $this->wcfm_options['module_options'] : array();
		$this->wcfm_module_options = apply_filters( 'wcfm_module_options', $this->wcfm_module_options );
		
		add_filter( 'wcfm_is_pref_pat_for_product', array( &$this, 'wcfmpref_pay_for_product' ), 750 );
		add_filter( 'wcfm_is_pref_product_popup', array( &$this, 'wcfmpref_product_popup' ), 750 );
		add_filter( 'wcfm_is_pref_menu_manager', array( &$this, 'wcfmpref_menu_manager' ), 750 );
		add_filter( 'wcfm_is_pref_hover_submenu', array( &$this, 'wcfmpref_hover_submenu' ), 750 );
		add_filter( 'wcfm_is_pref_dashboard_logo', array( &$this, 'wcfmpref_dashboard_logo' ), 750 );
		add_filter( 'wcfm_is_pref_welcome_box', array( &$this, 'wcfmpref_welcome_box' ), 750 );
		
		add_filter( 'wcfm_is_pref_notice', array( &$this, 'wcfmpref_notice' ), 750 );
		add_filter( 'wcfm_is_pref_direct_message', array( &$this, 'wcfmpref_direct_message' ), 750 );
		add_filter( 'wcfm_is_pref_enquiry', array( &$this, 'wcfmpref_enquiry' ), 750 );
		add_filter( 'wcfm_is_pref_enquiry_tab', array( &$this, 'wcfmpref_enquiry_tab' ), 750 );
		add_filter( 'wcfm_is_pref_enquiry_button', array( &$this, 'wcfmpref_enquiry_button' ), 750 );
		add_filter( 'wcfm_is_pref_catalog', array( &$this, 'wcfmpref_catalog' ), 750 );
		add_filter( 'wcfm_is_pref_knowledgebase', array( &$this, 'wcfmpref_knowledgebase' ), 750 );
		add_filter( 'wcfm_is_pref_profile', array( &$this, 'wcfmpref_profile' ), 750 );
		add_filter( 'wcfm_is_pref_policies', array( &$this, 'wcfmpref_policies' ), 750 );
		add_filter( 'wcfm_is_pref_withdrawal', array( &$this, 'wcfmpref_withdrawal' ), 750 );
		add_filter( 'wcfm_is_pref_refund', array( &$this, 'wcfmpref_refund' ), 750 );
		
		add_filter( 'wcfm_is_pref_article', array( &$this, 'wcfmpref_article' ), 750 );
		
		add_filter( 'wcfm_is_pref_customer', array( &$this, 'wcfmpref_customer' ), 750 );
		
		add_filter( 'wcfm_is_pref_buddypress', array( &$this, 'wcfmpref_buddypress' ), 750 );
		
		add_filter( 'wcfm_is_pref_custom_field', array( &$this, 'wcfmpref_custom_field' ), 750 );
		
		add_filter( 'wcfm_is_allow_geo_lookup', array( &$this, 'wcfmpref_geo_lookup' ), 750 );
		
		// WCFM Marketplace Product Multivendor
		add_filter( 'wcfm_is_pref_product_multivendor', array( &$this, 'wcfmpref_product_multivendor' ), 750 );
		
	}
	
	// Pay for Product
  function wcfmpref_pay_for_product( $is_pref ) {
  	$pay_for_product = ( isset( $this->wcfm_module_options['pay_for_product'] ) ) ? $this->wcfm_module_options['pay_for_product'] : 'no';
  	if( $pay_for_product == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
	
	// Product popup
  function wcfmpref_product_popup( $is_pref ) {
  	$product_popup = ( isset( $this->wcfm_module_options['product_popup'] ) ) ? $this->wcfm_module_options['product_popup'] : 'no';
  	if( $product_popup == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Menu manager
  function wcfmpref_menu_manager( $is_pref ) {
  	$menu_manager = ( isset( $this->wcfm_module_options['menu_manager'] ) ) ? $this->wcfm_module_options['menu_manager'] : 'no';
  	if( $menu_manager == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
	
	// Hover Sub menu
  function wcfmpref_hover_submenu( $is_pref ) {
  	$submenu = ( isset( $this->wcfm_module_options['submenu'] ) ) ? $this->wcfm_module_options['submenu'] : 'no';
  	if( $submenu == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Dashboard Loago
  function wcfmpref_dashboard_logo( $is_pref ) {
  	$submenu = ( isset( $this->wcfm_options['dashboard_logo_disabled'] ) ) ? $this->wcfm_options['dashboard_logo_disabled'] : 'no';
  	if( $submenu == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Welcome Box
  function wcfmpref_welcome_box( $is_pref ) {
  	$submenu = ( isset( $this->wcfm_options['welcome_box_disabled'] ) ) ? $this->wcfm_options['welcome_box_disabled'] : 'no';
  	if( $submenu == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Notice
  function wcfmpref_notice( $is_pref ) {
  	$notice = ( isset( $this->wcfm_module_options['notice'] ) ) ? $this->wcfm_module_options['notice'] : 'no';
  	if( $notice == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Direct Message
  function wcfmpref_direct_message( $is_pref ) {
  	$direct_message = ( isset( $this->wcfm_module_options['direct_message'] ) ) ? $this->wcfm_module_options['direct_message'] : 'no';
  	if( $direct_message == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Enquiry
  function wcfmpref_enquiry( $is_pref ) {
  	$enquiry = ( isset( $this->wcfm_module_options['enquiry'] ) ) ? $this->wcfm_module_options['enquiry'] : 'no';
  	if( $enquiry == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Enquiry Tab
  function wcfmpref_enquiry_tab( $is_pref ) {
  	$enquiry_tab = ( isset( $this->wcfm_module_options['enquiry_tab'] ) ) ? $this->wcfm_module_options['enquiry_tab'] : 'no';
  	if( $enquiry_tab == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Enquiry Button
  function wcfmpref_enquiry_button( $is_pref ) {
  	$is_enquiry_button_disabled = ( isset( $this->wcfm_options['enquiry_button_disabled'] ) ) ? $this->wcfm_options['enquiry_button_disabled'] : 'no';
  	if( $is_enquiry_button_disabled == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Catalog
  function wcfmpref_catalog( $is_pref ) {
  	$catalog = ( isset( $this->wcfm_module_options['catalog'] ) ) ? $this->wcfm_module_options['catalog'] : 'no';
  	if( $catalog == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Knowledgebase
  function wcfmpref_knowledgebase( $is_pref ) {
  	$knowledgebase = ( isset( $this->wcfm_module_options['knowledgebase'] ) ) ? $this->wcfm_module_options['knowledgebase'] : 'no';
  	if( $knowledgebase == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Profile
  function wcfmpref_profile( $is_pref ) {
  	$profile = ( isset( $this->wcfm_module_options['profile'] ) ) ? $this->wcfm_module_options['profile'] : 'no';
  	if( $profile == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Plocies
  function wcfmpref_policies( $is_pref ) {
  	$policies = ( isset( $this->wcfm_module_options['policies'] ) ) ? $this->wcfm_module_options['policies'] : 'no';
  	if( $policies == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Withdrawal
  function wcfmpref_withdrawal( $is_pref ) {
  	$withdrawal = ( isset( $this->wcfm_module_options['withdrawal'] ) ) ? $this->wcfm_module_options['withdrawal'] : 'no';
  	if( $withdrawal == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Refund
  function wcfmpref_refund( $is_pref ) {
  	$refund = ( isset( $this->wcfm_module_options['refund'] ) ) ? $this->wcfm_module_options['refund'] : 'no';
  	if( $refund == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Article
  function wcfmpref_article( $is_pref ) {
  	$article = ( isset( $this->wcfm_module_options['article'] ) ) ? $this->wcfm_module_options['article'] : 'no';
  	if( $article == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Customer
  function wcfmpref_customer( $is_pref ) {
  	$customer = ( isset( $this->wcfm_module_options['customer'] ) ) ? $this->wcfm_module_options['customer'] : 'no';
  	if( $customer == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // BuddyPress
  function wcfmpref_buddypress( $is_pref ) {
  	$buddypress = ( isset( $this->wcfm_module_options['buddypress'] ) ) ? $this->wcfm_module_options['buddypress'] : 'no';
  	if( $buddypress == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Custom Field
  function wcfmpref_custom_field( $is_pref ) {
  	$custom_field = ( isset( $this->wcfm_module_options['custom_field'] ) ) ? $this->wcfm_module_options['custom_field'] : 'no';
  	if( $custom_field == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
  // Geo Lookup
  function wcfmpref_geo_lookup( $is_pref ) {
  	if ( !is_wcfm_analytics() || !WCFM_Dependencies::wcfma_plugin_active_check() ) {
  		$is_pref = false;
  	}
  	return $is_pref;
  }
  
  // WCFM Marketplace Product Multivendor
  function wcfmpref_product_multivendor( $is_pref ) {
  	global $WCFM, $WCFMmp;
  	$product_mulivendor = isset( $WCFMmp->wcfmmp_marketplace_options['product_mulivendor'] ) ? $WCFMmp->wcfmmp_marketplace_options['product_mulivendor'] : 'yes';
  	if( $product_mulivendor == 'no' ) $is_pref = false;
  	return $is_pref;
  }
  
}