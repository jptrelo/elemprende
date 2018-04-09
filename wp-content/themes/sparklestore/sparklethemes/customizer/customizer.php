<?php
/**
 * Sparkle Store Theme Customizer.
 *
 * @package Sparkle_Store
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sparklestore_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

  /**
   * List All Pages
  */
  $slider_pages = array();
  $slider_pages_obj = get_pages();
  $slider_pages[''] = esc_html__('Select Slider Page','sparklestore');
  foreach ($slider_pages_obj as $page) {
    $slider_pages[$page->ID] = $page->post_title;
  }

/**
 * Register custom section type for pro button link
*/
  $wp_customize->register_section_type( 'Sparklestore_Commerce_Customize_Section_Upsell' );

  // Register sections.
  $wp_customize->add_section( new Sparklestore_Commerce_Customize_Section_Upsell( $wp_customize, 'theme_upsell', array(
    'title'    => esc_html__( 'SparkleStore Pro', 'sparklestore' ),
    'pro_text' => esc_html__( 'Buy Pro', 'sparklestore' ),
    'pro_url'  => 'https://www.sparklewpthemes.com/wordpress-themes/sparklestorepro',
    'priority'  => 1,
  ) ) );


/**
 * Important Link
*/
$wp_customize->add_section( 'sparklestore_implink_section', array(
  'title'       => esc_html__( 'Important Links', 'sparklestore' ),
  'priority'      => 1
) );

    $wp_customize->add_setting( 'sparklestore_imp_links', array(
      'sanitize_callback' => 'sparklestore_text_sanitize'
    ));

    $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize,'sparklestore_imp_links', array(
        'settings'    => 'sparklestore_imp_links',
        'section'   => 'sparklestore_implink_section',
        'description' => '<a class="implink" href="http://docs.sparklewpthemes.com/sparklestore/" target="_blank">'.esc_html__('Documentation', 'sparklestore').'</a><a class="implink" href="http://demo.sparklewpthemes.com/sparklestore/demos/" target="_blank">'.esc_html__('Live Demo', 'sparklestore').'</a><a class="implink" href="http://sparklewpthemes.com/support" target="_blank">'.esc_html__('Support Forum', 'sparklestore').'</a><a class="implink" href="https://www.facebook.com/sparklethemes" target="_blank">'.esc_html__('Like Us in Facebook', 'sparklestore').'</a>',
      )
    ));

    $wp_customize->add_setting( 'sparklestore_rate_us', array(
      'sanitize_callback' => 'sparklestore_text_sanitize'
    ));

    $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize, 'sparklestore_rate_us', array(
          'settings'    => 'sparklestore_rate_us',
          'section'   => 'sparklestore_implink_section',
          'description' => sprintf(__( 'Please do rate our theme if you liked it %1$s', 'sparklestore'), '<a class="implink" href="https://wordpress.org/support/theme/sparklestore/reviews/?filter=5" target="_blank">'.esc_html__('Rate/Review','sparklestore').'</a>' ),
        )
    ));

    $wp_customize->add_setting( 'sparklestore_setup_instruction', array(
      'sanitize_callback' => 'sparklestore_text_sanitize'
    ));

    $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize, 'sparklestore_setup_instruction', array(
        'settings'    => 'sparklestore_setup_instruction',
        'section'   => 'sparklestore_implink_section',
        'description' => __( '<strong>Instruction - Setting up Home Page</strong><br/>
        1. Create a new page (any title, like Home )<br/>
        2. In right column: Page Attributes -> Template: Home Page<br/>
        3. Click on Publish<br/>
        4. Go to Appearance-> Customize -> Static Front Page<br/>
        5. Select - A static page<br/>
        6. In Front Page, select the page that you created in the step 1<br/>
        7. Save changes', 'sparklestore'),
      )
    ));

