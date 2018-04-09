<?php
/**
 * SweetHeat Theme Customizer
 *
 * @package SweetHeat
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sweetheat_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    //___General___//
    $wp_customize->add_section(
        'sweetheat_general',
        array(
            'title' => __('General', 'sweetheat'),
            'priority' => 9,
        )
    );
    //Logo Upload
    $wp_customize->add_setting(
        'site_logo',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_logo',
            array(
               'label'          => __( 'Upload your logo', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'site_logo',
               'priority' => 9,
            )
        )
    );
    //Logo size
    $wp_customize->add_setting(
        'logo_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '150',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'logo_size', array(
        'type'        => 'number',
        'priority'    => 10,
        'section'     => 'sweetheat_general',
        'label'       => __('Logo size', 'sweetheat'),
        'description' => __('Menu-content spacing will return to normal after you save &amp; exit the Customizer', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 300,
            'step'  => 5,
        ),
    ) );    
    //Favicon Upload
    $wp_customize->add_setting(
        'site_favicon',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_favicon',
            array(
               'label'          => __( 'Upload your favicon', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'site_favicon',
               'priority' => 11,
            )
        )
    );
    //Apple touch icon 144
    $wp_customize->add_setting(
        'apple_touch_144',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_144',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (144x144 pixels)', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'apple_touch_144',
               'priority'       => 12,
            )
        )
    );
    //Apple touch icon 114
    $wp_customize->add_setting(
        'apple_touch_114',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_114',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (114x114 pixels)', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'apple_touch_114',
               'priority'       => 13,
            )
        )
    );
    //Apple touch icon 72
    $wp_customize->add_setting(
        'apple_touch_72',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_72',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (72x72 pixels)', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'apple_touch_72',
               'priority'       => 14,
            )
        )
    );
    //Apple touch icon 57
    $wp_customize->add_setting(
        'apple_touch_57',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_57',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (57x57 pixels)', 'sweetheat' ),
               'type'           => 'image',
               'section'        => 'sweetheat_general',
               'settings'       => 'apple_touch_57',
               'priority'       => 15,
            )
        )
    );	
	//___Footer call to action___//
    $wp_customize->add_section(
        'notice_bar',
        array(
            'title' => __('Footer call to action', 'sweetheat'),
            'priority' => 15,
        )
    );
    //Call to action text
	$wp_customize->add_setting(
	    'action_text',
	    array(
	        'default' => '',
	        'sanitize_callback' => 'sweetheat_sanitize_text',
	    )
	);
	$wp_customize->add_control(
	    'action_text',
	    array(
	        'label' => __( 'Call to action text', 'sweetheat' ),
	        'section' => 'notice_bar',
	        'type' => 'text',
	        'priority' => 10
	    )
	);	
    //Call to action button
	$wp_customize->add_setting(
	    'action_button',
	    array(
	        'default' => '',
	        'sanitize_callback' => 'sweetheat_sanitize_text',
	    )
	);
	$wp_customize->add_control(
	    'action_button',
	    array(
	        'label' => __( 'Call to action button', 'sweetheat' ),
	        'section' => 'notice_bar',
	        'type' => 'text',
	        'priority' => 11
	    )
	);	
    //Call to action button link
	$wp_customize->add_setting(
	    'action_button_link',
	    array(
	        'default' => '',
	        'sanitize_callback' => 'esc_url_raw',
	    )
	);
	$wp_customize->add_control(
	    'action_button_link',
	    array(
	        'label' => __( 'Call to action button link', 'sweetheat' ),
	        'section' => 'notice_bar',
	        'type' => 'text',
	        'priority' => 12
	    )
	);
    //___Blog options___//
    $wp_customize->add_section(
        'blog_options',
        array(
            'title' => __('Blog options', 'sweetheat'),
            'priority' => 12,
        )
    );
    //Full content posts
    $wp_customize->add_setting(
      'full_content',
      array(
        'sanitize_callback' => 'sweetheat_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
        'full_content',
        array(
            'type' => 'checkbox',
            'label' => __('Check this box to display the full content of your posts on the home page.', 'sweetheat'),
            'section' => 'blog_options',
            'priority' => 11,
        )
    );
    //Excerpt
    $wp_customize->add_setting(
        'exc_lenght',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '55',
        )       
    );
    $wp_customize->add_control( 'exc_lenght', array(
        'type'        => 'number',
        'priority'    => 12,
        'section'     => 'blog_options',
        'label'       => __('Excerpt lenght', 'sweetheat'),
        'description' => __('Choose your excerpt length here. Default: 55 words', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 200,
            'step'  => 5,
        ),
    ) );
    //___Fonts___//
    $wp_customize->add_section(
        'sweetheat_typography',
        array(
            'title' => __('Fonts', 'sweetheat' ),
            'priority' => 15,
        )
    );
    $font_choices = 
        array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT Sans Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
            'Questrial' => 'Questrial',
            'Rambla:400,700,400italic,700italic' => 'Rambla',
            'Dosis:200,300,400,500,600,700,800' => 'Dosis',
            'Abel' => 'Abel',           
        );
    
    $wp_customize->add_setting(
        'headings_fonts',
        array(
            'sanitize_callback' => 'sweetheat_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'headings_fonts',
        array(
            'type' => 'select',
            'label' => __('Select your desired font for the headings.', 'sweetheat'),
            'section' => 'sweetheat_typography',
            'choices' => $font_choices
        )
    );
    
    $wp_customize->add_setting(
        'body_fonts',
        array(
            'sanitize_callback' => 'sweetheat_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'body_fonts',
        array(
            'type' => 'select',
            'label' => __('Select your desired font for the body.', 'sweetheat'),
            'section' => 'sweetheat_typography',
            'choices' => $font_choices
        )
    );
    //H1 size
    $wp_customize->add_setting(
        'h1_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '44',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h1_size', array(
        'type'        => 'number',
        'priority'    => 11,
        'section'     => 'sweetheat_typography',
        'label'       => __('H1 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //H2 size
    $wp_customize->add_setting(
        'h2_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '36',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h2_size', array(
        'type'        => 'number',
        'priority'    => 12,
        'section'     => 'sweetheat_typography',
        'label'       => __('H2 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //H3 size
    $wp_customize->add_setting(
        'h3_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '30',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h3_size', array(
        'type'        => 'number',
        'priority'    => 13,
        'section'     => 'sweetheat_typography',
        'label'       => __('H3 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //h4 size
    $wp_customize->add_setting(
        'h4_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '20',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h4_size', array(
        'type'        => 'number',
        'priority'    => 14,
        'section'     => 'sweetheat_typography',
        'label'       => __('H4 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //h5 size
    $wp_customize->add_setting(
        'h5_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '18',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h5_size', array(
        'type'        => 'number',
        'priority'    => 15,
        'section'     => 'sweetheat_typography',
        'label'       => __('H5 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //h6 size
    $wp_customize->add_setting(
        'h6_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '14',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h6_size', array(
        'type'        => 'number',
        'priority'    => 16,
        'section'     => 'sweetheat_typography',
        'label'       => __('H6 font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
        ),
    ) );
    //body
    $wp_customize->add_setting(
        'body_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '15',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'body_size', array(
        'type'        => 'number',
        'priority'    => 17,
        'section'     => 'sweetheat_typography',
        'label'       => __('Body font size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 24,
            'step'  => 1,
        ),
    ) );
    //Home page widget titles size
    $wp_customize->add_setting(
        'widget_title_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '38',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'widget_title_size', array(
        'type'        => 'number',
        'priority'    => 18,
        'section'     => 'sweetheat_typography',
        'label'       => __('Home page widget titles size', 'sweetheat'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 90,
            'step'  => 1,
        ),
    ) );
    //Menu background
    $wp_customize->add_setting(
        'menu_color',
        array(
            'default'           => '#000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'menu_color',
            array(
                'label' => __('Header background', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'menu_color',
                'priority' => 10
            )
        )
    );
    //Menu links
    $wp_customize->add_setting(
        'menu_links_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'menu_links_color',
            array(
                'label' => __('Menu links', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'menu_links_color',
                'priority' => 11
            )
        )
    );  	
    //Primary color
    $wp_customize->add_setting(
        'primary_color',
        array(
            'default'           => '#50a6c2',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_color',
            array(
                'label' => __('Primary color', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'primary_color',
                'priority' => 12
            )
        )
    );
    //Secondary color
    $wp_customize->add_setting(
        'secondary_color',
        array(
            'default'           => '#1d2730',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'secondary_color',
            array(
                'label' => __('Secondary color', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'secondary_color',
                'priority' => 13
            )
        )
    );
    //Site title
    $wp_customize->add_setting(
        'site_title_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_title_color',
            array(
                'label' => __('Site title', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'site_title_color',
                'priority' => 13
            )
        )
    );
    //Site description
    $wp_customize->add_setting(
        'site_desc_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_desc_color',
            array(
                'label' => __('Site description', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'site_desc_color',
                'priority' => 14
            )
        )
    );
    //Body
    $wp_customize->add_setting(
        'body_text_color',
        array(
            'default'           => '#aaa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_text_color',
            array(
                'label' => __('Text', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'body_text_color',
                'priority' => 16
            )
        )
    );
    //Footer
    $wp_customize->add_setting(
        'footer_color',
        array(
            'default'           => '#000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'footer_color',
            array(
                'label' => __('Footer background', 'sweetheat'),
                'section' => 'colors',
                'settings' => 'footer_color',
                'priority' => 17
            )
        )
    );                            			
}
add_action( 'customize_register', 'sweetheat_customize_register' );

/**
 * Sanitization
 */
//Text
function sweetheat_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}
//Checkbox
function sweetheat_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}
//Fonts
function sweetheat_sanitize_fonts( $input ) {
    $valid = array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
            'Questrial' => 'Questrial',
            'Rambla:400,700,400italic,700italic' => 'Rambla',
            'Dosis:200,300,400,500,600,700,800' => 'Dosis',
            'Abel' => 'Abel',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sweetheat_customize_preview_js() {
	wp_enqueue_script( 'sweetheat_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), true );
}
add_action( 'customize_preview_init', 'sweetheat_customize_preview_js' );
