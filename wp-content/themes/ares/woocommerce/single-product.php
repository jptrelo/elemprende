<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$ares_options = ares_get_options();

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

get_header( 'shop' ); 

?>

    <?php
            /**
             * woocommerce_before_main_content hook.
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
    ?>

    <header class="woocommerce-products-header page-header">

        <div class="col-md-12">

            <?php woocommerce_breadcrumb(); ?>
            
        </div>
    
    </header>

    <div class="col-md-<?php echo is_active_sidebar( 'sidebar-shop' ) && isset( $ares_options['shop_sidebar_on_product'] ) && $ares_options['shop_sidebar_on_product'] == 'on' ? '8' : '12'; ?>">
        
        <div id="ares-shop-wrap">

            <?php while ( have_posts() ) : the_post(); ?>

                    <?php wc_get_template_part( 'content', 'single-product' ); ?>

            <?php endwhile; // end of the loop. ?>

        </div>
        
    </div>
        
    <?php if ( is_active_sidebar( 'sidebar-shop' ) && isset( $ares_options['shop_sidebar_on_product'] ) && $ares_options['shop_sidebar_on_product'] == 'on' ) : ?>

        <div class="col-md-4 avenue-sidebar">

            <?php
                /**
                 * woocommerce_sidebar hook.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action( 'woocommerce_sidebar' );
            ?>

        </div>

    <?php endif; ?>

    <?php
        /**
         * woocommerce_after_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'woocommerce_after_main_content' );
    ?>

<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */


