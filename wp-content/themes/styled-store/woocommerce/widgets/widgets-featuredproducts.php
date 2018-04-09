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
class styledstore_woocommercefeaturedeproducts extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'styledstore_woocommercefeaturedeproducts', // Base ID
			esc_html__( 'ST:Slider Featured Products', 'styled-store' ), // Name
			array( 'description' => esc_html__( 'This widgets displays all the woocommerce featured products', 'styled-store' ) ) // Args
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
			$st_perpage = isset( $instance['st_perpage'] ) ? $instance['st_perpage'] : 4;
			$st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : ''; ?>

			<div class="st_featured_product" id="homepage-featured-sidebar">
				<div class="container">

					<?php if ( ! empty( $instance['title'] ) ) {
						echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] , $this->id_base ). $args['after_title'];
					} ?>
					<ul class="st_wc_featured_product woocommerce products" style="visibility: hidden;">
						<?php
							$tax_query = array();

							if( $st_category != '' ){
								$tax_query[] = array(
						            'taxonomy' => 'product_cat',
						            'field'    => 'slug',
						            'terms'    => esc_html( $st_category ),
						        );
						     }

					        $tax_query[] = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',
							);
							if( count( $tax_query ) > 1 ) {
								$tax_query[ 'relation'] = 'AND';
							}
							$query_args = array(
								'post_type'           => 'product',
								'post_status'         => 'publish',
								'ignore_sticky_posts' => 1,
								'posts_per_page'      => absint( $st_perpage ),
								'tax_query'           => $tax_query,
							);
					    $st_product_loop = new WP_Query( $query_args );
        				while ( $st_product_loop->have_posts() ) : $st_product_loop->the_post(); 
	        				global $product;  ?>
							<li class="st_wc_feature_list product" id="product-<?php echo absint( $product->id ); ?>">
								<?php
									echo '<div class="styledstore-woocomerce-product-thumb" style="position: relative"><div class="product-overlay"></div>';
											// sale falsh
											if ( ! function_exists( 'woocommerce_show_product_loop_sale_flash' ) ) { 
					                      		require_once '/includes/wc-template-functions.php'; 
					                  		} 		                    
					                  		$result = woocommerce_show_product_loop_sale_flash();

					                  		// product thumbnail
					                  		if ( !function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
				                        		require_once '/includes/wc-template-functions.php'; 
				                      		} 
					                      	$result = woocommerce_template_loop_product_thumbnail();

										    echo '<div class="add-to-cart-button"><div class="styledstore-product-buttons">';

												// add to cart button
												if( get_theme_mod( 'stwo_hide_add_to_cart' ) == '' ) :
												   	if ( ! function_exists( 'woocommerce_template_loop_add_to_cart' ) ) { 
							                            require_once '/includes/wc-template-functions.php'; 
								                        } 
								                        $result = woocommerce_template_loop_add_to_cart();
										   		endif;

											   // yith wish list
											   if (function_exists('YITH_WCWL')) {
									                $url = add_query_arg('add_to_wishlist', $product->id );
									                ?>
									                <a class="item-wishlist button" href="<?php echo esc_url( $url ); ?>"><i class="fa fa-heart"></i></a>
									                <?php
									            }
										    echo '</div></div>';
										echo '</div>';
								?>

			                	<div class="product_detail clearfix">
			                		<!-- product title -->
				                    <?php
				                    	if ( !function_exists( 'woocommerce_template_loop_product_link_open' ) ) { 
					                        require_once '/includes/wc-template-functions.php'; 
						                } 
						                $result = woocommerce_template_loop_product_link_open();

				                    	the_title('<h3>', '</h3>');

				                    	if ( !function_exists( 'woocommerce_template_loop_product_link_close' ) ) { 
					                        require_once '/includes/wc-template-functions.php'; 
					                    } 
					                    $result = woocommerce_template_loop_product_link_close();
				                    ?>

				                    <!-- product description -->
			                        <p class="short_desc"><?php $home_exc = strip_shortcodes(substr(strip_tags( get_the_content() ),0,30)); echo $home_exc; ?></p>

			                        <?php
			                        	/**
				                         * woocommerce_template_loop_rating hook
				                         */

			                        	if ( !function_exists( 'woocommerce_template_loop_rating' ) ) { 
										    require_once '/includes/wc-template-functions.php'; 
										}
										$result = woocommerce_template_loop_rating(); 
			                        ?>

				                    <?php
				                        /**
				                         * woocommerce_template_loop_price hook
				                         *
				                         * @hooked woocommerce_template_loop_price - 10
				                         */
				                      if ( !function_exists( 'woocommerce_template_loop_price' ) ) { 
				                            require_once '/includes/wc-template-functions.php'; 
				                        } 
				                        $result = woocommerce_template_loop_price(); 
									?>
			                	</div>
			                </li>
			                <?php
		                	endwhile;
	      					wp_reset_query();
		                ?>
					</ul>
				</div>
			</div>
			
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'FEATURED', 'styled-store' );
		$st_perpage = isset( $instance['st_perpage'] ) ? absint( $instance['st_perpage'] ) : 1;
		$st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : '';

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<!-- select total number of products to show on pages -->
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'st_perpage' ) ); ?>"><?php esc_html_e( 'Products per page:','styled-store' ); ?></label> 
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'st_perpage' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_perpage' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_html( $st_perpage ); ?>">
		</p>
		
		<!-- select category-->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'st_category' ) ); ?>">
			<?php esc_html_e( 'Category:', 'styled-store' ); ?> 
		        <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'st_category' ) ); ?>"
		                name="<?php echo esc_attr( $this->get_field_name( 'st_category' ) ); ?>" type="text">

		   <?php    if ( taxonomy_exists ( 'product_cat' )) :
		   			$teh_cats = get_terms('product_cat', array ( 'orderby' => 'count', 'include' => ',' ,'order' => 'DESC')); ?>
		   				<option value=""><?php esc_html_e( 'All Category', 'styled-store' ); ?></option>
			   			<?php foreach ($teh_cats as $key => $teh_cat) { ?>
				           	<option value='<?php echo esc_attr( $teh_cat->slug ); ?>'<?php echo ( $st_category == $teh_cat->slug) ? 'selected' : ''; ?>>
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['st_perpage'] = absint( $new_instance['st_perpage'] );
		$instance['st_column'] = absint( $new_instance['st_column'] );
		$instance['st_orderby'] = sanitize_text_field( $new_instance['st_orderby']);
		$instance['st_sort'] = sanitize_text_field( $new_instance['st_sort']);
		$instance['st_category'] = sanitize_text_field( $new_instance['st_category']);
		return $instance;
	}

} // class styledstore_woocommercefeaturedeproducts