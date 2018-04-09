<?php

/**
 * Enqueue scripts and styles.
 */
function ares_scripts() {

    wp_enqueue_style( 'ares-style', get_stylesheet_uri() );
    
    // Get the Options array
    $ares_options = ares_get_options();

    // Load Fonts from array
    $fonts = ares_fonts();
    $non_google_fonts = ares_non_google_fonts();
    
    // Are both fonts Google Fonts?
    if ( array_key_exists ( $ares_options['ares_font_family'], $fonts ) && !array_key_exists ( $ares_options['ares_font_family'], $non_google_fonts ) &&
        array_key_exists ( $ares_options['ares_font_family_secondary'], $fonts ) && !array_key_exists ( $ares_options['ares_font_family_secondary'], $non_google_fonts ) ) :
        
        if ( $ares_options['ares_font_family'] == $ares_options['ares_font_family_secondary'] ) :
            // Both fonts are Google Fonts and are the same, enqueue once
            wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $fonts[ $ares_options['ares_font_family'] ] ), array(), ARES_VERSION ); 
        else :
            // Both fonts are Google Fonts but are different, enqueue together
            wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $fonts[ $ares_options['ares_font_family'] ] . '|' . $fonts[ $ares_options['ares_font_family_secondary'] ] ), array(), ARES_VERSION ); 
        endif;
        
    elseif ( array_key_exists ( $ares_options['ares_font_family'], $fonts ) && !array_key_exists ( $ares_options['ares_font_family'], $non_google_fonts ) ) :
    
        // Only Primary is a Google Font. Enqueue it.
        wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $fonts[ $ares_options['ares_font_family'] ] ), array(), ARES_VERSION ); 
        
    elseif ( array_key_exists ( $ares_options['ares_font_family_secondary'], $fonts ) && !array_key_exists ( $ares_options['ares_font_family_secondary'], $non_google_fonts ) ) :
        
        // Only Secondary is a Google Font. Enqueue it.
        wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $fonts[ $ares_options['ares_font_family_secondary'] ] ), array(), ARES_VERSION ); 
        
    endif;
    
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css', array(), ARES_VERSION );
    wp_enqueue_style( 'animate', get_template_directory_uri() . '/inc/css/animate.css', array(), ARES_VERSION );
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/inc/css/font-awesome.min.css', array(), ARES_VERSION );
    wp_enqueue_style( 'camera', get_template_directory_uri() . '/inc/css/camera.css', array(), ARES_VERSION );
    wp_enqueue_style( 'ares-old-style', get_template_directory_uri() . '/inc/css/old_ares.css', array(), ARES_VERSION );
    wp_enqueue_style( 'ares-main-style', get_template_directory_uri() . '/inc/css/ares.css', array(), ARES_VERSION );

    wp_enqueue_script( 'jquery-easing', get_template_directory_uri() . '/inc/js/jquery.easing.1.3.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/inc/js/bootstrap.min.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'bigSlide', get_template_directory_uri() . '/inc/js/bigSlide.min.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'camera-js', get_template_directory_uri() . '/inc/js/camera.min.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'wow', get_template_directory_uri() . '/inc/js/wow.min.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'jquery-slimScroll', get_template_directory_uri() . '/inc/js/jquery.slimscroll.min.js', array('jquery'), ARES_VERSION, true );
    wp_enqueue_script( 'ares-main-script', get_template_directory_uri() . '/inc/js/ares.js', array('jquery', 'jquery-masonry'), ARES_VERSION, true );

    $slider_array = array(
        'desktop_height'    => isset( $ares_options['ares_slider_height'] )     ? $ares_options['ares_slider_height'] : '56',
        'slide_timer'       => isset( $ares_options['ares_slider_time'] )       ? $ares_options['ares_slider_time'] : 4000, 
        'animation'         => isset( $ares_options['sc_slider_fx'] )           ? $ares_options['sc_slider_fx'] : 'simpleFade',
        'pagination'        => isset( $ares_options['ares_slider_pagination'] ) ? $ares_options['ares_slider_pagination'] : 'off',
        'navigation'        => isset( $ares_options['ares_slider_navigation'] ) ? $ares_options['ares_slider_navigation'] : 'on',
        'animation_speed'   => isset( $ares_options['ares_slider_trans_time'] ) ? $ares_options['ares_slider_trans_time'] : 2000,
        'hover'             => isset( $ares_options['ares_slider_hover'] )      ? $ares_options['ares_slider_hover'] : 'on',
    );
    
    // Pass each JS object to the custom script using wp_localize_script
    wp_localize_script( 'ares-main-script', 'aresSlider', $slider_array );

    

    wp_enqueue_script( 'ares-navigation', get_template_directory_uri() . '/js/navigation.js', array(), ARES_VERSION, true );
    wp_enqueue_script( 'ares-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), ARES_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
    }

}
add_action( 'wp_enqueue_scripts', 'ares_scripts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ares_widgets_init() {

    $ares_options = ares_get_options();
    
    register_sidebar(array(
        'name' => __('Header Right (Toolbar)', 'ares'),
        'id' => 'sidebar-header-right',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="hidden">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Homepage C - Full-width', 'ares'),
        'id' => 'sidebar-banner',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s smartcat-animate fadeIn">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Homepage D - Half-width', 'ares'),
        'id' => 'sidebar-homepage-widget',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s col-sm-6 smartcat-animate fadeIn">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Sidebar', 'ares'),
        'id' => 'sidebar-1',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2><div class="avenue-underline"></div>',
    ));
    
    register_sidebar(array(
        'name' => __('Shop', 'ares'),
        'id' => 'sidebar-shop',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer', 'ares'),
        'id' => 'sidebar-footer',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="' . esc_attr( $ares_options['ares_footer_columns'] ) . ' widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2><div class="avenue-underline"></div>',
    ));
        
}
add_action( 'widgets_init', 'ares_widgets_init' );

/**
 * Hex to rgb(a) converter function.
 */
function ares_hex2rgba( $color, $opacity = false ) {

    $default = 'rgb(0,0,0)';

    // Return default if no color provided
    if ( empty( $color ) ) { return $default; }

    // Sanitize $color if "#" is provided
    if ( $color[0] == '#' ) { $color = substr( $color, 1 ); }

    // Check if color has 6 or 3 characters and get values
    if ( strlen( $color ) == 6 ) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    // Convert hexadec to rgb
    $rgb =  array_map( 'hexdec', $hex );

    // Check if opacity is set(rgba or rgb)
    if( $opacity ) {

        if( abs( $opacity ) > 1 ) { $opacity = 1.0; }
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';

    } else {

        $output = 'rgb('.implode(",",$rgb).')';

    }

    // Return rgb(a) color string
    return $output;

}

/**
 * Inject dynamic CSS rules with wp_head.
 */
function ares_custom_css() { 

    $ares_options = ares_get_options(); ?>

    <style>

        h1,h2,h3,h4,h5,h6,
        .sc-slider-wrapper .camera_caption > div span,
        #site-branding div.navigation ul#primary-menu,
        .ares-button,
        p.comment-form-comment label,
        input#submit,
        .sc_team_single_member .sc_team_single_skills .progress,
        .parallax h2,
        input#submit, input[type="submit"],
        a.ares-button.slider-button,
        #site-navigation.main-navigation div#primary-menu ul > li a,
        form#scmod-contact-form  .group > label,
        .ares-contact-info .contact-row .detail,
        header.woocommerce-products-header.page-header .woocommerce-breadcrumb,
        .woocommerce ul.products li.product .price,
        .woocommerce a.button.add_to_cart_button,
        .woocommerce div.product p.price, 
        .woocommerce div.product span.price,
        .woocommerce span.onsale,
        .woocommerce button.single_add_to_cart_button,
        .woocommerce div.product .woocommerce-tabs ul.tabs li a,
        .woocommerce input[type="submit"].button,
        .ares-pricing-table .price,
        .ares-pricing-table .subtitle,
        .faq-item .faq-content,
        #cart-slide-wrap .cart-product .cart-details,
        .woocommerce.widget_shopping_cart ul li > a:nth-of-type(2),
        .widget.woocommerce a.button,
        .widget.woocommerce.widget_products .product-title,
        div#alt-single-wrap .post-meta,
        .widget.woocommerce .button,
        .woocommerce-cart .woocommerce a.button {
            font-family: <?php echo esc_attr( $ares_options['ares_font_family'] ); ?>;
        }
        
        body {
            font-size: <?php echo esc_attr( $ares_options['ares_font_size'] ); ?>px;
            font-family: <?php echo esc_attr( $ares_options['ares_font_family_secondary'] ); ?>;
        }
        
        .ares-callout .detail,
        .ares-faq .faq-item .faq-answer {
            font-family: <?php echo esc_attr( $ares_options['ares_font_family_secondary'] ); ?>;
        }
        
        .ares-faq .faq-item .faq-answer {
            font-size: <?php echo esc_attr( $ares_options['ares_font_size'] ); ?>px;
        }
        
        blockquote {
            font-size: <?php echo esc_attr( $ares_options['ares_font_size'] + 4 ); ?>px;
        }
        
        /*
        ----- Header Heights ---------------------------------------------------------
        */

        @media (min-width:992px) {
            #site-branding {
               height: <?php echo intval( $ares_options['ares_branding_bar_height'] ); ?>px;
            }
            #site-branding img {
               max-height: <?php echo intval( $ares_options['ares_branding_bar_height'] ); ?>px;
            }
        }

        div#content {
            margin-top: <?php echo esc_attr( $ares_options['ares_branding_bar_height'] + ( $ares_options['ares_headerbar_bool'] == 'yes' ? 40 : 0 ) ); ?>px;
        }

        <?php if ( $ares_options['ares_headerbar_bool'] != 'yes' ) : ?>
        
            div#content {
                margin-top: 80px !important;
            }
            
        <?php endif; ?>
            
        /*
        ----- Theme Colors -----------------------------------------------------
        */
       
        <?php 
        
        $colors_array = ares_get_theme_skin_colors();
        
        $primary_theme_color = $colors_array['primary'];
        $secondary_theme_color = $colors_array['accent']; 
        
        ?>
       
        /* --- Primary --- */
        
        a,
        a:visited,
        .primary-color,
        .button-primary .badge,
        .button-link,
        .sc-primary-color,
        .icon404,
        .nav-menu > li a:hover,
        .smartcat_team_member:hover h4,
        #site-navigation.main-navigation li a:hover,
        #site-navigation.main-navigation li.current_page_item a,
        #site-cta .site-cta .fa,
        .sc_team_single_member .sc_single_main .sc_personal_quote span.sc_team_icon-quote-left,
        .ares-contact-info .contact-row .detail a:hover,
        footer#colophon.site-footer .ares-contact-info .contact-row .detail a:hover,
        .woocommerce .star-rating span
        {
            color: <?php echo esc_attr( $primary_theme_color ); ?>;
        }
        @media (max-width: 600px) {
            .nav-menu > li.current_page_item a {
                color: <?php echo esc_attr( $primary_theme_color ); ?>;
            }      
        }
        a.button-primary,
        #site-cta .site-cta .fa.hover,
        fieldset[disabled] .button-primary.active,
        #main-heading,
        #secondary-heading,
        ul.social-icons li a:hover,
        #site-toolbar .row .social-bar a:hover,
        #footer-callout,
        #site-cta .site-cta .fa:hover,
        #post-slider-cta,
        header.page-header .page-title,
        nav.navigation.posts-navigation,
        input#submit,
        input[type="submit"],
        .sc_team_single_member .sc_team_single_skills .progress,
        .sc-tags .sc-single-tag,
        .woocommerce-breadcrumb,
        .pagination-links .page-numbers.current,
        .wc-pagination ul span.page-numbers.current,
        .woocommerce a.button.add_to_cart_button,
        .woocommerce input[type="submit"].button,#cart-slide-wrap .cart-product .cart-details .price,
        div#cart-slide-wrap .inner-wrap a.ares-button,
        .woocommerce span.onsale,
        div#header-cart .cart-count,
        .widget.woocommerce a.button,
        .widget.woocommerce button.button,
        .woocommerce-cart .woocommerce a.button
        {
            background: <?php echo esc_attr( $primary_theme_color ); ?>;
        }
        .woocommerce button.single_add_to_cart_button,
        .woocommerce a.checkout-button.button,
        .footer-boxes .ares-pricing-table .widget .inner.special .pricing-table-header,
        .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
        .woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
            background: <?php echo esc_attr( $primary_theme_color ); ?> !important;
        }
        #site-cta .site-cta .fa {
            border: 2px solid <?php echo esc_attr( $primary_theme_color ); ?>;
        }
        .sc-primary-border,
        .scroll-top:hover {
            border-color: <?php echo esc_attr( $primary_theme_color ); ?>;
        }
        .site-branding .search-bar .search-field:focus {
            border-bottom: 1px solid <?php echo esc_attr( $primary_theme_color ); ?>;
        }
        .news-item .post-content .title a:hover,
        .post-content.no-image .title a:hover,
        #masonry-blog-wrapper .blog-roll-item .inner h3.post-title a:hover {
            color: <?php echo esc_attr( $primary_theme_color ); ?> !important;
        }
               
        /* --- Secondary --- */
        
        a:hover {
            color: <?php echo esc_attr( $secondary_theme_color ); ?>;
        }
        .button-primary:hover,
        .button-primary:focus,
        .button-primary:active,
        .button-primary.active,
        .open .dropdown-toggle.button-primary,
        input#submit:hover,
        input[type="submit"]:hover,
        .woocommerce a.button.add_to_cart_button:hover,
        div#cart-slide-wrap .inner-wrap a.ares-button:hover,
        .widget.woocommerce a.button:hover,
        .widget.woocommerce button.button:hover,
        .woocommerce-cart .woocommerce a.button:hover {
            background-color: <?php echo esc_attr( $secondary_theme_color ); ?>;
        }
        
        .woocommerce button.single_add_to_cart_button:hover,
        .woocommerce input[type="submit"].button:hover,
        .woocommerce a.checkout-button.button:hover {
            background-color: <?php echo esc_attr( $secondary_theme_color ); ?> !important;
        }
        
    </style>

