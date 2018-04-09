<?php
/**
 * Functions hooked to custom hook.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_skip_to_content' ) ) :

	/**
	 * Add skip to content.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_skip_to_content() {
		?><a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'best-commerce' ); ?></a><?php
	}
endif;

add_action( 'best_commerce_action_before', 'best_commerce_skip_to_content', 15 );

if ( ! function_exists( 'best_commerce_site_branding' ) ) :

	/**
	 * Site branding.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_site_branding() {
		?>
		<div class="site-branding">

			<?php best_commerce_the_custom_logo(); ?>

			<?php $show_title = best_commerce_get_option( 'show_title' ); ?>
			<?php $show_tagline = best_commerce_get_option( 'show_tagline' ); ?>

			<?php if ( true === $show_title || true === $show_tagline ) : ?>
				<div id="site-identity">
					<?php if ( true === $show_title ) : ?>
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( true === $show_tagline ) : ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div><!-- #site-identity -->
			<?php endif; ?>
		</div><!-- .site-branding -->

		<div class="right-head">
			<?php best_commerce_render_quick_contact(); ?>

			<?php if ( best_commerce_woocommerce_status() ) : ?>
				<div class="cart-section">
					<div class="shopping-cart-views">
						<ul>
						<?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
							<?php $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ); ?>
							<?php if ( absint( $wishlist_page_id ) > 0 ) : ?>
								<li>
									<a class="wishlist-btn" href="<?php echo esc_url( get_permalink( $wishlist_page_id ) ); ?>"><i class="fa fa-heart" aria-hidden="true"></i><span class="wish-value"><?php echo absint( yith_wcwl_count_products() ); ?></span></a>
								</li>
							<?php endif; ?>
						<?php endif; ?>
						<li class="cart-contents"><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
							<i class="fa fa-cart-plus" aria-hidden="true"></i>
							<span class="cart-value"><span class="cart-items"><?php echo absint( WC()->cart->get_cart_contents_count() ); ?></span>&nbsp;<?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?></span>
						</a></li>
						</ul>
					</div><!-- .shopping-cart-views -->
				 </div><!-- .cart-section -->
			<?php endif; ?>
		</div><!-- .right-head -->
		<?php
	}

endif;

add_action( 'best_commerce_action_header', 'best_commerce_site_branding' );

if ( ! function_exists( 'best_commerce_mobile_navigation' ) ) :

	/**
	 * Mobile navigation.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_mobile_navigation() {
		?>
		<div class="mobile-nav-wrap">
			<a id="mobile-trigger" href="#mob-menu"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
			<?php $enable_category_menu = best_commerce_get_option( 'enable_category_menu' ); ?>
			<?php if ( true === $enable_category_menu ) : ?>
				<a id="mobile-trigger2" href="#category-list"><i class="fa fa-folder-o" aria-hidden="true"></i></a>
			<?php endif; ?>
			<div id="mob-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => '',
					'fallback_cb'    => 'best_commerce_primary_navigation_fallback',
				) );
				?>
			</div><!-- #mob-menu -->

		</div><!-- .mobile-nav-wrap -->
		<?php
	}

endif;

add_action( 'best_commerce_action_before', 'best_commerce_mobile_navigation', 20 );

if ( ! function_exists( 'best_commerce_add_top_head_content' ) ) :

	/**
	 * Add top head section.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_top_head_content() {

		// Check if top head content is disabled.
		$status = apply_filters( 'best_commerce_filter_top_head_status', false );

		if ( true !== $status ) {
			return;
		}
		?>
		<div id="tophead">
			<div class="container">

				<?php best_commerce_render_special_offer(); ?>

				<?php best_commerce_render_top_quick_links(); ?>

				<?php if ( true === best_commerce_get_option( 'show_social_in_header' ) && has_nav_menu( 'social' ) ) : ?>
					<div id="header-social">
						<?php the_widget( 'Best_Commerce_Social_Widget' ); ?>
					</div><!-- #header-social -->
				<?php endif; ?>
			</div><!-- .container -->
		</div><!-- #tophead -->
		<?php
	}

endif;

add_action( 'best_commerce_action_before_header', 'best_commerce_add_top_head_content', 5 );

if ( ! function_exists( 'best_commerce_check_top_head_status' ) ) :

	/**
	 * Top head status.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $status Active status.
	 * @return bool Modified status.
	 */
	function best_commerce_check_top_head_status( $status ) {

		$enable_offer = best_commerce_get_option( 'enable_offer' );
		$social_status = ( ! ( false === has_nav_menu( 'social' ) || false === best_commerce_get_option( 'show_social_in_header' ) ) ) ? true : false;
		$quick_link_status = best_commerce_woocommerce_status();

		if ( true === $enable_offer || true === $social_status || true === $quick_link_status ) {
			$status = true;
		}

		return $status;
	}

endif;

add_filter( 'best_commerce_filter_top_head_status', 'best_commerce_check_top_head_status' );

