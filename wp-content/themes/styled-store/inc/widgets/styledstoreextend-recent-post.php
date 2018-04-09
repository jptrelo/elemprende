<?php
/**
 * This file is  is used to create styledstore recent widgets.
 * @package Styled Store
 * @since versioni 1.0
 */
class styledstore_recent_post extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		/**
		 * Specifies the widget name, description, class name and instatiates it
		 */
		parent::__construct( 
			'styledstore_recent_post',
			esc_html__( 'ST: Recent Posts', 'styled-store' ),
			array(
				'classname'   => 'styledstore_recent_post',
				'description' => esc_html__( 'This is custom widgets that shows recent post which includes featured image of particulat post', 'styled-store' )
			) 
		);
	}

		/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'styled-store' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$st_show_featuredimage = isset( $instance['st_show_featuredimage'] ) ? $instance['st_show_featuredimage'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
				<li class="product-list-height">
					<?php if ( has_post_thumbnail() && ! empty( $st_show_featuredimage ) ): ?>
							<div class="recent-widget-thumbnail col-xs-4">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'styledstore-thumbnail-recent' ); ?>
								</a>
							</div>
					<?php	endif; ?>
						
					<div class="recent-posts-details col-xs-8" id="<?php if ( has_post_thumbnail() ) echo 'recentsposts-with-postthumbnail'; ?>">
						<span class="recentposts_widget_title"><a href="<?php the_permalink(); ?>" > <?php echo  wp_trim_words( get_the_title(), 3, '' ); ?></a></span> <?php
							if ( 'post' === get_post_type() ) {
								if( $show_date ) { $st_on = 'on'; } else { $st_on = ''; }

								$author_description = sprintf( '<span class="byline"><span class="recenstpost-authorvcard"><span class="posted_by">%1$s</span><a class="url fn n" id="recentspost-author-url" href="%2$s">%3$s</a>&nbsp %4$s</span></span>',
									_x( 'By &nbsp', 'Used before post author name.', 'styled-store' ),
									esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
									get_the_author(),
									$st_on
								);
							}

							printf( '<span class="recentposts_postmeta">%1$s</span>',
								$author_description	
							);

							if ( $show_date ) : ?>
								<span class="recentspost_postdate"><?php echo esc_html( get_the_date() ); ?></span>
							<?php endif; 
							
							$comments_number = get_comments_number();
							$comments_data = sprintf(
							/* translators: 1: number of comments, 2: post title */
							_nx(
								'%1$s comment',
								'%1$s comments',
								$comments_number,
								'Comments Title',
								'styled-store'
							),
							number_format_i18n( $comments_number )
							
							);
							printf('<span class="recents-post-comments">(%1$s)</span>', $comments_data);
							?>

					</div>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();
		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['st_show_featuredimage'] = isset( $new_instance['st_show_featuredimage'] ) ? (bool) $new_instance['st_show_featuredimage'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$st_show_featuredimage = isset( $instance['st_show_featuredimage'] ) ? (bool) $instance['st_show_featuredimage'] : false;
	?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'styled-store' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'styled-store' ); ?></label></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $st_show_featuredimage);?> id="<?php echo esc_attr( $this->get_field_id( 'st_show_featuredimage' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_show_featuredimage' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'st_show_featuredimage' ) ); ?>"><?php esc_html_e( 'Show featured Image?', 'styled-store' ); ?></label></p>
<?php
	}
}
