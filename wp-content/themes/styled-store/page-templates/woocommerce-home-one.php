<?php
/**
 * Template Name: Woocommerce Home Page One
 *
 * @description This template is used by woocommerce product to create homepage
 * @since version 1.0
 * @package styledstore-pro
 */

get_header(); ?>
	<div class="styledstore-homepage styledstore-homepage-one">
		<!-- category slider -->
		<?php if( get_theme_mod( 'styledstore_slider_category' ) ) :

			do_action( 'styledstore_slider' );
		else:
			//if slider not choosen display banner widgets section
			get_sidebar( 'banner' );
		
		endif; ?>
		
		<!-- featured product -->
		<?php get_sidebar( 'homepage-featured' ); ?>

		<!-- Showcase product -->
		<?php get_sidebar( 'homepage-showcase' ); ?>
		
		<!-- browse category, category with thumbnail -->
		<?php get_sidebar( 'category-list' ); ?>

		

		<!-- store services higlight -->
		<?php get_sidebar( 'service-info' ); ?>

		<!-- promo ad-1 -->
		<?php get_sidebar( 'promo' ); ?>

		<!-- four col section -->
		<?php get_sidebar( 'product-overview' ); ?>
		
		<!-- latest articles -->
		<?php get_sidebar( 'recent-post-slider' ); ?>
	</div>
	
<?php get_footer();
