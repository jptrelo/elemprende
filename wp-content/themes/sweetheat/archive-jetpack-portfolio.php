<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SweetHeat
 */

get_header(); ?>


			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="entry-title">
						<?php echo __('Our Work', 'sweetheat'); ?>
					</h1>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
				</header><!-- .page-header -->

				<section id="our-work" class="work-page">

					<div class="grid">
							<?php while ( have_posts() ) : the_post(); ?>

								<figure class="work">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail('sweetheat-portfolio'); ?>
									<?php endif; ?>
									<figcaption>							
										<h3><?php the_title(); ?></h3>
										<p><a href="<?php the_permalink(); ?>"><?php echo __('Read more', 'sweetheat'); ?></a></p>
									</figcaption>
								</figure>

							<?php endwhile; ?>

							<?php sweetheat_paging_nav(); ?>

						<?php else : ?>

							<?php get_template_part( 'content', 'none' ); ?>

						<?php endif; ?>

					</div>
				</section><!--#our-work-->			

<?php get_footer(); ?>