/**
 * Top Header Quick Contact Information Options 
*/
  $wp_customize->add_section( 'sparklestore_header_quickinfo', array(
      'priority'       => 25,
      'capability'     => 'edit_theme_options',
      'title'          => esc_html__( 'Quick Contact Info', 'sparklestore' )
  ) );

      $wp_customize->add_setting('sparklestore_email_icon', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize', // done
      ));
      
      $wp_customize->add_control('sparklestore_email_icon',array(
          'type' => 'text',
          'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$sSee more here%3$s', 'sparklestore' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
          'label' => esc_html__('Enter Email Icon', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_email_icon',
      ));
      
      $wp_customize->add_setting('sparklestore_email_title', array(
          'default' => '',
          'sanitize_callback' => 'sanitize_email',  // done
      ));
      
      $wp_customize->add_control('sparklestore_email_title',array(
          'type' => 'text',
          'label' => esc_html__('Email Address', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_email_title',
      ));
      
      
      $wp_customize->add_setting('sparklestore_phone_icon', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize', // done
      ));
      
      $wp_customize->add_control('sparklestore_phone_icon',array(
          'type' => 'text',
          'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
          'label' => esc_html__('Phone Icon', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_phone_icon',
      ));
      
      $wp_customize->add_setting('sparklestore_phone_number', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize',  // done
      ));
      
      $wp_customize->add_control('sparklestore_phone_number',array(
          'type' => 'text',
          'label' => esc_html__('Phone Number', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_phone_number',
      ));

      $wp_customize->add_setting('sparklestore_address_icon', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize', // done
      ));
      
      $wp_customize->add_control('sparklestore_address_icon',array(
          'type' => 'text',
          'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
          'label' => esc_html__('Address Icon', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_address_icon',
      ));
      
      $wp_customize->add_setting('sparklestore_map_address', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize',  // done
      ));
      
      $wp_customize->add_control('sparklestore_map_address',array(
          'type' => 'text',
          'label' => esc_html__('Address', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_map_address',
      ));    
      
      
      $wp_customize->add_setting('sparklestore_start_open_icon', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize', // done
      ));
      
      $wp_customize->add_control('sparklestore_start_open_icon',array(
          'type' => 'text',
          'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
          'label' => esc_html__('Start Time Icon', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_start_open_icon',
      ));
      
      $wp_customize->add_setting('sparklestore_start_open_time', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize',  // done
      ));
      
      $wp_customize->add_control('sparklestore_start_open_time',array(
          'type' => 'text',
          'label' => esc_html__('Opening Time', 'sparklestore'),
          'section' => 'sparklestore_header_quickinfo',
          'setting' => 'sparklestore_start_open_time',
      ));
      
/**
 * General Settings Panel
*/
$wp_customize->add_panel('sparklestore_general_settings', array(
   'capabitity' => 'edit_theme_options',
   'priority' => 25,
   'title' => esc_html__('General Settings', 'sparklestore')
));

    $wp_customize->get_section('title_tagline')->panel = 'sparklestore_general_settings';
    $wp_customize->get_section('title_tagline' )->priority = 1;

    $wp_customize->get_section('header_image')->panel = 'sparklestore_general_settings';
    $wp_customize->get_section('header_image' )->priority = 2;

    $wp_customize->get_section('colors')->title = esc_html__( 'Themes Colors', 'sparklestore' );
    $wp_customize->get_section('colors')->panel = 'sparklestore_general_settings';
    $wp_customize->get_section('header_image' )->priority = 3;

    $wp_customize->get_section('background_image')->panel = 'sparklestore_general_settings';
    $wp_customize->get_section('header_image' )->priority = 4;

    /**
     * Web Page Layout Section
    */
    $wp_customize->add_section( 'sparklestore_web_page_layout', array(
        'title'           => esc_html__('WebLayout Options', 'sparklestore'),
        'panel'           => 'sparklestore_general_settings'
    ));

      $wp_customize->add_setting('sparklestore_web_page_layout_options', array(
          'default' => 'disable',
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize',
          //'transport' => 'postMessage'
      ));

      $wp_customize->add_control('sparklestore_web_page_layout_options', array(
          'type' => 'radio',
          'label' => esc_html__('Enable / Disable Top Header', 'sparklestore'),
          'section' => 'sparklestore_web_page_layout',
          'settings' => 'sparklestore_web_page_layout_options',
          'choices' => array(
            'enable' => esc_html__('Boxed Layout', 'sparklestore'),
            'disable' => esc_html__('Full Width Layout', 'sparklestore')
          )
      ));

/**
 * Banner Slider
*/
  $wp_customize->add_section( 'sparklestore_banner_slider', array(
    'title'           => esc_html__('Slider Settings Options', 'sparklestore'),
    'priority'        => '61',
  ));

      $wp_customize->add_setting('sparklestore_slider_options', array(
          'default' => 'enable',
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize',
      ));

      $wp_customize->add_control('sparklestore_slider_options', array(
        'type' => 'radio',
        'label' => esc_html__('Enable/Disable Section', 'sparklestore'),
        'section' => 'sparklestore_banner_slider',
        'settings' => 'sparklestore_slider_options',
        'choices' => array(
             'enable' => esc_html__('Enable', 'sparklestore'),
             'disable' => esc_html__('Disable', 'sparklestore')
            )
      ));

      $wp_customize->add_setting( 'sparklestore_banner_all_sliders', array(
        'sanitize_callback' => 'sparklestore_sanitize_repeater',
        'default' => json_encode( array(
          array(
                'selectpage' => '' ,
                'button_text' => '',
                'button_url' => ''
              )
          ) )        
        ) );

      $wp_customize->add_control( new Sparklestore_Repeater_Controler( $wp_customize, 'sparklestore_banner_all_sliders', array(
        'label'   => __('Slider Settings Area','sparklestore'),
        'section' => 'sparklestore_banner_slider',
        'settings' => 'sparklestore_banner_all_sliders',
        'sparklestore_box_label' => __('Slider Settings Options','sparklestore'),
        'sparklestore_box_add_control' => __('Add New Slider','sparklestore'),
      ),
      array(
        'selectpage' => array(
          'type'        => 'select',
          'label'       => __( 'Select Slider Page', 'sparklestore' ),
          'options'   => $slider_pages
        ),
        'button_text' => array(
          'type'        => 'text',
          'label'       => __( 'Enter Button Text', 'sparklestore' ),
          'default'   => ''
        ),
        'button_url' => array(
          'type'        => 'text',
          'label'       => __( 'Enter Button Url', 'sparklestore' ),
          'default'   => ''
        )
      )
    ) 
  );


/**
 * Breadcrumbs Settings
*/
$wp_customize->add_panel('sparklestore_breadcrumbs_settings', array(
   'capability' => 'edit_theme_options',
   'description'=> esc_html__('Manage breadcrumbs settings here as you want', 'sparklestore'),
   'priority' => 62,
   'title' => esc_html__('Breadcrumbs Settings', 'sparklestore')
));

    $wp_customize->add_section('sparklestore_woocommerce_breadcrumbs_settings', array(
      'priority' => 2,
      'title' => esc_html__('WooCommerce Breadcrumbs', 'sparklestore'),
      'panel' => 'sparklestore_breadcrumbs_settings'
    ));           

        $wp_customize->add_setting('sparklestore_breadcrumbs_woocommerce_background_image', array(
           'default' => '',
           'capability' => 'edit_theme_options',
           'sanitize_callback' => 'esc_url_raw'
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'sparklestore_breadcrumbs_woocommerce_background_image', array(
           'label' => esc_html__('Upload Breadcrumbs Background Image', 'sparklestore'),
           'section' => 'sparklestore_woocommerce_breadcrumbs_settings',
           'setting' => 'sparklestore_breadcrumbs_woocommerce_background_image'
        )));

  $wp_customize->add_section('sparklestore_breadcrumbs_normal_page_section', array(
      'priority' => 4,
      'title' => esc_html__('Normal Post/Page & Archive Settings', 'sparklestore'),
      'panel' => 'sparklestore_breadcrumbs_settings'
   ));

      $wp_customize->add_setting('sparklestore_breadcrumbs_normal_page_background_image', array(
         'default' => '',
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'esc_url_raw'
      ));

      $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'sparklestore_breadcrumbs_normal_page_background_image', array(
         'label' => esc_html__('Upload Breadcrumbs Background Image', 'sparklestore'),
         'section' => 'sparklestore_breadcrumbs_normal_page_section',
         'setting' => 'sparklestore_breadcrumbs_normal_page_background_image'
      )));

