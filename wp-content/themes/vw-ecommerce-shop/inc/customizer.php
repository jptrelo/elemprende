<?php
/**
 * VW Ecommerce Shop Theme Customizer
 *
 * @package VW Ecommerce Shop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function vw_ecommerce_shop_customize_register( $wp_customize ) {

	//add home page setting pannel
	$wp_customize->add_panel( 'vw_ecommerce_shop_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'VW Settings', 'vw-ecommerce-shop' ),
	    'description' => __( 'Description of what this panel does.', 'vw-ecommerce-shop' ),
	) );

	$wp_customize->add_section( 'vw_ecommerce_shop_left_right', array(
    	'title'      => __( 'General Settings', 'vw-ecommerce-shop' ),
		'priority'   => 30,
		'panel' => 'vw_ecommerce_shop_panel_id'
	) );

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_ecommerce_shop_theme_options',array(
	        'default' => __('Right Sidebar','vw-ecommerce-shop'),
	        'sanitize_callback' => 'vw_ecommerce_shop_sanitize_choices'	        
	));
	$wp_customize->add_control('vw_ecommerce_shop_theme_options',
	    array(
	        'type' => 'radio',
	        'label' => __('Do you want this section','vw-ecommerce-shop'),
	        'section' => 'vw_ecommerce_shop_left_right',
	        'choices' => array(
	            'Left Sidebar' => __('Left Sidebar','vw-ecommerce-shop'),
	            'Right Sidebar' => __('Right Sidebar','vw-ecommerce-shop'),
	            'One Column' => __('One Column','vw-ecommerce-shop'),
	            'Three Columns' => __('Three Columns','vw-ecommerce-shop'),
	            'Four Columns' => __('Four Columns','vw-ecommerce-shop'),
	            'Grid Layout' => __('Grid Layout','vw-ecommerce-shop')
	        ),
	    )
    );	

	//Topbar section
	$wp_customize->add_section('vw_ecommerce_shop_topbar',array(
		'title'	=> __('Topbar Section','vw-ecommerce-shop'),
		'description'	=> __('Add Header Content here','vw-ecommerce-shop'),
		'priority'	=> null,
		'panel' => 'vw_ecommerce_shop_panel_id',
	));
	
	$wp_customize->add_setting('vw_ecommerce_shop_shipping',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_shipping',array(
		'label'	=> __('Add Text','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar',
		'setting'	=> 'vw_ecommerce_shop_shipping',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('vw_ecommerce_shop_return',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_return',array(
		'label'	=> __('Add Text','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar',
		'setting'	=> 'vw_ecommerce_shop_return',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('vw_ecommerce_shop_cash',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_cash',array(
		'label'	=> __('Add Text','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar',
		'setting'	=> 'vw_ecommerce_shop_cash',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('vw_ecommerce_shop_contact',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_contact',array(
		'label'	=> __('Add Phone Number','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar',
		'setting'	=> 'vw_ecommerce_shop_contact',
		'type'		=> 'text'
	));

	//Social Icons(topbar)
	$wp_customize->add_section('vw_ecommerce_shop_topbar_header',array(
		'title'	=> __('Social Icon Section','vw-ecommerce-shop'),
		'description'	=> __('Add Header Content here','vw-ecommerce-shop'),
		'priority'	=> null,
		'panel' => 'vw_ecommerce_shop_panel_id',
	));

	$wp_customize->add_setting('vw_ecommerce_shop_youtube_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_youtube_url',array(
		'label'	=> __('Add Youtube link','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar_header',
		'setting'	=> 'vw_ecommerce_shop_youtube_url',
		'type'		=> 'url'
	));

	$wp_customize->add_setting('vw_ecommerce_shop_facebook_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_facebook_url',array(
		'label'	=> __('Add Facebook link','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar_header',
		'setting'	=> 'vw_ecommerce_shop_facebook_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('vw_ecommerce_shop_twitter_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_twitter_url',array(
		'label'	=> __('Add Twitter link','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar_header',
		'setting'	=> 'vw_ecommerce_shop_twitter_url',
		'type'	=> 'url'
	));
	
	$wp_customize->add_setting('vw_ecommerce_shop_insta_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_insta_url',array(
		'label'	=> __('Add Instagram link','vw-ecommerce-shop'),
		'section'	=> 'vw_ecommerce_shop_topbar_header',
		'setting'	=> 'vw_ecommerce_shop_insta_url',
		'type'	=> 'url'
	));
	
	//home page slider
	$wp_customize->add_section( 'vw_ecommerce_shop_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'vw-ecommerce-shop' ),
		'priority'   => 30,
		'panel' => 'vw_ecommerce_shop_panel_id'
	) );

	for ( $count = 1; $count <= 4; $count++ ) {

		// Add color scheme setting and control.
		$wp_customize->add_setting( 'vw_ecommerce_shop_slidersettings-page-' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		) );

		$wp_customize->add_control( 'vw_ecommerce_shop_slidersettings-page-' . $count, array(
			'label'    => __( 'Select Slide Image Page', 'vw-ecommerce-shop' ),
			'section'  => 'vw_ecommerce_shop_slidersettings',
			'type'     => 'dropdown-pages'
		) );

	}

	//Trending Product
	$wp_customize->add_section('vw_ecommerce_shop_products',array(
		'title'	=> __('Trending Products','vw-ecommerce-shop'),
		'description'=> __('This section will appear below the slider.','vw-ecommerce-shop'),
		'panel' => 'vw_ecommerce_shop_panel_id',
	));	
	
	$wp_customize->add_setting('vw_ecommerce_shop_maintitle',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_maintitle',array(
		'label'	=> __('Section Title','vw-ecommerce-shop'),
		'section'=> 'vw_ecommerce_shop_products',
		'setting'=> 'vw_ecommerce_shop_maintitle',
		'type'=> 'text'
	));	

	for ( $count = 0; $count <= 0; $count++ ) {

		$wp_customize->add_setting( 'vw_ecommerce_shop_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		));
		$wp_customize->add_control( 'vw_ecommerce_shop_page' . $count, array(
			'label'    => __( 'Select Page', 'vw-ecommerce-shop' ),
			'section'  => 'vw_ecommerce_shop_products',
			'type'     => 'dropdown-pages'
		));
	}

	//Footer Text
	$wp_customize->add_section('vw_ecommerce_shop_footer',array(
		'title'	=> __('Footer','vw-ecommerce-shop'),
		'description'=> __('This section will appear in the footer','vw-ecommerce-shop'),
		'panel' => 'vw_ecommerce_shop_panel_id',
	));	
	
	$wp_customize->add_setting('vw_ecommerce_shop_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('vw_ecommerce_shop_footer_text',array(
		'label'	=> __('Copyright Text','vw-ecommerce-shop'),
		'section'=> 'vw_ecommerce_shop_footer',
		'setting'=> 'vw_ecommerce_shop_footer_text',
		'type'=> 'text'
	));	
}

add_action( 'customize_register', 'vw_ecommerce_shop_customize_register' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class vw_ecommerce_shop_customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'vw_ecommerce_shop_customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new vw_ecommerce_shop_customize_Section_Pro(
				$manager,
				'example_1',
				array(
					'priority'   => 9,
					'title'    => esc_html__( 'VW E-commerce Pro', 'vw-ecommerce-shop' ),
					'pro_text' => esc_html__( 'Upgrade Pro',         'vw-ecommerce-shop' ),
					'pro_url'  => 'https://www.vwthemes.com/premium/ecommerce-wordpress-theme/'
				)
			)	
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-ecommerce-shop-customize-controls', trailingslashit( get_template_directory_uri() ) . '/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-ecommerce-shop-customize-controls', trailingslashit( get_template_directory_uri() ) . '/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
vw_ecommerce_shop_customize::get_instance();