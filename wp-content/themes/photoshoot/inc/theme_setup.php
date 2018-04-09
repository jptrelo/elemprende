<?php
if ( ! function_exists( 'photoshoot_setup' ) ) :
function photoshoot_setup() {
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 770;
	}
	/*
	 * Make photoshoot theme available for translation.
	 */
	load_theme_textdomain( 'photoshoot' );
	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', photoshoot_font_url() ) );
	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	// This theme uses wp_nav_menu() in two locations.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'photoshoot-full-width', 1038, 576, true );
	add_image_size( 'photoshoot-topphoto-width', 266, 215, true );
	add_theme_support( "title-tag" );
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Header Menu', 'photoshoot' ),
	) );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );
	add_theme_support( 'custom-background', apply_filters( 'photoshoot_custom_background_args', array(
	'default-color' => 'f5f5f5',
	) ) );
	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'photoshoot_get_featured_posts',
		'max_posts' => 6,
	) );
	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // photoshoot_setup
add_action( 'after_setup_theme', 'photoshoot_setup' );
/*
 * Register Lato Google font for photoshoot.
 */
function photoshoot_font_url() {
	$photoshoot_font_url = '';
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'photoshoot' ) ) {
	//	$photoshoot_font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}
	return $photoshoot_font_url;
}
$photoshoot_options = get_option( 'photoshoot_theme_options' );
global $photoshoot_options;
/*
 * Function for photoshoot theme title.
 */
function photoshoot_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}
	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );
	// Add the site description for the home/front page.
	$photoshoot_site_description = get_bloginfo( 'description', 'display' );
	if ( $photoshoot_site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $photoshoot_site_description";
	}
	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'photoshoot' ), max( $paged, $page ) );
	}
	return $title;
}
add_filter( 'wp_title', 'photoshoot_wp_title', 10, 2 );
/*
 * Register widget areas.
 */
function photoshoot_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'photoshoot' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the right.', 'photoshoot' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'photoshoot' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Left sidebar that appears on the left sidebar page template.', 'photoshoot' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Area One', 'photoshoot' ),
		'id'            => 'footer-1',
		'description'   => __( 'Footer Area One that appears on the left.', 'photoshoot' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Two', 'photoshoot' ),
		'id'            => 'footer-2',
		'description'   => __( 'Footer Area Two that appears on the left.', 'photoshoot' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Three', 'photoshoot' ),
		'id'            => 'footer-3',
		'description'   => __( 'Footer Area Three that appears on the left.', 'photoshoot' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget no-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="footer-menu-title">',
		'after_title'   => '</h2>',
	) );
	
	
}
add_action( 'widgets_init', 'photoshoot_widgets_init' ); ?>