<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Best_Commerce
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content-wrapper">
		<?php $archive_image = best_commerce_get_option( 'archive_image' ); ?>
		<?php if ( has_post_thumbnail() && 'disable' !== $archive_image ) : ?>
			<?php
			$archive_image_alignment = best_commerce_get_option( 'archive_image_alignment' );
			$args = array(
				'class' => 'best-commerce-post-thumb align' . esc_attr( $archive_image_alignment ),
			);
			the_post_thumbnail( esc_attr( $archive_image ), $args );
			?>
		<?php endif; ?>

		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php best_commerce_posted_on(); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php $archive_layout = best_commerce_get_option( 'archive_layout' ); ?>

			<?php if ( 'full' === $archive_layout ) : ?>
				<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'best-commerce' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'best-commerce' ),
					'after'  => '</div>',
				) );
				?>
			<?php else : ?>
				<?php the_excerpt(); ?>
			<?php endif ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php best_commerce_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .entry-content-wrapper -->
</article><!-- #post-## -->
