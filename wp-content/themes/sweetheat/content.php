<?php
/**
 * @package SweetHeat
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry standard'); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-image">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
				<?php the_post_thumbnail(); ?>
			</a>			
		</div>	
	<?php endif; ?>

	<div class="entry-body">
		<?php if ( (get_theme_mod('full_content') == 1) && is_home() ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
	</div>

	<footer class="entry-meta">
		<?php sweetheat_posted_on(); ?>
		<?php sweetheat_entry_footer_index(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
