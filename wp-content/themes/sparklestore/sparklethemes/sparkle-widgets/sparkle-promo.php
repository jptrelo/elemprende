<?php
/**
 ** Adds sparklestore_promo_pages widget.
**/
add_action('widgets_init', 'sparklestore_promo_pages');
function sparklestore_promo_pages() {
    register_widget('sparklestore_promo_pages_area');
}
class sparklestore_promo_pages_area extends WP_Widget {
    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_promo_pages_area', esc_html__('SP: Promo Widget Section','sparklestore'), array(
            'description' => esc_html__('A widget that promote you busincess visual way', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
             
        
        $fields = array(            
            
            'banner_start_group_left' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left',
                'sparklestore_widgets_title' => esc_html__('Promo Section One', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),

            'sparklestore_promo_one' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_one',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),
           
            'sparklestore_promo_one_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_one_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo One Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),
            
            'banner_end_group_left' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left',
                'sparklestore_widgets_field_type' => 'group_end',
            ),
            
            // Promo two Area
            
            'banner_start_group_left_two' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left_two',
                'sparklestore_widgets_title' => esc_html__('Promo Section Two', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),
            
            'sparklestore_promo_two' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_two',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),
           
            'sparklestore_promo_two_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_two_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo Two Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),
            
            'banner_end_group_left_two' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left_two',
                'sparklestore_widgets_field_type' => 'group_end',
            ),
            
            // Promo three Area

            'banner_start_group_left_three' => array(
                'sparklestore_widgets_name' => 'banner_start_group_left_three',
                'sparklestore_widgets_title' => esc_html__('Promo Section Three', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'group_start',
            ),
            
            'sparklestore_promo_three' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_three',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),       
           
            'sparklestore_promo_three_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_three_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo Three Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),

            'banner_end_group_left_three' => array(
                'sparklestore_widgets_name' => 'banner_end_group_left_three',
                'sparklestore_widgets_field_type' => 'group_end',
            ),

            'sparklestore_promo_info' => array(
                'sparklestore_widgets_name' => 'sparklestore_promo_info',
                'sparklestore_widgets_title' => esc_html__('Check to Disable Promo Information', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'checkbox',
            ),

        );

        return $fields;
    }

    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        
        $promo_one               = empty( $instance['sparklestore_promo_one'] ) ? '' : $instance['sparklestore_promo_one'];
        $promo_one_button_link   = empty( $instance['sparklestore_promo_one_button_link'] ) ? '' : $instance['sparklestore_promo_one_button_link'];
        $promo_two               = empty( $instance['sparklestore_promo_two'] ) ? '' : $instance['sparklestore_promo_two'];
        $promo_two_button_link   = empty( $instance['sparklestore_promo_two_button_link'] ) ? '' : $instance['sparklestore_promo_two_button_link'];
        $promo_three             = empty( $instance['sparklestore_promo_three'] ) ? '' : $instance['sparklestore_promo_three'];
        $promo_three_button_link = empty( $instance['sparklestore_promo_three_button_link'] ) ? '' : $instance['sparklestore_promo_three_button_link'];
        $promo_info         = empty( $instance['sparklestore_promo_info'] ) ? '' : $instance['sparklestore_promo_info'];

        echo $before_widget; 
    ?>
        <div class="promosection">            
            <div class="container">
              <div class="row">
                  <div class="promoarea-div">
                    <?php
                         if( !empty( $promo_one ) ) {
                         $promo_one = new WP_Query( 'page_id='.$promo_one );
                         if( $promo_one->have_posts() ) { while( $promo_one->have_posts() ) { $promo_one->the_post();
                         $promo_one_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>                         
                        <div class="promoarea">
                              <?php if(!empty( $promo_one_image )) { ?>
                                  <a href="<?php echo esc_url( $promo_one_button_link ); ?>">
                                      <figure class="promoimage">
                                          <img src="<?php echo esc_url( $promo_one_image[0] ); ?>" alt="<?php the_title(); ?>" />
                                      </figure>
                                  </a>
                              <?php } if($promo_info != 1) { ?>
                                <div class="textwrap">
                                    <span><?php the_content(); ?></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                              <?php } ?>
                        </div>                         
                    <?php } } wp_reset_postdata(); } ?>
                  </div>
                  
                  <div class="promoarea-div">
                    <?php
                         if( !empty( $promo_two ) ) {
                         $promo_two = new WP_Query( 'page_id='.$promo_two );
                         if( $promo_two->have_posts() ) { while( $promo_two->have_posts() ) { $promo_two->the_post();
                         $promo_two_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>                         
                        <div class="promoarea">
                              <?php if(!empty( $promo_two_image )) { ?>
                                  <a href="<?php echo esc_url( $promo_two_button_link ); ?>">
                                      <figure class="promoimage">
                                          <img src="<?php echo esc_url( $promo_two_image[0] ); ?>" alt="<?php the_title(); ?>" />
                                      </figure>
                                  </a>
                              <?php } if($promo_info != 1) {  ?>
                                <div class="textwrap">
                                    <span><?php the_content(); ?></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                              <?php } ?>
                        </div>                         
                    <?php } } wp_reset_postdata(); } ?>
                  </div>            
                  
                  <div class="promoarea-div">
                    <?php
                         if( !empty( $promo_three ) ) {
                         $promo_three = new WP_Query( 'page_id='.$promo_three );
                         if( $promo_three->have_posts() ) { while( $promo_three->have_posts() ) { $promo_three->the_post();
                         $promo_three_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
                    ?>                         
                        <div class="promoarea">
                              <?php if(!empty( $promo_three_image )) { ?>
                                  <a href="<?php echo esc_url( $promo_three_button_link ); ?>">
                                      <figure class="promoimage">
                                          <img src="<?php echo esc_url( $promo_three_image[0] ); ?>" alt="<?php the_title(); ?>" />
                                      </figure>
                                  </a>
                              <?php } if($promo_info != 1) { ?>
                                <div class="textwrap">
                                    <span><?php the_content(); ?></span>
                                    <h2><?php the_title(); ?></h2>
                                </div>
                              <?php } ?>
                        </div>                         
                    <?php } } wp_reset_postdata(); } ?>
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