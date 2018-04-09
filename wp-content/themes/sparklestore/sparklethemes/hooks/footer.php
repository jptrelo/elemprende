<?php
/**
 * Footer Area Before
*/
if ( ! function_exists( 'sparklestore_footer_before' ) ) {
	function sparklestore_footer_before(){ ?>
		<footer class="footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
	<?php
	}
}
add_action( 'sparklestore_footer_before', 'sparklestore_footer_before', 5 );

/**
 * SparkleStore Footer Widget Area
*/
if ( ! function_exists( 'sparklestore_footer_widget_area' ) ) {
	function sparklestore_footer_widget_area(){ ?>
		<div class="footer-middle">
			<div class="container">                
			<div class="clear">                

				<?php if ( is_active_sidebar( 'sparklefooterareaone' ) ) { ?>
					<div class="footerarea">
						<?php dynamic_sidebar( 'sparklefooterareaone' ); ?>
					</div>
				<?php } ?>

				<?php if ( is_active_sidebar( 'sparklefooterareatwo' ) ) { ?>
					<div class="footerarea">
						<?php dynamic_sidebar( 'sparklefooterareatwo' ); ?>
					</div>
				<?php } ?>

				<?php if ( is_active_sidebar( 'sparklefooterareathree' ) ) { ?>
					<div class="footerarea">
						<?php dynamic_sidebar( 'sparklefooterareathree' ); ?>
					</div>
				<?php } ?>

				<?php if ( is_active_sidebar( 'sparklefooterareafour' ) ) { ?>
					<div class="footerarea">
						<?php dynamic_sidebar( 'sparklefooterareafour' ); ?>
					</div>
				<?php } ?> 
					
			</div>
			</div>
		</div>
	<?php
	}
}
add_action( 'sparklestore_footer_widget', 'sparklestore_footer_widget_area', 10 );


/**
 * Top Footer Area
*/
if ( ! function_exists( 'sparklestore_top_footer_before' ) ) {
	function sparklestore_top_footer_before(){ ?>
		<div class="footer-top">
		  <div class="container">
	        <div class="sociallink">
	      		<?php apply_filters( 'sparklestore_social_links', 5 ); ?>	            
	        </div>
	        <div class="paymentlogo">
	        	<?php apply_filters( 'sparklestore_payment_logo', 10 ); ?>
	        </div>
		  </div>
		</div>
		<?php
	}
}
add_action( 'sparklestore_top_footer', 'sparklestore_top_footer_before', 15 );


/**
 * Bottom Footer Area
*/
if ( ! function_exists( 'sparklestore_bottom_footer_before' ) ) {
	function sparklestore_bottom_footer_before(){ ?>
		<div class="footer-bottom">
		    <div class="container">
		        
	       		<div class="coppyright">
					<?php $copyright = get_theme_mod( 'sparklestore_footer_copyright' ); if( !empty( $copyright ) ) { ?>
						<?php echo apply_filters( 'sparklestore_copyright_text', $copyright . ' - ' ); ?>	
					<?php } else { ?>
						<?php echo esc_html( apply_filters( 'sparklestore_copyright_text', $content = '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) .' - ' ) ); ?>
					<?php } ?>
					<?php if ( apply_filters( 'sparklsestore_credit_link', true ) ) { 
						printf( esc_html__( '%1$s By %2$s', 'sparklestore' ), ' WordPress Theme : SparkleStore ', '<a href=" ' . esc_url('http://sparklewpthemes.com/') . ' " rel="designer" target="_blank">'.esc_html__('Sparkle Themes','sparklestore').'</a>' ); ?>
					<?php } ?>
				</div><!-- .site-info -->

	            <div class="companylinks">
	          		<?php apply_filters( 'sparklestore_footer_menu', 5 ); ?>
	            </div>

		    </div>
		</div>
		<?php
	}
}
add_action( 'sparklestore_bottom_footer', 'sparklestore_bottom_footer_before', 20 );


/**
 * Footer Area After
*/
if ( ! function_exists( 'sparklestore_footer_after' ) ) {
	function sparklestore_footer_after(){ ?>
		</footer>
	<?php
	}
}
add_action( 'sparklestore_footer_after', 'sparklestore_footer_after', 25 );