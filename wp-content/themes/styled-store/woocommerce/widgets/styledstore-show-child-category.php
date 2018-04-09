<?php 
/**
 * Showing child category
 *
 * Gets and displays child category of parent category
 *
 * @author   StyledThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @extends  WC_Widget
 */

class styledstore_show_child_category extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		/**
		 * Specifies the widget name, description, class name and instatiates it
		 */
		parent::__construct( 
			'styledstore_show_child_category',
			esc_html__( 'ST: Show child category', 'styled-store' ),
			array(
				'classname'   => 'styledstore_show_child_category',
				'description' => esc_html__( 'This is woocommerce custom widgets that shows child category of parent category', 'styled-store' )
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

		$after_widget = $args["after_widget"];
		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $this->id_base ). $args['after_title'];
			}

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			
			$term_id = isset( $instance['st_parent_category'] ) ? $instance['st_parent_category'] : false;
			$st_child_cat_number = ( ! empty( $instance['st_child_cat_number'] ) ) ? absint( $instance['st_child_cat_number'] ) : 4;
			$st_show_product_count = isset( $instance['st_show_product_count'] ) ? $instance['st_show_product_count'] : false;
			$args = array(
					'taxonomy' => 'product_cat',
					'number'      => $st_child_cat_number,
					'orderby' => 'name',
					'order' => 'DESC',
					'parent' => $term_id
					);
			$child_cats = get_categories($args);
			echo '<ul>';
				foreach ($child_cats as $key => $child_cat) {
					$cat_links = get_category_link( $child_cat->term_id );
					echo '';
						echo '<li class="styledstore-child-cats">
								<a class="st-category-links" href="'.esc_url( $cat_links ).'"">'.esc_html( $child_cat->name ).'</a>
							   </li>';
					echo '</a>';	
					if ( $st_show_product_count) 
						echo '<span class="styledstore-child-cats-counts">'.esc_html( $child_cat->count ).'</span>';
					
					}
			echo '</ul>';
		echo $after_widget;	
		
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
		$instance['st_parent_category'] = sanitize_text_field( $new_instance['st_parent_category'] );
		$instance['st_child_cat_number'] = absint( $new_instance['st_child_cat_number'] );
		$instance[ 'st_show_product_count'] = absint( $new_instance['st_show_product_count'] );
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
		$st_child_cat_number    = isset( $instance['st_child_cat_number'] ) ? absint( $instance['st_child_cat_number'] ) : 4;
		$st_parent_category = isset( $instance['st_parent_category'] ) ? $instance['st_parent_category'] : false;
		$st_show_product_count = isset( $instance['st_show_product_count'] ) ? (bool)$instance['st_show_product_count'] : false;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($title ); ?>" /></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('st_parent_category' ) ); ?>"><?php echo esc_html__('Select Parent Category:', 'styled-store'); ?> 
	        <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'st_parent_category' ) ); ?>"
	                name="<?php echo esc_attr( $this->get_field_name( 'st_parent_category' ) ); ?>" type="text">
	           	 <?php   if ( taxonomy_exists ( 'product_cat' )) :
				   			$teh_cats = get_terms('product_cat', array ( 'orderby' => 'count', 'include' => ',' ,'order' => 'DESC'));
					   			foreach ($teh_cats as $key => $teh_cat) { 
					   				if ($teh_cat->parent == 0) { ?>
							           	<option value='<?php echo esc_attr( $teh_cat->term_id ); ?>'<?php echo ($st_parent_category==$teh_cat->term_id) ? esc_attr( 'selected' ) : ''; ?>>
							           		<?php echo esc_html( $teh_cat->slug ); ?>
							          	</option>
					         		<?php } 
					         	}	
		         		endif;?>   	
	        </select>                
	      	</label>
		</p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'st_child_cat_number' ) ); ?>"><?php esc_html_e( 'Number of Category to show:','styled-store' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'st_child_cat_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_child_cat_number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_html( $st_child_cat_number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $st_show_product_count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'st_show_product_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_show_product_count' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'st_show_product_count' ) ); ?>"><?php esc_html_e( 'Display product count?', 'styled-store' ); ?></label></p>
<?php
	}
}
