<?php
/**
 * Adds Foo_Widget widget.
 */
class Styledstore_Showcase_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Styledstore_Showcase_Widget', // Base ID
			esc_html__( 'ST: Showcase Product ID', 'styled-store' ), // Name
			array( 'description' => esc_html__( 'Enter Product ID here to display in showcase', 'styled-store' ), ) // Args
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
		
		$st_id_one = isset( $instance['product_id_one'] ) ? $instance['product_id_one'] : false;
		$st_id_two = isset( $instance['product_id_two'] ) ? $instance['product_id_two'] : false;
		?>
		<?php 
		$_product_one = new WC_Product( $st_id_one );
		$_product_one_data = get_post( $st_id_one );

		
		$_product_two = new WC_Product( $st_id_two );
		$_product_two_data = get_post( $st_id_two );
		

		//var_dump($_product_one_data);

		$img_one = wp_get_attachment_image_src( get_post_thumbnail_id( $st_id_one ),'full');
		$img_two = wp_get_attachment_image_src( get_post_thumbnail_id( $st_id_two ),'full');
		?>
		 	
		 </h2>
		<div class="col-sm-6" id="st-showcase-left" style="background-image: url(<?php echo esc_url( $img_one[0] ); ?>)">
          <div class="promotion" style="float: right">
               <div class="name"><?php echo $_product_one_data->post_title ; ?></div>
               <div class="brand">
               		<?php
               			if( $_product_one->get_category_ids() ) {
               				echo wc_get_product_category_list( $st_id_one, ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $_product_one->get_category_ids() ), 'styled-store' ) . ' ', '</span>' );
               			}
               		?>
               	
               </div>
               <div class="price"><?php echo $_product_one->get_price_html(); ?></div>
               <div class="showcase_product_content">
               	<?php echo $_product_one_data->post_content; ?>
               </div>
               
               <ul class="showcase_action">
               	<span class="add_to_cart"><?php echo do_shortcode('[add_to_cart id="99"]'); ?></span>
               	<span class="details button alt"><a href="<?php echo $_product_one->add_to_cart_url();?>"><?php esc_html_e( 'Details','styled-store'); ?></a></span>
               </ul>
          </div>
    	</div>
 	

		<div class="col-sm-6" id="st-showcase-right" style="background-image: url(<?php echo esc_url( $img_two[0]); ?>)">
			<div class="promotion">
               <div class="name"><?php echo $_product_two_data->post_title; ?></div>
               <div class="brand">
               		<?php
               			if( $_product_two->get_category_ids() ) {
           					echo wc_get_product_category_list( $st_id_two, ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $_product_two->get_category_ids() ), 'styled-store' ) . ' ', '</span>' );
           				}
               		?>
               </div>
               <div class="price"><?php echo $_product_two->get_price_html(); ?></div>
               <div class="showcase_product_content">
               	<?php echo $_product_two_data->post_content; ?>
               </div>
               
               <ul class="showcase_action">
               	<span class="add_to_cart"><?php echo do_shortcode('[add_to_cart id="'.$st_id_two.'"]'); ?></span>
               	<span class="details button alt"><a href="<?php echo esc_url( $_product_two->add_to_cart_url() );?>"><?php esc_html_e( 'Details','styled-store'); ?></a></span>
               </ul>
          </div>
    	</div>

		</div>
		<?php
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
		$product_id_one = ! empty( $instance['product_id_one'] ) ? $instance['product_id_one'] : esc_html__( '', 'styled-store' );
		$product_id_two = ! empty( $instance['product_id_two'] ) ? $instance['product_id_two'] : esc_html__( '', 'styled-store' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'product_id_one' ) ); ?>"><?php esc_attr_e( 'Product ID one:', 'styled-store' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_id_one' ) ); ?>" type="text" value="<?php echo esc_attr( $product_id_one ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'product_id_two' ) ); ?>"><?php esc_attr_e( 'Product ID two:', 'styled-store' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_id_two' ) ); ?>" type="text" value="<?php echo esc_attr( $product_id_two ); ?>">
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
		$instance['product_id_one'] = ( ! empty( $new_instance['product_id_one'] ) ) ? strip_tags( $new_instance['product_id_one'] ) : '';
		$instance['product_id_two'] = ( ! empty( $new_instance['product_id_two'] ) ) ? strip_tags( $new_instance['product_id_two'] ) : '';

		return $instance;
	}

} // class Foo_Widget
