<?php
/**
 * The template for displaying the footer
 * Contains the closing of the body tag and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @since Bizplan 0.1
 */
?>
		<?php if ( !bizplan_get_option( 'disable_footer_widget') && is_active_sidebar( 'footer-sidebar' ) ): ?>
			<section class="wrapper block-top-footer">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar( 'footer-sidebar' ); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
		
		<footer  class="wrapper site-footer" role="contentinfo">
			<div class="container">
				<div class="footer-inner">
					<div class="site-info">
						<?php echo wp_kses_post( html_entity_decode( bizplan_get_option( 'footer_text' ) ) ); ?>
					</div><!-- .site-info -->
				</div>
			</div>
		</footer><!-- #colophon -->
		<?php wp_footer(); ?>
	</body>
</html>