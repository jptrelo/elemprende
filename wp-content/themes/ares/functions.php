<?php
/**
 * Ares functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Ares
 */

if ( ! function_exists( 'ares_setup' ) ) :

    if( !defined( 'ARES_VERSION' ) ) :
        define( 'ARES_VERSION', '2.0.3' );
    endif;

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function ares_setup() {

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Ares, use a find and replace
         * to change 'ares' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'ares', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
                'primary' => esc_html__( 'Primary', 'ares' ),
        ) );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
        ) );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support( 'custom-logo', array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
        ) );
        
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        
        if( ! get_option( 'ares' ) ) :
            
            // Options array does not exist from a previous version
            
            add_option( 'ares', ares_get_options() );
        
        else :
            
            if ( !get_option( 'ares_migration_process' ) || get_option( 'ares_migration_process' ) != 'completed' ) : 
            
                ares_migration_process();
                
            endif;
            
        endif;
        
    }
    
endif;
add_action( 'after_setup_theme', 'ares_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ares_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'ares_content_width', 1170 );
}
add_action( 'after_setup_theme', 'ares_content_width', 0 );

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load TGM
 */
  require_once dirname(__FILE__) . '/inc/tgm.php';

/**
 * Load the main custom theme functions file.
 */
require get_template_directory() . '/inc/ares/ares.php';

function ares_get_options() {
    
    return get_option( 'ares', array(
        
        'ares_headerbar_bool'           => 'yes',                          
        'ares_facebook_url'             => '',
        'ares_twitter_url'              => '',
        'ares_linkedin_url'             => '',
        'ares_gplus_url'                => '',
        'ares_instagram_url'            => '',
        'ares_youtube_url'              => '',
        'ares_theme_color'              => 'aqua',                          
        'ares_theme_background_pattern' => 'crossword',                     
        'ares_font_size'                => 14,                              
        'ares_font_family'              => 'Rajdhani, sans-serif',      
        'ares_font_family_secondary'    => 'Roboto, sans-serif',      
        'ares_frontpage_content_bool'   => 'yes',
        
        'ares_slider_bool'              => 'yes',
        'ares_slide1_image'             => get_template_directory_uri() . '/inc/images/ares_demo.jpg',
        'ares_slide1_text'              => __( 'Ares: Responsive Multi-purpose WordPress Theme', 'ares' ),
        'ares_slide2_image'             => get_template_directory_uri() . '/inc/images/ares_demo.jpg',
        'ares_slide2_text'              => __( 'Ares: Responsive Multi-purpose WordPress Theme', 'ares' ),
        'ares_slide3_image'             => get_template_directory_uri() . '/inc/images/ares_demo.jpg',
        'ares_slide3_text'              => __( 'Ares: Responsive Multi-purpose WordPress Theme', 'ares' ),
        
        'ares_cta_bool'                 => 'yes',
        'ares_cta1_title'               => __( 'Theme Options', 'ares' ),
        'ares_cta1_icon'                => 'fa fa-gears',
        'ares_cta1_text'                => __( 'Change typography, colors, layouts...', 'ares' ),
        'ares_cta1_url'                 => '',
        'ares_cta1_button_text'         => __( 'Click Here', 'ares' ),
        'ares_cta2_title'               => __( 'Responsive Layout', 'ares' ),
        'ares_cta2_icon'                => 'fa fa-mobile',
        'ares_cta2_text'                => __( 'Fully responsive and mobile-ready', 'ares' ),
        'ares_cta2_url'                 => '',
        'ares_cta2_button_text'         => __( 'Click Here', 'ares' ),
        'ares_cta3_title'               => __( 'Elegant Design', 'ares' ),
        'ares_cta3_icon'                => 'fa fa-leaf',
        'ares_cta3_text'                => __( 'Beautiful design to give your site an elegant look', 'ares' ),
        'ares_cta3_url'                 => '',
        'ares_cta3_button_text'         => __( 'Click Here', 'ares' ),
        
        'ares_homepage_sidebar'         => 'sidebar-off',                     
        'ares_blog_layout'              => 'col2r',                           
        'ares_blog_featured'            => 'on',                              
        'ares_single_layout'            => 'col2r',                         
        'ares_single_featured'          => 'on',                            
        'ares_single_date'              => 'on',                            
        'ares_single_author'            => 'on',                            

        'ares_footer_cta'               => 'on',                          
        'ares_footer_cta_text'          => __( 'GET A NO RISK, FREE CONSULTATION TODAY', 'ares' ),
        'ares_footer_button_text'       => __( 'CONTACT US', 'ares' ),
        'ares_footer_button_url'        => '',
        'ares_footer_columns'           => 'col-md-4',
        'ares_footer_text'              => __( '© 2017 Your Company Name', 'ares' ),
    
        'ares_post_slider_cta_bool'     => 'yes',
        'ares_cta_header_one'           => __( 'Modern design with a responsive layout', 'ares' ),
        'ares_cta_header_two'           => __( 'User-friendly & Easily Customizable', 'ares' ),
        'ares_branding_bar_height'      => 80,
        
        'cart_icon_toggle'              => 'on',
        'woo_products_per_row'          => 4,
        
    ) );
    
}

function ares_migration_process() {
    
    // Options array exists from a previous version, set defaults on newer Customizer options

    $existing_ares_options = ares_get_options();

    if ( ! array_key_exists( 'woo_products_per_row', $existing_ares_options ) ) :
        $existing_ares_options['woo_products_per_row'] = 4;
    endif; 

    if ( ! array_key_exists( 'ares_font_family_secondary', $existing_ares_options ) ) :
        $existing_ares_options['ares_font_family_secondary'] = 'Roboto, sans-serif';
    endif; 

    if ( ! array_key_exists( 'ares_post_slider_cta_bool', $existing_ares_options ) ) :
        $existing_ares_options['ares_post_slider_cta_bool'] = 'yes';
    endif; 

    if ( ! array_key_exists( 'ares_branding_bar_height', $existing_ares_options ) ) :
        $existing_ares_options['ares_branding_bar_height'] = 80;
    endif; 

    if ( ! array_key_exists( 'cart_icon_toggle', $existing_ares_options ) ) :
        $existing_ares_options['cart_icon_toggle'] = 'on';
    endif; 

    if ( array_key_exists( 'ares_font_size', $existing_ares_options ) ) : 

        switch ( $existing_ares_options['ares_font_size'] ):

            case '10px' :
                $existing_ares_options['ares_font_size'] = 10;
                break;

            case '12px' :
                $existing_ares_options['ares_font_size'] = 12;
                break;

            case '14px' :
                $existing_ares_options['ares_font_size'] = 14;
                break;

            case '16px' :
                $existing_ares_options['ares_font_size'] = 16;
                break;

            case '18px' :
                $existing_ares_options['ares_font_size'] = 18;
                break;

            default :
                $existing_ares_options['ares_font_size'] = 14;

        endswitch;

    endif;

    if ( array_key_exists( 'ares_font_family', $existing_ares_options ) ) : 

        switch ( $existing_ares_options['ares_font_family'] ):

            case 'MS Sans Serif, Geneva, sans-serif' :
                $existing_ares_options['ares_font_family'] = 'MS Sans Serif, Tahoma, sans-serif';
                break;

            case 'Lobster, cursive' :
                $existing_ares_options['ares_font_family'] = 'Lobster Two, cursive';
                break;

            default :
                break;

        endswitch;

    endif;

    update_option( 'ares', $existing_ares_options );
    update_option( 'ares_migration_process', 'completed' );
    
}
