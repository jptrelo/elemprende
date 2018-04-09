<?php
/**
 * Helper functions.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_global_layout_options() {
		$choices = array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'best-commerce' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'best-commerce' ),
			'three-columns' => esc_html__( 'Three Columns', 'best-commerce' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_category_menu_type_options' ) ) :

	/**
	 * Returns category menu type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_category_menu_type_options() {
		$choices = array(
			'category'    => esc_html__( 'Post Categories', 'best-commerce' ),
			'product_cat' => esc_html__( 'Product Categories', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_carousel_status_options' ) ) :

	/**
	 * Returns carousel status options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_carousel_status_options() {
		$choices = array(
			'static-front-page' => esc_html__( 'Static Front Page', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_featured_carousel_type' ) ) :

	/**
	 * Returns the featured carousel type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_featured_carousel_type() {
		$choices = array(
			'featured-category'         => esc_html__( 'Post Category', 'best-commerce' ),
			'featured-product-category' => esc_html__( 'Product Category', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_archive_layout_options() {
		$choices = array(
			'full'    => esc_html__( 'Full Post', 'best-commerce' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable    True for adding No Image option.
	 * @param array $allowed        Allowed image size options.
	 * @param bool  $show_dimension True for showing dimension.
	 * @return array Image size options.
	 */
	function best_commerce_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;

		$choices = array();

		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'best-commerce' );
		}

		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'best-commerce' );
		$choices['medium']    = esc_html__( 'Medium', 'best-commerce' );
		$choices['large']     = esc_html__( 'Large', 'best-commerce' );
		$choices['full']      = esc_html__( 'Full (original)', 'best-commerce' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ) {
					$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;

if ( ! function_exists( 'best_commerce_get_image_alignment_options' ) ) :

	/**
	 * Returns image alignment options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_image_alignment_options() {
		$choices = array(
			'none'   => esc_html_x( 'None', 'alignment', 'best-commerce' ),
			'left'   => esc_html_x( 'Left', 'alignment', 'best-commerce' ),
			'center' => esc_html_x( 'Center', 'alignment', 'best-commerce' ),
			'right'  => esc_html_x( 'Right', 'alignment', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_featured_carousel_widget_alignment' ) ) :

	/**
	 * Returns featured carousel widget alignment options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function best_commerce_get_featured_carousel_widget_alignment() {
		$choices = array(
			'left'  => esc_html_x( 'Left', 'alignment', 'best-commerce' ),
			'right' => esc_html_x( 'Right', 'alignment', 'best-commerce' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'best_commerce_get_woocommerce_pages' ) ) :

	/**
	 * Returns WooCommerce pages.
	 *
	 * @since 1.0.0
	 *
	 * @return array Pages details.
	 */
	function best_commerce_get_woocommerce_pages() {
		// WC pages to check against.
		$check_pages = array(
			esc_html_x( 'Shop base', 'page setting', 'best-commerce' ) => array(
				'option'    => 'woocommerce_shop_page_id',
				'shortcode' => '',
			),
			esc_html_x( 'Cart', 'page setting', 'best-commerce' ) => array(
				'option'    => 'woocommerce_cart_page_id',
				'shortcode' => '[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']',
			),
			esc_html_x( 'Checkout', 'page setting', 'best-commerce' ) => array(
				'option'    => 'woocommerce_checkout_page_id',
				'shortcode' => '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']',
			),
			esc_html_x( 'My account', 'page setting', 'best-commerce' ) => array(
				'option'    => 'woocommerce_myaccount_page_id',
				'shortcode' => '[' . apply_filters( 'woocommerce_my_account_shortcode_tag', 'woocommerce_my_account' ) . ']',
			),
		);

		$pages_output = array();
		foreach ( $check_pages as $page_name => $values ) {
			$page_id = get_option( $values['option'] );
			$page_set = $page_exists = $page_visible = false;
			$shortcode_present = $shortcode_required = false;

			// Page checks.
			if ( $page_id ) {
				$page_set = true;
			}
			if ( get_post( $page_id ) ) {
				$page_exists = true;
			}
			if ( 'publish' === get_post_status( $page_id ) ) {
				$page_visible = true;
			}

			// Shortcode checks.
			if ( $values['shortcode']  && get_post( $page_id ) ) {
				$shortcode_required = true;
				$page = get_post( $page_id );
				if ( strstr( $page->post_content, $values['shortcode'] ) ) {
					$shortcode_present = true;
				}
			}

			// Wrap up our findings into an output array.
			$pages_output[] = array(
				'page_name'          => $page_name,
				'page_id'            => $page_id,
				'page_set'           => $page_set,
				'page_exists'        => $page_exists,
				'page_visible'       => $page_visible,
				'shortcode'          => $values['shortcode'],
				'shortcode_required' => $shortcode_required,
				'shortcode_present'  => $shortcode_present,
			);
		} // End foreach().

		return $pages_output;
	}

endif;

if ( ! function_exists( 'best_commerce_woocommerce_pages_status' ) ) :

	/**
	 * Returns WooCommerce pages status.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Page status.
	 */
	function best_commerce_woocommerce_pages_status() {
		$output = true;

		$pages = best_commerce_get_woocommerce_pages();
		foreach ( $pages as $page ) {
			if ( true === $page['page_set'] ) {
				if ( true === $page['shortcode_required'] && true !== $page['shortcode_present'] ) {
					$output = false;
					break;
				}
			} else {
				$output = false;
				break;
			}
		}

		return $output;
	}

endif;

if ( ! function_exists( 'best_commerce_woocommerce_pages_status_message' ) ) :

	/**
	 * Returns WooCommerce pages status message.
	 *
	 * @since 1.0.0
	 *
	 * @return string Message.
	 */
	function best_commerce_woocommerce_pages_status_message() {
		$output = '';

		$pages = best_commerce_get_woocommerce_pages();
		foreach ( $pages as $page ) {
			if ( true === $page['page_set'] ) {
				if ( true === $page['shortcode_required'] && true !== $page['shortcode_present'] ) {
					$output .= '<li>' . sprintf( esc_html__( '%1$s page does not contain %2$s shortcode.', 'best-commerce' ), $page['page_name'], $page['shortcode'] ) . '</li>';
				}
			} else {
				$output .= '<li>' . sprintf( esc_html__( '%s page is not set.', 'best-commerce' ), $page['page_name'] ) . '</li>';
			}
		}

		if ( ! empty( $output ) ) {
			$output = '<ul>' . $output . '</ul>';
		}

		return $output;
	}

endif;
