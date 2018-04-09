<?php

// ---------------------------------------------
// Blog Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_blog_section', array(
    'title'                 => __( 'Blog Layout', 'ares'),
    'description'           => __( 'Customize the Blog of your site', 'ares' ),
    'priority'              => 10
) );

    // Blog Layout - Include Sidebar?
    $wp_customize->add_setting( 'ares[ares_blog_layout]', array(
        'default'               => 'col2r',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_col_sidebar',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_blog_layout]', array(
        'label'   => __( 'Include the right sidebar on the blog?', 'ares' ),
        'section' => 'ares_blog_section',
        'type'    => 'radio',
        'choices'    => array(
            'col1'      => __( 'No Sidebar', 'ares' ),
            'col2r'     => __( 'Right Sidebar', 'ares' ),
        )
    ));

    // Show / Hide Post images on the Blog?
    $wp_customize->add_setting( 'ares[ares_blog_featured]', array(
         'default'               => 'on',
         'transport'             => 'refresh',
         'sanitize_callback'     => 'ares_sanitize_on_off',
         'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_blog_featured]', array(
        'label'   => __( 'Show or Hide the Post images on the blog page?', 'ares' ),
        'section' => 'ares_blog_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    
    