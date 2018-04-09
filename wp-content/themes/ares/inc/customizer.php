<?php
/**
 * Ares Theme Customizer
 *
 * @package Ares
 */



function ares_customize_enqueue() {
    
    wp_enqueue_script( 'ares-customizer-js', get_template_directory_uri() . '/inc/js/customizer.js', array( 'jquery', 'customize-controls' ), false, true );
}

if( !function_exists( 'ares_pro_init' ) ) :
    add_action( 'customize_controls_enqueue_scripts', 'ares_customize_enqueue' );
endif;


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ares_customize_register( $wp_customize ) {

    // Header
    require_once( 'customizer-panels/settings-header-footer.php' );

    // Frontpage
    require_once( 'customizer-panels/settings-frontpage.php' );

    // Slider
    require_once( 'customizer-panels/settings-slider.php' );

    // Blog
    require_once( 'customizer-panels/settings-blog.php' );

    // Single
    require_once( 'customizer-panels/settings-single-post.php' );
    
    // Appearance
    require_once( 'customizer-panels/settings-appearance.php' );
    
    // WooCommerce / Shop
    if ( class_exists( 'WooCommerce' ) ) :
        require_once( 'customizer-panels/settings-shop.php' );
    endif;
    
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'ares_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'ares_customize_partial_blogdescription',
        ) );
    }
}
add_action( 'customize_register', 'ares_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function ares_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function ares_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ares_customize_preview_js() {
	wp_enqueue_script( 'ares-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'ares_customize_preview_js' );

/**
 * Sanitization Functions
 */
function ares_sanitize_integer( $input ) {
    return intval( $input );
}

function ares_sanitize_col_sidebar( $input ) {

    $valid_keys = array(
        'col1'      => __( 'No Sidebar', 'ares' ),
        'col2r'     => __( 'Right Sidebar', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_sidebar_off_on( $input ) {

    $valid_keys = array(
        'sidebar-off'   => __( 'No Sidebar', 'ares' ),
        'sidebar-on'    => __( 'Right Sidebar', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_show_hide( $input ) {

    $valid_keys = array(
        'yes'   => __( 'Show', 'ares' ),
        'no'    => __( 'Hide', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_on_off( $input ) {

    $valid_keys = array(
        'on'    => __( 'Show', 'ares' ),
        'off'   => __( 'Hide', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_yes_no( $input ) {

    $valid_keys = array(
        'on'    => __( 'Yes', 'ares' ),
        'off'   => __( 'No', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_products_per_row( $input ) {

    $valid_keys = array(
        2   =>  __( 'Two', 'ares' ),
        3   =>  __( 'Three', 'ares' ),
        4   =>  __( 'Four', 'ares' ),
        5   =>  __( 'Five', 'ares' ),
    );

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}

function ares_sanitize_icon( $input ) {

    $valid_keys = ares_icons();

    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }

}
