<?php
/*
Template Name: Team
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
			'post_type' 		  => 'employees',
			'posts_per_page'	  => -1	
		) );

		if ($r->have_posts()) :
?>


	
	<section id="team">
		
		<div class="row">
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<?php 
				$photo = get_post_meta( get_the_ID(), 'wpcf-photo', true );
				$facebook = get_post_meta( get_the_ID(), 'wpcf-facebook', true );
				$twitter = get_post_meta( get_the_ID(), 'wpcf-twitter', true );
				$google = get_post_meta( get_the_ID(), 'wpcf-google-plus', true );
			?>
			<div class="team-member large-4 medium-4 columns">
				<?php if ($photo != '') : ?>
					<div class="team-image"><img src="<?php echo esc_url($photo); ?>" alt=""></div>
				<?php endif; ?>	
				<h5><?php the_title(); ?></h5>
				<p class="team-info"><?php the_content(); ?></p>

				<div class="share-social">
					<ul>
						<?php if ($facebook != '') : ?>
							<li><a class="facebook" href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>
						<?php if ($twitter != '') : ?>
							<li><a class="twitter" href="<?php echo esc_url($twitter); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?>
						<?php if ($google != '') : ?>
							<li><a class="google" href="<?php echo esc_url($google); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
						<?php endif; ?>		
					</ul>
				</div>
			</div>
			<?php endwhile; ?>
		</div><!--.row-->

	</section>
<?php endif; ?>	


<?php get_footer(); ?>
