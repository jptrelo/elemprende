<?php

// ---------------------------------------------
// Single Post Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_single_post_section', array(
    'title'                 => __( 'Single Layout', 'ares'),
    'description'           => __( 'Customize the single templates for Posts/Pages', 'ares' ),
    'priority'              => 10
) );

    // Single Layout - Include Sidebar?
    $wp_customize->add_setting( 'ares[ares_single_layout]', array(
        'default'               => 'col2r',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_col_sidebar',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_single_layout]', array(
        'label'   => __( 'Include the right sidebar on the single template?', 'ares' ),
        'section' => 'ares_single_post_section',
        'type'    => 'radio',
        'choices'    => array(
            'col1'      => __( 'No Sidebar', 'ares' ),
            'col2r'     => __( 'Right Sidebar', 'ares' ),
        )
    ));

    // Single Post Images
    $wp_customize->add_setting( 'ares[ares_single_featured]', array(
        'default'               => 'on',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_on_off',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_single_featured]', array(
        'label'   => __( 'Show or Hide the Post images on single posts?', 'ares' ),
        'section' => 'ares_single_post_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    // Single Post Dates
    $wp_customize->add_setting( 'ares[ares_single_date]', array(
        'default'               => 'on',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_on_off',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_single_date]', array(
        'label'   => __( 'Show or Hide the Date on single posts?', 'ares' ),
        'section' => 'ares_single_post_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    // Single Post Author
    $wp_customize->add_setting( 'ares[ares_single_author]', array(
        'default'               => 'on',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_on_off',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_single_author]', array(
        'label'   => __( 'Show or Hide the Author on single posts?', 'ares' ),
        'section' => 'ares_single_post_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    