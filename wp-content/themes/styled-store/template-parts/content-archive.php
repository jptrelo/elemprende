<?php
/**
 * Template part for displaying archive post.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package styledstore
 */

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if( has_post_thumbnail( )) : ?>
		<header class="entry-header">
			<?php styledstore_the_post_thumbnail(); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>
		
	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); 

		styledstore_entry_date() ?>

		<div class="entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Read More<span class="screen-reader-text"> "%s"</span>', 'styled-store' ),
				get_the_title()
			) ); ?>
			<!-- *dislay tags on blog page*/ -->
			<footer class="entry-footer clearfix">
				<?php styledstore_entry_taxonomies(); ?>
			</footer>
			
		</div>
</article><!-- #post-## -->