<?php

class Sweetheat_Testimonials extends WP_Widget {

// constructor
    function sweetheat_testimonials() {
		$widget_ops = array('classname' => 'sweetheat_testimonials_widget', 'description' => __( 'Display testimonials from your clients.', 'sweetheat') );
        parent::WP_Widget(false, $name = __('Sweetheat FP: Testimonials', 'sweetheat'), $widget_ops);
		$this->alt_option_name = 'sweetheat_testimonials_widget';
    }
	
	// widget form creation
	function form($instance) {

	// Check values
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	?>
	
	<p><?php _e('In order to display this widget, you must first add some testimonials from the dashboard. Add as many as you want and the theme will automatically display them all.', 'sweetheat'); ?></p>

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
		if ( isset($alloptions['sweetheat_testimonials']) )
			delete_option('sweetheat_testimonials');		  
		  
		return $instance;
	}

	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'sweetheat_testimonials', 'widget' );
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

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'What our clients say', 'sweetheat' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'testimonials'
		) ) );
		
		echo $args['before_widget'];
		
		if ($r->have_posts()) :
?>
		<section id="our-testimonials">
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>

			<div class="row">
				<div class="large-12 columns">
					<div class="testimonials-wrapper">
						<ul class="testimonials">
							<?php while ( $r->have_posts() ) : $r->the_post(); ?>
								<?php $function = get_post_meta( get_the_ID(), 'wpcf-client-function', true ); ?>
								<li>
									<div class="qoute"><?php the_content(); ?></div>
									<h4 class="author"><?php the_title(); ?>
									<?php if ($function != '') : ?>
										<span class="company"><?php echo esc_html($function); ?></span>
									<?php endif; ?>
									</h4>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
					<a href="#0" class="view-more show-for-medium-up"><?php echo __('See all testimonials', 'sweetheat'); ?></a>	
				</div>	
			</div>
			<div class="testimonials-all">
				<div class="testimonials-all-wrapper">
					<ul>
						<?php while ( $r->have_posts() ) : $r->the_post(); ?>
							<?php $function = get_post_meta( get_the_ID(), 'wpcf-client-function', true ); ?>
							<li class="testimonials-item">
								<div class="qoute"><?php the_content(); ?></div>
								<h4 class="author"><?php the_title(); ?>
								<?php if ($function != '') : ?>
									<span class="company"><?php echo esc_html($function); ?></span>
								<?php endif; ?>
								</h4>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
				<a href="#0" class="close-btn"><?php echo __('Close', 'sweetheat'); ?></a>
			</div>					
		</section>		
	<?php
		echo $args['after_widget'];
	
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'sweetheat_testimonials', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}