<?php
/*
Template Name: Services
*/

get_header(); ?>

	<header id="page-header">
		<div class="row">
			<div class="large-12 columns">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
		</div>
	</header><!--#page-header-->


<?php
		$r = new WP_Query(
		array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'services',
			'posts_per_page'	  => -1	
		) );

		if ($r->have_posts()) :
?>


	
		<section id="services" class="services-area">
			<div class="row">			
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<?php $icon = get_post_meta( get_the_ID(), 'wpcf-service-icon', true ); ?>
					<div class="service large-4 medium-4 small-12 columns text-center">
						<?php if ($icon) : ?>
							<span class="icon-3x icon-round"><?php echo '<i class="fa ' . esc_html($icon) . '"></i>'; ?></span>
						<?php endif; ?>
						<h4 class="service-title"><?php the_title(); ?></h4>
						<div class="service-desc"><?php the_content(); ?></div>
					</div>
				<?php endwhile; ?>
			</div>			
		</section>
<?php endif; ?>	


<?php get_footer(); ?>
