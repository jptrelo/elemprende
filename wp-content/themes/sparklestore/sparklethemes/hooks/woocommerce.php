<?php
/**
 * Header Type to Shopping Cart function 
*/
if (!function_exists('sparklestore_shopping_cart')) {
    function sparklestore_shopping_cart() { ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
            <div class="header-icon">
                <i class="fa fa-shopping-bag"></i>
                <span class="name-text"><?php esc_html_e('My Cart', 'sparklestore'); ?></span>
                <span class="count">
                    <?php echo wp_kses_data(sprintf(WC()->cart->get_cart_contents_count())); ?>
                </span>
            </div>
        </a>        
        <?php
    }

    if ( ! function_exists( 'sparklestore_cart_link_fragment' ) ) {
        function sparklestore_cart_link_fragment( $fragments ) {
            global $woocommerce;
            ob_start();
            sparklestore_shopping_cart();
            $fragments['a.cart-contents'] = ob_get_clean();
            return $fragments;
        }
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'sparklestore_cart_link_fragment' );
}


/**
 * Product wishlist button function area
*/
if (defined('YITH_WCWL')) {

    function sparklestore_wishlist_products() {
        global $product;
        $url = add_query_arg( 'add_to_wishlist', $product->get_id() );
        $id = $product->get_id();
        $wishlist_url = YITH_WCWL()->get_wishlist_url(); ?> 

        <div class="add-to-wishlist-custom add-to-wishlist-<?php echo esc_attr($id); ?>">

            <div class="yith-wcwl-add-button show" style="display:block">
                <a href="<?php echo esc_url($url); ?>" data-toggle="tooltip" data-placement="top" rel="nofollow" data-product-id="<?php echo esc_attr($id); ?>" data-product-type="simple" title="<?php esc_html_e('Add to Wishlist', 'sparklestore'); ?>" class="add_to_wishlist link-wishlist">
                    <?php esc_html_e('Add Wishlist', 'sparklestore'); ?>
                </a>
                <img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/loading.gif'; ?>" class="ajax-loading" alt="loading" width="16" height="16">
            </div>

            <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
                <a class="link-wishlist" href="<?php echo esc_url($wishlist_url); ?>"><?php esc_html_e('View Wishlist', 'sparklestore'); ?></a>
            </div>

            <div class="yith-wcwl-wishlistexistsbrowse hide" style="display:none">
                <a class="link-wishlist" href="<?php echo esc_url($wishlist_url); ?>"><?php esc_html_e('Browse Wishlist', 'sparklestore'); ?></a>
            </div>

            <div class="clear"></div>
            <div class="yith-wcwl-wishlistaddresponse"></div>
        </div>
        <?php
    }

    /**
     * Wishlist Header Count Ajax Function
    */
    if (!function_exists('sparklestore_products_wishlist')) {

        function sparklestore_products_wishlist() {
            if (function_exists('YITH_WCWL')) {
                $wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
                <div class="top-wishlist text-right">
                    <a href="<?php echo esc_url($wishlist_url); ?>" title="Wishlist" data-toggle="tooltip">
                        <i class="fa fa-heart"></i>
                        <span class="title-wishlist hidden-xs">
                        <?php esc_html_e('Wishlist', 'sparklestore'); ?>
                        </span>
                        <div class="count">
                            <span><?php echo " (" . intval( yith_wcwl_count_products() ) . ") "; ?></span>
                        </div>
                    </a>
                </div>
                <?php
            }
        }
    }
    add_action('wp_ajax_yith_wcwl_update_single_product_list', 'sparklestore_products_wishlist');
    add_action('wp_ajax_nopriv_yith_wcwl_update_single_product_list', 'sparklestore_products_wishlist');
}


/**
 * Add the link to compare function area
*/
if (defined('YITH_WOOCOMPARE')) {

    function sparklestore_add_compare_link($product_id = false, $args = array()) {
        extract($args);
        if (!$product_id) {
            global $product;
            $productid = $product->get_id();
            $product_id = isset($productid) ? $productid : 0;
        }
        $is_button = !isset($button_or_link) || !$button_or_link ? get_option('yith_woocompare_is_button') : $button_or_link;

        if (!isset($button_text) || $button_text == 'default') {
            $button_text = get_option('yith_woocompare_button_text', esc_html__('Compare', 'sparklestore'));
            yit_wpml_register_string('Plugins', 'plugin_yit_compare_button_text', $button_text);
            $button_text = yit_wpml_string_translate('Plugins', 'plugin_yit_compare_button_text', $button_text);
        }
        printf('<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>', '#', 'compare link-compare' . ( $is_button == 'button' ? ' button' : '' ), $product_id, $button_text);
    }

    remove_action('woocommerce_after_shop_loop_item', array('YITH_Woocompare_Frontend', 'add_compare_link'), 20);
}


