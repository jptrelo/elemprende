<?php
/**
 * The template for displaying all single pages.
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
					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', 'page' );
			
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile; // End of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		<div class="col-md-3 col-sm-4">
			<?php if ( is_active_sidebar( 'styled_store_page_left_sidebar' )) {
					dynamic_sidebar( 'styled_store_page_right_sidebar' );
			} ?>
		</div>	
	</div>
</div>		
<?php get_footer();
