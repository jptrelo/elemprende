<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Best_Commerce
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Hook - best_commerce_single_image.
	 *
	 * @hooked best_commerce_add_image_in_single_display - 10
	 */
	do_action( 'best_commerce_single_image' );
	?>
	<header class="entry-header">
		<div class="entry-meta">
			<?php best_commerce_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'best-commerce' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php best_commerce_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

