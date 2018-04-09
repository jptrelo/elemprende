<?php

// ---------------------------------------------
// Appearance - Customizer Panel
// ---------------------------------------------
$wp_customize->add_panel( 'ares_appearance_panel', array(
    'title'                 => __( 'Appearance', 'ares' ),
    'description'           => __( 'Customize the appearance of your site', 'ares' ),
    'priority'              => 10
) );

// ---------------------------------------------
// Colors Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_colors_section', array(
    'title'                 => __( 'Skin Color', 'ares'),
    'description'           => __( 'Customize the colors of your site', 'ares' ),
    'panel'                 => 'ares_appearance_panel'
) );

    // Theme Color
    $wp_customize->add_setting( 'ares[ares_theme_color]', array(
        'default'               => 'aqua',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_theme_color]', array(
        'label'   => __( 'Select the theme skin color', 'ares' ),
        'section' => 'ares_colors_section',
        'type'    => 'radio',
        'choices'    => array(
            'aqua'      => __( 'Aqua', 'ares' ),
            'green'     => __( 'Green', 'ares' ),
            'red'       => __( 'Red', 'ares' ),
        )
    ));

// ---------------------------------------------
// Background Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_background_section', array(
    'title'                 => __( 'Background Pattern', 'ares'),
    'description'           => __( 'Customize the site\'s textured background pattern', 'ares' ),
    'panel'                 => 'ares_appearance_panel'
) );
    
    // Theme Background Pattern
    $wp_customize->add_setting( 'ares[ares_theme_background_pattern]', array(
        'default'               => 'crossword',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_theme_background_pattern]', array(
        'label'     => __( 'Select the background pattern', 'ares' ),
        'section'   => 'ares_background_section',
        'type'      => 'radio',
        'choices'   => has_filter('ares_get_background_patterns') ? apply_filters( 'ares_get_background_patterns', ares_get_background_patterns() ) : ares_get_background_patterns()
    ));

// ---------------------------------------------
// Fonts Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_fonts_section', array(
    'title'                 => __( 'Fonts', 'ares'),
    'description'           => __( 'Customize the site\'s fonts', 'ares' ),
    'panel'                 => 'ares_appearance_panel'
) );

    // Primary Font Family
    $wp_customize->add_setting( 'ares[ares_font_family]', array(
        'default'               => 'Rajdhani, sans-serif',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_font_family]', array(
        'label'   => __( 'Select the primary font family (Headings)', 'ares' ),
        'section' => 'ares_fonts_section',
        'type'    => 'select',
        'choices' => ares_fonts()
    ));

    // Secondary Font Family
    $wp_customize->add_setting( 'ares[ares_font_family_secondary]', array(
        'default'               => 'Roboto, sans-serif',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'sanitize_text_field',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_font_family_secondary]', array(
        'label'   => __( 'Select the secondary font family (Body)', 'ares' ),
        'section' => 'ares_fonts_section',
        'type'    => 'select',
        'choices' => ares_fonts()
    ));
    
    // Main Font Size
    $wp_customize->add_setting( 'ares[ares_font_size]', array (
        'default'               => 14,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_integer',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_font_size]', array(
        'type'                  => 'number',
        'section'               => 'ares_fonts_section',
        'label'                 => __( 'Body Font Size', 'ares' ),
        'input_attrs'           => array(
            'min' => 10,
            'max' => 40,
            'step' => 1,
    ) ) );
