<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Ares
 */

$ares_options = ares_get_options();

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>

</head>

<?php 

if ( $ares_options['ares_theme_background_pattern'] == 'witewall_3' || 
    $ares_options['ares_theme_background_pattern'] == 'brickwall' || 
    $ares_options['ares_theme_background_pattern'] == 'skulls' || 
    $ares_options['ares_theme_background_pattern'] == 'crossword' || 
    $ares_options['ares_theme_background_pattern'] == 'food' ) :

    $bg_image_src = get_template_directory_uri() . '/inc/images/' . esc_attr( $ares_options['ares_theme_background_pattern'] ) . '.png';
    
elseif ( !defined( 'ARES_PRO_URL' ) ) : 
    
    $bg_image_src = get_template_directory_uri() . '/inc/images/crossword.png';
    
else :
    
    $bg_image_src = ARES_PRO_URL . 'assets/images/' . esc_attr( $ares_options['ares_theme_background_pattern'] ) . '.png';

endif;

?>

<body <?php body_class(); ?> style="background-image: url(<?php echo esc_url( $bg_image_src ); ?>);">

    <div id="page" class="site">

        <header id="masthead" class="site-header" role="banner">

            <?php if ( $ares_options['ares_headerbar_bool'] == 'yes' ) : ?>

                <?php do_action( 'ares_toolbar' ); ?>

            <?php endif; ?>

            <div id="site-branding" class="container">

                <div class="branding">

                    <?php if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) : ?>

                        <?php the_custom_logo(); ?>

                    <?php else : ?>

                        <?php if ( get_bloginfo( 'name' ) ) : ?>

                            <h2 class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                    <?php bloginfo( 'name' );?>
                                </a>
                            </h2>

                        <?php endif; ?>

                        <?php if ( get_bloginfo( 'description' ) ) : ?>

                            <h5 class="site-description">
                                <?php echo get_bloginfo( 'description' ); ?>
                            </h5>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

                <div class="navigation">

                    <nav id="site-navigation" class="main-navigation" role="navigation">

                        <?php wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                        ) ); ?>
                        
                    </nav><!-- #site-navigation -->
                    
                </div>

                <?php if ( class_exists( 'WooCommerce' ) && isset( $ares_options['cart_icon_toggle'] ) && $ares_options['cart_icon_toggle'] == 'on' ) : ?>
                
                    <div id="header-cart" class="tablet-hidden">
                        <span class="fa fa-shopping-cart"></span>
                        <?php if ( WC()->cart->get_cart_contents_count() && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <?php endif; ?>
                    </div>
                
                <?php endif; ?>

                <div class="mobile-trigger-wrap">
                    
                    <?php if ( class_exists( 'WooCommerce' ) && isset( $ares_options['cart_icon_toggle'] ) && $ares_options['cart_icon_toggle'] == 'on' ) : ?>

                        <div id="header-cart">
                            <span class="fa fa-shopping-cart"></span>
                            <?php if ( WC()->cart->get_cart_contents_count() && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>
                    
                    <span id="mobile-menu-trigger"><span class="fa fa-bars"></span></span>
                    
                </div>

                <div id="mobile-overlay"></div>
                <div id="cart-overlay"></div>

                <div id="mobile-menu-wrap">

                    <nav id="menu" role="navigation">

                        <img id="mobile-menu-close" src="<?php echo esc_url( get_template_directory_uri() . '/inc/images/close-mobile.png' ); ?>" alt="<?php _e( 'Close Menu', 'ares' ); ?>">

                        <?php if ( has_nav_menu( 'primary' ) ) : ?>

                            <?php wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'mobile-menu',
                            ) ); ?>

                        <?php else : ?>

                            <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>

                                <ul id="mobile-menu" class="menu">

                                    <li class="menu-item menu-item-type-custom menu-item-object-custom">

                                        <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">
                                           <?php _e( 'Add a Primary Menu?', 'ares' ); ?>
                                        </a>

                                    </li>

                                </ul>
                        
                            <?php endif; ?>
                        
                        <?php endif; ?>
                        
                    </nav>

                </div>
                
                <?php if ( class_exists( 'WooCommerce' ) && isset( $ares_options['cart_icon_toggle'] ) && $ares_options['cart_icon_toggle'] == 'on' ) : ?>
                
                    <div id="cart-slide-wrap">

                        <img id="cart-slide-close" src="<?php echo esc_url( get_template_directory_uri() . '/inc/images/close-mobile.png' ); ?>" alt="<?php _e( 'Close Menu', 'ares' ); ?>">
                        
                        <div class="inner-wrap">

                            <h3 class="cart-title">
                                <?php echo isset( $ares_options['slide_in_cart_title'] ) && $ares_options['slide_in_cart_title'] != '' ? esc_html( $ares_options['slide_in_cart_title'] ) : __( 'My Cart', 'ares' ); ?>
                            </h3>
                            
                            <?php
                            
                            global $woocommerce;
                            $items = $woocommerce->cart->get_cart();

                            foreach( $items as $item => $values ) :

                                $cart_product =  wc_get_product( $values['data']->get_id() ); ?>

                                <div class="cart-product">

                                    <div class="cart-image">
                                        <a href="<?php echo esc_url( $cart_product->get_permalink() ); ?>">
                                            <?php echo $cart_product->get_image( 'large' ); ?>
                                        </a>
                                    </div>

                                    <div class="cart-details">
                                        <a href="<?php echo esc_url( $cart_product->get_permalink() ); ?>">
                                            <span class="title">
                                                <?php echo esc_html( $cart_product->get_title() ); ?>
                                            </span>
                                        </a>
                                        <span class="price <?php echo $cart_product->get_sale_price() ? 'sale' : ''; ?>">
                                            <?php echo $cart_product->get_sale_price() ? esc_html( get_woocommerce_currency_symbol() . number_format( $cart_product->get_sale_price(), 2, '.', ',' ) ) : esc_html( get_woocommerce_currency_symbol() . number_format( $cart_product->get_regular_price(), 2, '.', ',' ) ); ?>
                                            <span style="text-transform: lowercase;">x</span> <?php echo intval( $values['quantity'] ); ?>
                                        </span>
                                    </div>

                                </div>

                            <?php endforeach; ?>
                            
                            <h3 class="subtotal">
                                <span class="label"><?php _e( 'Subtotal: ', 'ares' ); ?></span>
                                <?php echo WC()->cart->get_cart_subtotal(); ?>
                            </h3>
                            
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="ares-button hollow-button">
                                <?php echo esc_html_e( 'View Cart' ,'ares' ); ?>
                            </a>
                            
                            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="ares-button">
                                <?php echo esc_html_e( 'Checkout' ,'ares' ); ?>
                            </a>
                            
                        </div>

                    </div>
                
                <?php endif; ?>

            </div>

        </header><!-- #masthead -->

        <div id="content" class="site-content">
