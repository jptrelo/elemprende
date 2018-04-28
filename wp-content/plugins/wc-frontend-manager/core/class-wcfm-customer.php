<?php
/**
 * WCFM plugin core
 *
 * Plugin Cutomer Support Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   3.4.6
 */
 
class WCFM_Customer {

	public function __construct() {
		global $WCFM;
		
		if ( !is_admin() || defined('DOING_AJAX') ) {
			if( apply_filters( 'wcfm_is_allow_customers', true ) ) {
				// WC Customer Query Var Filter
				add_filter( 'wcfm_query_vars', array( &$this, 'customers_wcfm_query_vars' ), 20 );
				add_filter( 'wcfm_endpoint_title', array( &$this, 'customers_wcfm_endpoint_title' ), 20, 2 );
				add_action( 'init', array( &$this, 'customers_wcfm_init' ), 20 );
				
				// WCFM Customer Endpoint Edit
				add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfm_customers_endpoints_slug' ) );
				
				// WC Customer Menu Filter
				add_filter( 'wcfm_menus', array( &$this, 'customers_wcfm_menus' ), 20 );
				
				// Customers Load WCFMu Scripts
				add_action( 'wcfm_load_scripts', array( &$this, 'wcfm_customers_load_scripts' ), 30 );
				add_action( 'after_wcfm_load_scripts', array( &$this, 'wcfm_customers_load_scripts' ), 30 );
				
				// Customers Load WCFMu Styles
				add_action( 'wcfm_load_styles', array( &$this, 'wcfm_customers_load_styles' ), 30 );
				add_action( 'after_wcfm_load_styles', array( &$this, 'wcfm_customers_load_styles' ), 30 );
				
				// Customers Load WCFMu views
				add_action( 'wcfm_load_views', array( &$this, 'wcfm_customers_load_views' ), 30 );
				add_action( 'before_wcfm_load_views', array( &$this, 'wcfm_customers_load_views' ), 30 );
				
				// Customers Ajax Controllers
				add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfm_customers_ajax_controller' ) );
				
				if( wcfm_is_vendor() ) {
					// Customers args
					add_filter( 'wcfm_get_customers_args', array( &$this, 'wcfm_filter_customers' ) );
				
					// Edit Customer
					add_action( 'wcfm_customers_manage', array( &$this, 'wcfm_customers_manage' ) );
				}
				
				add_filter( 'wcfm_message_types', array( &$this, 'wcfm_customer_message_types' ), 35 );
				
				// Customer Details Change Customer
				add_action( 'wp_ajax_customer_details_change_url', array( $this, 'customer_details_change_url' ) );
			}
		}
	}
	
	/**
   * WCFM Customers Query Var
   */
  function customers_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_customers_vars = array(
			'wcfm-customers'                 => ! empty( $wcfm_modified_endpoints['wcfm-customers'] ) ? $wcfm_modified_endpoints['wcfm-customers'] : 'wcfm-customers',
			'wcfm-customers-manage'          => ! empty( $wcfm_modified_endpoints['wcfm-customers-manage'] ) ? $wcfm_modified_endpoints['wcfm-customers-manage'] : 'wcfm-customers-manage',
			'wcfm-customers-details'         => ! empty( $wcfm_modified_endpoints['wcfm-customers-details'] ) ? $wcfm_modified_endpoints['wcfm-customers-details'] : 'wcfm-customers-details',
		);
		