if ( ! function_exists( 'best_commerce_footer_copyright' ) ) :

	/**
	 * Footer copyright.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_footer_copyright() {

		// Check if footer is disabled.
		$footer_status = apply_filters( 'best_commerce_filter_footer_status', true );
		if ( true !== $footer_status ) {
			return;
		}

		// Copyright content.
		$copyright_text = best_commerce_get_option( 'copyright_text' );
		$copyright_text = apply_filters( 'best_commerce_filter_copyright_text', $copyright_text );
		?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<?php
			$footer_menu_content = wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => 'div',
				'container_id'   => 'footer-navigation',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
			?>
		<?php endif; ?>
		<?php if ( ! empty( $copyright_text ) ) : ?>
			<div class="copyright">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>
		<?php endif; ?>
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'best-commerce' ) ); ?>"><?php printf( esc_html__( 'Powered by %s', 'best-commerce' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( '%1$s by %2$s', 'best-commerce' ), 'Best Commerce', '<a href="https://axlethemes.com">Axle Themes</a>' ); ?>
		</div>
		<?php
	}

endif;

add_action( 'best_commerce_action_footer', 'best_commerce_footer_copyright', 10 );

if ( ! function_exists( 'best_commerce_add_sidebar' ) ) :

	/**
	 * Add sidebar.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_sidebar() {

		global $post;

		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'best_commerce_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		// Include primary sidebar.
		if ( 'no-sidebar' !== $global_layout ) {
			get_sidebar();
		}

		// Include secondary sidebar.
		switch ( $global_layout ) {
			case 'three-columns':
				get_sidebar( 'secondary' );
				break;

			default:
				break;
		}
	}

endif;

add_action( 'best_commerce_action_sidebar', 'best_commerce_add_sidebar' );

if ( ! function_exists( 'best_commerce_custom_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_custom_posts_navigation() {

		the_posts_pagination();
	}

endif;

add_action( 'best_commerce_action_posts_navigation', 'best_commerce_custom_posts_navigation' );

if ( ! function_exists( 'best_commerce_add_image_in_single_display' ) ) :

	/**
	 * Add image in single template.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_image_in_single_display() {

		if ( has_post_thumbnail() ) {
			$args = array(
				'class' => 'best-commerce-post-thumb aligncenter',
			);
			the_post_thumbnail( 'large', $args );
		}
	}

endif;

add_action( 'best_commerce_single_image', 'best_commerce_add_image_in_single_display' );

if ( ! function_exists( 'best_commerce_footer_goto_top' ) ) :

	/**
	 * Go to top.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_footer_goto_top() {

		echo '<a href="#page" class="scrollup" id="btn-scrollup"><i class="fa fa-angle-up"></i></a>';
	}

endif;

add_action( 'best_commerce_action_after', 'best_commerce_footer_goto_top', 20 );

if ( ! function_exists( 'best_commerce_add_footer_widgets' ) ) :

	/**
	 * Add footer widgets.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_footer_widgets() {

		get_template_part( 'template-parts/footer-widgets' );

	}
endif;

add_action( 'best_commerce_action_before_footer', 'best_commerce_add_footer_widgets', 5 );

if ( ! function_exists( 'best_commerce_add_custom_header' ) ) :

	/**
	 * Add custom header.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_custom_header() {

		if ( is_front_page() || is_home() ) {
			return;
		}

		$image = get_header_image();
		$extra_style = '';
		if ( ! empty( $image ) ) {
			$extra_style .= 'style="background-image:url(\'' . esc_url( $image ) . '\');"';
		}
		?>
		<div id="custom-header" <?php echo $extra_style; ?>>
			<div class="custom-header-wrapper">
				<div class="container">
					<?php do_action( 'best_commerce_action_custom_header_title' ); ?>
					<?php do_action( 'best_commerce_add_breadcrumb' ); ?>
				</div><!-- .custom-header-content -->
			</div><!-- .container -->
		</div><!-- #custom-header -->
		<?php
	}

endif;

add_action( 'best_commerce_action_before_content', 'best_commerce_add_custom_header', 6 );

if ( ! function_exists( 'best_commerce_add_title_in_custom_header' ) ) :

	/**
	 * Add title in custom header.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_title_in_custom_header() {

		echo '<h1 class="page-title">';

		if ( is_home() ) {
			echo esc_html( best_commerce_get_option( 'blog_page_title' ) );
		} elseif ( is_singular() ) {
			echo single_post_title( '', false );
		} elseif ( is_archive() ) {
			the_archive_title();
		} elseif ( is_search() ) {
			printf( esc_html__( 'Search Results for: %s', 'best-commerce' ),  get_search_query() );
		} elseif ( is_404() ) {
			esc_html_e( '404 Error', 'best-commerce' );
		}

		echo '</h1>';
	}

endif;

add_action( 'best_commerce_action_custom_header_title', 'best_commerce_add_title_in_custom_header' );

if ( ! function_exists( 'best_commerce_add_breadcrumb' ) ) :

	/**
	 * Add breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_breadcrumb() {

		// Bail if home page.
		if ( is_front_page() || is_home() ) {
			return;
		}

		echo'<div id="breadcrumb">';
		best_commerce_breadcrumb();
		echo '</div>';

	}

endif;

add_action( 'best_commerce_add_breadcrumb', 'best_commerce_add_breadcrumb', 10 );
