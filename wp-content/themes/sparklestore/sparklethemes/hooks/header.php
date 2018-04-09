<?php
/**
 * Header Section Skip Area
*/
if ( ! function_exists( 'sparklestore_skip_links' ) ) {
	/**
	 * Skip links
	 * @since  1.0.0
	 * @return void
	 */
	function sparklestore_skip_links() { ?>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sparklestore' ); ?></a>
		<?php
	}
}
add_action( 'sparklestore_header_before', 'sparklestore_skip_links', 5 );


if ( ! function_exists( 'sparklestore_header_before' ) ) {
	/**
	 * Header Area
	 * @since  1.0.0
	 * @return void
	*/
	function sparklestore_header_before() { ?>
		<header id="masthead" class="site-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader" role="banner">		
			<div class="header-container">
		<?php
	}
}
add_action( 'sparklestore_header_before', 'sparklestore_header_before', 10 );

/**
 * Top Header Area
*/
if ( ! function_exists( 'sparklestore_top_header' ) ) {
	
	function sparklestore_top_header() { ?>
	    <div class="topheader">
	        <div class="container">
                <div class="row">
		        	<div class="quickinfowrap">			            
						<ul class="quickinfo">
							<?php
								$emial_icon       = esc_attr( get_theme_mod('sparklestore_email_icon') );
								$email_address    = sanitize_email( get_theme_mod('sparklestore_email_title') );
								
								$phone_icon       = esc_attr( get_theme_mod('sparklestore_phone_icon') );
								$phone_number     = esc_attr( get_theme_mod('sparklestore_phone_number') );
								
								$map_address_iocn = esc_attr( get_theme_mod('sparklestore_address_icon') );
								$map_address      = esc_html( get_theme_mod('sparklestore_map_address') );
								
								$shop_open_icon   = esc_attr( get_theme_mod('sparklestore_start_open_icon') );
								$shop_open_time   = esc_attr( get_theme_mod('sparklestore_start_open_time') );
							?>
								
							<?php if(!empty( $email_address )) { ?>        							
			                    <li>
			                    	<span class="<?php if(!empty( $emial_icon )) { echo esc_attr( $emial_icon ); } ?>">&nbsp;</span>
			                    	<a href="mailto:<?php echo esc_attr( antispambot( $email_address ) ); ?>"><?php echo esc_attr( antispambot( $email_address ) ); ?></a>
			                    </li>
		                    <?php }  ?>
		                    
		                    <?php if(!empty( $phone_number )) { ?>        							
			                    <li>
			                    	<span class="<?php if(!empty( $phone_icon )) { echo esc_attr( $phone_icon ); } ?>">&nbsp;</span>
			                   		<?php echo esc_attr( $phone_number ); ?>
			                    </li>
		                    <?php }  ?>
		                    
		                    <?php if(!empty( $map_address )) { ?>        							
			                    <li>
			                    	<span class="<?php if(!empty( $map_address_iocn )) { echo esc_attr( $map_address_iocn ); } ?>">&nbsp;</span>
			                    	<a target="_blank" href="https://www.google.com.np/maps/place/<?php echo esc_html( $map_address ); ?>"><?php echo esc_html( $map_address ); ?></a>
			                    </li>
		                    <?php }  ?>
		                    
		                    <?php if(!empty( $shop_open_time )) { ?>        							
			                    <li>
			                    	<span class="<?php if(!empty( $shop_open_icon )) { echo esc_attr( $shop_open_icon ); } ?>">&nbsp;</span>
			                    	<?php echo esc_attr( $shop_open_time ); ?>
			                    </li>
		                    <?php }  ?>        	                    
						</ul>
		        	</div>

		          	<div class="toplinkswrap">
			            <div class="toplinks">
			              	<?php wp_nav_menu( array( 'theme_location' => 'sparkletopmenu', 'depth' => 1) ); ?>
			            </div><!-- End Header Top Links --> 
		          	</div>
                </div>
	        </div>
	    </div>
		<?php
	}
}
add_action( 'sparklestore_header', 'sparklestore_top_header', 15 );


/**
 * Main Header Area
*/
if ( ! function_exists( 'sparklestore_main_header' ) ) {
	
	function sparklestore_main_header() { ?>
		<div class="mainheader">
			<div class="container sp-clearfix">
		        <div class="sparklelogo">
	              	<?php 
	              		if ( function_exists( 'the_custom_logo' ) ) {
							the_custom_logo();
						} 
					?>
	              	<div class="site-branding">				              		
	              		<h1 class="site-title">
	              			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	              				<?php bloginfo( 'name' ); ?>
	              			</a>
	              		</h1>
	              		<?php
	              		$description = get_bloginfo( 'description', 'display' );
	              		if ( $description || is_customize_preview() ) { ?>
	              			<p class="site-description"><?php echo $description; ?></p>
	              		<?php } ?>
	              	</div>
		        </div><!-- End Header Logo --> 

		        <div class="rightheaderwrap sp-clearfix">
		        	<div class="category-search-form">
		        	  	<?php 
		        	  		if ( sparklestore_is_woocommerce_activated() ) {  
		        	  			sparklestore_advance_search_form(); 
		        	  		} 
		        	  	?>
		        	</div>
    	             
    	          	<?php if ( sparklestore_is_woocommerce_activated() ) {  ?>
    	          		<div class="view-cart">
    		          		<?php echo sparklestore_shopping_cart(); ?>
	    		        	<div class="top-cart-content">
	    		        	    <div class="block-subtitle"><?php esc_html_e('Recently added item(s)', 'sparklestore'); ?></div>
	    		        	    <?php the_widget('WC_Widget_Cart', 'title='); ?>
	    		        	</div>
	    		        </div>
    		        <?php } ?>

      	        	<?php if( function_exists ( 'sparklestore_products_wishlist' ) ) { ?>
      		          	<div class="wishlist">
      		          		<?php sparklestore_products_wishlist(); ?>
      		          	</div>
      	          	<?php } ?>
		        </div>
			</div>
		</div>		    
		<?php
	}
}
add_action( 'sparklestore_header', 'sparklestore_main_header', 20 );


if ( ! function_exists( 'sparklestore_header_after' ) ) {
	/**
	 * Header Area
	 * @since  1.0.0
	 * @return void
	*/
	function sparklestore_header_after() {
		?>
			</div>
		</header><!-- #masthead -->
		<?php
	}
}
add_action( 'sparklestore_header_after', 'sparklestore_header_after', 25 );