		$query_vars = array_merge( $query_vars, $query_customers_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Customers End Point Title
   */
  function customers_wcfm_endpoint_title( $title, $endpoint ) {
  	global $wp;
  	switch ( $endpoint ) {
  		case 'wcfm-customers' :
				$title = __( 'Customers Dashboard', 'wc-frontend-manager' );
			break;
			case 'wcfm-customers-manage' :
				$title = __( 'Customers Manager', 'wc-frontend-manager' );
			break;
			case 'wcfm-customers-details' :
				$title = __( 'Customers Details', 'wc-frontend-manager' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Customers Endpoint Intialize
   */
  function customers_wcfm_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfm_customers' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfm_customers', 1 );
		}
  }
  
  /**
	 * WCFM Customers Endpoiint Edit
	 */
  function wcfm_customers_endpoints_slug( $endpoints ) {
		
		$customers_endpoints = array(
													'wcfm-customers'  		      => 'wcfm-customers',
													'wcfm-customers-manage'  	  => 'wcfm-customers-manage',
													'wcfm-customers-details'    => 'wcfm-customers-details'
													);
		$endpoints = array_merge( $endpoints, $customers_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Customers Menu
   */
  function customers_wcfm_menus( $menus ) {
  	global $WCFM;
  	
		$customers_menus = array( 'wcfm-customers' => array(   'label'  => __( 'Customers', 'wc-frontend-manager'),
																													 'url'       => get_wcfm_customers_url(),
																													 'icon'      => 'user-circle-o',
																													 'has_new'    => true,
																													 'new_class'  => 'wcfm_sub_menu_items_customer_manage',
																													 'new_url'    => get_wcfm_customers_manage_url(),
																													 'capability' => 'wcfm_customer_menu',
																													 'submenu_capability' => 'wcfm_add_new_customer_sub_menu',
																													 'priority'  => 46
																													) );
		$menus = array_merge( $menus, $customers_menus );
		
  	return $menus;
  }
  
  /**
   * Customers Scripts
   */
  public function wcfm_customers_load_scripts( $end_point ) {
	  global $WCFM;
    
	  switch( $end_point ) {
	  	case 'wcfm-customers':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfm_customers_js', $WCFM->library->js_lib_url . 'customers/wcfm-script-customers.js', array('jquery', 'dataTables_js'), $WCFM->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager = (array) get_option( 'wcfm_screen_manager' );
	    	$wcfm_screen_manager_data = array();
	    	if( isset( $wcfm_screen_manager['customer'] ) ) $wcfm_screen_manager_data = $wcfm_screen_manager['customer'];
	    	if( !isset( $wcfm_screen_manager_data['admin'] ) ) {
					$wcfm_screen_manager_data['admin'] = $wcfm_screen_manager_data;
					$wcfm_screen_manager_data['vendor'] = $wcfm_screen_manager_data;
				}
				if( wcfm_is_vendor() ) {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['vendor'];
				} else {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['admin'];
				}
				if( !apply_filters( 'wcfm_allow_order_customer_details', true ) ) {
					$wcfm_screen_manager_data[2] = 'yes';
				}
				$wcfm_screen_manager_data[5] = 'yes';
				$wcfm_screen_manager_data[6] = 'yes';
				$wcfm_screen_manager_data[9] = apply_filters( 'wcfm_customers_additonal_data_hidden', 'yes' );
	    	wp_localize_script( 'wcfm_customers_js', 'wcfm_customers_screen_manage', $wcfm_screen_manager_data );
      break;
      
      case 'wcfm-customers-manage':
      	$WCFM->library->load_collapsible_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_upload_lib();
      	
      	$WCFM->library->load_colorpicker_lib();
				wp_enqueue_script( 'iris', admin_url('js/iris.min.js'),array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), false, 1);
				wp_enqueue_script( 'wp-color-picker', admin_url('js/color-picker.min.js'), array('iris'), false,1);
				
				$colorpicker_l10n = array('clear' => __('Clear'), 'defaultString' => __('Default'), 'pick' => __('Select Color'));
				wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
				
				wp_enqueue_script( 'wcfm_multiinput_js', $WCFM->library->js_lib_url . 'wcfm-script-multiinput.js', array('jquery'), $WCFM->version, true );
				
	  		wp_enqueue_script( 'wcfm_customers_manage_js', $WCFM->library->js_lib_url . 'customers/wcfm-script-customers-manage.js', array('jquery'), $WCFM->version, true );
	  		
	  		// Localized Script
        $wcfm_messages = get_wcfm_customers_manage_messages();
			  wp_localize_script( 'wcfm_customers_manage_js', 'wcfm_customers_manage_messages', $wcfm_messages );
	  	break;
      
      case 'wcfm-customers-details':
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_datatable_lib();
      	$WCFM->library->load_datatable_download_lib();
	    	wp_enqueue_script( 'wcfm_customers_details_js', $WCFM->library->js_lib_url . 'customers/wcfm-script-customers-details.js', array('jquery'), $WCFM->version, true );
      break;
	  }
	}
	
	/**
   * Customers Styles
   */
	public function wcfm_customers_load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	    case 'wcfm-customers':
	    	wp_enqueue_style( 'wcfm_customers_css',  $WCFM->library->css_lib_url . 'customers/wcfm-style-customers.css', array(), $WCFM->version );
		  break;
		  
		  case 'wcfm-customers-manage':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
	  		wp_enqueue_style( 'wcfm_customers_manage_css',  $WCFM->library->css_lib_url . 'customers/wcfm-style-customers-manage.css', array(), $WCFM->version );
	  	break;
		  
		  case 'wcfm-customers-details':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
		  	wp_enqueue_style( 'wcfm_dashboard_css',  $WCFM->library->css_lib_url . 'wcfm-style-dashboard.css', array(), $WCFM->version );
	    	wp_enqueue_style( 'wcfm_customers_details_css',  $WCFM->library->css_lib_url . 'customers/wcfm-style-customers-details.css', array(), $WCFM->version );
		  break;
	  }
	}
	
