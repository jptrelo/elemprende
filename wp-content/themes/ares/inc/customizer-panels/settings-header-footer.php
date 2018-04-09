<?php

// ---------------------------------------------
// Header - Customizer Panel
// ---------------------------------------------
$wp_customize->add_panel( 'ares_header_panel', array(
    'title'                 => __( 'Header & Footer', 'ares' ),
    'description'           => __( 'Customize the appearance of your Header', 'ares' ),
    'priority'              => 10
) );

// Move Site Identity
$wp_customize->add_section( 'title_tagline', array(
    'title'                 => __( 'Site Title & Tagline', 'ares' ),
    'panel'                 => 'ares_header_panel'
) );


// ---------------------------------------------
// Toolbar Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_toolbar_section', array(
    'title'                 => __( 'Toolbar & Social Links', 'ares'),
    'description'           => __( 'Customize the Toolbar in the Header and the Social Links it contains', 'ares' ),
    'panel'                 => 'ares_header_panel'
) );

    // Show / Hide the Toolbar?
    $wp_customize->add_setting( 'ares[ares_headerbar_bool]', array(
        'default'               => 'yes',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_show_hide',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_headerbar_bool]', array(
        'label'   => __( 'Show or hide the Toolbar section?', 'ares' ),
        'section' => 'ares_toolbar_section',
        'type'    => 'radio',
        'choices'    => array(
            'yes'   => __( 'Show', 'ares' ),
            'no'    => __( 'Hide', 'ares' ),
        )
    ));

    // Facebook URL
    $wp_customize->add_setting( 'ares[ares_facebook_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_facebook_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'Facebook URL', 'ares' ),
    ) );

    // Twitter URL
    $wp_customize->add_setting( 'ares[ares_twitter_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_twitter_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'Twitter URL', 'ares' ),
    ) );

    // LinkedIn URL
    $wp_customize->add_setting( 'ares[ares_linkedin_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_linkedin_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'LinkedIn URL', 'ares' ),
    ) );

    // Google+ URL
    $wp_customize->add_setting( 'ares[ares_gplus_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_gplus_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'Google+ URL', 'ares' ),
    ) );

    // Instagram URL
    $wp_customize->add_setting( 'ares[ares_instagram_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_instagram_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'Instagram URL', 'ares' ),
    ) );

    // YouTube URL
    $wp_customize->add_setting( 'ares[ares_youtube_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_youtube_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_toolbar_section',
        'label'                 => __( 'YouTube URL', 'ares' ),
    ) );

// ---------------------------------------------
// Header Height Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_header_height_section', array(
    'title'                 => __( 'Branding & Nav Bar', 'ares'),
    'description'           => __( 'Customize the Branding & Navigation bar in the Header', 'ares' ),
    'panel'                 => 'ares_header_panel'
) );

    // Branding Bar Height
    $wp_customize->add_setting( 'ares[ares_branding_bar_height]', array (
        'default'               => 80,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_integer',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_branding_bar_height]', array(
        'type'                  => 'number',
        'section'               => 'ares_header_height_section',
        'label'                 => __( 'Branding & Nav Bar Height', 'ares' ),
        'description'           => __( 'Adjust the height of the branding & navigation bar in the Header', 'ares' ),
        'input_attrs'           => array(
            'min' => 80,
            'max' => 400,
            'step' => 1,
    ) ) );

// ---------------------------------------------
// Footer Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_footer_section', array(
    'title'                 => __( 'Footer', 'ares'),
    'description'           => __( 'Customize the Footer', 'ares' ),
    'panel'                 => 'ares_header_panel'
) );

    // Show / Hide the Footer CTA?
    $wp_customize->add_setting( 'ares[ares_footer_cta]', array(
        'default'               => 'on',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_on_off',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_cta]', array(
        'label'   => __( 'Show or hide the Footer CTA section?', 'ares' ),
        'section' => 'ares_footer_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));
    
    // Footer CTA Text
    $wp_customize->add_setting( 'ares[ares_footer_cta_text]', array(
        'default'               => __( 'GET A NO RISK, FREE CONSULTATION TODAY', 'ares' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_cta_text]', array(
        'type'                  => 'text',
        'section'               => 'ares_footer_section',
        'label'                 => __( 'CTA - Text', 'ares' ),
    ) );
    
    // Footer CTA Text
    $wp_customize->add_setting( 'ares[ares_footer_button_text]', array(
        'default'               => __( 'CONTACT US', 'ares' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_button_text]', array(
        'type'                  => 'text',
        'section'               => 'ares_footer_section',
        'label'                 => __( 'CTA - Button Label', 'ares' ),
    ) );
    
    // Footer CTA Button Text
    $wp_customize->add_setting( 'ares[ares_footer_button_text]', array(
        'default'               => __( 'CONTACT US', 'ares' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_button_text]', array(
        'type'                  => 'text',
        'section'               => 'ares_footer_section',
        'label'                 => __( 'CTA - Button Label', 'ares' ),
    ) );
    
    // Footer CTA Button URL
    $wp_customize->add_setting( 'ares[ares_footer_button_url]', array(
        'default'               => '',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_button_url]', array(
        'type'                  => 'text',
        'section'               => 'ares_footer_section',
        'label'                 => __( 'CTA - Button URL', 'ares' ),
    ) );

    // Footer Widget Area Columns
    $wp_customize->add_setting( 'ares[ares_footer_columns]', array(
        'default'               => 'col-md-4',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_columns]', array(
        'label'   => __( 'Footer Widget Area - Columns', 'ares' ),
        'label'   => __( 'Save changes and reload to preview column changes', 'ares' ),
        'section' => 'ares_footer_section',
        'type'    => 'radio',
        'choices'    => array(
            'col-md-12'     => __( 'One', 'ares' ),
            'col-md-6'      => __( 'Two', 'ares' ),
            'col-md-4'      => __( 'Three', 'ares' ),
            'col-md-3'      => __( 'Four', 'ares' ),
        )
    ));
    
    // Footer Copyright Text
    $wp_customize->add_setting( 'ares[ares_footer_text]', array(
        'default'               => __( '© 2017 Your Company Name', 'ares' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_footer_text]', array(
        'type'                  => 'text',
        'section'               => 'ares_footer_section',
        'label'                 => __( 'Copyright Area Text', 'ares' ),
    ) );
    