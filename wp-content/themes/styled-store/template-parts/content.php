<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package styledstore
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if( has_post_thumbnail() ) : ?>
		<header class="entry-header">
			<?php styledstore_the_post_thumbnail(); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="entry-meta">
		<!-- display meta information of post -->
			<?php styledstore_entry_date() ?>
		</div>

		<div class="entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Read More<span class="screen-reader-text"> "%s"</span>', 'styled-store' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'styled-store' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>'
			) );

			if ( get_theme_mod( 'styledstore_blog_tax' ) == '') { ?>

				<footer class="entry-footer clearfix">

					<?php styledstore_entry_taxonomies(); ?>
							
				</footer>
			<?php } ?>		
		</div>
</article><!-- #post-## -->