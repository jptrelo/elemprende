<?php
/**
 * Custom theme widgets.
 *
 * @package Best_Commerce
 */

// Load widget helper.
require_once get_template_directory() . '/vendors/widget-helper/widget-helper.php';

if ( ! function_exists( 'best_commerce_register_widgets' ) ) :

	/**
	 * Register widgets.
	 *
	 * @since 1.0.0
	 */
	function best_commerce_register_widgets() {

		// Social widget.
		register_widget( 'Best_Commerce_Social_Widget' );

		// Featured Page widget.
		register_widget( 'Best_Commerce_Featured_Page_Widget' );

		// Advanced Recent Posts widget.
		register_widget( 'Best_Commerce_Advanced_Recent_Posts_Widget' );
	}

endif;

add_action( 'widgets_init', 'best_commerce_register_widgets' );

if ( ! class_exists( 'Best_Commerce_Social_Widget' ) ) :

	/**
	 * Social widget class.
	 *
	 * @since 1.0.0
	 */
	class Best_Commerce_Social_Widget extends Best_Commerce_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'best-commerce-social';
			$args['label'] = esc_html__( 'BC: Social', 'best-commerce' );

			$args['widget'] = array(
				'classname'                   => 'best_commerce_widget_social',
				'description'                 => esc_html__( 'Social Icons Widget', 'best-commerce' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'best-commerce' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			if ( has_nav_menu( 'social' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'social',
					'container'      => false,
					'depth'          => 1,
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
				) );
			}

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Best_Commerce_Featured_Page_Widget' ) ) :

	/**
	 * Featured page widget class.
	 *
	 * @since 1.0.0
	 */
	class Best_Commerce_Featured_Page_Widget extends Best_Commerce_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'best-commerce-featured-page';
			$args['label'] = esc_html__( 'BC: Featured Page', 'best-commerce' );

			$args['widget'] = array(
				'classname'                   => 'best_commerce_widget_featured_page',
				'description'                 => esc_html__( 'Displays single featured Page', 'best-commerce' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'best-commerce' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'featured_page' => array(
					'label'            => esc_html__( 'Select Page:', 'best-commerce' ),
					'type'             => 'dropdown-pages',
					'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'best-commerce' ),
					),
				'content_type' => array(
					'label'   => esc_html__( 'Show Content:', 'best-commerce' ),
					'type'    => 'select',
					'default' => 'full',
					'choices' => array(
						'short' => esc_html__( 'Short', 'best-commerce' ),
						'full'  => esc_html__( 'Full', 'best-commerce' ),
						),
					),
				'excerpt_length' => array(
					'label'       => esc_html__( 'Excerpt Length:', 'best-commerce' ),
					'description' => esc_html__( 'Applies when Short is selected in Show Content.', 'best-commerce' ),
					'type'        => 'number',
					'default'     => 40,
					'min'         => 1,
					'max'         => 100,
					'style'       => 'max-width:60px;',
					),
				'featured_image' => array(
					'label'   => esc_html__( 'Select Image:', 'best-commerce' ),
					'type'    => 'select',
					'default' => 'medium',
					'choices' => best_commerce_get_image_sizes_options(),
					),
				'featured_image_alignment' => array(
					'label'   => esc_html__( 'Select Image Alignment:', 'best-commerce' ),
					'type'    => 'select',
					'default' => 'left',
					'choices' => best_commerce_get_image_alignment_options(),
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( absint( $values['featured_page'] ) > 0 ) {

				$qargs = array(
					'p'             => absint( $values['featured_page'] ),
					'post_type'     => 'page',
					'no_found_rows' => true,
					);

				$the_query = new WP_Query( $qargs );

				if ( $the_query->have_posts() ) {

					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						// Display featured image.
						if ( 'disable' !== $values['featured_image'] && has_post_thumbnail() ) {
							the_post_thumbnail( esc_attr( $values['featured_image'] ), array( 'class' => 'align' . esc_attr( $values['featured_image_alignment'] ) ) );
						}

						echo '<div class="featured-page-widget">';

						// Render widget title.
						if ( ! empty( $values['title'] ) ) {
							echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
						}

						if ( 'short' === $values['content_type'] ) {
							if ( absint( $values['excerpt_length'] ) > 0 ) {
								$excerpt = best_commerce_get_the_excerpt( absint( $values['excerpt_length'] ) );
								echo wp_kses_post( wpautop( $excerpt ) );
								echo '<a href="' . esc_url( get_permalink() ) . '" class="custom-button">' . esc_html__( 'Read more', 'best-commerce' ) . '</a>';
							}
						} else {
							the_content();
						}

						echo '</div><!-- .featured-page-widget -->';

					} // End while.

					// Reset.
					wp_reset_postdata();

				} // End if.
			}

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Best_Commerce_Advanced_Recent_Posts_Widget' ) ) :

	/**
	 * Advanced recent posts widget class.
	 *
	 * @since 1.0.0
	 */
	class Best_Commerce_Advanced_Recent_Posts_Widget extends Best_Commerce_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'best-commerce-advanced-recent-posts';
			$args['label'] = esc_html__( 'BC: Advanced Recent Posts', 'best-commerce' );

			$args['widget'] = array(
				'classname'                   => 'best_commerce_widget_advanced_recent_posts',
				'description'                 => esc_html__( 'Advanced Recent Posts Widget. Displays recent posts with thumbnail.', 'best-commerce' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'best-commerce' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'post_category' => array(
					'label'           => esc_html__( 'Select Category:', 'best-commerce' ),
					'type'            => 'dropdown-taxonomies',
					'show_option_all' => esc_html__( 'All Categories', 'best-commerce' ),
					),
				'featured_image' => array(
					'label'   => esc_html__( 'Featured Image:', 'best-commerce' ),
					'type'    => 'select',
					'default' => 'thumbnail',
					'choices' => best_commerce_get_image_sizes_options( true, array( 'disable', 'thumbnail' ), false ),
					),
				'image_width' => array(
					'label'       => esc_html__( 'Image Width:', 'best-commerce' ),
					'description' => esc_html__( 'px', 'best-commerce' ),
					'type'        => 'number',
					'default'     => 90,
					'min'         => 1,
					'max'         => 150,
					'style'       => 'max-width:60px;',
					'newline'     => false,
					),
				'post_number' => array(
					'label'   => esc_html__( 'No of Posts:', 'best-commerce' ),
					'type'    => 'number',
					'default' => 4,
					'min'     => 1,
					'max'     => 50,
					'style'   => 'max-width:60px;',
					),
				'excerpt_length' => array(
					'label'       => esc_html__( 'Excerpt Length:', 'best-commerce' ),
					'description' => esc_html__( 'Number of words. Enter 0 to disable.', 'best-commerce' ),
					'type'        => 'number',
					'default'     => 10,
					'min'         => 0,
					'max'         => 100,
					'style'       => 'max-width:60px;',
					),
				'disable_date' => array(
					'label'   => esc_html__( 'Disable Date', 'best-commerce' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			?>
			<?php
			$qargs = array(
				'posts_per_page'      => absint( $values['post_number'] ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				);

			if ( absint( $values['post_category'] ) > 0 ) {
				$qargs['cat'] = $values['post_category'];
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="advanced-recent-posts-widget">

					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<div class="advanced-recent-posts-item">

							<?php if ( 'disable' !== $values['featured_image'] && has_post_thumbnail() ) : ?>
								<div class="advanced-recent-posts-thumb">
									<a href="<?php the_permalink(); ?>">
										<?php
										$img_attributes = array(
											'class' => 'alignleft',
											'style' => 'max-width:' . esc_attr( $values['image_width'] ) . 'px;',
											);
										the_post_thumbnail( esc_attr( $values['featured_image'] ), $img_attributes );
										?>
									</a>
								</div>
							<?php endif; ?>
							<div class="advanced-recent-posts-text-wrap">
								<h3 class="advanced-recent-posts-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>

								<?php if ( false === $values['disable_date'] ) : ?>
									<div class="advanced-recent-posts-meta">
										<span class="advanced-recent-posts-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
									</div>
								<?php endif; ?>

								<?php if ( absint( $values['excerpt_length'] ) > 0 ) : ?>
									<div class="advanced-recent-posts-summary">
									<?php
									$excerpt = best_commerce_get_the_excerpt( absint( $values['excerpt_length'] ) );
									echo wp_kses_post( wpautop( $excerpt ) );
									?>
									</div>
								<?php endif; ?>
							</div><!-- .advanced-recent-posts-text-wrap -->

						</div><!-- .advanced-recent-posts-item -->
					<?php endwhile; ?>

				</div><!-- .advanced-recent-posts-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

			<?php

			echo $args['after_widget'];
		}

	}

endif;