/**
 * Add the link to quickview function area
*/
if (defined('YITH_WCQV')) {
    function sparklestore_quickview() {
        global $product;
        $quick_view = YITH_WCQV_Frontend();
        remove_action('woocommerce_after_shop_loop_item', array($quick_view, 'yith_add_quick_view_button'), 15);
        $label = esc_html(get_option('yith-wcqv-button-label'));
        echo '<a href="#" class="link-quickview yith-wcqv-button" data-product_id="' . $product->get_id() . '">' . $label . '</a>';
    }
}


/**
 * Load advance search WooCommerce function area
*/
if (!function_exists('sparklestore_advance_search_form')) {
    function sparklestore_advance_search_form() {
        echo '<div class="search-box">';
            $args = array(
                'number' => '',
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true
            );
            $product_categories = get_terms('product_cat', $args);
            $categories_show = '<option value="">' . esc_html__('All Categories', 'sparklestore') . '</option>';
            $check = '';
            if (is_search()) {
                if (isset($_GET['term']) && $_GET['term'] != '') {
                    $check = $_GET['term'];
                }
            }
            $checked = '';
            $allcat = esc_html__('All Categories', 'sparklestore');
            $categories_show .= '<optgroup class="ap-adv-search" label="' . $allcat . '">';
            foreach ($product_categories as $category) {
                if (isset($category->slug)) {
                    if (trim($category->slug) == trim($check)) {
                        $checked = 'selected="selected"';
                    }
                    $categories_show .= '<option ' . $checked . ' value="' . $category->slug . '">' . $category->name . '</option>';
                    $checked = '';
                }
            }

            $categories_show .= '</optgroup>';
                $form = '<form role="search" method="get" id="search_mini_form"  action="' . esc_url(home_url('/')) . '">
                        <select id="cat" class="cate-dropdown hidden-sm hidden-md" name="term">' . $categories_show . '</select>
                        <input id="search" type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_html__('Search entire store here...', 'sparklestore') . '" class="searchbox" maxlength="128" />
                         <button type="submit" title="Search" class="search-btn-bg" id="submit-button">
                            <span>' . esc_html__('Search', 'sparklestore') . '</span>
                         </button>
                        <input type="hidden" name="post_type" value="product" />
                        <input type="hidden" name="taxonomy" value="product_cat" />
                    </form>';
            echo $form;
        echo '</div>';
    }

}


/**
 * Sparkle Tabs Category Products Ajax Function
*/
if (!function_exists('sparklestore_tabs_ajax_action')) {
    function sparklestore_tabs_ajax_action() {
        $cat_slug       = $_POST['category_slug'];
        $product_number = $_POST['product_num'];
        ob_start(); ?>

        <div class="sparkletabproductarea">

            <ul class="tabsproduct cS-hidden">                            
                <?php
                    $product_args = array(
                        'post_type' => 'product',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $cat_slug
                            )),
                        'posts_per_page' => $product_number
                    );

                    $query = new WP_Query($product_args);

                    if ($query->have_posts()) { 
                        while ($query->have_posts()) { $query->the_post();
                           wc_get_template_part('content', 'product');
                        } 
                    }                     
                    wp_reset_postdata(); 
                ?>
            </ul>

        </div>
        <?php
            $sparkle_html = ob_get_contents();
            ob_get_clean();
            echo $sparkle_html;
            die();
    }
}
add_action('wp_ajax_sparklestore_tabs_ajax_action', 'sparklestore_tabs_ajax_action');
add_action('wp_ajax_nopriv_sparklestore_tabs_ajax_action', 'sparklestore_tabs_ajax_action');

/**
 * Load sparkleStore WooCommerce action and filter function area
*/
function sparklestore_woocommerce_breadcrumb() {
    do_action('breadcrumb-woocommerce');
}
add_action('woocommerce_before_main_content', 'sparklestore_woocommerce_breadcrumb', 9);

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

add_filter('woocommerce_show_page_title', '__return_false');

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

