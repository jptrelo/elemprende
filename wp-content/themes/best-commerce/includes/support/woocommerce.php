<?php
/**
 * WooCommerce support class.
 *
 * @package Best_Commerce
 */

/**
 * Woocommerce support class.
 *
 * @since 1.0.0
 */
class Best_Commerce_Woocommerce {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$this->init();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	function init() {

		// Wrapper.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'woo_wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'woo_wrapper_end' ), 10 );

		// Breadcrumb.
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'custom_woocommerce_breadcrumbs_defaults' ) );
		add_action( 'wp', array( $this, 'hooking_woo' ) );

		// Sidebar.
		add_action( 'woocommerce_sidebar', array( $this, 'add_secondary_sidebar' ), 11 );

		// Modify global layout.
		add_filter( 'best_commerce_filter_theme_global_layout', array( $this, 'modify_global_layout' ), 15 );

		// Remove archive title.
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Remove product title.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

		// Loop columns.
		add_filter( 'loop_shop_columns', array( $this, 'custom_loop_columns' ) );

		// Upsell columns.
		add_filter( 'woocommerce_upsells_columns', array( $this, 'custom_upsell_columns' ) );

		// Related posts loop columns.
		add_filter( 'woocommerce_related_products_columns', array( $this, 'custom_related_products_columns' ) );

		// Loop image size.
		add_filter( 'single_product_archive_thumbnail_size', array( $this, 'loop_image_size' ) );
	}

	/**
	 * Hooking Woocommerce.
	 *
	 * @since 1.0.0
	 */
	function hooking_woo() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

		if ( is_woocommerce() ) {
			add_action( 'best_commerce_add_breadcrumb', 'woocommerce_breadcrumb', 10 );
			remove_action( 'best_commerce_add_breadcrumb', 'best_commerce_add_breadcrumb', 10 );
		}

		// Fixing primary sidebar.
		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		if ( in_array( $global_layout, array( 'no-sidebar' ), true ) ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		}

		// Hide page title.
		if ( is_shop() ) {
			add_filter( 'woocommerce_show_page_title', '__return_false' );
		}

		// Hide custom header in shop page.
		if ( is_shop() && is_front_page() ) {
			remove_action( 'best_commerce_action_before_content', 'best_commerce_add_custom_header', 6 );
		}

		// Custom shop title.
		if ( is_shop() && ! is_front_page() ) {
			remove_action( 'best_commerce_action_custom_header_title', 'best_commerce_add_title_in_custom_header' );
			add_action( 'best_commerce_action_custom_header_title', array( $this, 'custom_shop_title' ) );
		}
	}

	/**
	 * Modify global layout.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout Layout.
	 */
	function modify_global_layout( $layout ) {

		$woo_page_layout = best_commerce_get_option( 'woo_page_layout' );

		if ( is_woocommerce() && ! empty( $woo_page_layout ) ) {
			$layout = esc_attr( $woo_page_layout );
		}

		// Fix for shop page.
		if ( is_shop() && ( $shop_id = absint( wc_get_page_id( 'shop' ) ) ) > 0 ) {
			$post_options = get_post_meta( $shop_id, 'best_commerce_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$layout = esc_attr( $post_options['post_layout'] );
			}
		}

		return $layout;
	}

	/**
	 * Add secondary sidebar in Woocommerce.
	 *
	 * @since 1.0.0
	 */
	function add_secondary_sidebar() {

		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		switch ( $global_layout ) {
			case 'three-columns':
				get_sidebar( 'secondary' );
			break;

			default:
			break;
		}

	}

	/**
	 * Woocommerce content wrapper start.
	 *
	 * @since 1.0.0
	 */
	function woo_wrapper_start() {
		echo '<div id="primary">';
		echo '<main role="main" class="site-main" id="main">';
	}

	/**
	 * Woocommerce content wrapper end.
	 *
	 * @since 1.0.0
	 */
	function woo_wrapper_end() {
		echo '</main><!-- #main -->';
		echo '</div><!-- #primary -->';
	}

	/**
	 * Woocommerce breadcrumb defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array Modified breadcrumb defaults.
	 */
	function custom_woocommerce_breadcrumbs_defaults( $defaults ) {

		$defaults['delimiter']   = '';
		$defaults['wrap_before'] = '<div id="breadcrumb" itemprop="breadcrumb"><ul id="crumbs">';
		$defaults['wrap_after']  = '</ul></div>';
		$defaults['before']      = '<li>';
		$defaults['after']       = '</li>';
		$defaults['home']        = esc_html__( 'Home', 'best-commerce' );
		return $defaults;

	}

	/**
	 * Custom loop columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Number.
	 * @return string Modified number.
	 */
	function custom_loop_columns( $column ) {

		return 3;
	}

	/**
	 * Custom upsell columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Number.
	 * @return string Modified number.
	 */
	function custom_upsell_columns( $column ) {

		return 3;
	}

	/**
	 * Columns in related products.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Number.
	 * @return string Modified number.
	 */
	function custom_related_products_columns( $input ) {

		return 3;
	}

	/**
	 * Loop image size.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Size.
	 * @return string Modified size.
	 */
	function loop_image_size( $input ) {

		$input = 'best-commerce-product';
		return $input;
	}

	/**
	 * Custom shop title.
	 *
	 * @since 1.0.0
	 */
	function custom_shop_title( $input ) {

		$shop_title = esc_html__( 'Shop', 'best-commerce' );
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );

		if ( $shop_page_id ) {
			$shop_title = get_the_title( $shop_page_id );
		}

		echo '<h1 class="page-title">';
		echo esc_html( $shop_title );
		echo '</h1>';
	}
}

// Initialize.
$best_commerce_woocommerce = new Best_Commerce_Woocommerce();
