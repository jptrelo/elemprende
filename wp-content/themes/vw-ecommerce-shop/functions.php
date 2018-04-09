<?php
/**
 * VW Ecommerce Shop functions and definitions
 *
 * @package VW Ecommerce Shop
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'vw_ecommerce_shop_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
 
/* Theme Setup */
function vw_ecommerce_shop_setup() {

	if ( ! isset( $content_width ) )
		$content_width = 640; /* pixels */
	
	load_theme_textdomain( 'vw-ecommerce-shop', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );
	add_image_size('vw-ecommerce-shop-homepage-thumb',240,145,true);
	
       register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'vw-ecommerce-shop' ),
	) );

	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', vw_ecommerce_shop_font_url() ) );

	// Theme Activation Notice
	global $pagenow;
	
	if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
		add_action( 'admin_notices', 'vw_ecommerce_shop_activation_notice' );
	}
}
endif;
add_action( 'after_setup_theme', 'vw_ecommerce_shop_setup' );

// Notice after Theme Activation
function vw_ecommerce_shop_activation_notice() {
	echo '<div class="notice notice-success is-dismissible">';
		echo '<p>'. esc_html__( 'Thank you for choosing VW Ecommerce theme. Would like to have you on our Welcome page so that you can reap all the benefits of our VW Ecommerce theme.', 'vw-ecommerce-shop' ) .'</p>';
		echo '<p><a href="'. esc_url( admin_url( 'themes.php?page=vw_ecommerce_shop_guide' ) ) .'" class="button button-primary">'. esc_html__( 'Welcome Page', 'vw-ecommerce-shop' ) .'</a></p>';
	echo '</div>';
}

/* Theme Widgets Setup */
function vw_ecommerce_shop_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on blog page sidebar', 'vw-ecommerce-shop' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on page sidebar', 'vw-ecommerce-shop' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Sidebar 3', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on page sidebar', 'vw-ecommerce-shop' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Navigation 1', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on footer 1', 'vw-ecommerce-shop' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Navigation 2', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on footer 2', 'vw-ecommerce-shop' ),
		'id'            => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Navigation 3', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on footer 3', 'vw-ecommerce-shop' ),
		'id'            => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Navigation 4', 'vw-ecommerce-shop' ),
		'description'   => __( 'Appears on footer 4', 'vw-ecommerce-shop' ),
		'id'            => 'footer-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'vw_ecommerce_shop_widgets_init' );

/* Theme Font URL */
function vw_ecommerce_shop_font_url(){
	$font_url = '';
	$font_family = array();
	$font_family[] = 'PT Sans:300,400,600,700,800,900';
	$font_family[] = 'Roboto:400,700';
	$font_family[] = 'Roboto Condensed:400,700';
	$font_family[] = 'Montserrat:300,400,600,700,800,900';

	$query_args = array(
		'family'	=> urlencode(implode('|',$font_family)),
	);
	$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
	return $font_url;
}

/* Theme enqueue scripts */
function vw_ecommerce_shop_scripts() {
	wp_enqueue_style( 'vw-ecommerce-shop-font', vw_ecommerce_shop_font_url(), array() );
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/css/bootstrap.css' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );	
	wp_enqueue_style( 'effect', get_template_directory_uri().'/css/effect.css' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/fontawesome-all.css' );
	wp_enqueue_style( 'vw-ecommerce-shop-customcss', get_template_directory_uri() . '/css/custom.css' );
	if ( is_home() || is_front_page() ) { 
		wp_enqueue_style( 'nivo-style', get_template_directory_uri().'/css/nivo-slider.css' );
		wp_enqueue_script( 'nivo-slider', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
		wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery') ,'',true);
		wp_enqueue_script( 'custom-front', get_template_directory_uri() . '/js/custom-front.js', array('jquery') ,'',true);
	}
	wp_enqueue_script( 'vw-ecommerce-shop-customscripts', get_template_directory_uri() . '/js/custom.js', array('jquery') );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/* Enqueue the Dashicons script */
	wp_enqueue_style( 'dashicons' );

}
add_action( 'wp_enqueue_scripts', 'vw_ecommerce_shop_scripts' );

function vw_ecommerce_shop_ie_stylesheet(){
	wp_enqueue_style('vw-ecommerce-shop-ie', get_template_directory_uri().'/css/ie.css');
	wp_style_add_data( 'vw-ecommerce-shop-ie', 'conditional', 'IE' );
}
add_action('wp_enqueue_scripts','vw_ecommerce_shop_ie_stylesheet');

/*radio button sanitization*/

 function vw_ecommerce_shop_sanitize_choices( $input, $setting ) {
    global $wp_customize; 
    $control = $wp_customize->get_control( $setting->id ); 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

define('VW_ECOMMERCE_SHOP_FREE_THEME_DOC','https://vwthemes.net/docs/vw-ecommerce-lite/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_SUPPORT','https://www.vwthemes.com/support/vw-theme/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_REVIEW','https://www.vwthemes.com/topic/reviews-and-testimonials/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_BUY_NOW','https://www.vwthemes.com/premium/ecommerce-wordpress-theme/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_LIVE_DEMO','https://vwthemes.net/vw-ecommerce-theme/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_PRO_DOC','https://vwthemes.net/docs/vw-ecommerce-pro/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_FAQ','https://www.vwthemes.com/faqs/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_CHILD_THEME','https://developer.wordpress.org/themes/advanced-topics/child-themes/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_CONTACT','https://www.vwthemes.com/contact/','vw-ecommerce-shop');
define('VW_ECOMMERCE_SHOP_DEMO_DATA','https://vwthemes.net/docs/ecommercedemodata.xml.zip','vw-ecommerce-shop');

define('vw_ecommerce_shop_CREDIT','https://www.vwthemes.com','vw-ecommerce-shop');

if ( ! function_exists( 'vw_ecommerce_shop_credit' ) ) {
	function vw_ecommerce_shop_credit(){
			echo "<a href=".esc_url(vw_ecommerce_shop_CREDIT)." target='_blank'>".esc_html('VWThemes','vw-ecommerce-shop')."</a>";
	}
}

/* Implement the Custom Header feature. */
require get_template_directory() . '/inc/custom-header.php';

/* Custom template tags for this theme. */
require get_template_directory() . '/inc/template-tags.php';

/* Customizer additions. */
require get_template_directory() . '/inc/customizer.php';

/* Implement the About theme page */
require get_template_directory() . '/inc/getting-started/getting-started.php';