<?php }
add_action( 'wp_head', 'ares_custom_css' );

/**
 * Returns all posts as an array.
 * Pass true to include Pages.
 *
 * @param boolean $include_pages
 * @return array of posts
 */
function ares_all_posts_array( $include_pages = false ) {

    $posts = get_posts( array(
        'post_type'        => $include_pages ? array( 'post', 'page' ) : 'post',
        'posts_per_page'   => -1,
        'post_status'      => 'publish',
        'orderby'          => 'title',
        'order'            => 'ASC',
    ));

    $posts_array = array(
        'none'  => __( 'None', 'ares' ),
    );

    foreach ( $posts as $post ) :

        if ( ! empty( $post->ID ) ) :
            $posts_array[ $post->ID ] = $post->post_title;
        endif;

    endforeach;

    return $posts_array;

}

/**
 * Render the toolbar in the header.
 */
function ares_render_toolbar() {

    $ares_options = ares_get_options(); ?>
    
    <div id="site-toolbar">

        <div class="container">

            <div class="row">

                <div class="col-xs-<?php echo is_active_sidebar( 'sidebar-header-right' ) ? '6' : '12'; ?> social-bar">

                    <?php if ( $ares_options['ares_facebook_url'] ) : ?>
                        <a href="<?php echo esc_url( $ares_options['ares_facebook_url'] ); ?>" target="_blank" class="icon-facebook animated fadeInDown">
                            <i class="fa fa-facebook"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $ares_options['ares_twitter_url'] ) : ?>
                    <a href="<?php echo esc_url( $ares_options['ares_twitter_url'] ); ?>" target="_blank" class="icon-twitter animated fadeInDown">
                            <i class="fa fa-twitter"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $ares_options['ares_linkedin_url'] ) : ?>
                        <a href="<?php echo esc_url( $ares_options['ares_linkedin_url'] ); ?>" target="_blank" class="icon-linkedin animated fadeInDown">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $ares_options['ares_gplus_url'] ) : ?>
                        <a href="<?php echo esc_url( $ares_options['ares_gplus_url'] ); ?>" target="_blank" class="icon-gplus animated fadeInDown">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $ares_options['ares_instagram_url'] ) : ?>
                        <a href="<?php echo esc_url( $ares_options['ares_instagram_url'] ); ?>" target="_blank" class="icon-instagram animated fadeInDown">
                            <i class="fa fa-instagram"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $ares_options['ares_youtube_url'] ) : ?>
                        <a href="<?php echo esc_url( $ares_options['ares_youtube_url'] ); ?>" target="_blank" class="icon-youtube animated fadeInDown">
                            <i class="fa fa-youtube"></i>
                        </a>
                    <?php endif; ?>

                </div>

                <?php if ( is_active_sidebar( 'sidebar-header-right' ) ) : ?>

                    <div class="col-xs-6 contact-bar">

                        <?php dynamic_sidebar( 'sidebar-header-right' ); ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

<?php }
add_action( 'ares_toolbar', 'ares_render_toolbar' );

/**
 * Render the slider on the frontpage.
 */
add_action( 'ares_slider', 'ares_render_slider', 10 );
function ares_render_slider() { ?>

    <?php get_template_part('template-parts/content', 'slider' ); ?>
    
<?php
}

/**
 * Returns all available fonts as an array
 *
 * @return array of fonts
 */
if( !function_exists( 'ares_fonts' ) ) {

    function ares_fonts() {

        $font_family_array = array(
            
            // Web Fonts
            'Arial, Helvetica, sans-serif'                      => 'Arial',
            'Arial Black, Gadget, sans-serif'                   => 'Arial Black',
            'Courier New, monospace'                            => 'Courier New',
            'Georgia, serif'                                    => 'Georgia',
            'Impact, Charcoal, sans-serif'                      => 'Impact',
            'Lucida Console, Monaco, monospace'                 => 'Lucida Console',
            'Lucida Sans Unicode, Lucida Grande, sans-serif'    => 'Lucida Sans Unicode',
            'MS Sans Serif, Tahoma, sans-serif'                 => 'MS Sans Serif',
            'MS Serif, New York, serif'                         => 'MS Serif',
            'Palatino Linotype, Book Antiqua, Palatino, serif'  => 'Palatino Linotype',
            'Tahoma, Geneva, sans-serif'                        => 'Tahoma',
            'Times New Roman, Times, serif'                     => 'Times New Roman',
            'Trebuchet MS, sans-serif'                          => 'Trebuchet MS',
            'Verdana, Geneva, sans-serif'                       => 'Verdana',
            
            // Google Fonts
            'Abel, sans-serif'                                  => 'Abel',
            'Arvo, serif'                                       => 'Arvo:400,400i,700',
            'Bangers, cursive'                                  => 'Bangers',
            'Courgette, cursive'                                => 'Courgette',
            'Domine, serif'                                     => 'Domine',
            'Dosis, sans-serif'                                 => 'Dosis:200,300,400',
            'Droid Sans, sans-serif'                            => 'Droid+Sans:400,700',
            'Economica, sans-serif'                             => 'Economica:400,700',
            'Josefin Sans, sans-serif'                          => 'Josefin+Sans:300,400,600,700',
            'Itim, cursive'                                     => 'Itim',
            'Lato, sans-serif'                                  => 'Lato:100,300,400,700,900,300italic,400italic',
            'Lobster Two, cursive'                              => 'Lobster+Two',
            'Lora, serif'                                       => 'Lora',
            'Lilita One, cursive'                               => 'Lilita+One',
            'Montserrat, sans-serif'                            => 'Montserrat:400,700',
            'Noto Serif, serif'                                 => 'Noto+Serif',
            'Old Standard TT, serif'                            => 'Old+Standard+TT:400,400i,700',
            'Open Sans, sans-serif'                             => 'Open Sans',
            'Open Sans Condensed, sans-serif'                   => 'Open+Sans+Condensed:300,300i,700',
            'Orbitron, sans-serif'                              => 'Orbitron',
            'Oswald, sans-serif'                                => 'Oswald:300,400',
            'Poiret One, cursive'                               => 'Poiret+One',
            'PT Sans Narrow, sans-serif'                        => 'PT+Sans+Narrow',
            'Rajdhani, sans-serif'                              => 'Rajdhani:300,400,500,600',
            'Raleway, sans-serif'                               => 'Raleway:200,300,400,500,700',
            'Roboto, sans-serif'                                => 'Roboto:100,300,400,500',
            'Roboto Condensed, sans-serif'                      => 'Roboto+Condensed:400,300,700',
            'Shadows Into Light, cursive'                       => 'Shadows+Into+Light',
            'Shrikhand, cursive'                                => 'Shrikhand',
            'Source Sans Pro, sans-serif'                       => 'Source+Sans+Pro:200,400,600',
            'Teko, sans-serif'                                  => 'Teko:300,400,600',
            'Titillium Web, sans-serif'                         => 'Titillium+Web:400,200,300,600,700,200italic,300italic,400italic,600italic,700italic',
            'Trirong, serif'                                    => 'Trirong:400,700',
            'Ubuntu, sans-serif'                                => 'Ubuntu',
            'Vollkorn, serif'                                   => 'Vollkorn:400,400i,700',
            'Voltaire, sans-serif'                              => 'Voltaire',
            
        );

        return apply_filters( 'ares_fonts', $font_family_array );

    }

}

/**
 * Retrieve non-Google based fonts.
 */
function ares_non_google_fonts() {
    
    return array(
            
        // Web Fonts
        'Arial, Helvetica, sans-serif'                      => 'Arial',
        'Arial Black, Gadget, sans-serif'                   => 'Arial Black',
        'Courier New, monospace'                            => 'Courier New',
        'Georgia, serif'                                    => 'Georgia',
        'Impact, Charcoal, sans-serif'                      => 'Impact',
        'Lucida Console, Monaco, monospace'                 => 'Lucida Console',
        'Lucida Sans Unicode, Lucida Grande, sans-serif'    => 'Lucida Sans Unicode',
        'MS Sans Serif, Tahoma, sans-serif'                 => 'MS Sans Serif',
        'MS Serif, New York, serif'                         => 'MS Serif',
        'Palatino Linotype, Book Antiqua, Palatino, serif'  => 'Palatino Linotype',
        'Tahoma, Geneva, sans-serif'                        => 'Tahoma',
        'Times New Roman, Times, serif'                     => 'Times New Roman',
        'Trebuchet MS, sans-serif'                          => 'Trebuchet MS',
        'Verdana, Geneva, sans-serif'                       => 'Verdana',

    );
    
}

