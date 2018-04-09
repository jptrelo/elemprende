<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package styledstore
 */

?>
<footer id="footer">
	<!-- get sidebar footer top for  -->
	<?php get_sidebar( 'footer-top');?>	
	<!-- add payment links  -->	
	<div class="footer-bottom">
		<?php do_action( 'styledstore_add_payment_links' ); ?>
	</div>	
		<div class="footer-bottombar">
			<div class="container">

				<?php
				if  ( get_theme_mod( 'styledstore_show_footer_text' ) != '1' ) {?>
					<div class="copyright">
	                    <?php esc_html_e( 'Styled Store WordPress Theme by', 'styled-store' ); ?>
						<a href="<?php echo esc_url('http://styledthemes.com/'); ?>" target="_blank"><?php esc_html_e( 'StyledThemes', 'styled-store' ); ?></a>
					</div>
				<?php } ?>

				<div class="footer-menu">
						
					<?php $styledstore_primary_nav = array(
						'theme_location'	=> 'footer',
						'container'	=> false,
						'menu_class'	=> 'sm',
						'menu_id'	=> 'footer-menu',
						'depth'	=> 1,
						'fallback_cb' => false
					);
					wp_nav_menu( $styledstore_primary_nav ); ?>
					
				</div>
			</div>
		</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
