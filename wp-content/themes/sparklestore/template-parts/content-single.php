<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sparkle_Store
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">

	<div class="post-container">
		<?php 
			if ( has_post_thumbnail() ) { 
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'sparklestore-blogs', true);
		?>
			<div class="post-img">

				<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
					 <figure>
						<img src="<?php echo esc_url( $image[0] ); ?>" class="img-responsive" alt="<?php the_title(); ?>">											 
					</figure>
				</a>
				<div class="blogdate">
					<time class="entry-date updated">
						<?php the_time( 'F d Y' ) ?>
					</time>
				</div>
			   									   
			</div>
		<?php } ?>
		<div class="post-detail-container">
			<div class="title">	
					<h2 class="entry-title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>										
			</div>
			<ul class="list-info">
				<li><i class="fa fa-user"></i>  <?php the_author(); ?></li>
				<li><i class="fa fa-folder-open"></i> <?php the_category(', '); ?></li>
				<li><i class="fa fa-comment"></i> <?php comments_popup_link( esc_html__( '0 Comment', 'sparklestore' ),  esc_html__( '1 Comment', 'sparklestore' ), esc_html__( '% Comments', 'sparklestore' ), esc_html__( 'Comments are Closed', 'sparklestore' ) ); ?></li>
				<li><i class="fa fa-tag"></i> <?php the_tags( ' ' ); ?></li>							
			</ul>

			<div class="blogdesc-wrap">
			  	<div class="blogdesc">								
		   			<?php
						the_content( );

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sparklestore' ),
							'after'  => '</div>',
						) );
					?> 
			  	</div>								  
			</div>								
  		</div>
		
	</div>

</article><!-- #post-## -->