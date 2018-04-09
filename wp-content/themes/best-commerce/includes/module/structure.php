<?php
/**
 * Theme functions related to structure.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_doctype' ) ) :

	/**
	 * Doctype Declaration.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_doctype() {
		?><!DOCTYPE html><html <?php language_attributes(); ?>><?php
	}
endif;

add_action( 'best_commerce_action_doctype', 'best_commerce_doctype', 10 );

if ( ! function_exists( 'best_commerce_head' ) ) :

	/**
	 * Header Codes.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open() ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif;
	}
endif;

add_action( 'best_commerce_action_head', 'best_commerce_head', 10 );

if ( ! function_exists( 'best_commerce_page_start' ) ) :

	/**
	 * Page Start.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_page_start() {
		?><div id="page" class="hfeed site"><?php
	}
endif;

add_action( 'best_commerce_action_before', 'best_commerce_page_start' );

if ( ! function_exists( 'best_commerce_page_end' ) ) :

	/**
	 * Page End.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_page_end() {
		?></div> <!-- .main-container --></div><!-- #page --><?php
	}
endif;

add_action( 'best_commerce_action_after', 'best_commerce_page_end' );

if ( ! function_exists( 'best_commerce_content_start' ) ) :

	/**
	 * Content Start.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_content_start() {
		?><div id="content" class="site-content"><div class="container"><div class="inner-wrapper"><?php
	}
endif;

add_action( 'best_commerce_action_before_content', 'best_commerce_content_start' );

if ( ! function_exists( 'best_commerce_content_end' ) ) :

	/**
	 * Content End.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_content_end() {
		?></div><!-- .inner-wrapper --></div><!-- .container --></div><!-- #content --><?php
	}
endif;

add_action( 'best_commerce_action_after_content', 'best_commerce_content_end' );

add_action( 'best_commerce_action_after_content', 'best_commerce_content_end' );

if ( ! function_exists( 'best_commerce_header_start' ) ) :

	/**
	 * Header Start.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_header_start() {
		?><header id="masthead" class="site-header" role="banner"><div class="container"><?php
	}
endif;

add_action( 'best_commerce_action_before_header', 'best_commerce_header_start' );

if ( ! function_exists( 'best_commerce_header_end' ) ) :

	/**
	 * Header End.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_header_end() {
		?></div><!-- .container --></header><!-- #masthead --><div class="main-container"><?php
	}
endif;

add_action( 'best_commerce_action_after_header', 'best_commerce_header_end' );

if ( ! function_exists( 'best_commerce_footer_start' ) ) :

	/**
	 * Footer Start.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_footer_start() {
		$footer_status = apply_filters( 'best_commerce_filter_footer_status', true );

		if ( true !== $footer_status ) {
			return;
		}

		?><footer id="colophon" class="site-footer" role="contentinfo"><div class="container"><?php
	}
endif;

add_action( 'best_commerce_action_before_footer', 'best_commerce_footer_start' );

if ( ! function_exists( 'best_commerce_footer_end' ) ) :

	/**
	 * Footer End.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_footer_end() {
		$footer_status = apply_filters( 'best_commerce_filter_footer_status', true );

		if ( true !== $footer_status ) {
			return;
		}

		?></div><!-- .container --></footer><!-- #colophon --><?php
	}
endif;

add_action( 'best_commerce_action_after_footer', 'best_commerce_footer_end' );
