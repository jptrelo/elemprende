<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package styledstore
 */

get_header(); ?>
	
<div class="container">
	<div class="row">
		<div class="col-md-9 col-sm-8">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php /* Start the Loop */
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'single' ); 

						if( get_theme_mod( 'styledstore_hide_post_mavigation' ) == '') { ?>

							<div class="styledstore-post-navigation clearfix">
							
								<?php the_post_navigation( array(
									'next_text' => '
										<span class="screen-reader-text">' . esc_html__( 'Next post:', 'styled-store' ) . '</span> '.
										'<span class="post-nav-title">'.esc_html__( 'Next', 'styled-store' ) .'</span>'.
										'<span class="meta-nav" aria-hidden="true">&raquo;</span>', 
									'prev_text' => 
										'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'styled-store' ) . '</span> '. 
										'<span class="meta-nav" aria-hidden="true">&laquo;</span> '.
										'<span class="post-nav-title">'.esc_html__( 'Previous', 'styled-store' ) . '</span>'

								) ); ?>
							</div>
						<?php }
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile; // End of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		<div class="col-md-3 col-xs-12 col-sm-4">
			<?php get_sidebar(); ?>
		</div>	
	</div>
</div>		

<?php get_footer();
