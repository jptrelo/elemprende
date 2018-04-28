<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Awaiting_Reviews
 * @subpackage Woo_Awaiting_Reviews/public
 * @author     Fausto Rodrigo Toloi <fausto@nw2web.com.br>
 */
class Woo_Awaiting_Reviews_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Custom endpoint name.
     *
     * @var string
     */
    public static $endpoint = 'woo-aw-reviews';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct() {

        //$this->plugin_name = $plugin_name;
        //$this->version = $version;


        // Actions used to insert a new endpoint in the WordPress.
        add_action('init', array($this, 'add_endpoints'));



        add_filter('query_vars', array($this, 'add_query_vars'), 0);

        // Change the My Accout page title.
        add_filter('the_title', array($this, 'endpoint_title'));

        // Insering your new tab/page into the My Account page.
        add_filter('woocommerce_account_menu_items', array($this, 'new_menu_items'));
        add_action('woocommerce_account_' . self::$endpoint . '_endpoint', array($this, 'endpoint_content'));

        // Return to the same page after post the Review
        add_action('comment_post_redirect', array($this, 'redirect_after_comment'), 10, 1);

        // Add [awaitingreviews] shortcode to display the same info inside My Account
        add_shortcode('awaitingreviews', array($this, 'shorcode_display_awaiting_reviews'));


        add_action('init', array($this, 'flush_rewrite_rules_maybe'));
    }

    /**
     * Return to same page after submit comment.
     * 
     * @param type $location
     * @param type $comment
     * @return string
     */
    function redirect_after_comment($url) {
        return $_SERVER["HTTP_REFERER"];
    }

    /**
     * Register new endpoint to use inside My Account page.
     *
     * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
     */
    public function add_endpoints() {
        add_rewrite_endpoint(self::$endpoint, EP_ROOT | EP_PAGES);
    }

    /**
     * Check if is needed to flush rewrite rules 
     */
    public function flush_rewrite_rules_maybe() {
        if (get_option('flush_rewrite_rules_flag')) {
            flush_rewrite_rules();
            delete_option('flush_rewrite_rules_flag');
        }
    }

    /**
     * Add new query var.
     *
     * @param array $vars
     * @return array
     */
    public function add_query_vars($vars) {
        $vars[] = self::$endpoint;

        return $vars;
    }

    /**
     * Set endpoint title.
     *
     * @param string $title
     * @return string
     */
    public function endpoint_title($title) {
        global $wp_query;

        $is_endpoint = isset($wp_query->query_vars[self::$endpoint]);

        if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {
            // New page title.
            $title = __('Awaiting Reviews', 'woo-awaiting-reviews');

            remove_filter('the_title', array($this, 'endpoint_title'));
        }

        return $title;
    }

    /**
     * Insert the new endpoint into the My Account menu.
     *
     * @param array $items
     * @return array
     */
    public function new_menu_items($items) {
        // Remove the logout menu item.
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);

        // Insert Awaiting Reviews endpoint.
        $items[self::$endpoint] = __('Awaiting Reviews', 'woo-awaiting-reviews');

        // Insert back the logout item.
        $items['customer-logout'] = $logout;

        return $items;
    }

    /**
     * Endpoint HTML content.
     */
    public function endpoint_content() {
        echo '<h3>' . esc_html__('Products awaiting reviews', 'woo-awaiting-reviews') . '</h3>';
        $this->display_awaiting_reviews();
    }

    /**
     *  Do all the magic, look for products within the sales orders that have not yet been reviewed.
     */
    public function display_awaiting_reviews() {

        // Check if user is logged in 
        if (!is_user_logged_in()) {
            //Set variables to redirect to the same page
            $defaults = array(
                'message' => '',
                'redirect' => '#', // this one
                'hidden' => false
            );
            $args = wp_parse_args($args, $defaults);
            
            ob_start();
            echo '<div class="woocommerce">';
            wc_get_template('global/form-login.php', $args);
            echo '</div>';
            echo ob_get_clean();
            return;
        }

        // Need to review something? 
        $has_reviews = false;

        // Get all orders
        $customer_orders = get_posts(array(
            'numberposts' => -1,
            'meta_key' => '_customer_user',
            'meta_value' => get_current_user_id(),
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        ));

        // Loop orders to find those that aren't commented
        foreach ($customer_orders as $post) :

            // Need to instance the order
            $order = new WC_Order($post->ID);

            // Get all products from this order 
            $items = $order->get_items();

            // Loop trought items 
            foreach ($items as $item) {
                // Get product ID
                $product_id = $item['product_id'];

                // This product has comments enable?
                if (!comments_open($product_id))
                    break;
                //echo ':P';
                // Query all comments from this product to find if user already done a review
                $comments = get_comments(
                        array(
                            'post_id' => $product_id,
                            'user_id' => get_current_user_id(),
                        )
                );

                // Check if user hasen't done a review yet.
                if (!$comments):
                    // Start plugin
                    echo '<div class="woo-awaiting-review woocommerce">';

                    //So yes, need to review
                    $has_reviews = true;

                    /* Display thumb image */
                    $img_url = $image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'thumbnail');
                    echo '<img src="' . $image[0] . '" data-id="' . $item['product_id'] . ' " style="float:left; margin-right:15px;">';

                    /* We don't need another query.
                     * By doing this we can remain the 
                     * original code from:
                     * single-product-reviews.php
                     * reduced inside: 
                     * war-public-display_awaiting_reviews.php
                     * in case you wanna copy and paste
                     */
                    $product = (object) array('id' => $product_id); // wc_get_product($product_id);
                    // Needed to use query_posts to make it global instance
                    query_posts('p=' . $product_id . '&post_type=product');

                    // Loop trought all items needed to review
                    while (have_posts()): the_post();
                        // Partials: include almost the same file as single-product-reviews.php Woocommerce Template
                        include(dirname(__FILE__) . '/partials/war-public-display_awaiting_reviews.php');
                    endwhile;

                    // Reset global query to go on
                    wp_reset_query();

                    // end 
                    echo '</div>';

                    //Only one product at time.
                    return;
                else :
                // Debug: If you need to show the user all the Reviews done
                //_e('You had reviwed '. $item['name'], 'woo-awaiting-reviews');
                endif;
            }

            if (!$has_reviews) {
                // So, there's no need another review
                echo '<center>';
                _e("You have already reviewed all your products, thank you!", 'woo-awaiting-reviews');
                echo '</center>';
            }
        endforeach;
    }

    /**
     * Create a shortcode to display all products that need a review from current user
     *
     * @since    1.0.0
     */
    public function shorcode_display_awaiting_reviews($atts = null) {
        ob_start();
        $this->display_awaiting_reviews();
        return ob_get_clean();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        // I've kept this if you need to include some CSS by your own.
        wp_enqueue_style('woocommerce-general');
        //Suffix -public added to fix enqueue
        wp_enqueue_style($this->plugin_name . '-public', plugin_dir_url(__FILE__) . 'css/woo-awaiting-reviews-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script('wc-single-product');
    }

    public function simple_script() {
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery('body').on('init', '.rating', function () {
                jQuery('.rating').hide().before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');
            })
        </script>
        <?php

        echo ob_get_clean();
    }

}
