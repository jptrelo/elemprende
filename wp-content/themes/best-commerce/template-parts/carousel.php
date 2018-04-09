<?php
/**
 * Functions hooked to custom hook.
 *
 * @package Best_Commerce
 */

$carousel_details = best_commerce_get_featured_carousel_details();

$featured_carousel_widget_alignment = best_commerce_get_option( 'featured_carousel_widget_alignment' );

$carousel_settings = array(
	'slidesToShow'   => is_active_sidebar( 'sidebar-featured-widget-area' ) ? 2 : 3,
	'slidesToScroll' => 1,
	'dots'           => false,
	'prevArrow'      => '<span data-role="none" class="slick-prev" tabindex="0"><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
	'nextArrow'      => '<span data-role="none" class="slick-next" tabindex="0"><i class="fa fa-angle-right" aria-hidden="true"></i></span>',
	'responsive'     => array(
		array(
			'breakpoint' => 479,
			'settings'   => array(
				'slidesToShow' => 1,
				),
			),
		),
	);

$featured_carousel_enable_autoplay = best_commerce_get_option( 'featured_carousel_enable_autoplay' );
$featured_carousel_transition_delay = best_commerce_get_option( 'featured_carousel_transition_delay' );

if ( true === $featured_carousel_enable_autoplay ) {
	$carousel_settings['autoplay']      = true;
	$carousel_settings['autoplaySpeed'] = 1000 * absint( $featured_carousel_transition_delay );
}

$carousel_settings_encoded = wp_json_encode( $carousel_settings );
$section_classes = '';
$section_classes .= 'featured-section-widget-' . esc_attr( $featured_carousel_widget_alignment );
$section_classes .= ' featured-section-widget-' . ( ( is_active_sidebar( 'sidebar-featured-widget-area' ) ) ? 'enabled' : 'disabled' );
?>

<div id="main-featured-section" class="<?php echo esc_attr( $section_classes ); ?>">
	<div class="container">
		<div class="inner-wrapper">
			<?php if ( is_active_sidebar( 'sidebar-featured-widget-area' ) ) : ?>
				<div class="featured-section-widget widget-area">
					<?php dynamic_sidebar( 'sidebar-featured-widget-area' ); ?>
				</div><!-- .featured-section-widget -->
			<?php endif; ?>
			<div class="featured-section-carousel">
				<div class="main-product-carousel-wrapper">
					<div class="inner-wrapper">
						<?php if ( ! empty( $carousel_details ) ) : ?>
							<div class="main-product-carousel" data-slick='<?php echo $carousel_settings_encoded; ?>'>

								<?php foreach ( $carousel_details as  $item ) : ?>

									<div class="main-product-carousel-item">
										<div class="main-product-carousel-item-inner">
											<?php if ( isset( $item['price'] ) && ! empty( $item['price'] ) ): ?>
												<span class="featured-product-price"><?php echo wc_price( $item['price'], array( 'decimals' => 0 ) ); ?></span>
											<?php endif; ?>
											<div class="product-thumb">
												<a href="<?php echo esc_url( $item['url'] ); ?>" class="product-thumb-wrapper">
													<?php if ( ! empty( $item['images'] ) ) : ?>
														<img src="<?php echo esc_url( $item['images'][0] ); ?>" alt="" />
													<?php endif; ?>
												</a>
												<div class="view-details-wrapper">
													<a href="<?php echo esc_url( $item['url'] ); ?>" class="custom-button"><?php esc_html_e( 'View Details', 'best-commerce' ); ?></a>
												</div><!-- .view-details-wrapper  -->
											</div><!-- .product-thumb -->
											<h3 class="main-product-carousel-title">
												<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
											</h3>
										</div><!-- .main-product-carousel-item-inner -->
									</div><!-- .main-product-carousel-item -->

								<?php endforeach; ?>

							</div><!-- .main-product-carousel -->
						<?php endif; ?>
					</div><!-- .inner-wrapper -->
				</div><!-- .main-product-carousel-wrapper -->
			</div><!-- .featured-section-carousel -->
		</div><!-- .inner-wrapper -->
	</div><!-- .container -->
</div><!-- #main-featured-section -->