function sparklestore_woocommerce_template_loop_product_thumbnail() { ?>
    <div class="itemimg">
        <div class="itemimginfo">
            <a class="productimage" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                <?php echo woocommerce_get_product_thumbnail(); ?>
            </a>
            <?php 
                global $post, $product;
                if ($product->is_on_sale()) :
                echo apply_filters('woocommerce_sale_flash', '<div class="new-label new-top-right">' . esc_html__('Sale!', 'sparklestore') . '</div>', $post, $product);
            ?>
            <?php endif; ?>
            <?php
                global $product_label_custom;
                if ($product_label_custom != '') {
                    echo '<div class="new-label new-top-left">' . $product_label_custom . '</div>';
                }
            ?>
            <div class="box-hover">
                <ul class="add-to-links">
                    <?php if (function_exists('sparklestore_quickview')) { ?>
                        <li><?php sparklestore_quickview();?></li>
                    <?php } ?>
                    <?php if (function_exists('sparklestore_wishlist_products')) { ?>
                        <li><?php sparklestore_wishlist_products(); ?></li>
                    <?php } ?>
                    <?php if (function_exists('sparklestore_add_compare_link')) { ?>
                        <li><?php sparklestore_add_compare_link(); ?><li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </div>
    <?php
}

add_action('woocommerce_before_shop_loop_item_title', 'sparklestore_woocommerce_template_loop_product_thumbnail', 10);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

function sparklestore_woocommerce_template_loop_product_title() { ?>
    <div class="item-title">
        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </div>
<?php }

add_action('woocommerce_shop_loop_item_title', 'sparklestore_woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
function sparklestore_woocommerce_after_shop_loop_item_title() { ?>
    <div class="price-rating-wrap">
        <?php woocommerce_template_loop_rating(); ?>
        <?php woocommerce_template_loop_price(); ?>
    </div>
<?php }
add_action('woocommerce_after_shop_loop_item_title', 'sparklestore_woocommerce_after_shop_loop_item_title');

/**
 * Woo Commerce Add Content Primary Div Function
*/
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
if (!function_exists('sparklestore_woocommerce_output_content_wrapper')) {
    function sparklestore_woocommerce_output_content_wrapper() { ?>
        <div class="inner_page">
            <div class="container">
                <div class="row">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main" role="main">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'sparklestore_woocommerce_output_content_wrapper', 10);

remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
if (!function_exists('sparklestore_woocommerce_output_content_wrapper_end')) {

    function sparklestore_woocommerce_output_content_wrapper_end() {  ?>
                        </main><!-- #main -->
                    </div><!-- #primary -->

                    <?php get_sidebar('woocommerce'); ?>

                </div>
            </div>
        </div>
    <?php
    }

}
add_action('woocommerce_after_main_content', 'sparklestore_woocommerce_output_content_wrapper_end', 10);

/**
 * Remove WooCommerce Default Sidebar
*/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Woo Commerce Number of row filter Function
*/
add_filter('loop_shop_columns', 'sparklestore_loop_columns');
if (!function_exists('sparklestore_loop_columns')) {
    function sparklestore_loop_columns() {
        $product_num = intval(get_theme_mod('sparklestore_woocommerce_product_row', '3'));
        if ($product_num) {
            $number = $product_num;
        } else {
            $number = 3;
        }
        return $number;
    }
}

add_action('body_class', 'sparklestore_woo_body_class');
if (!function_exists('sparklestore_woo_body_class')) {
    function sparklestore_woo_body_class($class) {
        $class[] = 'columns-' . sparklestore_loop_columns();
        return $class;
    }
}

/**
 * Woo Commerce Number of Columns filter Function
*/
$column = get_theme_mod('sparklestore_woocommerce_display_product_number', '12');
add_filter('loop_shop_per_page', create_function('$cols', 'return ' . $column . ';'), 20);

/**
 * WooCommerce display related product.
*/
if (!function_exists('sparklestore_related_products_args')) {
  function sparklestore_related_products_args( $args ) {
      $args['columns']  = intval(get_theme_mod('sparklestore_woocommerce_product_row', '3'));
      return $args;
  }
}
add_filter( 'woocommerce_output_related_products_args', 'sparklestore_related_products_args' );

/**
 * WooCommerce display upsell product.
*/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
if ( ! function_exists( 'sparklestore_woocommerce_upsell_display' ) ) {
  function sparklestore_woocommerce_upsell_display() {
      woocommerce_upsell_display(3,3); 
  }
}
add_action( 'woocommerce_after_single_product_summary', 'sparklestore_woocommerce_upsell_display', 15 );


/**
 * You may be interested in…
*/
add_filter( 'woocommerce_cross_sells_total', 'sparklestore_change_cross_sells_product_no' );
function sparklestore_change_cross_sells_product_no( $columns ) {
    return 2;
}

/**
 * WooCommerce products manage settins
*/
/*<div class="social">
    <ul class="link">
      <li class="fb"><a href="#"></a></li>
      <li class="tw"><a href="#"></a></li>
      <li class="googleplus"><a href="#"></a></li>
      <li class="pintrest"><a href="#"></a></li>
      <li class="linkedin"><a href="#"></a></li>
      <li class="youtube"><a href="#"></a></li>
    </ul>
</div>*/