<?php
/**
 * Core functions.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_get_option' ) ) :

	/**
	 * Get theme option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function best_commerce_get_option( $key ) {

		$best_commerce_default_options = best_commerce_get_default_theme_options();

		if ( empty( $key ) ) {
			return;
		}

		$default = ( isset( $best_commerce_default_options[ $key ] ) ) ? $best_commerce_default_options[ $key ] : '';
		$theme_options = get_theme_mod( 'theme_options', $best_commerce_default_options );
		$theme_options = array_merge( $best_commerce_default_options, $theme_options );
		$value = '';

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;

	}

endif;

if ( ! function_exists( 'best_commerce_get_options' ) ) :

	/**
	 * Get all theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Theme options.
	 */
	function best_commerce_get_options() {

		$value = array();
		$value = get_theme_mod( 'theme_options' );
		return $value;

	}

endif;

if ( ! function_exists( 'best_commerce_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function best_commerce_get_default_theme_options() {

		$defaults = array();

		// Site Identity.
		$defaults['show_title']   = true;
		$defaults['show_tagline'] = true;

		// Header.
		$defaults['enable_offer']          = false;
		$defaults['offer_title']           = esc_html__( 'Special Offer:', 'best-commerce' );
		$defaults['offer_link_text']       = '';
		$defaults['offer_link_url']        = '';
		$defaults['enable_quick_contact']  = false;
		$defaults['contact_number_title']  = esc_html__( 'Phone', 'best-commerce' );
		$defaults['contact_number']        = '';
		$defaults['contact_email_title']   = esc_html__( 'Email', 'best-commerce' );
		$defaults['contact_email']         = '';
		$defaults['contact_address_title'] = esc_html__( 'Address', 'best-commerce' );
		$defaults['contact_address']       = '';
		$defaults['contact_address_url']   = '';
		$defaults['show_social_in_header'] = true;

		// Menu.
		$defaults['show_search_in_header']      = true;
		$defaults['enable_category_menu']       = false;
		$defaults['category_menu_label']        = esc_html__( 'All Categories', 'best-commerce' );
		$defaults['category_menu_type']         = 'category';
		$defaults['category_menu_depth']        = 3;

		// Layout.
		$defaults['global_layout']           = 'right-sidebar';
		$defaults['archive_layout']          = 'excerpt';
		$defaults['archive_image']           = 'medium';
		$defaults['archive_image_alignment'] = 'left';

		// Footer.
		$defaults['copyright_text'] = esc_html__( 'Copyright &copy; All rights reserved.', 'best-commerce' );

		// Blog.
		$defaults['excerpt_length'] = 40;
		$defaults['read_more_text'] = esc_html__( 'Read More', 'best-commerce' );

		// Carousel.
		$defaults['featured_carousel_status']           = array();
		$defaults['featured_carousel_type']             = 'featured-category';
		$defaults['featured_carousel_number']           = 4;
		$defaults['featured_carousel_category']         = 0;
		$defaults['featured_carousel_product_category'] = 0;
		$defaults['featured_carousel_widget_alignment'] = 'left';
		$defaults['featured_carousel_enable_autoplay']  = false;
		$defaults['featured_carousel_transition_delay'] = 3;

		return apply_filters( 'best_commerce_filter_default_theme_options', $defaults );
	}

endif;
