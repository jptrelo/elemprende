<?php

class Sweetheat_Clients extends WP_Widget {

// constructor
    function sweetheat_clients() {
		$widget_ops = array('classname' => 'sweetheat_clients_widget', 'description' => __( 'Show your visitors your impressive client list.', 'sweetheat') );
        parent::WP_Widget(false, $name = __('Sweetheat FP: Clients', 'sweetheat'), $widget_ops);
		$this->alt_option_name = 'sweetheat_clients_widget';

    }
	
	// widget form creation
	function form($instance) {

	// Check values
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	?>

	<p><?php _e('In order to display this widget, you must first add some clients from the dashboard. Add as many as you want and the theme will automatically display their logos in a carousel.', 'sweetheat'); ?></p>
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
		if ( isset($alloptions['sweetheat_clients']) )
			delete_option('sweetheat_clients');		  
		  
		return $instance;
	}
	
	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'sweetheat_clients', 'widget' );
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

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Our clients', 'sweetheat' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'clients',
			'posts_per_page'	  => -1	
		) ) );
		
		echo $args['before_widget'];

		if ($r->have_posts()) :
?>
		<section id="our-clients">
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<div class="row">
				<div class="large-12 columns">
					<ul class="clients">
						<?php while ( $r->have_posts() ) : $r->the_post(); ?>
							<?php $link = get_post_meta( get_the_ID(), 'wpcf-client-link', true ); ?>
							<?php if ( has_post_thumbnail() ) : ?>
								<li>
									<?php if ($link) : ?>
										<a href="<?php echo esc_url($link); ?>"><?php the_post_thumbnail(); ?></a>
									<?php else : ?>
										<?php the_post_thumbnail(); ?>
									<?php endif; ?>
								</li>
							<?php endif; ?>
						<?php endwhile; ?>
					</ul>				
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
			wp_cache_set( 'sweetheat_clients', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}