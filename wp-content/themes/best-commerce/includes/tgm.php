<?php
/**
 * Plugin recommendation.
 *
 * @package Best_Commerce
 */

// Load TGM library.
require_once trailingslashit( get_template_directory() ) . 'vendors/tgm/class-tgm-plugin-activation.php';

if ( ! function_exists( 'best_commerce_register_recommended_plugins' ) ) :

	/**
	 * Register recommended plugins.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_register_recommended_plugins() {

		$plugins = array(
			array(
				'name'     => esc_html__( 'WooCommerce', 'best-commerce' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'YITH WooCommerce Wishlist', 'best-commerce' ),
				'slug'     => 'yith-woocommerce-wishlist',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Contact Form 7', 'best-commerce' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'One Click Demo Import', 'best-commerce' ),
				'slug'     => 'one-click-demo-import',
				'required' => false,
			),
		);

		$config = array();

		tgmpa( $plugins, $config );

	}

endif;

add_action( 'tgmpa_register', 'best_commerce_register_recommended_plugins' );
