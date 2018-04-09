<?php
/**
 * Functions hooked to core hooks.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_customize_search_form' ) ) :

	/**
	 * Customize search form.
	 *
	 * @since 1.0.0
	 *
	 * @return string The search form HTML output.
	 */
	function best_commerce_customize_search_form() {

		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<label>
			<span class="screen-reader-text">' . esc_html_x( 'Search for:', 'label', 'best-commerce' ) . '</span>
			<input type="search" class="search-field" placeholder="' . esc_attr__( 'Search&hellip;', 'best-commerce' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'best-commerce' ) . '" />
			</label>
			<input type="submit" class="search-submit" value="&#xf002;" /></form>';

		return $form;

	}

endif;

add_filter( 'get_search_form', 'best_commerce_customize_search_form', 15 );

if ( ! function_exists( 'best_commerce_add_primary_navigation' ) ) :

	/**
	 * Add primary navigation.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_primary_navigation() {
		?>
		<div id="main-nav" class="clear-fix">
			<div class="container">
				<?php best_commerce_render_quick_categories(); ?>

				<nav id="site-navigation" class="main-navigation" role="navigation">
					<div class="wrap-menu-content">
						<?php
						wp_nav_menu(
							array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'fallback_cb'    => 'best_commerce_primary_navigation_fallback',
							)
						);
						?>
					</div><!-- .wrap-menu-content -->
				</nav><!-- #site-navigation -->


				<?php $show_search_in_header = best_commerce_get_option( 'show_search_in_header' ); ?>
				<?php if ( true === $show_search_in_header ) : ?>
					<div class="header-search-box">
						<a href="#" class="search-icon"><i class="fa fa-search"></i></a>
						<div class="search-box-wrap">
							<?php get_search_form(); ?>
						</div>
					</div><!-- .header-search-box -->
				<?php endif; ?>

			</div> <!-- .container -->
		</div><!-- #main-nav -->
		<?php
	}

endif;

add_action( 'best_commerce_action_after_header', 'best_commerce_add_primary_navigation', 20 );

if ( ! function_exists( 'best_commerce_implement_excerpt_length' ) ) :

	/**
	 * Implement excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length The number of words.
	 * @return int Excerpt length.
	 */
	function best_commerce_implement_excerpt_length( $length ) {

		if ( is_admin() ) {
			return $length;
		}

		$excerpt_length = best_commerce_get_option( 'excerpt_length' );
		$excerpt_length = apply_filters( 'best_commerce_filter_excerpt_length', $excerpt_length );

		if ( absint( $excerpt_length ) > 0 ) {
			$length = absint( $excerpt_length );
		}

		return $length;

	}

endif;

add_filter( 'excerpt_length', 'best_commerce_implement_excerpt_length', 999 );

if ( ! function_exists( 'best_commerce_implement_read_more' ) ) :

	/**
	 * Implement read more in excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The excerpt.
	 */
	function best_commerce_implement_read_more( $more ) {

		if ( is_admin() ) {
			return $more;
		}

		$read_more_text = best_commerce_get_option( 'read_more_text' );
		$more_link = '&hellip;&nbsp;<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . esc_html( $read_more_text ) . '</a>';
		return $more_link;

	}

endif;

add_filter( 'excerpt_more', 'best_commerce_implement_read_more' );

if ( ! function_exists( 'best_commerce_content_more_link' ) ) :

	/**
	 * Implement read more in content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more_link Read More link element.
	 * @param string $more_link_text Read More text.
	 * @return string Link.
	 */
	function best_commerce_content_more_link( $more_link, $more_link_text ) {

		$read_more_text = best_commerce_get_option( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			$more_link = str_replace( $more_link_text, esc_html( $read_more_text ), $more_link );
		}

		return $more_link;

	}

endif;

add_filter( 'the_content_more_link', 'best_commerce_content_more_link', 10, 2 );

if ( ! function_exists( 'best_commerce_custom_body_class' ) ) :

	/**
	 * Custom body class.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $input One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function best_commerce_custom_body_class( $input ) {

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$input[] = 'group-blog';
		}

		// Site layout.
		$input[] = 'site-layout-boxed';

		// Global layout.
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

		$input[] = 'global-layout-' . esc_attr( $global_layout );

		// Common class for three columns.
		switch ( $global_layout ) {
			case 'three-columns':
				$input[] = 'three-columns-enabled';
			break;

			default:
			break;
		}

		return $input;
	}

endif;

add_filter( 'body_class', 'best_commerce_custom_body_class' );

if ( ! function_exists( 'best_commerce_custom_content_width' ) ) :

	/**
	 * Custom content width.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_custom_content_width() {

		global $post, $wp_query, $content_width;

		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post  && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'best_commerce_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}
		switch ( $global_layout ) {

			case 'no-sidebar':
				$content_width = 1220;
				break;

			case 'three-columns':
				$content_width = 570;
				break;

			case 'left-sidebar':
			case 'right-sidebar':
				$content_width = 895;
				break;

			default:
				break;
		}

	}
endif;

add_filter( 'template_redirect', 'best_commerce_custom_content_width' );

if ( ! function_exists( 'best_commerce_custom_links_in_primary_navigation' ) ) :

	/**
	 * Add custom links in primary navigation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $items The HTML list content for the menu items.
	 * @param object $args  An object containing wp_nav_menu() arguments.
	 * @return string Modified HTML list content for the menu items.
	 */
	function best_commerce_custom_links_in_primary_navigation( $items, $args ) {

		if ( 'primary' === $args->theme_location ) {
			$classes = '';
			if ( is_front_page() ) {
				$classes .= ' current-menu-item';
			}
			$items = '<li class="' . $classes . '"><a href="' . esc_url( home_url( '/' ) ) . '"><i class="fa fa-home" aria-hidden="true"></i>' . esc_html__( 'Home', 'best-commerce' ) . '</a></li>' . $items;
		}

		return $items;

	}
endif;

add_filter( 'wp_nav_menu_items', 'best_commerce_custom_links_in_primary_navigation', 10, 2 );
