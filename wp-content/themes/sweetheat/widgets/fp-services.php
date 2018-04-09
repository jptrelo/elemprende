<?php

class Sweetheat_Services extends WP_Widget {

// constructor
    function sweetheat_services() {
		$widget_ops = array('classname' => 'sweetheat_services_widget', 'description' => __( 'Show what services you are able to provide.', 'sweetheat') );
        parent::WP_Widget(false, $name = __('Sweetheat FP: Services', 'sweetheat'), $widget_ops);
		$this->alt_option_name = 'sweetheat_services_widget';

    }
	
	// widget form creation
	function form($instance) {

	// Check values
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	?>

	<p><?php _e('In order to display this widget, you must first add some services from the dashboard. Add as many as you want and the theme will automatically display them all.', 'sweetheat'); ?></p>
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
		if ( isset($alloptions['sweetheat_services']) )
			delete_option('sweetheat_services');		  
		  
		return $instance;
	}

	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'sweetheat_services', 'widget' );
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

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Our Services', 'sweetheat' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'services',
			'posts_per_page'	  => -1
		) ) );

		echo $args['before_widget'];
		
		if ($r->have_posts()) :
?>
		<section id="services" class="services-area">
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>

			<div class="row">
				<div class="large-12 columns">				
				
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<?php $icon = get_post_meta( get_the_ID(), 'wpcf-service-icon', true ); ?>
					<?php $link = get_post_meta( get_the_ID(), 'wpcf-service-link', true ); ?>
					<div class="service large-4 medium-4 small-12 columns text-center">
						<?php if ($icon) : ?>
							<span class="icon-3x icon-round"><?php echo '<i class="fa ' . esc_html($icon) . '"></i>'; ?></span>
						<?php endif; ?>
						<h4 class="service-title">
							<?php if ($link) : ?>
								<a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a>
							<?php else : ?>
								<?php the_title(); ?>
							<?php endif; ?>
						</h4>
						<div class="service-desc"><?php the_content(); ?></div>
					</div>
				<?php endwhile; ?>
				</div>
			</div>			
		</section>		
	<?php
	
		echo $args['after_widget'];
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'sweetheat_services', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}