/**
 * Render the CTA Trio on the frontpage.
 */
function ares_render_cta_trio() {

    $ares_options = ares_get_options(); ?>
    
    <div id="site-cta-wrap">
    
        <div id="site-cta" class="container <?php echo $ares_options['ares_slider_bool'] == 'yes' ? '' : 'no-slider'; ?>"><!-- #CTA boxes -->

            <div class="row">

                <div class="col-md-4 site-cta smartcat-animate fadeInUp" data-wow-delay=".2s">

                    <div class="icon-wrap center">
                        <a href="<?php echo esc_url( $ares_options['ares_cta1_url'] ) ?>">
                            <i class="<?php echo esc_attr( $ares_options['ares_cta1_icon'] ); ?> animated"></i>
                        </a>
                    </div>

                    <h3>
                        <?php echo esc_attr( $ares_options['ares_cta1_title'] ); ?>
                    </h3>

                    <p class="tagline">
                        <?php echo $ares_options['ares_cta1_text']; ?>
                    </p>

                    <p class="">
                        <a href="<?php echo esc_url( $ares_options['ares_cta1_url'] ) ?>">
                            <?php echo $ares_options['ares_cta1_button_text'];  ?>
                        </a>
                    </p>                                

                </div>

                <div class="col-md-4 site-cta smartcat-animate fadeInUp">

                    <div class="icon-wrap center">
                        <a href="<?php echo esc_url( $ares_options['ares_cta2_url'] ) ?>">
                            <i class="<?php echo esc_attr( $ares_options['ares_cta2_icon'] ); ?> animated"></i>
                        </a>
                    </div>

                    <h3>
                        <?php echo esc_attr( $ares_options['ares_cta2_title'] ); ?>
                    </h3>

                    <p class="tagline">
                        <?php echo $ares_options['ares_cta2_text']; ?>
                    </p>

                    <p class="">
                        <a href="<?php echo esc_url( $ares_options['ares_cta2_url'] ) ?>">
                            <?php echo $ares_options['ares_cta2_button_text'];  ?>
                        </a>
                    </p>                                

                </div>

                <div class="col-md-4 site-cta smartcat-animate fadeInUp" data-wow-delay=".3s">

                    <div class="icon-wrap center">
                        <a href="<?php echo esc_url( $ares_options['ares_cta3_url'] ) ?>">
                            <i class="<?php echo esc_attr( $ares_options['ares_cta3_icon'] ); ?> animated"></i>
                        </a>
                    </div>

                    <h3>
                        <?php echo esc_attr( $ares_options['ares_cta3_title'] ); ?>
                    </h3>

                    <p class="tagline">
                        <?php echo $ares_options['ares_cta3_text']; ?>
                    </p>

                    <p class="">
                        <a href="<?php echo esc_url( $ares_options['ares_cta3_url'] ) ?>">
                            <?php echo $ares_options['ares_cta3_button_text'];  ?>
                        </a>
                    </p>                                

                </div>

            </div>

        </div><!-- #CTA boxes -->
    
        <div class="clear"></div>
        
    </div>
    
<?php }
add_action( 'ares_cta_trio', 'ares_render_cta_trio' );


/**
 * Render the footer.
 */
function ares_render_footer() {
    
    $ares_options = ares_get_options(); ?>
    
    <footer id="colophon" class="site-footer <?php echo $ares_options['ares_frontpage_content_bool'] == 'no' ? 'no-top-margin' : ''; ?>" role="contentinfo">
        
        <?php if( $ares_options['ares_footer_cta'] == 'on' ) : ?>
    
            <div id="footer-callout">

                <div class="container">

                    <div class="row">

                        <div class="col-sm-8 text-left">
                            <h3 class="smartcat-animate fadeInUp"><?php echo $ares_options['ares_footer_cta_text']; ?></h3>
                        </div>

                        <div class="col-sm-4 text-right">
                            <a class="ares-button button-cta smartcat-animate fadeInUp" href="<?php echo $ares_options['ares_footer_button_url']; ?>">
                                <?php echo $ares_options['ares_footer_button_text']; ?>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
    
        <?php endif; ?>
        
        <?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
        
            <div class="footer-boxes container">

                <div class="row ">

                    <div class="col-md-12">

                        <div id="secondary" class="widget-area" role="complementary">

                            <?php dynamic_sidebar( 'sidebar-footer' ); ?>
            
                            <div class="clear"></div>
                            
                        </div>

                    </div>            

                </div>        

            </div>
        
        <?php endif; ?>
        
        <div class="site-info">
            
            <div class="container">
            
                <div class="row ">

                    <div class="col-xs-9 text-left">
                        
                        <span class="ares-copyright">
                            <?php echo $ares_options['ares_footer_text']; ?>
                        </span>
                        
                        <?php do_action( 'ares_designer' ); ?>
                        
                    </div>

                    <div class="col-xs-3 text-right">

                        <i class="scroll-top fa fa-chevron-up"></i>

                    </div>              
                    
                </div>
            
            </div>
            
        </div><!-- .site-info -->
        
    </footer><!-- #colophon -->
    
<?php }
add_action( 'ares_footer', 'ares_render_footer' );

add_action( 'ares_designer', 'ares_add_designer', 10 );
function ares_add_designer() { ?>
    
    <a href="https://smartcatdesign.net/" rel="designer" style="display: inline-block !important" class="rel">
        <?php printf( esc_html__( 'Designed by %s', 'ares' ), 'Smartcat' ); ?> 
        <img src="<?php echo get_template_directory_uri() . '/inc/images/cat_logo_mini.png'?>" alt="<?php printf( esc_attr__( '%s Logo', 'ares'), 'Smartcat' ); ?>" />
    </a>
    
<?php }

