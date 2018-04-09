<?php
/**
** Adds sparklestore_cat_collection_tabs_widget widget.
*/
add_action('widgets_init', 'sparklestore_cat_collection_tabs_widget');
function sparklestore_cat_collection_tabs_widget() {
   register_widget('sparklestore_cat_collection_tabs_widget_area');
}

class sparklestore_cat_collection_tabs_widget_area extends WP_Widget {

   /**
    * Register widget with WordPress.
   **/
   public function __construct() {
       parent::__construct(
           'sparklestore_cat_collection_tabs_widget_area', esc_html__('SP: Woo Category Tabs','sparklestore'), array(
           'description' => esc_html__('A widget that shows WooCommerce category in multiple tabs with category related products', 'sparklestore')
       ));
   }
   
   private function widget_fields() {
        $taxonomy     = 'product_cat';
        $empty        = 1;
        $orderby      = 'name';  
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;
        $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
        );
 
        $woocommerce_categories = array();
        $woocommerce_categories_obj = get_categories($args);
        foreach ($woocommerce_categories_obj as $category) {
         $woocommerce_categories[$category->term_id] = $category->name;
        }

        $fields = array(

           'sparklestore_select_category' => array(
               'sparklestore_widgets_name' => 'sparklestore_select_category',
               'sparklestore_mulicheckbox_title' => esc_html__('Select Category Tabs', 'sparklestore'),
               'sparklestore_widgets_field_type' => 'multicheckboxes',
               'sparklestore_widgets_field_options' => $woocommerce_categories
            ),

           'sparklestore_pro_number_products' => array(
               'sparklestore_widgets_name' => 'sparklestore_pro_number_products',
               'sparklestore_widgets_title' => esc_html__('Enter the Number Products Display', 'sparklestore'),
               'sparklestore_widgets_field_type' => 'number',
            )        
           
       );

       return $fields;
   }

   public function widget($args, $instance) {
       extract($args);
       extract($instance);       
       /**
        * wp query for first block
       */  
       $sparklestore_cat_id = $instance['sparklestore_select_category'];
       if(!empty( $sparklestore_cat_id )) {
           $first_cat_id =  key( $sparklestore_cat_id );
       }
       $product_number = empty( $instance['sparklestore_pro_number_products'] ) ? 5 : $instance['sparklestore_pro_number_products'];
       
       echo $before_widget;            
   ?>

       <div class="sparkletabsproductwrap">           
           <div class="container"> 
           <div class="row"> 
               <div class="sparkletabs">
                   <ul class="sparkletablinks" data-id="<?php echo intval( $product_number ); ?>">
                       <?php
                           if(!empty($sparklestore_cat_id)){
                               foreach ($sparklestore_cat_id as $key => $storecat_id) {
                                   $term = get_term_by( 'id', $key, 'product_cat');
                               ?>
                                   <li><a href="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
                               <?php
                               }
                           }
                       ?>
                   </ul>       
               </div>
               <div class="sparkletablinkscontent clearfix">

                   <div class="preloader" style="display:none;">
                       <img src="<?php echo get_template_directory_uri(); ?>/assets/images/rhombus.gif">
                   </div>

                   <div class="tabscontentwrap">                   
                       <div class="sparkletabproductarea">                           
                           <ul class="tabsproduct cS-hidden">                            
                               <?php 
                                   $product_args = array(
                                       'post_type' => 'product',
                                       'tax_query' => array(
                                           array(
                                               'taxonomy'  => 'product_cat',
                                               'field'     => 'term_id', 
                                               'terms'     => $first_cat_id                                                                 
                                           )),
                                       'posts_per_page' => $product_number
                                   );
                                   $query = new WP_Query($product_args);

                                   if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                               ?>
                                   <?php wc_get_template_part( 'content', 'product' ); ?>
                                   
                               <?php } } wp_reset_postdata(); ?>
                           </ul>
                       </div>
                   </div>
               </div>
           </div>
           </div>
       </div>

   <?php         
       echo $after_widget;
   }
  
   public function update($new_instance, $old_instance) {
       $instance = $old_instance;
       $widget_fields = $this->widget_fields();
       foreach ($widget_fields as $widget_field) {
           extract($widget_field);
           $instance[$sparklestore_widgets_name] = sparklestore_widgets_updated_field_value($widget_field, $new_instance[$sparklestore_widgets_name]);
       }
       return $instance;
   }

   public function form($instance) {
       $widget_fields = $this->widget_fields();
       foreach ($widget_fields as $widget_field) {
           extract($widget_field);
           $sparklestore_widgets_field_value = !empty($instance[$sparklestore_widgets_name]) ? $instance[$sparklestore_widgets_name] : '';
           sparklestore_widgets_show_widget_field($this, $widget_field, $sparklestore_widgets_field_value);
       }
   }
}