/**
 * Home 1 - Full Width Section
*/
$sparklestore_home_section = $wp_customize->get_section( 'sidebar-widgets-sparklemainwidgetarea' );
if ( ! empty( $sparklestore_home_section ) ) {
    $sparklestore_home_section->panel = '';
    $sparklestore_home_section->title = esc_html__( 'Sparkle: Main Widget Area', 'sparklestore' );
    $sparklestore_home_section->priority = 61;
}

/**
 * Services Section
*/
	$wp_customize->add_section( 'sparklestore_services_area', array(
		'title'           => esc_html__('Services Area Settings', 'sparklestore'),
		'priority'        => 61,
  ));

      $wp_customize->add_setting('sparklestore_services_area_settings', array(
          'default' => 'disable',
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize',
    	));

    	$wp_customize->add_control('sparklestore_services_area_settings', array(
    		'type' => 'radio',
    		'label' => esc_html__('Enable/Disable Section', 'sparklestore'),
    		'section' => 'sparklestore_services_area',
    		'settings' => 'sparklestore_services_area_settings',
    		'choices' => array(
             'enable' => esc_html__('Enable', 'sparklestore'),
             'disable' => esc_html__('Disable', 'sparklestore')
            )
    	));

    	$wp_customize->add_setting('sparklestore_services_section', array(
            'default' => 'disable',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize'  //done
    	));

    	$wp_customize->add_control('sparklestore_services_section', array(
    		'type' => 'radio',
    		'label' => esc_html__('Manage Services Area Location', 'sparklestore'),
    		'section' => 'sparklestore_services_area',
    		'settings' => 'sparklestore_services_section',
    		'description' => esc_html__('Options to Manage Service Area Below the Header or Abote the Footer Area', 'sparklestore'),
    		'choices' => array(
             'enable' => esc_html__('Below the Header', 'sparklestore'),
             'disable' => esc_html__('Abover the Footer', 'sparklestore')
            )
    	));

	 // Services Area One
    	$wp_customize->add_setting('sparklestore_services_icon_one', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize', 
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_services_icon_one',array(
            'type' => 'text',
            'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
            'label' => esc_html__('Service Icon One', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_services_icon_one',
        ));
    	
    	  $wp_customize->add_setting('sparklestore_service_title_one', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_title_one',array(
            'type' => 'text',
            'label' => esc_html__('Service One Title', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_title_one',
        ));

        $wp_customize->add_setting('sparklestore_service_desc_one', array(
            'default' => '',
           	'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_desc_one',array(
            'type' => 'text',
            'label' => esc_html__('Service Area Description', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_desc_one',
        ));

    // Services Area Two
        $wp_customize->add_setting('sparklestore_services_icon_two', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_services_icon_two',array(
            'type' => 'text',
            'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-headphones','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
           'label' => esc_html__('Service Icon Two', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_services_icon_two',
        ));
    	
    	  $wp_customize->add_setting('sparklestore_service_title_two', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_title_two',array(
            'type' => 'text',
            'label' => esc_html__('Service Two Title', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_title_two',
        ));

        $wp_customize->add_setting('sparklestore_service_desc_two', array(
            'default' => '',
           	'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_desc_two',array(
            'type' => 'text',
            'label' => esc_html__('Service Area Description', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_desc_two',
        ));

    // Services Area Three
        $wp_customize->add_setting('sparklestore_services_icon_three', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_services_icon_three',array(
            'type' => 'text',
            'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-dollar','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
            'label' => esc_html__('Service Icon Three', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_services_icon_three',
        ));
    	
    	  $wp_customize->add_setting('sparklestore_service_title_three', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_title_three',array(
            'type' => 'text',
            'label' => esc_html__('Service Three Title', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_title_three',
        ));

        $wp_customize->add_setting('sparklestore_service_desc_three', array(
            'default' => '',
           	'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_desc_three',array(
            'type' => 'text',
            'label' => esc_html__('Service Area Description', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_desc_three',
        ));

     // Services Area Four
        $wp_customize->add_setting('sparklestore_services_icon_four', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_services_icon_four',array(
            'type' => 'text',
            'description' => sprintf( esc_html__( 'Use font awesome icon: Eg: %1$s. %2$s See more here%3$s', 'sparklestore' ), 'fa fa-mobile','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
            'label' => esc_html__('Service Icon Four', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_services_icon_four',
        ));
    	
    	  $wp_customize->add_setting('sparklestore_service_title_four', array(
            'default' => '',
            'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_title_four',array(
            'type' => 'text',
            'label' => esc_html__('Service Four Title', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_title_four',
        ));

        $wp_customize->add_setting('sparklestore_service_desc_four', array(
            'default' => '',
           	'sanitize_callback' => 'sparklestore_text_sanitize',
            'transport' => 'postMessage'
        ));
        
        $wp_customize->add_control('sparklestore_service_desc_four',array(
            'type' => 'text',
            'label' => esc_html__('Service Area Description', 'sparklestore'),
            'section' => 'sparklestore_services_area',
            'setting' => 'sparklestore_service_desc_four',
        ));

$imagepath =  get_template_directory_uri() . '/assets/images/';

/**
 * Start of the WooCommerce Design panel
*/
$wp_customize->add_panel('sparklestore_woocommerce_design_options', array(
  'capabitity' => 'edit_theme_options',
  'description' => esc_html__('Mange products and singel product page settings', 'sparklestore'),
  'priority' => 62,
  'title' => esc_html__('WooCommerce Settings', 'sparklestore')
));
     
        // Site archive layout setting
        $wp_customize->add_section('sparklestore_woocommerce_products_settings', array(
          'priority' => 2,
          'title' => esc_html__('Products Pages Settings', 'sparklestore'),
          'panel' => 'sparklestore_woocommerce_design_options'
        ));

            $wp_customize->add_setting('sparklestore_woocommerce_products_page_layout', array(
              'default' => 'rightsidebar',
              'capability' => 'edit_theme_options',
              'sanitize_callback' => 'sparklestore_page_layout_sanitize'  //done
            ));

            $wp_customize->add_control(new Sparklestore_Image_Radio_Control($wp_customize, 'sparklestore_woocommerce_products_page_layout', array(
              'type' => 'radio',
              'label' => esc_html__('Select Products pages Layout', 'sparklestore'),
              'section' => 'sparklestore_woocommerce_products_settings',
              'settings' => 'sparklestore_woocommerce_products_page_layout',
              'choices' => array( 
                      'leftsidebar' => $imagepath.'left-sidebar.png',  
                      'rightsidebar' => $imagepath.'right-sidebar.png',
                      'onsidebar' => $imagepath.'no-sidebar.png ',                      
                    )
            )));

            $wp_customize->add_setting('sparklestore_woocommerce_product_row', array(
              'default' => '3',
              'capability' => 'edit_theme_options',
              'sanitize_callback' => 'sparklestore_row_layout_sanitize'  //done
            ));

            $wp_customize->add_control('sparklestore_woocommerce_product_row', array(
              'type' => 'select',
              'label' => esc_html__('Select Products Pages Row', 'sparklestore'),
              'section' => 'sparklestore_woocommerce_products_settings',
              'settings' => 'sparklestore_woocommerce_product_row',
              'choices' => array( 
                      '2' => '2',  
                      '3' => '3', 
                      '4' => '4',
            )));

            $wp_customize->add_setting('sparklestore_woocommerce_display_product_number', array(
              'default' => 12,
              'capability' => 'edit_theme_options',
              'sanitize_callback' => 'sparklestore_number_sanitize'  // done
            ));

            $wp_customize->add_control('sparklestore_woocommerce_display_product_number', array(
              'type' => 'number',
              'label' => esc_html__('Enter Products Display Per Page', 'sparklestore'),
              'section' => 'sparklestore_woocommerce_products_settings',
              'settings' => 'sparklestore_woocommerce_display_product_number'
            ));

    

        // WooCommerce Singel Product Page Settings
        $wp_customize->add_section('sparklestore_woocommerce_single_products_page_settings', array(
          'priority' => 2,
          'title' => esc_html__('Single Products Page Settings', 'sparklestore'),
          'panel' => 'sparklestore_woocommerce_design_options'
        ));

        $wp_customize->add_setting('sparklestore_woocommerce_single_products_page_layout', array(
          'default' => 'rightsidebar',
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'sparklestore_page_layout_sanitize'  //done
        ));

        $wp_customize->add_control(new Sparklestore_Image_Radio_Control($wp_customize, 'sparklestore_woocommerce_single_products_page_layout', array(
          'type' => 'radio',
          'label' => esc_html__('Select Single Products Page Layout', 'sparklestore'),
          'section' => 'sparklestore_woocommerce_single_products_page_settings',
          'settings' => 'sparklestore_woocommerce_single_products_page_layout',
          'choices' => array( 
                  'leftsidebar' => $imagepath.'left-sidebar.png',  
                  'rightsidebar' => $imagepath.'right-sidebar.png',
                  'onsidebar' => $imagepath.'no-sidebar.png ', 
                )
        )));        

/**
 * Footer Settings Area 
*/
$wp_customize->add_panel('sparklestore_footer_settings', array(
   'capabitity' => 'edit_theme_options',
   'priority' => 63,
   'title' => esc_html__('Footer Settings', 'sparklestore')
));

        // Start of the Social Link Options
        $wp_customize->add_section('sparklestore_social_link_activate_settings', array(
            'priority' => '1',
            'title'    => esc_html__('Social Media Options', 'sparklestore'),
            'panel'    => 'sparklestore_footer_settings'
        ));

            $wp_customize->add_setting('sparklestore_social_link_activate', array(
                'default' => 1,
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sparklestore_checkbox_sanitize'  //done
            ));

            $wp_customize->add_control('sparklestore_social_link_activate', array(
                'type' => 'checkbox',
                'label' => esc_html__('Check to activate social links area', 'sparklestore'),
                'section' => 'sparklestore_social_link_activate_settings',
                'settings' => 'sparklestore_social_link_activate'
            ));

            $sparklestore_social_links = array( 
                'sparklestore_social_facebook' => array(
                    'id' => 'sparklestore_social_facebook',
                    'title' => esc_html__('Facebook', 'sparklestore'),
                    'default' => '',
                ),
                'sparklestore_social_twitter' => array(
                    'id' => 'sparklestore_social_twitter',
                    'title' => esc_html__('Twitter', 'sparklestore'),
                    'default' => '',
                ),
                'sparklestore_social_googleplus' => array(
                    'id' => 'sparklestore_social_googleplus',
                    'title' => esc_html__('Google-Plus', 'sparklestore'),
                    'default' => '',
                ),
                'sparklestore_social_pinterest' => array(
                    'id' => 'sparklestore_social_pinterest',
                    'title' => esc_html__('Pinterest', 'sparklestore'),
                    'default' => '',
                ),
                'sparklestore_social_linkedin' => array(
                    'id' => 'sparklestore_social_linkedin',
                    'title' => esc_html__('Linkedin', 'sparklestore'),
                    'default' => '',
                ),
                'sparklestore_social_youtube' => array(
                    'id' => 'sparklestore_social_youtube',
                    'title' => esc_html__('YouTube', 'sparklestore'),
                    'default' => '',
                )
            );

            $i = 20;
            foreach($sparklestore_social_links as $sparklestore_social_link) {
                $wp_customize->add_setting($sparklestore_social_link['id'], array(
                    'default' => $sparklestore_social_link['default'],
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'esc_url_raw'
                ));

                $wp_customize->add_control($sparklestore_social_link['id'], array(
                    'label' => $sparklestore_social_link['title'],
                    'section'=> 'sparklestore_social_link_activate_settings',
                    'settings'=> $sparklestore_social_link['id'],
                    'priority' => $i
                ));

                $wp_customize->add_setting($sparklestore_social_link['id'].'_checkbox', array(
                    'default' => 0,
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'sparklestore_checkbox_sanitize'  // done
                ));
                $wp_customize->add_control($sparklestore_social_link['id'].'_checkbox', array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Check to show in new tab', 'sparklestore'),
                    'section'=> 'sparklestore_social_link_activate_settings',
                    'settings'=> $sparklestore_social_link['id'].'_checkbox',
                    'priority' => $i
                ));
                $i++;

            }
        
        // Payment Logo Section    
        $wp_customize->add_section( 'paymentlogo_images', array(
            'title'           => esc_html__('Payment Logo Images', 'sparklestore'),
            'priority'        => '2',
            'panel'           => 'sparklestore_footer_settings'
        ));
        
            $wp_customize->add_setting( 'paymentlogo_image_one', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_one', array(
                'section'       => 'paymentlogo_images',
                'label'         => esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          => 'image',
            )));

            $wp_customize->add_setting( 'paymentlogo_image_two', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'  // done
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_two', array(
                'section'       => 'paymentlogo_images',
                'label'         => esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          => 'image',
            )));

            $wp_customize->add_setting( 'paymentlogo_image_three', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'  // done
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_three', array(
                'section'       =>      'paymentlogo_images',
                'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          =>      'image',
            )));

            $wp_customize->add_setting( 'paymentlogo_image_four', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'   // done
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_four', array(
                'section'       =>      'paymentlogo_images',
                'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          =>      'image',
            )));

            $wp_customize->add_setting( 'paymentlogo_image_five', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'   // done
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_five', array(
                'section'       =>      'paymentlogo_images',
                'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          =>      'image',
            )));

            $wp_customize->add_setting( 'paymentlogo_image_six', array(
                'default'       =>      '',
                'sanitize_callback' => 'esc_url_raw'  // done
            ));
           
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_six', array(
                'section'       =>      'paymentlogo_images',
                'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
                'type'          =>      'image',
            )));

        $wp_customize->add_section( 'sparklestore_copyright', array(
            'title'           =>      esc_html__('Copyright Message Area', 'sparklestore'),
            'priority'        =>      '3',
            'panel'           =>      'sparklestore_footer_settings'
        ));

            $wp_customize->add_setting('sparklestore_footer_copyright', array(
                 'default' => '',
                 'capability' => 'edit_theme_options',
                 'sanitize_callback' => 'sparklestore_text_sanitize'  //done
            ));

            $wp_customize->add_control('sparklestore_footer_copyright', array(
             'type' => 'textarea',
             'label' => esc_html__('Copyright', 'sparklestore'),
             'section' => 'sparklestore_copyright',
             'settings' => 'sparklestore_footer_copyright'
            ));
            

    function sparklestore_checkbox_sanitize($input) {
      if ( $input == 1 ) {
         return 1;
      } else {
         return 0;
      }
    }

    function sparklestore_radio_enable_disable_sanitize($input) {
       $valid_keys = array(
         'enable' => esc_html__('Enable', 'sparklestore'),
         'disable' => esc_html__('Disable', 'sparklestore')
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }
    }

    function sparklestore_text_sanitize( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    function sparklestore_page_layout_sanitize($input) {
        $imagepath =  get_template_directory_uri() . '/images/';
        $valid_keys = array(
          'leftsidebar' => $imagepath.'left-sidebar.png',  
          'rightsidebar' => $imagepath.'right-sidebar.png',
          'onsidebar' => $imagepath.'no-sidebar.png ',
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
        } else {
         return '';
        }
    }

    function sparklestore_row_layout_sanitize($input) {
      $valid_keys = array(
         '2' => '2',  
         '3' => '3', 
         '4' => '4',
      );
      if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
      } else {
         return '';
      }
    }

    function sparklestore_number_sanitize( $int ) {
        return absint( $int );
    }

    function sparklestore_sanitize_repeater($input){        
      $input_decoded = json_decode( $input, true );
      $allowed_html = array(
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'a' => array(
          'href' => array(),
          'class' => array(),
          'id' => array(),
          'target' => array()
        ),
        'button' => array(
          'class' => array(),
          'id' => array()
        )
      ); 

      if(!empty($input_decoded)) {
        foreach ($input_decoded as $boxes => $box ){
          foreach ($box as $key => $value){
            $input_decoded[$boxes][$key] = sanitize_text_field( $value );
          }
        }
        return json_encode($input_decoded);
      }      
      return $input;
    }

}
add_action( 'customize_register', 'sparklestore_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
*/
function sparklestore_customize_preview_js() {
	wp_enqueue_script( 'sparklestore_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'sparklestore_customize_preview_js' );