	/**
   * Customers Views
   */
  public function wcfm_customers_load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
	  	case 'wcfm-customers':
        require_once( $WCFM->library->views_path . 'customers/wcfm-view-customers.php' );
      break;
      
      case 'wcfm-customers-manage':
        require_once( $WCFM->library->views_path . 'customers/wcfm-view-customers-manage.php' );
      break;
      
      case 'wcfm-customers-details':
        require_once( $WCFM->library->views_path . 'customers/wcfm-view-customers-details.php' );
      break;
	  }
	}
	
	/**
   * Customers Ajax Controllers
   */
  public function wcfm_customers_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFM->plugin_path . 'controllers/customers/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-customers':
					require_once( $controllers_path . 'wcfm-controller-customers.php' );
					new WCFM_Customers_Controller();
				break;
				
				case 'wcfm-customers-manage':
					require_once( $controllers_path . 'wcfm-controller-customers-manage.php' );
					new WCFM_Customers_Manage_Controller();
				break;
				
				case 'wcfm-customers-details':
					//require_once( $controllers_path . 'wcfm-controller-customers-details.php' );
					//new WCFM_Customers_Details_Controller();
				break;
				
				case 'wcfm-customers-details-orders':
					require_once( $controllers_path . 'wcfm-controller-customers-details.php' );
					new WCFM_Customers_Details_Orders_Controller();
				break;
				
				case 'wcfm-customers-details-bookings':
					require_once( $controllers_path . 'wcfm-controller-customers-details.php' );
					new WCFM_Customers_Details_Bookings_Controller();
				break;
				
				case 'wcfm-customers-details-appointments':
					require_once( $controllers_path . 'wcfm-controller-customers-details.php' );
					new WCFM_Customers_Details_Appointments_Controller();
				break;
  		}
  	}
  }
  
  // Filter Customer
	function wcfm_filter_customers( $args ) {
		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $vendor_id;
		return $args;
	}
	
	// Customer Manage
	function wcfm_customers_manage( $customer_id ) {
		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		update_user_meta( $customer_id, '_wcfm_vendor', $vendor_id );
	}
	
	function wcfm_customer_message_types( $message_types ) {
  	if( apply_filters( 'wcfm_is_allow_manage_customer', true ) ) {
  		$message_types['new_customer'] = __( 'New Customer', 'wc-frontend-manager' );
  	}
  	return $message_types;
  }
  
  /**
   * Customer Details Change URL
   */
  function customer_details_change_url() {
  	global $WCFM, $_POST;
  	
  	if( isset( $_POST['customer_details_change'] ) && !empty( $_POST['customer_details_change'] ) ) {
  		$customer_id = absint( $_POST['customer_details_change'] );
  		echo '{"status": true, "redirect": "' . get_wcfm_customers_details_url($customer_id) . '"}';
  	}
  	
  	die;
  }
}