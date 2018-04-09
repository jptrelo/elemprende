<?php
/**
 * Template Name: FrontPage Template
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sparkle_Store
 */

get_header(); 

  $service_position = esc_attr( get_theme_mod( 'sparklestore_services_section','disable' ) );
  if(!empty( $service_position ) && $service_position == 'enable' ){
      do_action( 'sparklestore_services_area', 5 );
  }

  if ( is_active_sidebar( 'sparklemainwidgetarea' ) ) {  
      dynamic_sidebar( 'sparklemainwidgetarea' );  
  } 


  if(!empty( $service_position ) && $service_position == 'disable' ){
    echo '<div class="footer_service">';
      do_action( 'sparklestore_services_area', 5 );
    echo '</div>';
  }

get_footer();