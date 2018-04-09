<?php
/**
 * SweetHeat functions and definitions
 *
 * @package SweetHeat
 */


if ( ! function_exists( 'sweetheat_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sweetheat_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on SweetHeat, use a find and replace
	 * to change 'sweetheat' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sweetheat', get_template_directory() . '/languages' );

	// Set the content width based on the theme's design and stylesheet.
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 640; /* pixels */
	}	

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('sweetheat-portfolio', 500, 350, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sweetheat' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'sweetheat_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // sweetheat_setup
add_action( 'after_setup_theme', 'sweetheat_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sweetheat_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'sweetheat' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );
	//Register the front page widgets
	if ( function_exists('siteorigin_panels_activate') ) {
		register_widget( 'Sweetheat_Services' );
		register_widget( 'Sweetheat_Latest_News' );
		register_widget( 'Sweetheat_Testimonials' );
		register_widget( 'Sweetheat_Clients' );
		register_widget( 'Sweetheat_Portfolio' );
	}
	register_widget( 'Sweetheat_Video_Widget' );	
	
}
add_action( 'widgets_init', 'sweetheat_widgets_init' );

/**
 * Load the front page widgets.
 */
if ( function_exists('siteorigin_panels_activate') ) {
	require get_template_directory() . "/widgets/fp-services.php";
	require get_template_directory() . "/widgets/fp-latest-news.php";
	require get_template_directory() . "/widgets/fp-testimonials.php";
	require get_template_directory() . "/widgets/fp-clients.php";
	require get_template_directory() . "/widgets/fp-portfolio.php";
}
require get_template_directory() . "/widgets/video-widget.php";
/**
 * Enqueue scripts and styles.
 */
function sweetheat_scripts() {

	$headings_font = esc_html(get_theme_mod('headings_fonts'));
	$body_font = esc_html(get_theme_mod('body_fonts'));
	if( $headings_font ) {
		wp_enqueue_style( 'sweetheat-headings-fonts', '//fonts.googleapis.com/css?family='. $headings_font );	
	} else {
		wp_enqueue_style( 'sweetheat-headings-fonts', '//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700');
	}	
	if( $body_font ) {
		wp_enqueue_style( 'sweetheat-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );	
	} else {
		wp_enqueue_style( 'sweetheat-body-fonts', '//fonts.googleapis.com/css?family=Raleway:400,700');
	}

	wp_enqueue_style( 'sweetheat-foundation', get_template_directory_uri() . '/css/foundation.css' );

	wp_enqueue_style( 'sweetheat-style', get_stylesheet_uri() );
		
	wp_enqueue_style( 'sweetheat-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );

	wp_enqueue_script( 'sweetheat-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
		
	wp_enqueue_script( 'sweetheat-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'), true );
	
	wp_enqueue_script( 'jquery-masonry' );	

	wp_enqueue_script( 'sweetheat-slicknav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array('jquery'), true );
				
	wp_enqueue_script( 'sweetheat-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), true );

	wp_enqueue_script( 'sweetheat-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), true );	
		
	wp_enqueue_script( 'sweetheat-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sweetheat_scripts' );

/**
 * Row style for the page builder
 */
function sweetheat_panels_row_styles($styles) {
	$styles['full'] = __('Full width', 'sweetheat');
	return $styles;
}
add_filter('siteorigin_panels_row_styles', 'sweetheat_panels_row_styles');

/**
 * CExcerpt length
 */
function sweetheat_excerpt_length( $length ) {
	
	$excerpt = get_theme_mod('exc_lenght', '55');
	return $excerpt;

}
add_filter( 'excerpt_length', 'sweetheat_excerpt_length', 999 );

/**
 * Load html5shiv
 */
function sweetheat_html5shiv() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/js/html5shiv.js' ) . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}
add_action( 'wp_head', 'sweetheat_html5shiv' ); 

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Rows styles for Page Builder
 */
require get_template_directory() . '/inc/rows.php';

/**
 * Styles
 */
require get_template_directory() . '/styles.php';

/**
 *TGM Plugin activation.
 */
require_once dirname( __FILE__ ) . '/plugins/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'sweetheat_recommend_plugin' );
function sweetheat_recommend_plugin() {
 
    $plugins = array(
        array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => false,
        ),
        array(
            'name'               => 'Types - Custom Fields and Custom Post Types Management',
            'slug'               => 'types',
            'required'           => false,
        ),          
    );
 
    tgmpa( $plugins);
 
}