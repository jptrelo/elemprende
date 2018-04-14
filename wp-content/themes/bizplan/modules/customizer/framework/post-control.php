<?php
/**
*
* A Custom control for post dropdown
* @since Bizplan 0.1
* @uses  Class WP_Customize_Control
* 
*/
if ( class_exists( 'WP_Customize_Control' ) ) :

    class Bizplan_Customize_Post_Control extends WP_Customize_Control {
        
        /**
        *    
        * Renders the dropdown of all the posts   
        * @since Bizplan 0.1
        * @access public
        * @return void   
        *   
        */    
        public function render_content() {

            $post_args = array(
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            );

            $posts = get_posts( $post_args );

            if( !empty ( $posts ) ) {
                ?>
                <label>
                    <span class="customize-control-title">
                        <?php echo esc_html( $this->label ); ?>
                    </span>
                    <select <?php $this->link(); ?>>
                        <?php
                        
                        printf( '<option value="0" %s>%s</option>', selected( $this->value(), null, false ), esc_html__( 'Select', 'bizplan' ) );
                        
                        foreach ( $posts as $post ) {
                            printf( '<option value="%s" %s>%s</option>', absint( $post->ID ), selected( $this->value(), absint( $post->ID, false ) ), absint( $post->post_title ) );
                        }
                        ?>
                    </select>
                </label>
                <?php
            }
        }
    }
    
endif;