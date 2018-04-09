<?php

// ---------------------------------------------
// Shop / WooCommerce Section
// ---------------------------------------------
$wp_customize->add_section( 'ares_shop_section', array(
    'title'                 => __( 'WooCommerce Shop', 'ares'),
    'description'           => __( 'Customize the your WooCommerce Shop', 'ares' ),
    'priority'              => 10
) );

    // Show / Hide the Cart?
    $wp_customize->add_setting( 'ares[cart_icon_toggle]', array(
         'default'               => 'on',
         'transport'             => 'refresh',
         'sanitize_callback'     => 'ares_sanitize_on_off',
         'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[cart_icon_toggle]', array(
        'label'   => __( 'Show or Hide the WooCommerce Cart icon in the site branding bar?', 'ares' ),
        'section' => 'ares_shop_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));
    
    // Show / Hide Shop Sidebar on Shop Page?
    $wp_customize->add_setting( 'ares[shop_sidebar_on_archive]', array(
         'default'               => 'on',
         'transport'             => 'refresh',
         'sanitize_callback'     => 'ares_sanitize_on_off',
         'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[shop_sidebar_on_archive]', array(
        'label'   => __( 'Show or Hide the Shop sidebar on the Shop page?', 'ares' ),
        'section' => 'ares_shop_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    // Show / Hide Shop Sidebar on Single Product Page?
    $wp_customize->add_setting( 'ares[shop_sidebar_on_product]', array(
         'default'               => 'on',
         'transport'             => 'refresh',
         'sanitize_callback'     => 'ares_sanitize_on_off',
         'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[shop_sidebar_on_product]', array(
        'label'   => __( 'Show or Hide the Shop sidebar on the Single Product page?', 'ares' ),
        'section' => 'ares_shop_section',
        'type'    => 'radio',
        'choices'    => array(
            'on'    => __( 'Show', 'ares' ),
            'off'   => __( 'Hide', 'ares' ),
        )
    ));

    // Number of Products Per Shop Row?
    $wp_customize->add_setting( 'ares[woo_products_per_row]', array(
        'default'               => 4,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'ares_sanitize_products_per_row',
        'type'                  => 'option'
    ) );
    $wp_customize->add_control( 'ares[woo_products_per_row]', array(
        'label'         => __( 'Set the number of products to appear per row in the Shop', 'ares' ),
        'description'   => __( 'Useful for Shops with a sidebar - fewer items per row will increase overall item size', 'ares' ),
        'section'       => 'ares_shop_section',
        'type'          => 'select',
        'choices'       => array(
            2   =>  __( 'Two', 'ares' ),
            3   =>  __( 'Three', 'ares' ),
            4   =>  __( 'Four', 'ares' ),
            5   =>  __( 'Five', 'ares' ),
        )
    ));
