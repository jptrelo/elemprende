<?php
/**
 * Implementation of carousel feature.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_add_featured_carousel' ) ) :

	/**
	 * Add featured carousel section.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_featured_carousel() {
		$flag_featured_carousel = apply_filters( 'best_commerce_filter_featured_carousel_status', false );

		if ( true !== $flag_featured_carousel ) {
			return;
		}

		get_template_part( 'template-parts/carousel' );
	}

endif;

add_action( 'best_commerce_action_after_header', 'best_commerce_add_featured_carousel', 25 );

if ( ! function_exists( 'best_commerce_check_featured_carousel_status' ) ) :

	/**
	 * Check status of featured carousel.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $input Status.
	 */
	function best_commerce_check_featured_carousel_status( $input ) {

		$input = false;

		$featured_carousel_status = best_commerce_get_option( 'featured_carousel_status' );

		if ( is_array( $featured_carousel_status ) && ! empty( $featured_carousel_status ) ) {
			if ( in_array( 'static-front-page', $featured_carousel_status, true ) && ( is_front_page() && ! is_home() ) ) {
				$input = true;
			}
		}

		return $input;
	}

endif;

add_filter( 'best_commerce_filter_featured_carousel_status', 'best_commerce_check_featured_carousel_status' );

if ( ! function_exists( 'best_commerce_get_featured_carousel_details' ) ) :

	/**
	 * Get featured carousel details.
	 *
	 * @since 1.0.0
	 *
	 * @return array Details.
	 */
	function best_commerce_get_featured_carousel_details() {

		$input = array();

		$featured_carousel_type   = best_commerce_get_option( 'featured_carousel_type' );
		$featured_carousel_number = best_commerce_get_option( 'featured_carousel_number' );

		if ( ! best_commerce_woocommerce_status() && in_array( $featured_carousel_type, array( 'featured-product-category' ), true ) ) {
			return $input;
		}

		switch ( $featured_carousel_type ) {
			case 'featured-category':

				$featured_carousel_category = best_commerce_get_option( 'featured_carousel_category' );

				$qargs = array(
					'posts_per_page'   => absint( $featured_carousel_number ),
					'no_found_rows'    => true,
					'post_type'        => 'post',
					'suppress_filters' => false,
					'meta_query'       => array(
						array( 'key' => '_thumbnail_id' ),
					),
				);

				if ( absint( $featured_carousel_category ) > 0 ) {
					$qargs['cat'] = absint( $featured_carousel_category );
				}

				// Fetch posts.
				$all_posts = get_posts( $qargs );
				$items = array();

				if ( ! empty( $all_posts ) ) {

					$cnt = 0;
					foreach ( $all_posts as $key => $post ) {

						if ( has_post_thumbnail( $post->ID ) ) {
							$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
							$items[ $cnt ]['images'] = $image_array;
							$items[ $cnt ]['title']  = $post->post_title;
							$items[ $cnt ]['url']    = get_permalink( $post->ID );
							$cnt++;
						}
					}
				}

				if ( ! empty( $items ) ) {
					$input = $items;
				}

			break;

			case 'featured-product-category':

				$featured_carousel_product_category = best_commerce_get_option( 'featured_carousel_product_category' );

				$meta_query = WC()->query->get_meta_query();
				$tax_query  = WC()->query->get_tax_query();

				if ( absint( $featured_carousel_product_category ) > 0 ) {
					$tax_query[] = array(
						'taxonomy' => 'product_cat',
						'terms'    => absint( $featured_carousel_product_category ),
					);
				}

				$qargs = array(
					'posts_per_page'   => absint( $featured_carousel_number ),
					'no_found_rows'    => true,
					'post_type'        => 'product',
					'suppress_filters' => false,
					'meta_query'       => $meta_query,
					'tax_query'        => $tax_query,
				);

				// Fetch posts.
				$all_posts = get_posts( $qargs );
				$items = array();

				if ( ! empty( $all_posts ) ) {

					$cnt = 0;
					foreach ( $all_posts as $key => $post ) {

						if ( has_post_thumbnail( $post->ID ) ) {
							$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
							$items[ $cnt ]['images'] = $image_array;
							$items[ $cnt ]['title']  = $post->post_title;
							$items[ $cnt ]['url']    = get_permalink( $post->ID );
							$product = wc_get_product( $post->ID );
							$items[ $cnt ]['price']    = $product->get_price();

							$cnt++;
						}
					}
				}

				if ( ! empty( $items ) ) {
					$input = $items;
				}

			break;
		}

		return $input;

	}

endif;
