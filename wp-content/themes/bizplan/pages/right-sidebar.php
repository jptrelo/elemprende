<?php
/**
* Template Name: Right Sidebar
*
* This is the Right Sidebar Page Template of this theme. Will be rendered when user select such a page whose 
* page template is Right Sidebar. 
*
* @since Bizplan 0.1
*/ 
get_header();
bizplan_inner_banner();
?>
<section class="wrapper wrap-detail-page wrap-right-sidebar-detail-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-8">
				<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/single/content', '' );

						# If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; # End of the loop.
				?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>