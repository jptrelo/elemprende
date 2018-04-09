<?php
/**
 * Templale part for displaying single page.
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package styledstore
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'styledstore-singlepage' ); ?>>
	<?php if( has_post_thumbnail( )) : ?>
		<header class="entry-header">
			<?php styledstore_the_post_thumbnail(); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content(); ?>
			<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'styled-store' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) ); ?>

		</div>
</article><!-- #post-## -->