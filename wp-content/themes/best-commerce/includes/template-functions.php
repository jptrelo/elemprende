<?php
/**
 * Template functions.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_get_the_excerpt' ) ) :

	/**
	 * Fetch excerpt from the post.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $length      Excerpt length.
	 * @param WP_Post $post_object WP_Post instance.
	 * @return string Excerpt content.
	 */
	function best_commerce_get_the_excerpt( $length, $post_object = null ) {

		global $post;

		if ( is_null( $post_object ) ) {
			$post_object = $post;
		}

		$length = absint( $length );

		if ( 0 === $length ) {
			return;
		}

		$source_content = $post_object->post_content;

		if ( ! empty( $post_object->post_excerpt ) ) {
			$source_content = $post_object->post_excerpt;
		}

		$source_content = strip_shortcodes( $source_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );

		return $trimmed_content;
	}

endif;

if ( ! function_exists( 'best_commerce_breadcrumb' ) ) :

	/**
	 * Breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_breadcrumb() {

		if ( ! function_exists( 'breadcrumb_trail' ) ) {
			require_once trailingslashit( get_template_directory() ) . 'vendors/breadcrumbs/breadcrumbs.php';
		}

		$breadcrumb_args = array(
			'container'   => 'div',
			'show_browse' => false,
		);

		breadcrumb_trail( $breadcrumb_args );

	}

endif;

if ( ! function_exists( 'best_commerce_fonts_url' ) ) :

	/**
	 * Return fonts URL.
	 *
	 * @since 1.0.0
	 * @return string Font URL.
	 */
	function best_commerce_fonts_url() {

		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Roboto, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'best-commerce' ) ) {
			$fonts[] = 'Roboto:300,400,500,700';
		}

		/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'best-commerce' ) ) {
			$fonts[] = 'Montserrat:300,400,500,600,700';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}

endif;

if ( ! function_exists( 'best_commerce_primary_navigation_fallback' ) ) :

	/**
	 * Fallback for primary navigation.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_primary_navigation_fallback() {
		echo '<ul>';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '"><i class="fa fa-home" aria-hidden="true"></i>' . esc_html__( 'Home', 'best-commerce' ) . '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 5,
		) );
		echo '</ul>';
	}

endif;

if ( ! function_exists( 'best_commerce_the_custom_logo' ) ) :

	/**
	 * Render logo.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_the_custom_logo() {

		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}

	}

endif;

if ( ! function_exists( 'best_commerce_render_select_dropdown' ) ) :

	/**
	 * Render select dropdown.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $main_args Main arguments.
	 * @param string $callback Callback method.
	 * @param array  $callback_args Callback arguments.
	 * @return string Rendered markup.
	 */
	function best_commerce_render_select_dropdown( $main_args, $callback, $callback_args = array() ) {

		$defaults = array(
			'id'          => '',
			'name'        => '',
			'selected'    => 0,
			'echo'        => true,
			'add_default' => false,
		);

		$r = wp_parse_args( $main_args, $defaults );
		$output = '';
		$choices = array();

		if ( is_callable( $callback ) ) {
			$choices = call_user_func_array( $callback, $callback_args );
		}

		if ( ! empty( $choices ) || true === $r['add_default'] ) {

			$output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
			if ( true === $r['add_default'] ) {
				$output .= '<option value="">' . esc_html__( 'Default', 'best-commerce' ) . '</option>\n';
			}
			if ( ! empty( $choices ) ) {
				foreach ( $choices as $key => $choice ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ';
					$output .= selected( $r['selected'], $key, false );
					$output .= '>' . esc_html( $choice ) . '</option>\n';
				}
			}
			$output .= "</select>\n";
		}

		if ( $r['echo'] ) {
			echo $output;
		}

		return $output;
	}

endif;

if ( ! function_exists( 'best_commerce_get_numbers_dropdown_options' ) ) :

	/**
	 * Returns numbers dropdown options.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $min    Min.
	 * @param int    $max    Max.
	 * @param string $prefix Prefix.
	 * @param string $suffix Suffix.
	 * @return array Options array.
	 */
	function best_commerce_get_numbers_dropdown_options( $min = 1, $max = 4, $prefix = '', $suffix = '' ) {

		$output = array();

		if ( $min <= $max ) {
			for ( $i = $min; $i <= $max; $i++ ) {
				$string = $prefix . $i . $suffix;
				$output[ $i ] = $string;
			}
		}

		return $output;

	}

endif;

if ( ! function_exists( 'best_commerce_woocommerce_status' ) ) :

	/**
	 * Return WooCommerce status.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Active status.
	 */
	function best_commerce_woocommerce_status() {

		return class_exists( 'WooCommerce' );

	}

endif;

