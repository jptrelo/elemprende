<?php

class Sweetheat_Portfolio extends WP_Widget {

// constructor
    function sweetheat_portfolio() {
		$widget_ops = array('classname' => 'sweetheat_portfolio_widget', 'description' => __( 'Show your portfolio built with Jetpack', 'sweetheat') );
        parent::WP_Widget(false, $name = __('Sweetheat FP: Portfolio', 'sweetheat'), $widget_ops);
		$this->alt_option_name = 'sweetheat_portfolio_widget';	
    }
	
	// widget form creation
	function form($instance) {

	// Check values
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	?>

	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sweetheat'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['sweetheat_portfolio']) )
			delete_option('sweetheat_portfolio');		  
		  
		return $instance;
	}
	
	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'sweetheat_portfolio', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Latest news', 'sweetheat' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'jetpack-portfolio',
			'posts_per_page'	  => 6
		) ) );

		echo $args['before_widget'];
		
		if ($r->have_posts()) :
?>
		<section id="our-work">
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<div class="grid">
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<figure class="work">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail('sweetheat-portfolio'); ?>
						<?php endif; ?>
						<figcaption>							
							<h3><?php the_title(); ?></h3>
							<p><a href="<?php esc_url( the_permalink() ); ?>"><?php echo __('Read more', 'sweetheat'); ?></a></p>
						</figcaption>
					</figure>
				<?php endwhile; ?>
			</div>	
		</section>		
	<?php	
		echo $args['after_widget'];
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'sweetheat_portfolio', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}