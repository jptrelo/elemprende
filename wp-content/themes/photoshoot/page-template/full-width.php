<?php 
/*
 * Template Name: Full Width
 */
get_header();
if (function_exists('photoshoot_custom_breadcrumbs')) photoshoot_custom_breadcrumbs(); ?>
<div class="detail-section">
	<div class="container photoshoot-container">
    	<div class="row">
			<div class="col-md-12 no-padding">
				<?php while ( have_posts() ) : the_post();
					$photoshoot_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
						<div class="col-md-12 page-detail">
						<?php if(!empty($photoshoot_image)){ ?>
						<img src="<?php echo $photoshoot_image ?>" alt="<?php echo get_the_title(); ?>" class="img-responsive page-img">
						<?php }
						the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'photoshoot' ),
								'after' => '</div>',
							) ); ?>
						</div>
                        <div class="col-md-12 photoshoot-post-comment no-padding">
                        	<?php comments_template('', true); ?>
                        </div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>			
<?php get_footer(); ?>