<?php
/**
 * styledstore Theme Customizer.
 * @description This file build option inside customizer menu options
 * @package styledstore
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function styledstore_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'styledstore_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-desc',
			'container_inclusive' => false,
			'render_callback' => 'styledstore_customize_partial_blogdescription',
		) );
	}

	/*
    =================================================
    Basic Setting
    =================================================
    */

	$wp_customize->add_section( 'basic_settings', array(
		'title'          => __( 'Basic Settings', 'styled-store' ),
		'priority'       => 40,
	) );

	// Setting for blog layout
	$wp_customize->add_setting( 'blog_layout', array(
		'default' => 'blogright',
		'sanitize_callback' => 'styledstore_sanitize_select',
	) );
	// Control for blog layout	
	$wp_customize->add_control( 'blog_layout', array(
		'label'   => __( 'Blog Layout', 'styled-store' ),
		'section' => 'basic_settings',
		'priority' => 2,
		'type'    => 'radio',
			'choices' => array(
				'blogright' => __( 'Blog with Right Sidebar', 'styled-store' ),
				'blogleft' => __( 'Blog with Left Sidebar', 'styled-store' ),
				'blogwide' => __( 'Blog Full Width &amp; no Sidebars', 'styled-store' ),
			),
	));

	/*
    =================================================
    Move to top setting
    =================================================
    */

    $wp_customize->add_setting( 'styledstore_movetotop', array(
		'default'        => '0',
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'styledstore_movetotop', array(
		'settings' => 'movetotop',
		'label'    => __( 'Enable Move To Top', 'styled-store' ),
		'section'  => 'basic_settings',		
		'type'     => 'checkbox',
		'priority' => 5,
	) );


	//hide author meta information
    $wp_customize->add_setting( 'styledstore_blog_tax', array(
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	));

	$wp_customize->add_control( 'styledstore_blog_tax', array(
        'type' => 'checkbox',
        'label'    => __( 'Hide Blog Taxonomy Section', 'styled-store' ),
        'section' => 'basic_settings',
		'priority' => 6,
    ) );
	
	
	/*
    =================================================
    Social Networking setting
    =================================================
    */

    $wp_customize->add_section( 'social_networking', array(
		'title'          => __( 'Social Networking', 'styled-store' ),
		'priority'       => 50,
	) );


	
// Setting for hiding the social bar	
	$wp_customize->add_setting( 'styledstore_show_social_icon', array(
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	));

	
	// Control for hiding the social bar	
	$wp_customize->add_control( 'styledstore_show_social_icon', array(
        'label' => __( 'Show Social Bar', 'styled-store' ),
		'type' => 'checkbox',
		'section' => 'social_networking',
		'priority' => 1,
    ) );

	// Setting group for Facebook
	$wp_customize->add_setting( 'facebook_uid', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'facebook_uid', array(
		'settings' => 'facebook_uid',
		'label'    => __( 'Facebook', 'styled-store' ),
		'section'  => 'social_networking',
		'type'     => 'url',
		'priority' => 1,
	) );

	// Setting group for skype
	$wp_customize->add_setting( 'skype_uid', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'skype_uid', array(
		'label'    => __( 'Skype', 'styled-store' ),
		'section'  => 'social_networking',
		'type'     => 'url',
		'priority' => 2,
	) );

	// Setting group for Twitter
	$wp_customize->add_setting( 'twitter_uid', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'twitter_uid', array(
		'label'    => __( 'Twitter', 'styled-store' ),
		'section'  => 'social_networking',
		'type'     => 'url',
		'priority' => 3,
	) );

	// Setting group for Twitter
	$wp_customize->add_setting( 'rss_uid', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'rss_uid', array(
		'label'    => __( 'RSS', 'styled-store' ),
		'section'  => 'social_networking',
		'type'     => 'url',
		'priority' => 3,
	) );

	/*
    =================================================
    Homepage Setting
    =================================================
    */

    $wp_customize->add_panel( 'styledstore_homepage_panel', array(
		'title'          => __( 'Homepage Setting', 'styled-store' ),
		'priority'       => 51,
	) );

    //homepage basic setting
    $wp_customize->add_section( 'styledstore_homepage_section', array(
		'title'	=> __( 'Homepage Slider Setting', 'styled-store' ),
		'priority' => 51,
		'panel' => 'styledstore_homepage_panel'
	) );

	$wp_customize->add_setting( 'styledstore_slider_category', array(
	    'sanitize_callback' => 'styledstore_sanitize_dropdown_category'
	) );

	$wp_customize->add_control( new Styledstore_Category_Dropdown( $wp_customize, 'styledstore_slider_category',
	    array(
	        'label' => esc_html__( 'Choose Slider Category', 'styled-store' ),
	        'section' => 'styledstore_homepage_section',
	        'description' => esc_html__(' Select Category to show posts in Slider section. This will pull featured image of product with their respective tags and product title. ) ','styled-store'),
	        'type' => 'select',
	        'priority' => 1,
	    )
	) ); 

    /*
    =================================================
    Footer Setting
    =================================================
    */

    $wp_customize->add_panel( 'styledstore_footer', array(
		'title'          => __( 'Footer Setting', 'styled-store' ),
		'priority'       => 60,
	) );

	$wp_customize->add_section( 'styledstore_payment_gateway', array(
		'title'          => __( 'Payment Support', 'styled-store' ),
		'priority'       => 1,
		'panel'			=> 'styledstore_footer'
	) );

	// Setting group for a Visa
	 $wp_customize->add_setting( 'styledstore_support_payment_visa', array(
		'sanitize_callback' => 'styledstore_sanitize_upload',
    ));
	 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'styledstore_support_payment_visa', array(
        'label'    => __('Image 1', 'styled-store'),
        'section'  => 'styledstore_payment_gateway',
		'priority' => 1,
    )));
	
	// Setting group for a master card
	 $wp_customize->add_setting( 'styledstore_support_payment_mastercard', array(
		'sanitize_callback' => 'styledstore_sanitize_upload',
    ));
	
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'styledstore_support_payment_mastercard', array(
        'label'    => __( 'Image 2', 'styled-store'),
        'section'  => 'styledstore_payment_gateway',
		'priority' => 1,
    )));

    // Setting group for a master card
	 $wp_customize->add_setting( 'styledstore_support_payment_paypal', array(
		'sanitize_callback' => 'styledstore_sanitize_upload',
    ));
	
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'styledstore_support_payment_paypal', array(
        'label'    => __( 'Image 3', 'styled-store'),
        'section'  => 'styledstore_payment_gateway',
		'priority' => 1,
    )));

    // Setting group for a amazon
	 $wp_customize->add_setting( 'styledstore_support_payment_amazon', array(
		'sanitize_callback' => 'styledstore_sanitize_upload',
    ));
	
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'styledstore_support_payment_amazon', array(
        'label'    => __( 'Image 4', 'styled-store'),
        'section'  => 'styledstore_payment_gateway',
		'priority' => 1,
    )));
    // Setting group for a amazon
	 $wp_customize->add_setting( 'styledstore_support_payment_am', array(
		'sanitize_callback' => 'styledstore_sanitize_upload',
    ));
	
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'styledstore_support_payment_am', array(
        'label'    => __('Image 5', 'styled-store'),
        'section'  => 'styledstore_payment_gateway',
		'priority' => 1,
    )));

    //copy right notice
    $wp_customize->add_section( 'styledstore_footercopy', array(
		'title'          => __( 'Copyright Text', 'styled-store' ),
		'priority'       => 2,
		'panel'			=> 'styledstore_footer'
	) );

	// Setting for hiding the footer text	
	$wp_customize->add_setting( 'styledstore_show_footer_text', array(
		'defaul' => '',
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	));


	// Control for hiding the footer text	
	$wp_customize->add_control( 'styledstore_show_footer_text', array(
        'label' => __( 'Hide Copyright Text', 'styled-store' ),
		'type' => 'checkbox',
		'section' => 'styledstore_footercopy',
		'priority' => 1,
    ) );

    /*
    =================================================
    Wocommerce Setting @since version 1.1.0
    =================================================
    */

    $wp_customize->add_panel( 'styledstore_woocommerce', array(
		'title'          => esc_html__( 'Woocommerce Setting', 'styled-store' ),
		'priority'       => 61,
	) );

	$wp_customize->add_section( 'stwo_basic_setting', array(
		'title'          => esc_html__( 'Basic Setting', 'styled-store' ),
		'priority'       => 1,
		'panel'			=> 'styledstore_woocommerce'
	) );

	// Setting for blog layout
	$wp_customize->add_setting( 'stwo_shop_layout', array(
		'default' => 'shopright',
		'sanitize_callback' => 'styledstore_sanitize_select',
	) );
	// Control for woocommece shop page layout	
	$wp_customize->add_control( 'stwo_shop_layout', array(
	'label'   => esc_html__( 'Shop Page Layout', 'styled-store' ),
	'section' => 'stwo_basic_setting',
	'priority' => 1,
	'type'    => 'radio',
		'choices' => array(
			'shopright' => esc_html__( 'Shop with Right Sidebar', 'styled-store' ),
			'shopleft' => esc_html__( 'Shop with Left Sidebar', 'styled-store' ),
			'shopwide' => esc_html__( 'Shop Full Width &amp; no Sidebars', 'styled-store' ),
		),
	));

	//woocommerce hide cart link/icon from product page
	$wp_customize->add_setting( 'stwo_hide_add_to_cart', array(
		'default'        => '',
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'stwo_hide_add_to_cart', array(
		'label'    => esc_html__( 'Disable Add to cart link from product', 'styled-store' ),
		'description' => esc_html__( 'This remove the cart icon while hovering on product image', 'styled-store'),
		'section'  => 'stwo_basic_setting',
		'type'     => 'checkbox',
		'priority' => 2,
	) );

	//excerpt length
    $wp_customize->add_setting( 'stwo_excerpt_lenth', array(
		'default'        => '3',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'stwo_excerpt_lenth', array(
		'label'    => esc_html__( 'Excerpt Lenth', 'styled-store' ),
		'description' => esc_html__( 'This trim the lenth of short description on shop page ', 'styled-store'),
		'section'  => 'stwo_basic_setting',
		'type'     => 'number',
		'priority' => 3,
	) );

	//show onsale on dollar
	$wp_customize->add_setting( 'stwo_show_onsale_on_pricing_form', array(
		'default'        => '',
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'stwo_show_onsale_on_pricing_form', array(
		'label'    => esc_html__( 'Check this to show onsale on pricing format Eg: $10 OFF', 'styled-store' ),
		'section'  => 'stwo_basic_setting',
		'type'     => 'checkbox',
		'priority' => 4
	) );

	//woocommerce number of cross sellls products on cart page
	$wp_customize->add_setting( 'stwo_count_crosssells', array(
		'default'        => '2',
		'sanitize_callback' => 'styledstore_sanitize_text',
	) );

	$wp_customize->add_control( 'stwo_count_crosssells', array(
		'label'    => esc_html__( 'Number of Cross Sells Product', 'styled-store' ),
		'description' => esc_html__( 'This effects the total number of cross sells products on cartpage', 'styled-store' ),
		'section'  => 'stwo_basic_setting',
		'type'     => 'text',
		'priority' => 5,
	) );

	//woocommerce product number at shop page since version 1.5.3
	$wp_customize->add_setting( 'stwo_number_product_at_shop_page', array(
		'default'        => '10',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'stwo_number_product_at_shop_page', array(
		'label'    => esc_html__( 'Number of product to show on shop page', 'styled-store' ),
		'section'  => 'stwo_basic_setting',
		'type'     => 'number',
		'priority' => 10
	) );

	//disable flipper image
	$wp_customize->add_setting( 'stwo_disable_fipper_image', array(
		'default'        => 0,
		'sanitize_callback' => 'styledstore_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'stwo_disable_fipper_image', array(
		'label'    => esc_html__( 'Disable flipper from shop page', 'styled-store' ),
		'description' => esc_html__( 'This will remove flipping functionality on shop page', 'styled-store'),
		'section'  => 'stwo_basic_setting',
		'type'     => 'checkbox',
		'priority' => 12,
	) );


} //end of styledstore_customize_register customizer 
add_action( 'customize_register', 'styledstore_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function styledstore_customize_preview_js() {

	wp_enqueue_script( 'styledstore_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'styledstore_customize_preview_js' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Styled Store 1.0
 * @see styledstore_customize_register()
 *
 * @return void
 */
function styledstore_customize_partial_blogname() {

	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Styled Store 1.0
 * @see styledstore_customize_register()
 *
 * @return void
 */
function styledstore_customize_partial_blogdescription() {

	bloginfo( 'description' );
}

/**
 * adds sanitization callback function : checkbox
 * @package Styledstore 
*/
function styledstore_sanitize_checkbox( $input ) {

if ( $input == 1 ) {
	    return 1;
	} else {
	    return '';
	}
}


/**
 * adds sanitization callback function : Image sanitization callback example.
 * @package Styledstore 
 * @version 1.0
*/
function styledstore_sanitize_upload( $image, $setting ) {
		/*
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		*/
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
		// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}

/**
 * adds sanitization callback function : TEXT
 * @package Styledstore 
 * @version 1.0
*/
function styledstore_sanitize_text( $input ) {

	return wp_kses_post( force_balance_tags( $input ) );
}

/**
 * Sanitize radio checkbox
 * @since Styled Store 1.1.0
 * @see styledstore_customize_register()
 * @version 1.1.0
 * @return sanitize value
 */

function styledstore_sanitize_select( $input, $setting ) {

   // Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize radio checkbox
 * @since Styled Store 1.1.0
 * @see styledstore_customize_register()
 * @version 1.1.0
 * @return void
 */
function styledstore_sanitize_dropdown_category( $input ) {
    return absint( $input );
}