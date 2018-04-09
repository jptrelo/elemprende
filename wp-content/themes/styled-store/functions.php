<?php
/**
 * styledstore functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package styledstore
 */

if ( ! function_exists( 'styledstore_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function styledstore_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on styledstore, use a find and replace
	 * to change 'styled-store' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'styled-store', get_template_directory() . '/languages' );

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
		'primary' => esc_html__( 'Primary', 'styled-store' ),
		'footer' => esc_html__( 'Footer', 'styled-store' ),
		'mobile' => esc_html__( 'Mobile Menu', 'styled-store' )
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

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'styledstore_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	 /*
	 * Enable support for custom logo.
	 * @see https://codex.wordpress.org/Theme_Logo
	 */
    add_theme_support( 'custom-logo', array(
    	'width'       => 100,
		'height'      => 30,
	   	'flex-width' => true,
	   	'flex-height' => true
	) );  

	//set thumbnnail size of 700*450 
	add_image_size( 'styledstoreblog-image', 825, 450, true );
	//set thumbnail image forblog post
	add_image_size ( 'styledstore-blog-fulwidth', 1110, 450, true);
	//set thumbnail image forblog post
	add_image_size ( 'styledstore-homepage-slider', 1349, 494, true);
	//set thumbnail size for single post thumb image
	add_image_size ( 'styledstore-product-thumb', 130, 90, true);
	//set thumbnail image for recent post
	add_image_size ( 'styledstore-thumbnail-recent', 80, 100, true);

	/**
	 * Declare WooCommerce Support
	 */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
endif;
add_action( 'after_setup_theme', 'styledstore_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function styledstore_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'styledstore_content_width', 640 );
}
add_action( 'after_setup_theme', 'styledstore_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function styledstore_widgets_init() {
	//create blog right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Right Sidebar', 'styled-store' ),
		'id'            => 'styled_store_blog_right_sidebar',
		'description'   => esc_html__( 'This is blog right sidebar column which appears on blog not on othere pages.', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create blog right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Left Sidebar', 'styled-store' ),
		'id'            => 'styled_store_blog_left_sidebar',
		'description'   => esc_html__( 'This is blog left sidebar column which appears on blog not on othere pages.', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create page right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Page Right Sidebar', 'styled-store' ),
		'id'            => 'styled_store_page_right_sidebar',
		'description'   => esc_html__( 'This is page right sidebar column which appears on right of page', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create page right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Page left Sidebar', 'styled-store' ),
		'id'            => 'styled_store_page_left_sidebar',
		'description'   => esc_html__( 'This is page left sidebar column which appears on left of the page', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create page right sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Banner', 'styled-store' ),
		'id'            => 'styled_store_banner',
		'description'   => esc_html__( 'This is banner sidebar which appears just below the header ', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create featured sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Featured Section', 'styled-store' ),
		'id'            => 'styledstore-homepage-featured',
		'description'   => esc_html__( 'This sidebar renders its content on page with Woocommerce Homepage template Selected', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create showcase product
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage showcase Product', 'styled-store' ),
		'id'            => 'styledstore-homepage-showcase-product',
		'description'   => __( 'This sidebar renders two showcase products in Main homepage', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create homepage category section
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Category Section', 'styled-store' ),
		'id'            => 'styledstore-category-section',
		'description'   => esc_html__( 'This sidebar renders its content on page with Woocommerce Homepage template Selected', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create homepage service section
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Service Section', 'styled-store' ),
		'id'            => 'styledstore-service-section',
		'description'   => esc_html__( 'This sidebar renders its content on page with Woocommerce Homepage template Selected', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget col-xs-12 col-md-4 %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create homepage promo section
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Promo Section', 'styled-store' ),
		'id'            => 'styledstore-promo-section',
		'description'   => esc_html__( 'This sidebar renders its content on page with Woocommerce Homepage template Selected', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create homepage recent post setion
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Recent Post Section', 'styled-store' ),
		'id'            => 'styledstore-recent-post-slider-section',
		'description'   => esc_html__( 'This sidebar renders its content on page with Woocommerce Homepage template Selected', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create product overview sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Product Overview', 'styled-store' ),
		'id'            => 'product_overview',
		'description'   => esc_html__( 'Product Overview Section Like Best Sell, Top Rated Product, New Arraivels etc, contains upto 4 widget in a row', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget col-xs-12 col-sm-6 col-md-3 %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'styled-store' ),
		'id'            => 'shop',
		'description'   => esc_html__( 'Sidebar for shop page', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

		//create footer top 1
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Top 1', 'styled-store' ),
		'id'            => 'styled_store_footertop1',
		'description'   => esc_html__( 'This is footer top column which appears on top of footer', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create footer top 2
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Top 2', 'styled-store' ),
		'id'            => 'styled_store_footertop2',
		'description'   => esc_html__( 'This is second footer top column which appears on top of footer', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create footer top 3
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Top 3', 'styled-store' ),
		'id'            => 'styled_store_footertop3',
		'description'   => esc_html__( 'This is third footer top column which appears on top of footer', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create footer top 4
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Top 4', 'styled-store' ),
		'id'            => 'styled_store_footertop4',
		'description'   => esc_html__( 'This is fourth footer top column which appears on top of footer', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

	//create footer top 5
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Top 5', 'styled-store' ),
		'id'            => 'styled_store_footertop5',
		'description'   => esc_html__( 'This is fifth footer top column which appears on top of footer', 'styled-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );

}
add_action( 'widgets_init', 'styledstore_widgets_init' );

/**
 * Register custom fonts.
 */
function styledstore_fonts_url() {
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$roboto_font = _x( 'on', 'Roboto font: on or off', 'styled-store' );

	if ( 'off' !== $roboto_font ) {

		$font_families = apply_filters( 'styledstore_add_fonts', array(
			'roboto' => 'Roboto:400,300,400italic,500,700,700italic,500italic,900|Source+Sans+Pro:100,400,700'
		) );

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 */
function styledstore_scripts() {
	
	$styledstore_theme_ver = wp_get_theme()->get('Version');

	wp_enqueue_style( 'styledstore-google-font-roboto', styledstore_fonts_url() );

	//Enqueue font awesome minified css
	wp_enqueue_style( 'font-awesome', get_template_directory_uri(). '/css/font-awesome.min.css', array(), '4.6.1' );
	//Enqueue bootstrap minified css
	wp_enqueue_style( 'bootstrap', get_template_directory_uri(). '/css/bootstrap.min.css', array(), '3.3.6');
	//Enqueue bootstrap minified css
	wp_enqueue_style( 'sm-core', get_template_directory_uri(). '/css/sm-core-css.css', array(), '1.0.0' );

	//Enqueue smart js css: sm-simple.css
	wp_enqueue_style( 'sm-mint', get_template_directory_uri(). '/css/sm-mint.css', array(), '1.0.0');

	//Enqueue bx slider css
	wp_enqueue_style( 'bxslider', get_template_directory_uri(). '/css/jquery.bxslider.css', array(), '4.1.2' );
	
	//Enqueue css for woocommerce styling
	wp_enqueue_style( 'styledstore-wocommerce', get_template_directory_uri(). '/css/woocommerce-css.css', array(), $styledstore_theme_ver);

		//Enqueue theme css file
	wp_enqueue_style( 'styledstore-main-style', get_stylesheet_uri(), array(), $styledstore_theme_ver );
	
	//Enqueue responsive css
	wp_enqueue_style( 'styledstore-responsive', get_template_directory_uri().'/css/responsive-css.css', array(), $styledstore_theme_ver );

	//Emqueue bootstrap minified js
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.6', true );

	//Emqueue bx slider minified js
	wp_enqueue_script( 'jquery-bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), '4.1.2', true );

	//Enqueue smart menu minified js
	wp_enqueue_script( 'jquery-smartmenus', get_template_directory_uri() . '/js/jquery.smartmenus.min.js', array('jquery'), '1.0.0', true );

	//Emqueue theme custom js
	wp_enqueue_script( 'styledstore-extra-js', get_template_directory_uri() . '/js/extra.js', array( 'jquery' ), $styledstore_theme_ver, true );

	//Enqueue match height js
	wp_enqueue_script( 'jquery-matchheight', get_template_directory_uri() . '/js/jquery.matchHeight-min.js', array( 'jquery' ), '0.7.0', true );

	//Emqueue skip link focus fix
	wp_enqueue_script( 'styledstore-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	//Add metadata to a script
	wp_enqueue_script( 'styledstore-respond', get_template_directory_uri() . '/js/respond.min.js' );
    wp_script_add_data( 'styledstore-respond', 'conditional', 'lt IE 9' );
 
    wp_enqueue_script( 'styledstore-html5shiv', get_template_directory_uri() . '/js/html5shiv.min.js' );
    wp_script_add_data( 'styledstore-html5shiv', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'styledstore_scripts' );


/**
*@DESCRIPTION This function register the widgets
* @package Styled Store
* @return add featured image on recent post
*/
function styledstore_register_widgets() {

	//register styledstore recents post
	register_widget( 'styledstore_recent_post' );
	//register styledstore recent post with slier
	register_widget( 'styledstore_recent_post_with_slider' );

	//check if woocommerce plugin is active: 
	if ( styledstore_check_woocommerce_activation() ) { 
		//register styeldstore featured products
		register_widget( 'styledstore_woocommercefeaturedeproducts' );
		//register styledstore show child category
		register_widget( 'styledstore_show_child_category' );
		//register styledstore show child category
		register_widget( 'styledstore_show_category_list' );
		//register stylestore show featured widgets with no slider
		register_widget( 'styledstore_woocommercefeaturedeproducts_slidernone' );
		//register best sale products
		register_widget( 'WC_Widget_best_sale_Products' );
		//register new arrival product
		register_widget( 'WC_Widget_new_arrival_Products' );
		//register new arrival product
		register_widget( 'Styledstore_Showcase_Widget' );
	}	
}
add_action( 'widgets_init', 'styledstore_register_widgets' );



/**
 * Include theme functions
 */
require( get_template_directory() . '/inc/styledstore-function.php' );
/**
 * Load initialize file
 */
include( get_template_directory(). '/inc/init.php');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load class for upsells links
 */

require get_template_directory(). '/styledstore-upsells/styled-store-pro-btn/class-customize.php';
