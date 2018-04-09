<?php
/**
 ** Adds sparklestore_full_promo widget.
**/
add_action('widgets_init', 'sparklestore_full_promo');
function sparklestore_full_promo() {
    register_widget('sparklestore_full_promo_area');
}
class sparklestore_full_promo_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_full_promo_area', esc_html__('SP: Full Promo Widget','sparklestore'), array(
            'description' => esc_html__('A widget that promote you busincess', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
       
        $fields = array( 

            'sparklestore_full_promo_page' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_page',
                'sparklestore_widgets_title' => esc_html__('Select Promo Page', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'selectpage'
            ),

            'sparklestore_full_promo_button_link' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_link',
                'sparklestore_widgets_title' => esc_html__('Promo Button Link', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'url',
            ),

            'sparklestore_full_promo_button_text' => array(
                'sparklestore_widgets_name' => 'sparklestore_full_promo_button_text',
                'sparklestore_widgets_title' => esc_html__('Promo Button Text', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'text',
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
        
        $sparklestore_full_promo_page  = empty( $instance['sparklestore_full_promo_page'] ) ? '' : $instance['sparklestore_full_promo_page'];
        $button_link     = empty( $instance['sparklestore_full_promo_button_link'] ) ? '' : $instance['sparklestore_full_promo_button_link'];
        $button_text     = empty( $instance['sparklestore_full_promo_button_text'] ) ? '' : $instance['sparklestore_full_promo_button_text'];
        $promo_info     = empty( $instance['sparklestore_promo_info'] ) ? '' : $instance['sparklestore_promo_info'];

        echo $before_widget; 
    ?>
    <div class="fullpromowrap">
        <div class="container">
            <div class="row">
            <?php
                 if( !empty( $sparklestore_full_promo_page ) ) {
                 $sparklestore_full_promo_page = new WP_Query( 'page_id='.$sparklestore_full_promo_page );
                 if( $sparklestore_full_promo_page->have_posts() ) { while( $sparklestore_full_promo_page->have_posts() ) { $sparklestore_full_promo_page->the_post();
                 $full_promo_image = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );         
            ?>
                <div class="promoimage" <?php if ( !empty( $full_promo_image ) ) { ?>style="background-image:url(<?php echo esc_url( $full_promo_image[0] ); ?>); background-size:cover;"<?php } ?>>
                    <div class="fullwrap"> 
                        <?php if($promo_info != 1) { ?>                          
                            <h4><?php the_title(); ?></h4>
                            <?php the_content(); ?>
                            <?php if ( !empty( $button_text ) ) { ?>
                                <a href="<?php echo esc_url( $button_link ); ?>">
                                  <button class="btn promolink"><?php echo esc_attr( $button_text ); ?></button>
                                </a>
                        <?php } } ?>
                    </div>               
                </div>
            <?php } } wp_reset_postdata(); } ?>
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