function ares_icons() {

    return array( 'fa fa-clock' => __('Select One', 'ares'), 'fa fa-500px' => __(' 500px', 'ares'), 'fa fa-amazon' => __(' amazon', 'ares'), 'fa fa-balance-scale' => __(' balance-scale', 'ares'), 'fa fa-battery-0' => __(' battery-0', 'ares'), 'fa fa-battery-1' => __(' battery-1', 'ares'), 'fa fa-battery-2' => __(' battery-2', 'ares'), 'fa fa-battery-3' => __(' battery-3', 'ares'), 'fa fa-battery-4' => __(' battery-4', 'ares'), 'fa fa-battery-empty' => __(' battery-empty', 'ares'), 'fa fa-battery-full' => __(' battery-full', 'ares'), 'fa fa-battery-half' => __(' battery-half', 'ares'), 'fa fa-battery-quarter' => __(' battery-quarter', 'ares'), 'fa fa-battery-three-quarters' => __(' battery-three-quarters', 'ares'), 'fa fa-black-tie' => __(' black-tie', 'ares'), 'fa fa-calendar-check-o' => __(' calendar-check-o', 'ares'), 'fa fa-calendar-minus-o' => __(' calendar-minus-o', 'ares'), 'fa fa-calendar-plus-o' => __(' calendar-plus-o', 'ares'), 'fa fa-calendar-times-o' => __(' calendar-times-o', 'ares'), 'fa fa-cc-diners-club' => __(' cc-diners-club', 'ares'), 'fa fa-cc-jcb' => __(' cc-jcb', 'ares'), 'fa fa-chrome' => __(' chrome', 'ares'), 'fa fa-clone' => __(' clone', 'ares'), 'fa fa-commenting' => __(' commenting', 'ares'), 'fa fa-commenting-o' => __(' commenting-o', 'ares'), 'fa fa-contao' => __(' contao', 'ares'), 'fa fa-creative-commons' => __(' creative-commons', 'ares'), 'fa fa-expeditedssl' => __(' expeditedssl', 'ares'), 'fa fa-firefox' => __(' firefox', 'ares'), 'fa fa-fonticons' => __(' fonticons', 'ares'), 'fa fa-genderless' => __(' genderless', 'ares'), 'fa fa-get-pocket' => __(' get-pocket', 'ares'), 'fa fa-gg' => __(' gg', 'ares'), 'fa fa-gg-circle' => __(' gg-circle', 'ares'), 'fa fa-hand-grab-o' => __(' hand-grab-o', 'ares'), 'fa fa-hand-lizard-o' => __(' hand-lizard-o', 'ares'), 'fa fa-hand-paper-o' => __(' hand-paper-o', 'ares'), 'fa fa-hand-peace-o' => __(' hand-peace-o', 'ares'), 'fa fa-hand-pointer-o' => __(' hand-pointer-o', 'ares'), 'fa fa-hand-rock-o' => __(' hand-rock-o', 'ares'), 'fa fa-hand-scissors-o' => __(' hand-scissors-o', 'ares'), 'fa fa-hand-spock-o' => __(' hand-spock-o', 'ares'), 'fa fa-hand-stop-o' => __(' hand-stop-o', 'ares'), 'fa fa-hourglass' => __(' hourglass', 'ares'), 'fa fa-hourglass-1' => __(' hourglass-1', 'ares'), 'fa fa-hourglass-2' => __(' hourglass-2', 'ares'), 'fa fa-hourglass-3' => __(' hourglass-3', 'ares'), 'fa fa-hourglass-end' => __(' hourglass-end', 'ares'), 'fa fa-hourglass-half' => __(' hourglass-half', 'ares'), 'fa fa-hourglass-o' => __(' hourglass-o', 'ares'), 'fa fa-hourglass-start' => __(' hourglass-start', 'ares'), 'fa fa-houzz' => __(' houzz', 'ares'), 'fa fa-i-cursor' => __(' i-cursor', 'ares'), 'fa fa-industry' => __(' industry', 'ares'), 'fa fa-internet-explorer' => __(' internet-explorer', 'ares'), 'fa fa-map' => __(' map', 'ares'), 'fa fa-map-o' => __(' map-o', 'ares'), 'fa fa-map-pin' => __(' map-pin', 'ares'), 'fa fa-map-signs' => __(' map-signs', 'ares'), 'fa fa-mouse-pointer' => __(' mouse-pointer', 'ares'), 'fa fa-object-group' => __(' object-group', 'ares'), 'fa fa-object-ungroup' => __(' object-ungroup', 'ares'), 'fa fa-odnoklassniki' => __(' odnoklassniki', 'ares'), 'fa fa-odnoklassniki-square' => __(' odnoklassniki-square', 'ares'), 'fa fa-opencart' => __(' opencart', 'ares'), 'fa fa-opera' => __(' opera', 'ares'), 'fa fa-optin-monster' => __(' optin-monster', 'ares'), 'fa fa-registered' => __(' registered', 'ares'), 'fa fa-safari' => __(' safari', 'ares'), 'fa fa-sticky-note' => __(' sticky-note', 'ares'), 'fa fa-sticky-note-o' => __(' sticky-note-o', 'ares'), 'fa fa-television' => __(' television', 'ares'), 'fa fa-trademark' => __(' trademark', 'ares'), 'fa fa-tripadvisor' => __(' tripadvisor', 'ares'), 'fa fa-tv' => __(' tv', 'ares'), 'fa fa-vimeo' => __(' vimeo', 'ares'), 'fa fa-wikipedia-w' => __(' wikipedia-w', 'ares'), 'fa fa-y-combinator' => __(' y-combinator', 'ares'), 'fa fa-yc' => __(' yc', 'ares'), 'fa fa-adjust' => __(' adjust', 'ares'), 'fa fa-anchor' => __(' anchor', 'ares'), 'fa fa-archive' => __(' archive', 'ares'), 'fa fa-area-chart' => __(' area-chart', 'ares'), 'fa fa-arrows' => __(' arrows', 'ares'), 'fa fa-arrows-h' => __(' arrows-h', 'ares'), 'fa fa-arrows-v' => __(' arrows-v', 'ares'), 'fa fa-asterisk' => __(' asterisk', 'ares'), 'fa fa-at' => __(' at', 'ares'), 'fa fa-automobile' => __(' automobile', 'ares'), 'fa fa-balance-scale' => __(' balance-scale', 'ares'), 'fa fa-ban' => __(' ban', 'ares'), 'fa fa-bank' => __(' bank', 'ares'), 'fa fa-bar-chart' => __(' bar-chart', 'ares'), 'fa fa-bar-chart-o' => __(' bar-chart-o', 'ares'), 'fa fa-barcode' => __(' barcode', 'ares'), 'fa fa-bars' => __(' bars', 'ares'), 'fa fa-battery-0' => __(' battery-0', 'ares'), 'fa fa-battery-1' => __(' battery-1', 'ares'), 'fa fa-battery-2' => __(' battery-2', 'ares'), 'fa fa-battery-3' => __(' battery-3', 'ares'), 'fa fa-battery-4' => __(' battery-4', 'ares'), 'fa fa-battery-empty' => __(' battery-empty', 'ares'), 'fa fa-battery-full' => __(' battery-full', 'ares'), 'fa fa-battery-half' => __(' battery-half', 'ares'), 'fa fa-battery-quarter' => __(' battery-quarter', 'ares'), 'fa fa-battery-three-quarters' => __(' battery-three-quarters', 'ares'), 'fa fa-bed' => __(' bed', 'ares'), 'fa fa-beer' => __(' beer', 'ares'), 'fa fa-bell' => __(' bell', 'ares'), 'fa fa-bell-o' => __(' bell-o', 'ares'), 'fa fa-bell-slash' => __(' bell-slash', 'ares'), 'fa fa-bell-slash-o' => __(' bell-slash-o', 'ares'), 'fa fa-bicycle' => __(' bicycle', 'ares'), 'fa fa-binoculars' => __(' binoculars', 'ares'), 'fa fa-birthday-cake' => __(' birthday-cake', 'ares'), 'fa fa-bolt' => __(' bolt', 'ares'), 'fa fa-bomb' => __(' bomb', 'ares'), 'fa fa-book' => __(' book', 'ares'), 'fa fa-bookmark' => __(' bookmark', 'ares'), 'fa fa-bookmark-o' => __(' bookmark-o', 'ares'), 'fa fa-briefcase' => __(' briefcase', 'ares'), 'fa fa-bug' => __(' bug', 'ares'), 'fa fa-building' => __(' building', 'ares'), 'fa fa-building-o' => __(' building-o', 'ares'), 'fa fa-bullhorn' => __(' bullhorn', 'ares'), 'fa fa-bullseye' => __(' bullseye', 'ares'), 'fa fa-bus' => __(' bus', 'ares'), 'fa fa-cab' => __(' cab', 'ares'), 'fa fa-calculator' => __(' calculator', 'ares'), 'fa fa-calendar' => __(' calendar', 'ares'), 'fa fa-calendar-check-o' => __(' calendar-check-o', 'ares'), 'fa fa-calendar-minus-o' => __(' calendar-minus-o', 'ares'), 'fa fa-calendar-o' => __(' calendar-o', 'ares'), 'fa fa-calendar-plus-o' => __(' calendar-plus-o', 'ares'), 'fa fa-calendar-times-o' => __(' calendar-times-o', 'ares'), 'fa fa-camera' => __(' camera', 'ares'), 'fa fa-camera-retro' => __(' camera-retro', 'ares'), 'fa fa-car' => __(' car', 'ares'), 'fa fa-caret-square-o-down' => __(' caret-square-o-down', 'ares'), 'fa fa-caret-square-o-left' => __(' caret-square-o-left', 'ares'), 'fa fa-caret-square-o-right' => __(' caret-square-o-right', 'ares'), 'fa fa-caret-square-o-up' => __(' caret-square-o-up', 'ares'), 'fa fa-cart-arrow-down' => __(' cart-arrow-down', 'ares'), 'fa fa-cart-plus' => __(' cart-plus', 'ares'), 'fa fa-cc' => __(' cc', 'ares'), 'fa fa-certificate' => __(' certificate', 'ares'), 'fa fa-check' => __(' check', 'ares'), 'fa fa-check-circle' => __(' check-circle', 'ares'), 'fa fa-check-circle-o' => __(' check-circle-o', 'ares'), 'fa fa-check-square' => __(' check-square', 'ares'), 'fa fa-check-square-o' => __(' check-square-o', 'ares'), 'fa fa-child' => __(' child', 'ares'), 'fa fa-circle' => __(' circle', 'ares'), 'fa fa-circle-o' => __(' circle-o', 'ares'), 'fa fa-circle-o-notch' => __(' circle-o-notch', 'ares'), 'fa fa-circle-thin' => __(' circle-thin', 'ares'), 'fa fa-clock-o' => __(' clock-o', 'ares'), 'fa fa-clone' => __(' clone', 'ares'), 'fa fa-close' => __(' close', 'ares'), 'fa fa-cloud' => __(' cloud', 'ares'), 'fa fa-cloud-download' => __(' cloud-download', 'ares'), 'fa fa-cloud-upload' => __(' cloud-upload', 'ares'), 'fa fa-code' => __(' code', 'ares'), 'fa fa-code-fork' => __(' code-fork', 'ares'), 'fa fa-coffee' => __(' coffee', 'ares'), 'fa fa-cog' => __(' cog', 'ares'), 'fa fa-cogs' => __(' cogs', 'ares'), 'fa fa-comment' => __(' comment', 'ares'), 'fa fa-comment-o' => __(' comment-o', 'ares'), 'fa fa-commenting' => __(' commenting', 'ares'), 'fa fa-commenting-o' => __(' commenting-o', 'ares'), 'fa fa-comments' => __(' comments', 'ares'), 'fa fa-comments-o' => __(' comments-o', 'ares'), 'fa fa-compass' => __(' compass', 'ares'), 'fa fa-copyright' => __(' copyright', 'ares'), 'fa fa-creative-commons' => __(' creative-commons', 'ares'), 'fa fa-credit-card' => __(' credit-card', 'ares'), 'fa fa-crop' => __(' crop', 'ares'), 'fa fa-crosshairs' => __(' crosshairs', 'ares'), 'fa fa-cube' => __(' cube', 'ares'), 'fa fa-cubes' => __(' cubes', 'ares'), 'fa fa-cutlery' => __(' cutlery', 'ares'), 'fa fa-dashboard' => __(' dashboard', 'ares'), 'fa fa-database' => __(' database', 'ares'), 'fa fa-desktop' => __(' desktop', 'ares'), 'fa fa-diamond' => __(' diamond', 'ares'), 'fa fa-dot-circle-o' => __(' dot-circle-o', 'ares'), 'fa fa-download' => __(' download', 'ares'), 'fa fa-edit' => __(' edit', 'ares'), 'fa fa-ellipsis-h' => __(' ellipsis-h', 'ares'), 'fa fa-ellipsis-v' => __(' ellipsis-v', 'ares'), 'fa fa-envelope' => __(' envelope', 'ares'), 'fa fa-envelope-o' => __(' envelope-o', 'ares'), 'fa fa-envelope-square' => __(' envelope-square', 'ares'), 'fa fa-eraser' => __(' eraser', 'ares'), 'fa fa-exchange' => __(' exchange', 'ares'), 'fa fa-exclamation' => __(' exclamation', 'ares'), 'fa fa-exclamation-circle' => __(' exclamation-circle', 'ares'), 'fa fa-exclamation-triangle' => __(' exclamation-triangle', 'ares'), 'fa fa-external-link' => __(' external-link', 'ares'), 'fa fa-external-link-square' => __(' external-link-square', 'ares'), 'fa fa-eye' => __(' eye', 'ares'), 'fa fa-eye-slash' => __(' eye-slash', 'ares'), 'fa fa-eyedropper' => __(' eyedropper', 'ares'), 'fa fa-fax' => __(' fax', 'ares'), 'fa fa-feed' => __(' feed', 'ares'), 'fa fa-female' => __(' female', 'ares'), 'fa fa-fighter-jet' => __(' fighter-jet', 'ares'), 'fa fa-file-archive-o' => __(' file-archive-o', 'ares'), 'fa fa-file-audio-o' => __(' file-audio-o', 'ares'), 'fa fa-file-code-o' => __(' file-code-o', 'ares'), 'fa fa-file-excel-o' => __(' file-excel-o', 'ares'), 'fa fa-file-image-o' => __(' file-image-o', 'ares'), 'fa fa-file-movie-o' => __(' file-movie-o', 'ares'), 'fa fa-file-pdf-o' => __(' file-pdf-o', 'ares'), 'fa fa-file-photo-o' => __(' file-photo-o', 'ares'), 'fa fa-file-picture-o' => __(' file-picture-o', 'ares'), 'fa fa-file-powerpoint-o' => __(' file-powerpoint-o', 'ares'), 'fa fa-file-sound-o' => __(' file-sound-o', 'ares'), 'fa fa-file-video-o' => __(' file-video-o', 'ares'), 'fa fa-file-word-o' => __(' file-word-o', 'ares'), 'fa fa-file-zip-o' => __(' file-zip-o', 'ares'), 'fa fa-film' => __(' film', 'ares'), 'fa fa-filter' => __(' filter', 'ares'), 'fa fa-fire' => __(' fire', 'ares'), 'fa fa-fire-extinguisher' => __(' fire-extinguisher', 'ares'), 'fa fa-flag' => __(' flag', 'ares'), 'fa fa-flag-checkered' => __(' flag-checkered', 'ares'), 'fa fa-flag-o' => __(' flag-o', 'ares'), 'fa fa-flash' => __(' flash', 'ares'), 'fa fa-flask' => __(' flask', 'ares'), 'fa fa-folder' => __(' folder', 'ares'), 'fa fa-folder-o' => __(' folder-o', 'ares'), 'fa fa-folder-open' => __(' folder-open', 'ares'), 'fa fa-folder-open-o' => __(' folder-open-o', 'ares'), 'fa fa-frown-o' => __(' frown-o', 'ares'), 'fa fa-futbol-o' => __(' futbol-o', 'ares'), 'fa fa-gamepad' => __(' gamepad', 'ares'), 'fa fa-gavel' => __(' gavel', 'ares'), 'fa fa-gear' => __(' gear', 'ares'), 'fa fa-gears' => __(' gears', 'ares'), 'fa fa-gift' => __(' gift', 'ares'), 'fa fa-glass' => __(' glass', 'ares'), 'fa fa-globe' => __(' globe', 'ares'), 'fa fa-graduation-cap' => __(' graduation-cap', 'ares'), 'fa fa-group' => __(' group', 'ares'), 'fa fa-hand-grab-o' => __(' hand-grab-o', 'ares'), 'fa fa-hand-lizard-o' => __(' hand-lizard-o', 'ares'), 'fa fa-hand-paper-o' => __(' hand-paper-o', 'ares'), 'fa fa-hand-peace-o' => __(' hand-peace-o', 'ares'), 'fa fa-hand-pointer-o' => __(' hand-pointer-o', 'ares'), 'fa fa-hand-rock-o' => __(' hand-rock-o', 'ares'), 'fa fa-hand-scissors-o' => __(' hand-scissors-o', 'ares'), 'fa fa-hand-spock-o' => __(' hand-spock-o', 'ares'), 'fa fa-hand-stop-o' => __(' hand-stop-o', 'ares'), 'fa fa-hdd-o' => __(' hdd-o', 'ares'), 'fa fa-headphones' => __(' headphones', 'ares'), 'fa fa-heart' => __(' heart', 'ares'), 'fa fa-heart-o' => __(' heart-o', 'ares'), 'fa fa-heartbeat' => __(' heartbeat', 'ares'), 'fa fa-history' => __(' history', 'ares'), 'fa fa-home' => __(' home', 'ares'), 'fa fa-hotel' => __(' hotel', 'ares'), 'fa fa-hourglass' => __(' hourglass', 'ares'), 'fa fa-hourglass-1' => __(' hourglass-1', 'ares'), 'fa fa-hourglass-2' => __(' hourglass-2', 'ares'), 'fa fa-hourglass-3' => __(' hourglass-3', 'ares'), 'fa fa-hourglass-end' => __(' hourglass-end', 'ares'), 'fa fa-hourglass-half' => __(' hourglass-half', 'ares'), 'fa fa-hourglass-o' => __(' hourglass-o', 'ares'), 'fa fa-hourglass-start' => __(' hourglass-start', 'ares'), 'fa fa-i-cursor' => __(' i-cursor', 'ares'), 'fa fa-image' => __(' image', 'ares'), 'fa fa-inbox' => __(' inbox', 'ares'), 'fa fa-industry' => __(' industry', 'ares'), 'fa fa-info' => __(' info', 'ares'), 'fa fa-info-circle' => __(' info-circle', 'ares'), 'fa fa-institution' => __(' institution', 'ares'), 'fa fa-key' => __(' key', 'ares'), 'fa fa-keyboard-o' => __(' keyboard-o', 'ares'), 'fa fa-language' => __(' language', 'ares'), 'fa fa-laptop' => __(' laptop', 'ares'), 'fa fa-leaf' => __(' leaf', 'ares'), 'fa fa-legal' => __(' legal', 'ares'), 'fa fa-lemon-o' => __(' lemon-o', 'ares'), 'fa fa-level-down' => __(' level-down', 'ares'), 'fa fa-level-up' => __(' level-up', 'ares'), 'fa fa-life-bouy' => __(' life-bouy', 'ares'), 'fa fa-life-buoy' => __(' life-buoy', 'ares'), 'fa fa-life-ring' => __(' life-ring', 'ares'), 'fa fa-life-saver' => __(' life-saver', 'ares'), 'fa fa-lightbulb-o' => __(' lightbulb-o', 'ares'), 'fa fa-line-chart' => __(' line-chart', 'ares'), 'fa fa-location-arrow' => __(' location-arrow', 'ares'), 'fa fa-lock' => __(' lock', 'ares'), 'fa fa-magic' => __(' magic', 'ares'), 'fa fa-magnet' => __(' magnet', 'ares'), 'fa fa-mail-forward' => __(' mail-forward', 'ares'), 'fa fa-mail-reply' => __(' mail-reply', 'ares'), 'fa fa-mail-reply-all' => __(' mail-reply-all', 'ares'), 'fa fa-male' => __(' male', 'ares'), 'fa fa-map' => __(' map', 'ares'), 'fa fa-map-marker' => __(' map-marker', 'ares'), 'fa fa-map-o' => __(' map-o', 'ares'), 'fa fa-map-pin' => __(' map-pin', 'ares'), 'fa fa-map-signs' => __(' map-signs', 'ares'), 'fa fa-meh-o' => __(' meh-o', 'ares'), 'fa fa-microphone' => __(' microphone', 'ares'), 'fa fa-microphone-slash' => __(' microphone-slash', 'ares'), 'fa fa-minus' => __(' minus', 'ares'), 'fa fa-minus-circle' => __(' minus-circle', 'ares'), 'fa fa-minus-square' => __(' minus-square', 'ares'), 'fa fa-minus-square-o' => __(' minus-square-o', 'ares'), 'fa fa-mobile' => __(' mobile', 'ares'), 'fa fa-mobile-phone' => __(' mobile-phone', 'ares'), 'fa fa-money' => __(' money', 'ares'), 'fa fa-moon-o' => __(' moon-o', 'ares'), 'fa fa-mortar-board' => __(' mortar-board', 'ares'), 'fa fa-motorcycle' => __(' motorcycle', 'ares'), 'fa fa-mouse-pointer' => __(' mouse-pointer', 'ares'), 'fa fa-music' => __(' music', 'ares'), 'fa fa-navicon' => __(' navicon', 'ares'), 'fa fa-newspaper-o' => __(' newspaper-o', 'ares'), 'fa fa-object-group' => __(' object-group', 'ares'), 'fa fa-object-ungroup' => __(' object-ungroup', 'ares'), 'fa fa-paint-brush' => __(' paint-brush', 'ares'), 'fa fa-paper-plane' => __(' paper-plane', 'ares'), 'fa fa-paper-plane-o' => __(' paper-plane-o', 'ares'), 'fa fa-paw' => __(' paw', 'ares'), 'fa fa-pencil' => __(' pencil', 'ares'), 'fa fa-pencil-square' => __(' pencil-square', 'ares'), 'fa fa-pencil-square-o' => __(' pencil-square-o', 'ares'), 'fa fa-phone' => __(' phone', 'ares'), 'fa fa-phone-square' => __(' phone-square', 'ares'), 'fa fa-photo' => __(' photo', 'ares'), 'fa fa-picture-o' => __(' picture-o', 'ares'), 'fa fa-pie-chart' => __(' pie-chart', 'ares'), 'fa fa-plane' => __(' plane', 'ares'), 'fa fa-plug' => __(' plug', 'ares'), 'fa fa-plus' => __(' plus', 'ares'), 'fa fa-plus-circle' => __(' plus-circle', 'ares'), 'fa fa-plus-square' => __(' plus-square', 'ares'), 'fa fa-plus-square-o' => __(' plus-square-o', 'ares'), 'fa fa-power-off' => __(' power-off', 'ares'), 'fa fa-print' => __(' print', 'ares'), 'fa fa-puzzle-piece' => __(' puzzle-piece', 'ares'), 'fa fa-qrcode' => __(' qrcode', 'ares'), 'fa fa-question' => __(' question', 'ares'), 'fa fa-question-circle' => __(' question-circle', 'ares'), 'fa fa-quote-left' => __(' quote-left', 'ares'), 'fa fa-quote-right' => __(' quote-right', 'ares'), 'fa fa-random' => __(' random', 'ares'), 'fa fa-recycle' => __(' recycle', 'ares'), 'fa fa-refresh' => __(' refresh', 'ares'), 'fa fa-registered' => __(' registered', 'ares'), 'fa fa-remove' => __(' remove', 'ares'), 'fa fa-reorder' => __(' reorder', 'ares'), 'fa fa-reply' => __(' reply', 'ares'), 'fa fa-reply-all' => __(' reply-all', 'ares'), 'fa fa-retweet' => __(' retweet', 'ares'), 'fa fa-road' => __(' road', 'ares'), 'fa fa-rocket' => __(' rocket', 'ares'), 'fa fa-rss' => __(' rss', 'ares'), 'fa fa-rss-square' => __(' rss-square', 'ares'), 'fa fa-search' => __(' search', 'ares'), 'fa fa-search-minus' => __(' search-minus', 'ares'), 'fa fa-search-plus' => __(' search-plus', 'ares'), 'fa fa-send' => __(' send', 'ares'), 'fa fa-send-o' => __(' send-o', 'ares'), 'fa fa-server' => __(' server', 'ares'), 'fa fa-share' => __(' share', 'ares'), 'fa fa-share-alt' => __(' share-alt', 'ares'), 'fa fa-share-alt-square' => __(' share-alt-square', 'ares'), 'fa fa-share-square' => __(' share-square', 'ares'), 'fa fa-share-square-o' => __(' share-square-o', 'ares'), 'fa fa-shield' => __(' shield', 'ares'), 'fa fa-ship' => __(' ship', 'ares'), 'fa fa-shopping-cart' => __(' shopping-cart', 'ares'), 'fa fa-sign-in' => __(' sign-in', 'ares'), 'fa fa-sign-out' => __(' sign-out', 'ares'), 'fa fa-signal' => __(' signal', 'ares'), 'fa fa-sitemap' => __(' sitemap', 'ares'), 'fa fa-sliders' => __(' sliders', 'ares'), 'fa fa-smile-o' => __(' smile-o', 'ares'), 'fa fa-soccer-ball-o' => __(' soccer-ball-o', 'ares'), 'fa fa-sort' => __(' sort', 'ares'), 'fa fa-sort-alpha-asc' => __(' sort-alpha-asc', 'ares'), 'fa fa-sort-alpha-desc' => __(' sort-alpha-desc', 'ares'), 'fa fa-sort-amount-asc' => __(' sort-amount-asc', 'ares'), 'fa fa-sort-amount-desc' => __(' sort-amount-desc', 'ares'), 'fa fa-sort-asc' => __(' sort-asc', 'ares'), 'fa fa-sort-desc' => __(' sort-desc', 'ares'), 'fa fa-sort-down' => __(' sort-down', 'ares'), 'fa fa-sort-numeric-asc' => __(' sort-numeric-asc', 'ares'), 'fa fa-sort-numeric-desc' => __(' sort-numeric-desc', 'ares'), 'fa fa-sort-up' => __(' sort-up', 'ares'), 'fa fa-space-shuttle' => __(' space-shuttle', 'ares'), 'fa fa-spinner' => __(' spinner', 'ares'), 'fa fa-spoon' => __(' spoon', 'ares'), 'fa fa-square' => __(' square', 'ares'), 'fa fa-square-o' => __(' square-o', 'ares'), 'fa fa-star' => __(' star', 'ares'), 'fa fa-star-half' => __(' star-half', 'ares'), 'fa fa-star-half-empty' => __(' star-half-empty', 'ares'), 'fa fa-star-half-full' => __(' star-half-full', 'ares'), 'fa fa-star-half-o' => __(' star-half-o', 'ares'), 'fa fa-star-o' => __(' star-o', 'ares'), 'fa fa-sticky-note' => __(' sticky-note', 'ares'), 'fa fa-sticky-note-o' => __(' sticky-note-o', 'ares'), 'fa fa-street-view' => __(' street-view', 'ares'), 'fa fa-suitcase' => __(' suitcase', 'ares'), 'fa fa-sun-o' => __(' sun-o', 'ares'), 'fa fa-support' => __(' support', 'ares'), 'fa fa-tablet' => __(' tablet', 'ares'), 'fa fa-tachometer' => __(' tachometer', 'ares'), 'fa fa-tag' => __(' tag', 'ares'), 'fa fa-tags' => __(' tags', 'ares'), 'fa fa-tasks' => __(' tasks', 'ares'), 'fa fa-taxi' => __(' taxi', 'ares'), 'fa fa-television' => __(' television', 'ares'), 'fa fa-terminal' => __(' terminal', 'ares'), 'fa fa-thumb-tack' => __(' thumb-tack', 'ares'), 'fa fa-thumbs-down' => __(' thumbs-down', 'ares'), 'fa fa-thumbs-o-down' => __(' thumbs-o-down', 'ares'), 'fa fa-thumbs-o-up' => __(' thumbs-o-up', 'ares'), 'fa fa-thumbs-up' => __(' thumbs-up', 'ares'), 'fa fa-ticket' => __(' ticket', 'ares'), 'fa fa-times' => __(' times', 'ares'), 'fa fa-times-circle' => __(' times-circle', 'ares'), 'fa fa-times-circle-o' => __(' times-circle-o', 'ares'), 'fa fa-tint' => __(' tint', 'ares'), 'fa fa-toggle-down' => __(' toggle-down', 'ares'), 'fa fa-toggle-left' => __(' toggle-left', 'ares'), 'fa fa-toggle-off' => __(' toggle-off', 'ares'), 'fa fa-toggle-on' => __(' toggle-on', 'ares'), 'fa fa-toggle-right' => __(' toggle-right', 'ares'), 'fa fa-toggle-up' => __(' toggle-up', 'ares'), 'fa fa-trademark' => __(' trademark', 'ares'), 'fa fa-trash' => __(' trash', 'ares'), 'fa fa-trash-o' => __(' trash-o', 'ares'), 'fa fa-tree' => __(' tree', 'ares'), 'fa fa-trophy' => __(' trophy', 'ares'), 'fa fa-truck' => __(' truck', 'ares'), 'fa fa-tty' => __(' tty', 'ares'), 'fa fa-tv' => __(' tv', 'ares'), 'fa fa-umbrella' => __(' umbrella', 'ares'), 'fa fa-university' => __(' university', 'ares'), 'fa fa-unlock' => __(' unlock', 'ares'), 'fa fa-unlock-alt' => __(' unlock-alt', 'ares'), 'fa fa-unsorted' => __(' unsorted', 'ares'), 'fa fa-upload' => __(' upload', 'ares'), 'fa fa-user' => __(' user', 'ares'), 'fa fa-user-plus' => __(' user-plus', 'ares'), 'fa fa-user-secret' => __(' user-secret', 'ares'), 'fa fa-user-times' => __(' user-times', 'ares'), 'fa fa-users' => __(' users', 'ares'), 'fa fa-video-camera' => __(' video-camera', 'ares'), 'fa fa-volume-down' => __(' volume-down', 'ares'), 'fa fa-volume-off' => __(' volume-off', 'ares'), 'fa fa-volume-up' => __(' volume-up', 'ares'), 'fa fa-warning' => __(' warning', 'ares'), 'fa fa-wheelchair' => __(' wheelchair', 'ares'), 'fa fa-wifi' => __(' wifi', 'ares'), 'fa fa-wrench' => __(' wrench', 'ares'), 'fa fa-hand-grab-o' => __(' hand-grab-o', 'ares'), 'fa fa-hand-lizard-o' => __(' hand-lizard-o', 'ares'), 'fa fa-hand-o-down' => __(' hand-o-down', 'ares'), 'fa fa-hand-o-left' => __(' hand-o-left', 'ares'), 'fa fa-hand-o-right' => __(' hand-o-right', 'ares'), 'fa fa-hand-o-up' => __(' hand-o-up', 'ares'), 'fa fa-hand-paper-o' => __(' hand-paper-o', 'ares'), 'fa fa-hand-peace-o' => __(' hand-peace-o', 'ares'), 'fa fa-hand-pointer-o' => __(' hand-pointer-o', 'ares'), 'fa fa-hand-rock-o' => __(' hand-rock-o', 'ares'), 'fa fa-hand-scissors-o' => __(' hand-scissors-o', 'ares'), 'fa fa-hand-spock-o' => __(' hand-spock-o', 'ares'), 'fa fa-hand-stop-o' => __(' hand-stop-o', 'ares'), 'fa fa-thumbs-down' => __(' thumbs-down', 'ares'), 'fa fa-thumbs-o-down' => __(' thumbs-o-down', 'ares'), 'fa fa-thumbs-o-up' => __(' thumbs-o-up', 'ares'), 'fa fa-thumbs-up' => __(' thumbs-up', 'ares'), 'fa fa-ambulance' => __(' ambulance', 'ares'), 'fa fa-automobile' => __(' automobile', 'ares'), 'fa fa-bicycle' => __(' bicycle', 'ares'), 'fa fa-bus' => __(' bus', 'ares'), 'fa fa-cab' => __(' cab', 'ares'), 'fa fa-car' => __(' car', 'ares'), 'fa fa-fighter-jet' => __(' fighter-jet', 'ares'), 'fa fa-motorcycle' => __(' motorcycle', 'ares'), 'fa fa-plane' => __(' plane', 'ares'), 'fa fa-rocket' => __(' rocket', 'ares'), 'fa fa-ship' => __(' ship', 'ares'), 'fa fa-space-shuttle' => __(' space-shuttle', 'ares'), 'fa fa-subway' => __(' subway', 'ares'), 'fa fa-taxi' => __(' taxi', 'ares'), 'fa fa-train' => __(' train', 'ares'), 'fa fa-truck' => __(' truck', 'ares'), 'fa fa-wheelchair' => __(' wheelchair', 'ares'), 'fa fa-genderless' => __(' genderless', 'ares'), 'fa fa-intersex' => __(' intersex', 'ares'), 'fa fa-mars' => __(' mars', 'ares'), 'fa fa-mars-double' => __(' mars-double', 'ares'), 'fa fa-mars-stroke' => __(' mars-stroke', 'ares'), 'fa fa-mars-stroke-h' => __(' mars-stroke-h', 'ares'), 'fa fa-mars-stroke-v' => __(' mars-stroke-v', 'ares'), 'fa fa-mercury' => __(' mercury', 'ares'), 'fa fa-neuter' => __(' neuter', 'ares'), 'fa fa-transgender' => __(' transgender', 'ares'), 'fa fa-transgender-alt' => __(' transgender-alt', 'ares'), 'fa fa-venus' => __(' venus', 'ares'), 'fa fa-venus-double' => __(' venus-double', 'ares'), 'fa fa-venus-mars' => __(' venus-mars', 'ares'), 'fa fa-file' => __(' file', 'ares'), 'fa fa-file-archive-o' => __(' file-archive-o', 'ares'), 'fa fa-file-audio-o' => __(' file-audio-o', 'ares'), 'fa fa-file-code-o' => __(' file-code-o', 'ares'), 'fa fa-file-excel-o' => __(' file-excel-o', 'ares'), 'fa fa-file-image-o' => __(' file-image-o', 'ares'), 'fa fa-file-movie-o' => __(' file-movie-o', 'ares'), 'fa fa-file-o' => __(' file-o', 'ares'), 'fa fa-file-pdf-o' => __(' file-pdf-o', 'ares'), 'fa fa-file-photo-o' => __(' file-photo-o', 'ares'), 'fa fa-file-picture-o' => __(' file-picture-o', 'ares'), 'fa fa-file-powerpoint-o' => __(' file-powerpoint-o', 'ares'), 'fa fa-file-sound-o' => __(' file-sound-o', 'ares'), 'fa fa-file-text' => __(' file-text', 'ares'), 'fa fa-file-text-o' => __(' file-text-o', 'ares'), 'fa fa-file-video-o' => __(' file-video-o', 'ares'), 'fa fa-file-word-o' => __(' file-word-o', 'ares'), 'fa fa-file-zip-o' => __(' file-zip-o', 'ares'), 'fa fa-circle-o-notch' => __(' circle-o-notch', 'ares'), 'fa fa-cog' => __(' cog', 'ares'), 'fa fa-gear' => __(' gear', 'ares'), 'fa fa-refresh' => __(' refresh', 'ares'), 'fa fa-spinner' => __(' spinner', 'ares'), 'fa fa-check-square' => __(' check-square', 'ares'), 'fa fa-check-square-o' => __(' check-square-o', 'ares'), 'fa fa-circle' => __(' circle', 'ares'), 'fa fa-circle-o' => __(' circle-o', 'ares'), 'fa fa-dot-circle-o' => __(' dot-circle-o', 'ares'), 'fa fa-minus-square' => __(' minus-square', 'ares'), 'fa fa-minus-square-o' => __(' minus-square-o', 'ares'), 'fa fa-plus-square' => __(' plus-square', 'ares'), 'fa fa-plus-square-o' => __(' plus-square-o', 'ares'), 'fa fa-square' => __(' square', 'ares'), 'fa fa-square-o' => __(' square-o', 'ares'), 'fa fa-cc-amex' => __(' cc-amex', 'ares'), 'fa fa-cc-diners-club' => __(' cc-diners-club', 'ares'), 'fa fa-cc-discover' => __(' cc-discover', 'ares'), 'fa fa-cc-jcb' => __(' cc-jcb', 'ares'), 'fa fa-cc-mastercard' => __(' cc-mastercard', 'ares'), 'fa fa-cc-paypal' => __(' cc-paypal', 'ares'), 'fa fa-cc-stripe' => __(' cc-stripe', 'ares'), 'fa fa-cc-visa' => __(' cc-visa', 'ares'), 'fa fa-credit-card' => __(' credit-card', 'ares'), 'fa fa-google-wallet' => __(' google-wallet', 'ares'), 'fa fa-paypal' => __(' paypal', 'ares'), 'fa fa-area-chart' => __(' area-chart', 'ares'), 'fa fa-bar-chart' => __(' bar-chart', 'ares'), 'fa fa-bar-chart-o' => __(' bar-chart-o', 'ares'), 'fa fa-line-chart' => __(' line-chart', 'ares'), 'fa fa-pie-chart' => __(' pie-chart', 'ares'), 'fa fa-bitcoin' => __(' bitcoin', 'ares'), 'fa fa-btc' => __(' btc', 'ares'), 'fa fa-cny' => __(' cny', 'ares'), 'fa fa-dollar' => __(' dollar', 'ares'), 'fa fa-eur' => __(' eur', 'ares'), 'fa fa-euro' => __(' euro', 'ares'), 'fa fa-gbp' => __(' gbp', 'ares'), 'fa fa-gg' => __(' gg', 'ares'), 'fa fa-gg-circle' => __(' gg-circle', 'ares'), 'fa fa-ils' => __(' ils', 'ares'), 'fa fa-inr' => __(' inr', 'ares'), 'fa fa-jpy' => __(' jpy', 'ares'), 'fa fa-krw' => __(' krw', 'ares'), 'fa fa-money' => __(' money', 'ares'), 'fa fa-rmb' => __(' rmb', 'ares'), 'fa fa-rouble' => __(' rouble', 'ares'), 'fa fa-rub' => __(' rub', 'ares'), 'fa fa-ruble' => __(' ruble', 'ares'), 'fa fa-rupee' => __(' rupee', 'ares'), 'fa fa-shekel' => __(' shekel', 'ares'), 'fa fa-sheqel' => __(' sheqel', 'ares'), 'fa fa-try' => __(' try', 'ares'), 'fa fa-turkish-lira' => __(' turkish-lira', 'ares'), 'fa fa-usd' => __(' usd', 'ares'), 'fa fa-won' => __(' won', 'ares'), 'fa fa-yen' => __(' yen', 'ares'), 'fa fa-align-center' => __(' align-center', 'ares'), 'fa fa-align-justify' => __(' align-justify', 'ares'), 'fa fa-align-left' => __(' align-left', 'ares'), 'fa fa-align-right' => __(' align-right', 'ares'), 'fa fa-bold' => __(' bold', 'ares'), 'fa fa-chain' => __(' chain', 'ares'), 'fa fa-chain-broken' => __(' chain-broken', 'ares'), 'fa fa-clipboard' => __(' clipboard', 'ares'), 'fa fa-columns' => __(' columns', 'ares'), 'fa fa-copy' => __(' copy', 'ares'), 'fa fa-cut' => __(' cut', 'ares'), 'fa fa-dedent' => __(' dedent', 'ares'), 'fa fa-eraser' => __(' eraser', 'ares'), 'fa fa-file' => __(' file', 'ares'), 'fa fa-file-o' => __(' file-o', 'ares'), 'fa fa-file-text' => __(' file-text', 'ares'), 'fa fa-file-text-o' => __(' file-text-o', 'ares'), 'fa fa-files-o' => __(' files-o', 'ares'), 'fa fa-floppy-o' => __(' floppy-o', 'ares'), 'fa fa-font' => __(' font', 'ares'), 'fa fa-header' => __(' header', 'ares'), 'fa fa-indent' => __(' indent', 'ares'), 'fa fa-italic' => __(' italic', 'ares'), 'fa fa-link' => __(' link', 'ares'), 'fa fa-list' => __(' list', 'ares'), 'fa fa-list-alt' => __(' list-alt', 'ares'), 'fa fa-list-ol' => __(' list-ol', 'ares'), 'fa fa-list-ul' => __(' list-ul', 'ares'), 'fa fa-outdent' => __(' outdent', 'ares'), 'fa fa-paperclip' => __(' paperclip', 'ares'), 'fa fa-paragraph' => __(' paragraph', 'ares'), 'fa fa-paste' => __(' paste', 'ares'), 'fa fa-repeat' => __(' repeat', 'ares'), 'fa fa-rotate-left' => __(' rotate-left', 'ares'), 'fa fa-rotate-right' => __(' rotate-right', 'ares'), 'fa fa-save' => __(' save', 'ares'), 'fa fa-scissors' => __(' scissors', 'ares'), 'fa fa-strikethrough' => __(' strikethrough', 'ares'), 'fa fa-subscript' => __(' subscript', 'ares'), 'fa fa-superscript' => __(' superscript', 'ares'), 'fa fa-table' => __(' table', 'ares'), 'fa fa-text-height' => __(' text-height', 'ares'), 'fa fa-text-width' => __(' text-width', 'ares'), 'fa fa-th' => __(' th', 'ares'), 'fa fa-th-large' => __(' th-large', 'ares'), 'fa fa-th-list' => __(' th-list', 'ares'), 'fa fa-underline' => __(' underline', 'ares'), 'fa fa-undo' => __(' undo', 'ares'), 'fa fa-unlink' => __(' unlink', 'ares'), 'fa fa-angle-double-down' => __(' angle-double-down', 'ares'), 'fa fa-angle-double-left' => __(' angle-double-left', 'ares'), 'fa fa-angle-double-right' => __(' angle-double-right', 'ares'), 'fa fa-angle-double-up' => __(' angle-double-up', 'ares'), 'fa fa-angle-down' => __(' angle-down', 'ares'), 'fa fa-angle-left' => __(' angle-left', 'ares'), 'fa fa-angle-right' => __(' angle-right', 'ares'), 'fa fa-angle-up' => __(' angle-up', 'ares'), 'fa fa-arrow-circle-down' => __(' arrow-circle-down', 'ares'), 'fa fa-arrow-circle-left' => __(' arrow-circle-left', 'ares'), 'fa fa-arrow-circle-o-down' => __(' arrow-circle-o-down', 'ares'), 'fa fa-arrow-circle-o-left' => __(' arrow-circle-o-left', 'ares'), 'fa fa-arrow-circle-o-right' => __(' arrow-circle-o-right', 'ares'), 'fa fa-arrow-circle-o-up' => __(' arrow-circle-o-up', 'ares'), 'fa fa-arrow-circle-right' => __(' arrow-circle-right', 'ares'), 'fa fa-arrow-circle-up' => __(' arrow-circle-up', 'ares'), 'fa fa-arrow-down' => __(' arrow-down', 'ares'), 'fa fa-arrow-left' => __(' arrow-left', 'ares'), 'fa fa-arrow-right' => __(' arrow-right', 'ares'), 'fa fa-arrow-up' => __(' arrow-up', 'ares'), 'fa fa-arrows' => __(' arrows', 'ares'), 'fa fa-arrows-alt' => __(' arrows-alt', 'ares'), 'fa fa-arrows-h' => __(' arrows-h', 'ares'), 'fa fa-arrows-v' => __(' arrows-v', 'ares'), 'fa fa-caret-down' => __(' caret-down', 'ares'), 'fa fa-caret-left' => __(' caret-left', 'ares'), 'fa fa-caret-right' => __(' caret-right', 'ares'), 'fa fa-caret-square-o-down' => __(' caret-square-o-down', 'ares'), 'fa fa-caret-square-o-left' => __(' caret-square-o-left', 'ares'), 'fa fa-caret-square-o-right' => __(' caret-square-o-right', 'ares'), 'fa fa-caret-square-o-up' => __(' caret-square-o-up', 'ares'), 'fa fa-caret-up' => __(' caret-up', 'ares'), 'fa fa-chevron-circle-down' => __(' chevron-circle-down', 'ares'), 'fa fa-chevron-circle-left' => __(' chevron-circle-left', 'ares'), 'fa fa-chevron-circle-right' => __(' chevron-circle-right', 'ares'), 'fa fa-chevron-circle-up' => __(' chevron-circle-up', 'ares'), 'fa fa-chevron-down' => __(' chevron-down', 'ares'), 'fa fa-chevron-left' => __(' chevron-left', 'ares'), 'fa fa-chevron-right' => __(' chevron-right', 'ares'), 'fa fa-chevron-up' => __(' chevron-up', 'ares'), 'fa fa-exchange' => __(' exchange', 'ares'), 'fa fa-hand-o-down' => __(' hand-o-down', 'ares'), 'fa fa-hand-o-left' => __(' hand-o-left', 'ares'), 'fa fa-hand-o-right' => __(' hand-o-right', 'ares'), 'fa fa-hand-o-up' => __(' hand-o-up', 'ares'), 'fa fa-long-arrow-down' => __(' long-arrow-down', 'ares'), 'fa fa-long-arrow-left' => __(' long-arrow-left', 'ares'), 'fa fa-long-arrow-right' => __(' long-arrow-right', 'ares'), 'fa fa-long-arrow-up' => __(' long-arrow-up', 'ares'), 'fa fa-toggle-down' => __(' toggle-down', 'ares'), 'fa fa-toggle-left' => __(' toggle-left', 'ares'), 'fa fa-toggle-right' => __(' toggle-right', 'ares'), 'fa fa-toggle-up' => __(' toggle-up', 'ares'), 'fa fa-arrows-alt' => __(' arrows-alt', 'ares'), 'fa fa-backward' => __(' backward', 'ares'), 'fa fa-compress' => __(' compress', 'ares'), 'fa fa-eject' => __(' eject', 'ares'), 'fa fa-expand' => __(' expand', 'ares'), 'fa fa-fast-backward' => __(' fast-backward', 'ares'), 'fa fa-fast-forward' => __(' fast-forward', 'ares'), 'fa fa-forward' => __(' forward', 'ares'), 'fa fa-pause' => __(' pause', 'ares'), 'fa fa-play' => __(' play', 'ares'), 'fa fa-play-circle' => __(' play-circle', 'ares'), 'fa fa-play-circle-o' => __(' play-circle-o', 'ares'), 'fa fa-random' => __(' random', 'ares'), 'fa fa-step-backward' => __(' step-backward', 'ares'), 'fa fa-step-forward' => __(' step-forward', 'ares'), 'fa fa-stop' => __(' stop', 'ares'), 'fa fa-youtube-play' => __(' youtube-play', 'ares'), 'fa fa-500px' => __(' 500px', 'ares'), 'fa fa-adn' => __(' adn', 'ares'), 'fa fa-amazon' => __(' amazon', 'ares'), 'fa fa-android' => __(' android', 'ares'), 'fa fa-angellist' => __(' angellist', 'ares'), 'fa fa-apple' => __(' apple', 'ares'), 'fa fa-behance' => __(' behance', 'ares'), 'fa fa-behance-square' => __(' behance-square', 'ares'), 'fa fa-bitbucket' => __(' bitbucket', 'ares'), 'fa fa-bitbucket-square' => __(' bitbucket-square', 'ares'), 'fa fa-bitcoin' => __(' bitcoin', 'ares'), 'fa fa-black-tie' => __(' black-tie', 'ares'), 'fa fa-btc' => __(' btc', 'ares'), 'fa fa-buysellads' => __(' buysellads', 'ares'), 'fa fa-cc-amex' => __(' cc-amex', 'ares'), 'fa fa-cc-diners-club' => __(' cc-diners-club', 'ares'), 'fa fa-cc-discover' => __(' cc-discover', 'ares'), 'fa fa-cc-jcb' => __(' cc-jcb', 'ares'), 'fa fa-cc-mastercard' => __(' cc-mastercard', 'ares'), 'fa fa-cc-paypal' => __(' cc-paypal', 'ares'), 'fa fa-cc-stripe' => __(' cc-stripe', 'ares'), 'fa fa-cc-visa' => __(' cc-visa', 'ares'), 'fa fa-chrome' => __(' chrome', 'ares'), 'fa fa-codepen' => __(' codepen', 'ares'), 'fa fa-connectdevelop' => __(' connectdevelop', 'ares'), 'fa fa-contao' => __(' contao', 'ares'), 'fa fa-css3' => __(' css3', 'ares'), 'fa fa-dashcube' => __(' dashcube', 'ares'), 'fa fa-delicious' => __(' delicious', 'ares'), 'fa fa-deviantart' => __(' deviantart', 'ares'), 'fa fa-digg' => __(' digg', 'ares'), 'fa fa-dribbble' => __(' dribbble', 'ares'), 'fa fa-dropbox' => __(' dropbox', 'ares'), 'fa fa-drupal' => __(' drupal', 'ares'), 'fa fa-empire' => __(' empire', 'ares'), 'fa fa-expeditedssl' => __(' expeditedssl', 'ares'), 'fa fa-facebook' => __(' facebook', 'ares'), 'fa fa-facebook-f' => __(' facebook-f', 'ares'), 'fa fa-facebook-official' => __(' facebook-official', 'ares'), 'fa fa-facebook-square' => __(' facebook-square', 'ares'), 'fa fa-firefox' => __(' firefox', 'ares'), 'fa fa-flickr' => __(' flickr', 'ares'), 'fa fa-fonticons' => __(' fonticons', 'ares'), 'fa fa-forumbee' => __(' forumbee', 'ares'), 'fa fa-foursquare' => __(' foursquare', 'ares'), 'fa fa-ge' => __(' ge', 'ares'), 'fa fa-get-pocket' => __(' get-pocket', 'ares'), 'fa fa-gg' => __(' gg', 'ares'), 'fa fa-gg-circle' => __(' gg-circle', 'ares'), 'fa fa-git' => __(' git', 'ares'), 'fa fa-git-square' => __(' git-square', 'ares'), 'fa fa-github' => __(' github', 'ares'), 'fa fa-github-alt' => __(' github-alt', 'ares'), 'fa fa-github-square' => __(' github-square', 'ares'), 'fa fa-gittip' => __(' gittip', 'ares'), 'fa fa-google' => __(' google', 'ares'), 'fa fa-google-plus' => __(' google-plus', 'ares'), 'fa fa-google-plus-square' => __(' google-plus-square', 'ares'), 'fa fa-google-wallet' => __(' google-wallet', 'ares'), 'fa fa-gratipay' => __(' gratipay', 'ares'), 'fa fa-hacker-news' => __(' hacker-news', 'ares'), 'fa fa-houzz' => __(' houzz', 'ares'), 'fa fa-html5' => __(' html5', 'ares'), 'fa fa-instagram' => __(' instagram', 'ares'), 'fa fa-internet-explorer' => __(' internet-explorer', 'ares'), 'fa fa-ioxhost' => __(' ioxhost', 'ares'), 'fa fa-joomla' => __(' joomla', 'ares'), 'fa fa-jsfiddle' => __(' jsfiddle', 'ares'), 'fa fa-lastfm' => __(' lastfm', 'ares'), 'fa fa-lastfm-square' => __(' lastfm-square', 'ares'), 'fa fa-leanpub' => __(' leanpub', 'ares'), 'fa fa-linkedin' => __(' linkedin', 'ares'), 'fa fa-linkedin-square' => __(' linkedin-square', 'ares'), 'fa fa-linux' => __(' linux', 'ares'), 'fa fa-maxcdn' => __(' maxcdn', 'ares'), 'fa fa-meanpath' => __(' meanpath', 'ares'), 'fa fa-medium' => __(' medium', 'ares'), 'fa fa-odnoklassniki' => __(' odnoklassniki', 'ares'), 'fa fa-odnoklassniki-square' => __(' odnoklassniki-square', 'ares'), 'fa fa-opencart' => __(' opencart', 'ares'), 'fa fa-openid' => __(' openid', 'ares'), 'fa fa-opera' => __(' opera', 'ares'), 'fa fa-optin-monster' => __(' optin-monster', 'ares'), 'fa fa-pagelines' => __(' pagelines', 'ares'), 'fa fa-paypal' => __(' paypal', 'ares'), 'fa fa-pied-piper' => __(' pied-piper', 'ares'), 'fa fa-pied-piper-alt' => __(' pied-piper-alt', 'ares'), 'fa fa-pinterest' => __(' pinterest', 'ares'), 'fa fa-pinterest-p' => __(' pinterest-p', 'ares'), 'fa fa-pinterest-square' => __(' pinterest-square', 'ares'), 'fa fa-qq' => __(' qq', 'ares'), 'fa fa-ra' => __(' ra', 'ares'), 'fa fa-rebel' => __(' rebel', 'ares'), 'fa fa-reddit' => __(' reddit', 'ares'), 'fa fa-reddit-square' => __(' reddit-square', 'ares'), 'fa fa-renren' => __(' renren', 'ares'), 'fa fa-safari' => __(' safari', 'ares'), 'fa fa-sellsy' => __(' sellsy', 'ares'), 'fa fa-share-alt' => __(' share-alt', 'ares'), 'fa fa-share-alt-square' => __(' share-alt-square', 'ares'), 'fa fa-shirtsinbulk' => __(' shirtsinbulk', 'ares'), 'fa fa-simplybuilt' => __(' simplybuilt', 'ares'), 'fa fa-skyatlas' => __(' skyatlas', 'ares'), 'fa fa-skype' => __(' skype', 'ares'), 'fa fa-slack' => __(' slack', 'ares'), 'fa fa-slideshare' => __(' slideshare', 'ares'), 'fa fa-soundcloud' => __(' soundcloud', 'ares'), 'fa fa-spotify' => __(' spotify', 'ares'), 'fa fa-stack-exchange' => __(' stack-exchange', 'ares'), 'fa fa-stack-overflow' => __(' stack-overflow', 'ares'), 'fa fa-steam' => __(' steam', 'ares'), 'fa fa-steam-square' => __(' steam-square', 'ares'), 'fa fa-stumbleupon' => __(' stumbleupon', 'ares'), 'fa fa-stumbleupon-circle' => __(' stumbleupon-circle', 'ares'), 'fa fa-tencent-weibo' => __(' tencent-weibo', 'ares'), 'fa fa-trello' => __(' trello', 'ares'), 'fa fa-tripadvisor' => __(' tripadvisor', 'ares'), 'fa fa-tumblr' => __(' tumblr', 'ares'), 'fa fa-tumblr-square' => __(' tumblr-square', 'ares'), 'fa fa-twitch' => __(' twitch', 'ares'), 'fa fa-twitter' => __(' twitter', 'ares'), 'fa fa-twitter-square' => __(' twitter-square', 'ares'), 'fa fa-viacoin' => __(' viacoin', 'ares'), 'fa fa-vimeo' => __(' vimeo', 'ares'), 'fa fa-vimeo-square' => __(' vimeo-square', 'ares'), 'fa fa-vine' => __(' vine', 'ares'), 'fa fa-vk' => __(' vk', 'ares'), 'fa fa-wechat' => __(' wechat', 'ares'), 'fa fa-weibo' => __(' weibo', 'ares'), 'fa fa-weixin' => __(' weixin', 'ares'), 'fa fa-whatsapp' => __(' whatsapp', 'ares'), 'fa fa-wikipedia-w' => __(' wikipedia-w', 'ares'), 'fa fa-windows' => __(' windows', 'ares'), 'fa fa-wordpress' => __(' wordpress', 'ares'), 'fa fa-xing' => __(' xing', 'ares'), 'fa fa-xing-square' => __(' xing-square', 'ares'), 'fa fa-y-combinator' => __(' y-combinator', 'ares'), 'fa fa-y-combinator-square' => __(' y-combinator-square', 'ares'), 'fa fa-yahoo' => __(' yahoo', 'ares'), 'fa fa-yc' => __(' yc', 'ares'), 'fa fa-yc-square' => __(' yc-square', 'ares'), 'fa fa-yelp' => __(' yelp', 'ares'), 'fa fa-youtube' => __(' youtube', 'ares'), 'fa fa-youtube-play' => __(' youtube-play', 'ares'), 'fa fa-youtube-square' => __(' youtube-square', 'ares'), 'fa fa-ambulance' => __(' ambulance', 'ares'), 'fa fa-h-square' => __(' h-square', 'ares'), 'fa fa-heart' => __(' heart', 'ares'), 'fa fa-heart-o' => __(' heart-o', 'ares'), 'fa fa-heartbeat' => __(' heartbeat', 'ares'), 'fa fa-hospital-o' => __(' hospital-o', 'ares'), 'fa fa-medkit' => __(' medkit', 'ares'), 'fa fa-plus-square' => __(' plus-square', 'ares'), 'fa fa-stethoscope' => __(' stethoscope', 'ares'), 'fa fa-user-md' => __(' user-md', 'ares'), 'fa fa-wheelchair' => __(' wheelchair', 'ares'));
    
}

