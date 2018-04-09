<?php
/**
 * Callback functions for active_callback.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_is_featured_carousel_active' ) ) :

	/**
	 * Check if featured carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_featured_carousel_active( $control ) {

		$status = (array) $control->manager->get_setting( 'theme_options[featured_carousel_status]' )->value();
		if ( ! empty( $status ) ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_featured_category_carousel_active' ) ) :

	/**
	 * Check if featured category carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_featured_category_carousel_active( $control ) {

		$status = (array) $control->manager->get_setting( 'theme_options[featured_carousel_status]' )->value();
		if ( ! empty( $status ) && 'featured-category' === $control->manager->get_setting( 'theme_options[featured_carousel_type]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_featured_product_category_carousel_active' ) ) :

	/**
	 * Check if featured product category carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_featured_product_category_carousel_active( $control ) {

		$status = (array) $control->manager->get_setting( 'theme_options[featured_carousel_status]' )->value();
		if ( ! empty( $status ) && 'featured-product-category' === $control->manager->get_setting( 'theme_options[featured_carousel_type]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_category_menu_active' ) ) :

	/**
	 * Check if category menu is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_category_menu_active( $control ) {

		if ( $control->manager->get_setting( 'theme_options[enable_category_menu]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_offer_active' ) ) :

	/**
	 * Check if offer is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_offer_active( $control ) {

		if ( $control->manager->get_setting( 'theme_options[enable_offer]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_quick_contact_active' ) ) :

	/**
	 * Check if quick contact is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_quick_contact_active( $control ) {

		if ( $control->manager->get_setting( 'theme_options[enable_quick_contact]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'best_commerce_is_image_in_archive_active' ) ) :

	/**
	 * Check if image in archive is active.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function best_commerce_is_image_in_archive_active( $control ) {

		if ( 'disable' !== $control->manager->get_setting( 'theme_options[archive_image]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;
