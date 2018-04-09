<?php
/**
 * This file is  is used to create widgets for featured products
 * @package Styled Store
 * @since versioni 1.0
 * @return widgets for woocommerce featured products
 */

/**
 * Adds styledstore_woocommercefeaturedeproducts widget.
 */
class styledstore_woocommercefeaturedeproducts_slidernone extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'styledstore_woocommercefeaturedeproducts_slidernone', // Base ID
			esc_html__( 'ST:Featured Products', 'styled-store' ), // Name
			array( 'description' => esc_html__( 'This widgets displays all the woocommerce featured products with no slider. This mainly used on right and left sidebar', 'styled-store' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
			echo '<div class="featured-product-noSlider">';

				$st_perpage = isset( $instance['st_perpage'] ) ? $instance['st_perpage'] : false;
				$st_column = isset( $instance['st_column'] ) ? $instance['st_column'] : false;
				$st_orderby = isset( $instance['st_orderby'] ) ? $instance['st_orderby'] : false;
				$st_sort = isset( $instance['st_sort'] ) ? $instance['st_sort'] : '';
				$st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : '';
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $this->id_base ). $args['after_title'];
				}
					echo do_shortcode('[featured_products per_page="'.esc_html( $st_perpage ).'" columns="'. esc_html( $st_column ).' orderby="'.esc_html( $st_orderby ).'" order="'.esc_html( $st_sort ).' category="'.esc_html( $st_category ).'"]');

			echo '</div>';
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'FEATURED', 'styled-store' );
		$st_perpage = isset( $instance['st_perpage'] ) ? absint( $instance['st_perpage'] ) : 1;
		$st_column  = isset( $instance['st_column'] ) ? absint( $instance['st_column'] ) : 1;
		$st_orderby = isset( $instance['st_orderby'] ) ? $instance['st_orderby'] : 'title';
		$st_sort = isset( $instance['st_sort'] ) ? $instance['st_sort'] : 'desc';
		$st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : '';

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<!-- select total number of products to show on pages -->
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'st_perpage' ) ); ?>"><?php esc_html_e( 'Products per page:','styled-store' ); ?></label> 
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'st_perpage' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_perpage' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $st_perpage ); ?>">
		</p>
		<!-- select total number of column for all products-->
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'st_column' ) ); ?>"><?php esc_html_e( 'Columns:', 'styled-store' ); ?></label> 
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'st_column' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_column' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $st_column ); ?>">
		</p>
		<!-- select condition to order products-->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('st_orderby') ); ?>">
			<?php esc_html_e( 'Order By:', 'styled-store' ); ?> 
	        <select class='widefat' id="<?php echo esc_attr( $this->get_field_id('st_orderby' ) ); ?>"
	                name="<?php echo esc_attr( $this->get_field_name( 'st_orderby' ) ); ?>" type="text">
	           	<option value='<?php esc_attr( 'title' ); ?> <?php echo ( $st_orderby == 'title') ? esc_attr( 'selected' ) : ''; ?>>
	            	<?php esc_html_e( 'Title', 'styled-store' ); ?>
	          	</option>

	          	<option value='<?php echo esc_attr( 'date' ); ?>'<?php echo ( $st_orderby=='Date' ) ? esc_attr( 'selected' ) :''; ?>>
	            	<?php esc_html_e( 'Date', 'styled-store' ); ?>
	          	</option>
	          	<option value='<?php echo esc_attr( 'random' ); ?>'<?php echo ( $st_orderby=='Random' ) ? esc_attr( 'selected' ):''; ?>>
	            	<?php esc_html_e( 'Random', 'styled-store' ); ?>
	          	</option> 
	          	<option value='<?php echo esc_attr( 'Name' ); ?>'<?php echo ( $st_orderby=='Name')? esc_attr( 'selected' ):''; ?>>
	           	 	<?php esc_html_e( 'Name', 'styled-store' ); ?>
	          	</option> 
	        </select>                
	      	</label>
		</p>

		<!-- select to sort woorommcer products-->
		
		<label for="<?php echo esc_attr( $this->get_field_id('st_sort') ); ?>">
			<?php esc_html_e( 'Order By:', 'styled-store' ); ?> 
	        <select class='widefat' id="<?php echo esc_attr( $this->get_field_id('st_sort') ); ?>"
	                name="<?php echo esc_attr( $this->get_field_name('st_sort') ); ?>" type="text">
	           	<option value='<?php echo esc_attr_e( 'desc', 'styled-store' ); ?>'<?php echo ($st_sort=='desc') ? esc_attr( 'selected' ):''; ?>>
	            desc
	          	</option>

	          	<option value='<?php echo esc_attr_e( 'asc', 'styled-store' ); ?>'<?php echo ($st_sort=='asc') ? esc_attr( 'selected' ):''; ?>>
	            	asc
	          	</option>
	          	
	        </select>                
	      </label>
		</p>
		<!-- select category-->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('st_category') ); ?>">
				<?php esc_html_e( 'Category:', 'styled-store' ); ?> 
		        <select class='widefat' id="<?php echo esc_attr( $this->get_field_id('st_category' ) ); ?>"
		                name="<?php echo esc_attr( $this->get_field_name('st_category') ); ?>" type="text">

		   <?php    if ( taxonomy_exists ( 'product_cat' )) :
		   			$teh_cats = get_terms('product_cat', array ( 'orderby' => 'count', 'include' => ',' ,'order' => 'DESC')); ?>
		   				<option value=""><?php esc_html_e( 'All Category', 'styled-store' ); ?></option>
			   			<?php foreach ($teh_cats as $key => $teh_cat) { ?>
				           	<option value='<?php echo esc_attr( $teh_cat->slug ); ?>'<?php echo ( $st_category == $teh_cat->slug)? esc_attr( 'selected' ):''; ?>>
				           		<?php echo esc_html( $teh_cat->slug ); ?>
				          	</option>
			         <?php } 
		         	endif;?>   	             	
		        </select>                
	      	</label>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['st_perpage'] = (int) $new_instance['st_perpage'];
		$instance['st_column'] = (int) $new_instance['st_column'];
		$instance['st_orderby'] = sanitize_text_field($new_instance['st_orderby']);
		$instance['st_sort'] = sanitize_text_field($new_instance['st_sort']);
		$instance['st_category'] = sanitize_text_field($new_instance['st_category']);
		return $instance;
	}

} // class styledstore_woocommercefeaturedeproducts_slidernone