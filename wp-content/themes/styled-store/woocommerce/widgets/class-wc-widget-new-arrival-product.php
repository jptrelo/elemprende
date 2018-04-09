<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * New Arrival Products Widget.
 *
 * Gets and displays New Arrival products in an unordered list.
 *
 * @author   StyledThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @extends  WC_Widget
 */
class WC_Widget_new_arrival_Products extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_new_arrival_products';
		$this->widget_description = esc_html__( 'Display a list new arrival products on your site.', 'styled-store' );
		$this->widget_id          = 'woocommerce_new_arrival_products';
		$this->widget_name        = esc_html__( 'ST:New Arrival Products', 'styled-store' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => esc_html__( 'New Arrival Products', 'styled-store' ),
				'label' => esc_html__( 'Title', 'styled-store' )
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 5,
				'label' => esc_html__( 'Number of products to show', 'styled-store' )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : $this->settings['number']['std'];

		$query_args = array( 'posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );

		$query_args['meta_query'] = WC()->query->get_meta_query();

		$r = new WP_Query( $query_args );

		if ( $r->have_posts() ) {

			$this->widget_start( $args, $instance );

			echo '<ul class="product_list_widget">';

			while ( $r->have_posts() ) {
				$r->the_post();
				wc_get_template( 'content-widget-product.php', array( 'show_rating' => true ) );
			}

			echo '</ul>';

			$this->widget_end( $args );
		}

		wp_reset_postdata();

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}
}
