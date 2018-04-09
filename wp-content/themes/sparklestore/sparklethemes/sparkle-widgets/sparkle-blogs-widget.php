<?php
/**
 ** Adds sparklestore_blog_widget widget.
*/
add_action('widgets_init', 'sparklestore_blog_widget');
function sparklestore_blog_widget() {
    register_widget('sparklestore_blog_widget_area');
}

class sparklestore_blog_widget_area extends WP_Widget {

    /**
     * Register widget with WordPress.
    **/
    public function __construct() {
        parent::__construct(
            'sparklestore_blog_widget_area', esc_html__('SP: Blogs Widget Section','sparklestore'), array(
            'description' => esc_html__('A widget that shows blogs posts', 'sparklestore')
        ));
    }
    
    private function widget_fields() {
        
        $args = array(
          'type'       => 'post',
          'child_of'   => 0,
          'orderby'    => 'name',
          'order'      => 'ASC',
          'hide_empty' => 1,
          'taxonomy'   => 'category',
        );
        $categories = get_categories( $args );
        $cat_lists = array();
        foreach( $categories as $category ) {
            $cat_lists[$category->term_id] = $category->name;
        }

        $fields = array( 
          
            'sparklestore_blogs_top_title' => array(
                'sparklestore_widgets_name' => 'sparklestore_blogs_top_title',
                'sparklestore_widgets_title' => esc_html__('Blogs Main Title', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'title',
            ),

            'sparklestore_blogs_short_desc' => array(
                'sparklestore_widgets_name' => 'sparklestore_blogs_short_desc',
                'sparklestore_widgets_title' => esc_html__('Blogs Very Short Description', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'textarea',
                'sparklestore_widgets_row'    => 4,
            ),

            'blogs_category_list' => array(
              'sparklestore_widgets_name' => 'blogs_category_list',
              'sparklestore_mulicheckbox_title' => esc_html__('Select Blogs Category', 'sparklestore'),
              'sparklestore_widgets_field_type' => 'multicheckboxes',
              'sparklestore_widgets_field_options' => $cat_lists
            ),

            'sparklestore_number_blogs_posts' => array(
                'sparklestore_widgets_name' => 'sparklestore_number_blogs_posts',
                'sparklestore_widgets_title' => esc_html__('Enter Display Numebr of Posts', 'sparklestore'),
                'sparklestore_widgets_field_type' => 'number',
            )
            
        );

        return $fields;
    }

    public function widget($args, $instance) {        
        extract($args);
        extract($instance);
        /**
         ** wp query for first block
        **/
        $blog_main_title        = empty( $instance['sparklestore_blogs_top_title'] ) ? '' : $instance['sparklestore_blogs_top_title'];
        $blogs_category_list    = empty( $instance['blogs_category_list'] ) ? '' : $instance['blogs_category_list'];
        $shot_desc              = empty( $instance['sparklestore_blogs_short_desc'] ) ? '' : $instance['sparklestore_blogs_short_desc'];
        $number_blogs_posts     = empty( $instance['sparklestore_number_blogs_posts'] ) ? 5 : $instance['sparklestore_number_blogs_posts'];
    
        $blogs_cat_id = array();
        if(!empty($blogs_category_list)){
            $blogs_cat_id = array_keys($blogs_category_list);
        }
        $blogs_posts = new WP_Query( array(
            'posts_per_page'      => $number_blogs_posts,
            'post_type'           => 'post',
            'cat'                 => $blogs_cat_id,
            'ignore_sticky_posts' => true
        ));

        echo $before_widget; 
    ?>
        <div class="sparklestore-blogwrap">

            <div class="container">

                <div class="row">
                   
                    <div class="blocktitlewrap">
                        <div class="blocktitle">
                            <?php if( !empty( $blog_main_title ) ) { ?>
                                    <h2><?php echo esc_attr( $blog_main_title ); ?></h2>
                            <?php if(!empty( $shot_desc )) { ?>
                                <p><?php echo esc_html( $shot_desc ); ?></p>
                            <?php } } ?>
                        </div>
                        <div class="SparkleStoreAction">
                            <div class="sparkle-lSPrev"></div>
                            <div class="sparkle-lSNext"></div>
                        </div>
                    </div>
                    
                    <ul class="blogspostarea cS-hidden">                        
                        <?php 
                            if( $blogs_posts->have_posts() ) : while( $blogs_posts->have_posts() ) : $blogs_posts->the_post();
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), 'sparklestore-home-blog', true);
                        ?>
                            <li>
                                <div class="blogcontainer grid">  
                                    <?php if( has_post_thumbnail() ){ ?>
                                        <div class="blogimage">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="link">
                                                <img src="<?php echo esc_url( $image[0] ); ?>" title="<?php the_title(); ?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    <?php } ?>

                                    <div class="bloginner">
                                        <h5 class="blogtitle">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h5>                                            

                                        <div class="blogshortinfo">
                                             <?php the_excerpt(); ?>
                                        </div>
                                        <div class="blogmeta sp-clearfix">                                     
                                            <div class="blogcreated">
                                                <span class="created-date">
                                                    <?php echo the_time( 'd' ); ?>
                                                </span>
                                                <span class="created-month">
                                                    <?php echo the_time( 'M Y' ); ?>
                                                </span>                                                    
                                            </div>
                                            <div class="blogcomment">
                                                <i class="icon icon-comments"></i><?php comments_popup_link( esc_html__( '0 Comment', 'sparklestore' ),  esc_html__( '1 Comment', 'sparklestore' ), esc_html__( '% Comments', 'sparklestore' ), esc_html__( 'Comments are Closed', 'sparklestore' ) ); ?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </li>                         
                            
                        <?php endwhile; endif; wp_reset_postdata(); ?>
                    </ul>
            
                </div>

            </div>

        </div><!-- End Latest Blog -->
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