add_filter( 'ares_capacity', 'ares_check_capacity', 10, 1 );
function ares_check_capacity( $base_value = 1 ) {
    
    if ( function_exists( 'ares_strap_pl' ) && ares_strap_pl() ) :
        return $base_value + 6;
    else:
        return $base_value + 3;
    endif;
    
}

/**
 * 
 * Get an array containing the primary and accent colors in use by the theme.
 * 
 * @return String Array
 */
function ares_get_theme_skin_colors() {
    
    $ares_options = ares_get_options();
    
    $colors_array = array();
    
    if ( isset( $ares_options['ares_use_custom_colors'] ) && $ares_options['ares_use_custom_colors'] == 'custom' ) :
        
        $colors_array['primary'] = isset( $ares_options['ares_custom_primary'] ) ? $ares_options['ares_custom_primary'] : '#83CBDC';
        $colors_array['accent'] = isset( $ares_options['ares_custom_accent'] ) ? $ares_options['ares_custom_accent'] : '#57A9BD';

    else :

        switch ( $ares_options['ares_theme_color'] ) :

            case 'aqua' :
                $colors_array['primary'] = '#83CBDC';
                $colors_array['accent'] = '#57A9BD';
                break;

            case 'green' :
                $colors_array['primary'] = '#ACBD5D';
                $colors_array['accent'] = '#8F9E4A';
                break;

            case 'red' :
                $colors_array['primary'] = '#DC838D';
                $colors_array['accent'] = '#E05867';
                break;

            default :
                $colors_array['primary'] = '#83CBDC';
                $colors_array['accent'] = '#57A9BD';
                break;

        endswitch;

    endif;
    
    return $colors_array;

}

