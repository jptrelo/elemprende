<?php
/**
 * This file is  is used to create styledstore recent widgets.
 * @package Styled Store
 * @since versioni 1.0
 */
class styledstore_recent_post_with_slider extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		/**
		 * Specifies the widget name, description, class name and instatiates it
		 */
		parent::__construct( 
			'styledstore_recent_post_with_slider',
			esc_html__( 'ST: Recent Posts Slider', 'styled-store' ),
			array(
				'classname'   => 'styledstore_recent_post_with_slider',
				'description' => esc_html__( 'This is custom widgets that shows recent post with slider on full width', 'styled-store' )
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

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'styled-store' );

		$number = ( ! empty( $instance['number'] ) ) ? $instance['number'] : 5; ?>

		<div class="st_section_title"><?php echo esc_html( $title ); ?></div>
			<div class="st_latest_article">
				
				<?php $counter = 0;
				$st_loop = new WP_Query(array(
					'post_type'	=> 'post',
					'posts_per_page'	=> $number,
				));
				while( $st_loop->have_posts() ) : $st_loop->the_post();
				$counter++; ?>	
					<div id="post-<?php the_ID(); ?>" <?php post_class( 'st-article-'.$counter .' article_list col-md-3 col-xs-12'); ?>>
						<?php if( has_post_thumbnail() ) { ?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>">
								<div class="artclee_img">
								<?php the_post_thumbnail( 'shop_catalog' ); ?>
								</div>
							</a>	
						<?php } ?>
						<div class="article_detail">
							<h3 class="title"><?php the_title(); ?></h3>
							<span class="author_"><?php printf( '%1$s %2$s',  esc_html__( 'By', 'styled-store' ), esc_html( get_the_author() ) ); ?></span>
							<span class="post_date"><?php echo the_time( 'd.m.Y' ); ?></span>
						</div>
					</div>
			<?php endwhile;
				wp_reset_postdata(); ?>
		</div>
	<?php }

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
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$number    = isset( $instance['number'] ) ? $instance['number'] : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$st_show_featuredimage = isset( $instance['st_show_featuredimage'] ) ? (bool) $instance['st_show_featuredimage'] : false;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of posts to show:', 'styled-store' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

<?php
	}
}