if ( ! function_exists( 'best_commerce_render_quick_contact' ) ) :

	/**
	 * Render quick contact.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_render_quick_contact() {
		$enable_quick_contact  = best_commerce_get_option( 'enable_quick_contact' );
		if ( true !== $enable_quick_contact ) {
			return;
		}
		$contact_number_title  = best_commerce_get_option( 'contact_number_title' );
		$contact_number        = best_commerce_get_option( 'contact_number' );
		$contact_email_title   = best_commerce_get_option( 'contact_email_title' );
		$contact_email         = best_commerce_get_option( 'contact_email' );
		$contact_address_title = best_commerce_get_option( 'contact_address_title' );
		$contact_address       = best_commerce_get_option( 'contact_address' );
		$contact_address_url   = best_commerce_get_option( 'contact_address_url' );
		?>
		<div id="quick-contact">
			<ul class="quick-contact-list">
				<?php if ( ! empty( $contact_number ) ) : ?>
					<li class="quick-call">
						<?php if ( ! empty( $contact_number_title ) ) : ?>
							<strong><?php echo esc_html( $contact_number_title ); ?></strong>
						<?php endif; ?>
						<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\D+/', '', $contact_number ) ); ?>"><?php echo esc_html( $contact_number ); ?></a>
					</li>
				<?php endif; ?>

				<?php if ( ! empty( $contact_email ) ) : ?>
					<li class="quick-email">
						<?php if ( ! empty( $contact_email_title ) ) : ?>
							<strong><?php echo esc_html( $contact_email_title ); ?></strong>
						<?php endif; ?>
						<a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
					</li>
				<?php endif; ?>

				<?php if ( ! empty( $contact_address ) ) : ?>
					<li class="quick-address">
						<?php if ( ! empty( $contact_address_title ) ) : ?>
							<strong><?php echo esc_html( $contact_address_title ); ?></strong>
						<?php endif; ?>
						<?php if ( ! empty( $contact_address_url ) ) : ?>
							<a href="<?php echo esc_url( $contact_address_url ); ?>"><?php echo esc_html( $contact_address ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $contact_address ); ?>
						<?php endif; ?>
					</li>
				<?php endif; ?>
			</ul><!-- .quick-contact-list -->
		</div><!--  .quick-contact -->
		<?php
	}

endif;

if ( ! function_exists( 'best_commerce_render_top_quick_links' ) ) :

	/**
	 * Render top quick links.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_render_top_quick_links() {
		if ( true !== best_commerce_woocommerce_status() ) {
			return;
		}
		$url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		?>
		<div class="my-login">
			<ul>
				<?php if ( is_user_logged_in() ) : ?>
					<li>
						<a href="<?php echo esc_url( $url ); ?>"><i class="fa fa-lock" aria-hidden="true"></i><?php esc_html_e( 'My Account', 'best-commerce' ); ?></a>
					</li>
				<?php else : ?>
					<li>
						<a href="<?php echo esc_url( $url ); ?>"><i class="fa fa-user" aria-hidden="true"></i><?php esc_html_e( 'Login/Register', 'best-commerce' ); ?></a>
					</li>
				<?php endif; ?>
			</ul>
		</div><!-- .my-login -->
		<?php
	}

endif;

if ( ! function_exists( 'best_commerce_render_quick_categories' ) ) :

	/**
	 * Render quick categories.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_render_quick_categories() {
		$enable_category_menu = best_commerce_get_option( 'enable_category_menu' );
		$category_menu_label  = best_commerce_get_option( 'category_menu_label' );
		$category_menu_type   = best_commerce_get_option( 'category_menu_type' );
		$category_menu_depth  = best_commerce_get_option( 'category_menu_depth' );

		// Return if category menu is not active.
		if ( true !== $enable_category_menu ) {
			return;
		}

		// Return if taxonomy does not exist.
		if ( true !== taxonomy_exists( $category_menu_type ) ) {
			return;
		}

		$cat_args = array(
			'title_li'     => '',
			'taxonomy'     => esc_attr( $category_menu_type ),
			'depth'        => absint( $category_menu_depth ),
			'hide_empty'   => true,
			'hierarchical' => true,
		);
		?>
		<div id="category-list">
			<a href="#"><i class="fa fa-align-left" aria-hidden="true"></i><?php echo esc_html( $category_menu_label ); ?></a>
			<ul>
				<?php wp_list_categories( $cat_args ); ?>
			</ul>
		</div><!-- #category-list -->
		<?php
	}

endif;

if ( ! function_exists( 'best_commerce_render_special_offer' ) ) :

	/**
	 * Render special offer.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_render_special_offer() {
		$enable_offer = best_commerce_get_option( 'enable_offer' );
		if ( true !== $enable_offer ) {
			return;
		}
		$offer_title     = best_commerce_get_option( 'offer_title' );
		$offer_link_text = best_commerce_get_option( 'offer_link_text' );
		$offer_link_url  = best_commerce_get_option( 'offer_link_url' );
		?>
		<div class="special-offer">
			<?php if ( ! empty( $offer_title ) ) : ?>
				<span class="special-offer-title"><?php echo esc_html( $offer_title ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $offer_link_text ) && ! empty( $offer_link_url ) ) : ?>
				<strong><a href="<?php echo esc_url( $offer_link_url ); ?>"><?php echo esc_html( $offer_link_text ); ?></a></strong>
			<?php endif; ?>
		</div><!-- .special-offer -->
		<?php
	}

endif;