add_action( 'ares_free_widget_areas', 'ares_render_free_widget_areas', 10 );
function ares_render_free_widget_areas() {
    
    if( is_active_sidebar( 'sidebar-banner' ) ) : ?>
            
        <div id="homepage-area-c" class="full-banner">

            <div class="container">

                <div class="row">

                    <div class="col-md-12">

                        <div class="top-banner-text">
                            <?php dynamic_sidebar( 'sidebar-banner' ); ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="clear"></div>

    <?php endif;
        
    if( is_active_sidebar( 'sidebar-homepage-widget' ) ) :?>

        <div id="homepage-area-d" class="">

            <div class="container">

                <div class="row">

                    <div class="col-md-12">

                        <div class="top-banner-text">
                            <?php dynamic_sidebar( 'sidebar-homepage-widget' ); ?>
                        </div>            

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>
    
<?php }

function ares_get_background_patterns() {
    $patterns = array(
        'witewall_3'    => __( 'White Wall', 'ares' ),
        'brickwall'     => __( 'White Brick', 'ares' ),
        'skulls'        => __( 'Illustrations', 'ares' ),
        'crossword'     => __( 'Crossword', 'ares' ),
        'food'          => __( 'Food', 'ares' ),
    );
    return $patterns;
}

add_filter( 'loop_shop_columns', 'loop_columns' );
if ( !function_exists( 'loop_columns' ) ) {
    
    function loop_columns() {
        
        $ares_options = ares_get_options();
        return $ares_options['woo_products_per_row'];
        
    }
    
}

add_action( 'woocommerce_before_main_content', function() {
    
    $ares_options = ares_get_options();
    echo '<div class="woocommerce columns-' . $ares_options['woo_products_per_row'] . '">';
    
}, 20);

add_action( 'woocommerce_after_main_content', function() { 
    
    echo '</div>';
    
}, 20);
