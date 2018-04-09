<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SweetHeat
 */
?>
		</div>
	</div><!-- #content -->

	<?php if (get_theme_mod('action_text')) : ?>
	<div id="notice-bar">
		<div class="row">
			<div class="large-9 medium-9 columns">
				<h4 class="left"><?php echo get_theme_mod('action_text'); ?></h4>
			</div>
			<?php if (get_theme_mod('action_button')) : ?>
			<div class="large-3 medium-3 columns">
				<a href="<?php echo get_theme_mod('action_button_link'); ?>" class="btn"><?php echo get_theme_mod('action_button'); ?></a>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	
	<footer id="footer" class="site-footer" role="contentinfo">
		<div class="site-info row">
			<div class="large-12 columns">
				<p class="copyright left">
					<?php if ( get_theme_mod('footer_credits') == '') : ?>
						<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'sweetheat' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'sweetheat' ), 'WordPress' ); ?></a>
						<span class="sep"> | </span>
						<?php printf( __( 'Theme: %2$s by %1$s', 'sweetheat' ), 'aThemes', '<a href="http://athemes.com/theme/sweetheat">Sweetheat</a>' ); ?>
					<?php else : ?>
						<?php echo get_theme_mod('footer_credits'); ?>
					<?php endif; ?>
				<p>
				<a href="#top" class="back-to-top right"><?php echo __('Top', 'sweetheat'); ?></a>			
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
