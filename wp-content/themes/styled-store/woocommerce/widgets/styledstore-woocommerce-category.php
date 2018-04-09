<?php 
/**
    * Show list of woocommerce category
    * Gets and displays child category of parent category
    * @author   StyledThemes
    * @category Widgets
    * @package  WooCommerce/Widgets
    * @extends  WC_Widget
*/

class styledstore_show_category_list extends WP_Widget {

    /**
    * Specifies the widget name, description, class name and instatiates it
    */
    public function __construct() {
        /**
        * Specifies the widget name, description, class name and instatiates it
        */
        parent::__construct( 
            'styledstore_show_category_list',
            esc_html__( 'ST:Category List', 'styled-store' ),
            array(
                'classname'   => 'styledstore_show_category_list',
                'description' => esc_html__( 'This is woocommerce custom widgets that list all the category with its featured image', 'styled-store' )
            ) 
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
        $st_perpage = isset( $instance['st_perpage'] ) ? $instance['st_perpage'] : false;
        $st_orderby = isset( $instance['st_orderby'] ) ? $instance['st_orderby'] : false;
        $st_sort = isset( $instance['st_sort'] ) ? $instance['st_sort'] : '';
        $st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : '';  ?>

        <div class="st_cat_browse">
            <div class="container">

                <?php if ( ! empty( $instance['title'] ) ) {

                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $this->id_base ). $args['after_title'];
                } ?>
                <div class="st_cat_slider">	
                    <?php $prod_categories = get_categories(  array(
                        'orderby'=> $st_orderby,
                        'order' => $st_sort,
                        'number' => $st_perpage,
                        'include'   => $st_category,
                        'taxonomy'     => 'product_cat',
                        'hide_empty' => 1
                    ));

                    foreach( $prod_categories as $prod_cat ) :

                        $cat_thumb_id = get_woocommerce_term_meta( $prod_cat->term_id, 'thumbnail_id', true );
                        $shop_catalog_img  = wp_get_attachment_image_src( $cat_thumb_id, 'shop_catalog' );

                        $term_link = get_term_link( $prod_cat, 'product_cat' ); ?>
                        <div class="st_product_cat">
                            <a class="product-cat-link" href="<?php echo esc_url( $term_link ); ?>">
                               <?php 
                                    if( $shop_catalog_img[0] ) { ?>

                                        <img src="<?php echo esc_url( $shop_catalog_img[0] ); ?>" alt="<?php echo esc_attr( $prod_cat->name ); ?>" />
                                    <?php } else { ?>
                                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/300x300.png' ); ?>" alt="<?php esc_attr_e( 'Demo Image', 'styled-store' ); ?>" />
                                    <?php }

                                    echo '<div class="st-cate-desc">';
                                    echo "<h3>".esc_html( $prod_cat->name )."</h3>"; ?>
                                    <div class="cat-prod-count">
                                    <?php 
                                        printf( '%d Products', $prod_cat->count ); 
                                    ?>
                                    </div>
                                    <?php echo '</div>'; ?>
                            </a>
                        </div>
                    <?php endforeach; 
                    wp_reset_query(); ?>
                </div>
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

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Browse our Categories', 'styled-store' );
        $st_perpage = isset( $instance['st_perpage'] ) ? absint( $instance['st_perpage'] ) : 1;
        $st_orderby = isset( $instance['st_orderby'] ) ? $instance['st_orderby'] : 'title';
        $st_sort = isset( $instance['st_sort'] ) ? $instance['st_sort'] : 'desc';
        $st_category = isset( $instance['st_category'] ) ? $instance['st_category'] : '';

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'styled-store' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <!-- select total number of products to show on pages -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'st_perpage' ) ); ?>"><?php esc_attr_e( 'Products per page:','styled-store' ); ?></label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'st_perpage' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'st_perpage' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $st_perpage ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'st_orderby' ) ); ?>"><?php esc_attr_e( 'Order By:', 'styled-store' ); ?> 
                <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'st_orderby' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name('st_orderby' ) ); ?>" type="text">
                
                    <option value='<?php esc_attr_e( 'Title', 'styled-store' ); ?>'<?php echo ( $st_orderby == 'title' ) ? 'selected':''; ?>>
                    <?php esc_html_e( 'Title', 'styled-store' ); ?>
                    </option>

                    <option value='<?php esc_attr( 'Date' ); ?>'<?php echo ( $st_orderby == 'Date' ) ? 'selected' : ''; ?>>
                    <?php esc_html_e( 'Date', 'styled-store' ); ?>
                    </option>

                    <option value='<?php esc_attr( 'Random' ); ?>'<?php echo ( $st_orderby == 'Random' ) ? 'selected' : ''; ?>>
                    <?php esc_html_e( 'Random', 'styled-store' ); ?>
                    </option> 

                    <option value='<?php esc_attr( 'Name' ); ?>'<?php echo ( $st_orderby == 'Name' ) ? 'selected' : ''; ?>>
                    <?php esc_html_e( 'Name', 'styled-store' ); ?>
                    </option> 
                </select>                
            </label>
        </p>

        <!-- select to sort woorommcer products-->

        <label for="<?php echo esc_attr( $this->get_field_id( 'st_sort' ) ); ?>"><?php esc_html_e( 'Order:', 'styled-store' ); ?> 
            <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'st_sort' ) ); ?>"
            name="<?php echo esc_attr( $this->get_field_name( 'st_sort' ) ); ?>" type="text">
                <option value='<?php esc_attr_e( 'desc', 'styled-store' ); ?>'<?php echo ( $st_sort=='desc' )?'selected':''; ?>>
                desc
                </option>

                <option value='<?php esc_attr_e( 'Name', 'styled-store' ); ?>'<?php echo ( $st_sort=='asc' ) ? 'selected' : ''; ?>>
                asc
                </option>

            </select>                
        </label>
        </p>
        <!-- select category-->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'st_category' ) ); ?>"><?php esc_attr_e( 'Category:', 'styled-store');
                esc_html_e( 'You can select multiple categories pressing here', 'styled-store' );
                printf (
                '<select multiple="multiple" name="%s[]" id="%s" class="widefat" size="5" 	style="margin-bottom:10px">',
                $this->get_field_name('st_category'),
                $this->get_field_id('st_category')
                );

                    if ( taxonomy_exists ( 'product_cat' ) ) :
                        $teh_cats = get_terms('product_cat', array ( 'orderby' => 'count', 'include' => ',' ,'order' => 'DESC'));
                        foreach ($teh_cats as $key => $teh_cat) { ?>
                            <option value='<?php echo $teh_cat->term_id; ?>'<?php if( ! empty( $st_category ) )echo ( in_array( $teh_cat->term_id, $st_category ) ) ?'selected':''; ?>>
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
        $instance['st_perpage'] =  absint( $new_instance['st_perpage'] );
        $instance['st_column'] = absint( $new_instance['st_column'] );
        $instance['st_orderby'] = sanitize_text_field( $new_instance['st_orderby']);
        $instance['st_sort'] = sanitize_text_field( $new_instance['st_sort']);
        $instance['st_category'] = esc_sql( $new_instance['st_category'] );
        return $instance;
    }

} // class styledstore_show_category_list