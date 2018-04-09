<?php
/*
 * Enqueue scripts and styles for the front end.
 */
function photoshoot_scripts() {
	wp_enqueue_style( 'photoshoot-lato', photoshoot_font_url(), array(), null );
	wp_enqueue_style('photoshoot-bootstrap-min-css',get_template_directory_uri().'/css/bootstrap.min.css',array());
	wp_enqueue_style('style',get_stylesheet_uri(),array('photoshoot-bootstrap-min-css'));
	wp_enqueue_style('photoshoot_base-css',get_template_directory_uri().'/css/base.css',array());          

	wp_enqueue_script('jquery-masonry');
	wp_enqueue_script( 'photoshoot-base', get_template_directory_uri() . '/js/base.js', array('jquery-masonry'), '1.0' );	
	wp_enqueue_script('photoshoot-bootstrapjs',get_template_directory_uri().'/js/bootstrap.min.js',array('jquery'));	
	if(is_page_template('page-template/homepage.php')){
		wp_enqueue_script('photoshoot-defaultjs',get_template_directory_uri().'/js/default.js',array('jquery'));
		wp_enqueue_script('photoshoot-owl-carousel',get_template_directory_uri().'/js/owl.carousel.js',array('jquery'));	
		wp_enqueue_style('photoshoot_owl-carousel-css',get_template_directory_uri().'/css/owl.carousel.css',array());
	}
	if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
}
add_action( 'wp_enqueue_scripts', 'photoshoot_scripts' );
function photoshoot_admin_scripts() {
	wp_enqueue_script( 'photoshoot-script-default', get_template_directory_uri() . '/js/admin_default.js', array('jquery'), '1.0' );	
}
add_action( 'admin_enqueue_scripts', 'photoshoot_admin_scripts'); ?>