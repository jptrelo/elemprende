<?php

// ---------------------------------------------
// Slider - Customizer Panel
// ---------------------------------------------
$wp_customize->add_panel( 'ares_slider_panel', array(
    'title'                 => __( 'Slider', 'ares' ),
    'description'           => __( 'Customize the appearance of your Slider', 'ares' ),
    'priority'              => 10
) );

// ---------------------------------------------
// Slide Settings Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_slider_settings_section', array(
    'title'                 => __( 'Slider Settings', 'ares'),
    'description'           => __( 'Customize the general settings for the Slider', 'ares' ),
    'panel'                 => 'ares_slider_panel'
) );

    // Show / Hide Slider?
    $wp_customize->add_setting( 'ares[ares_slider_bool]', array(
        'default'               => 'yes',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_show_hide',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[ares_slider_bool]', array(
        'label'   => __( 'Show or hide the Slider?', 'ares' ),
        'section' => 'ares_slider_settings_section',
        'type'    => 'radio',
        'choices'    => array(
            'yes'   => __( 'Show', 'ares' ),
            'no'    => __( 'Hide', 'ares' ),
        )
    ));

// ---------------------------------------------
// Slides Loop
// ---------------------------------------------

for ( $ctr = 1; $ctr < apply_filters( 'ares_capacity', 1 ); $ctr++ ) :

    // ---------------------------------------------
    // Slide Section
    // ---------------------------------------------
    $wp_customize->add_section( 'ares_slide_' . $ctr . '_section', array(
        'title'                 => __( 'Slide #', 'ares') . $ctr,
        'description'           => __( 'Customize slide #', 'ares' ) . $ctr,
        'panel'                 => 'ares_slider_panel'
    ) );

        // Slide - Image
        $wp_customize->add_setting( 'ares[ares_slide' . $ctr . '_image]', array(
            'default'               => $ctr > 3 ? '' : get_template_directory_uri() . '/inc/images/ares_demo.jpg',
            'transport'             => 'refresh',
            'sanitize_callback'     => 'esc_url_raw',
            'type'                  => 'option'
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ares[ares_slide' . $ctr . '_image]', array(
            'mime_type'             => 'image',
            'settings'              => 'ares[ares_slide' . $ctr . '_image]',
            'section'               => 'ares_slide_' . $ctr . '_section',
            'label'                 => __( 'Slide Image', 'ares' ),
        ) ) );

        // Slide - Caption Text
        $wp_customize->add_setting( 'ares[ares_slide' . $ctr . '_text]', array(
            'default'               => __( 'Ares: Responsive Multi-purpose WordPress Theme', 'ares' ),
            'transport'             => 'refresh',
            'sanitize_callback'     => 'sanitize_text_field',
            'type'                  => 'option'
        ) );
        $wp_customize->add_control( 'ares[ares_slide' . $ctr . '_text]', array(
            'type'                  => 'text',
            'section'               => 'ares_slide_' . $ctr . '_section',
            'label'                 => __( 'Text Caption', 'ares' ),
        ) );

endfor;
