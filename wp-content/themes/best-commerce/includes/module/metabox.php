<?php
/**
 * Add custom metabox.
 *
 * @package Best_Commerce
 */

if ( ! function_exists( 'best_commerce_add_theme_meta_box' ) ) :

	/**
	 * Add the meta box.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_add_theme_meta_box() {

		$post_types = array( 'post', 'page' );

		foreach ( $post_types as $type ) {
			add_meta_box(
				'best-commerce-theme-settings',
				esc_html__( 'Theme Settings', 'best-commerce' ),
				'best_commerce_render_theme_settings_metabox',
				$type
			);
		}

	}

endif;

add_action( 'add_meta_boxes', 'best_commerce_add_theme_meta_box' );

if ( ! function_exists( 'best_commerce_render_theme_settings_metabox' ) ) :

	/**
	 * Render theme settings meta box.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_render_theme_settings_metabox() {

		global $post;
		$post_id = $post->ID;

		// Meta box nonce for verification.
		wp_nonce_field( basename( __FILE__ ), 'best_commerce_settings_meta_box_nonce' );

		// Fetch values of current post meta.
		$values = get_post_meta( $post_id, 'best_commerce_settings', true );

		$fields = wp_parse_args( $values, array(
			'post_layout' => '',
		));
		?>

		<p><strong><?php esc_html_e( 'Choose Layout', 'best-commerce' ); ?></strong></p>
		<?php
		$dropdown_args = array(
			'id'          => 'best_commerce_settings_post_layout',
			'name'        => 'best_commerce_settings[post_layout]',
			'selected'    => $fields['post_layout'],
			'add_default' => true,
			);
		best_commerce_render_select_dropdown( $dropdown_args, 'best_commerce_get_global_layout_options' );
	}

endif;

if ( ! function_exists( 'best_commerce_save_theme_settings_meta' ) ) :

	/**
	 * Save theme settings meta box value.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 */
	function best_commerce_save_theme_settings_meta( $post_id, $post ) {

		// Verify nonce.
		if (
			! ( isset( $_POST['best_commerce_settings_meta_box_nonce'] )
			&& wp_verify_nonce( sanitize_key( $_POST['best_commerce_settings_meta_box_nonce'] ), basename( __FILE__ ) ) )
		) {
			return;
		}

		// Bail if auto save or revision.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		// Check permission.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['best_commerce_settings'] ) && is_array( $_POST['best_commerce_settings'] ) ) {
			$post_value = wp_unslash( $_POST['best_commerce_settings'] );

			if ( ! array_filter( $post_value ) ) {

				delete_post_meta( $post_id, 'best_commerce_settings' );

			} else {

				$meta_fields = array(
					'post_layout' => array(
						'type' => 'select',
						),
					);

				$sanitized_values = array();

				foreach ( $post_value as $mk => $mv ) {

					if ( isset( $meta_fields[ $mk ]['type'] ) ) {
						switch ( $meta_fields[ $mk ]['type'] ) {
							case 'select':
								$sanitized_values[ $mk ] = sanitize_key( $mv );
								break;
							case 'checkbox':
								$sanitized_values[ $mk ] = absint( $mv ) > 0 ? 1 : 0;
								break;
							default:
								$sanitized_values[ $mk ] = sanitize_text_field( $mv );
								break;
						}
					}
				}

				update_post_meta( $post_id, 'best_commerce_settings', $sanitized_values );
			}
		} // End if isset.

	}

endif;

add_action( 'save_post', 'best_commerce_save_theme_settings_meta', 10, 2 );
