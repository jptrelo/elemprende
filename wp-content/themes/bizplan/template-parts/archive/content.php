<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @since Bizplan 0.1
 */
?>
<div class="masonry-grid">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-content' ); ?>>
		<div class="post-thumb-outer">
			<?php
			if( 'post' == get_post_type() ):
				$cat = bizplan_get_the_category();
				if( $cat ):
			?>
					<span class="cat">
						<?php
							$term_link = get_category_link( $cat[ 0 ]->term_id );
						?>
						<a href="<?php echo esc_url( $term_link ); ?>">
							<?php  echo esc_html( $cat[0]->name ); ?>
						</a>
					</span>
			<?php
				endif;
			endif;

			$args = array(
				'size' => 'bizplan-kt-390-320'
			);

			# Disabling dummy thumbnails when its in search page, also support for jetpack's infinite scroll
			if( 'post' != get_post_type() && bizplan_is_search() ){
				$args[ 'dummy' ] = false;
			}

			bizplan_post_thumbnail( $args ); 
			if( 'post' == get_post_type() ){
			?>	
					<div class="post-detail">
						<a href="<?php echo esc_url( bizplan_get_day_link() ); ?>" class="date">
							<span class="day"><?php echo esc_html( get_the_time('d') ); ?></span>
							<span class="monthyear">
								<p><?php echo esc_html( get_the_time( 'M' ) ); ?></p>
								<p><?php echo esc_html( get_the_time( 'Y' ) ); ?></p>
							</span>
						</a>
					</div>
			<?php } ?>
		</div>

		<?php if( 'post' == get_post_type() ): ?>
			<div class="post-format-outer">
				<span class="post-format">
					<a href="<?php echo esc_url( get_post_format_link( get_post_format() ) ); ?>">
						<span class="kfi <?php echo esc_attr( bizplan_get_icon_by_post_format() ); ?>"></span>
					</a>
				</span>
			</div>
		<?php endif; ?>

		<div class="post-content-inner">
			<header class="post-title">
				<h3>
					<a href="<?php the_permalink(); ?>">
						<?php echo esc_html( bizplan_remove_pipe( get_the_title(), true ) ); ?>
					</a>
				</h3>
			</header>
			<div class="post-text"><?php bizplan_excerpt( 25, true, '...' ); ?></div>
			<div class="button-container">
				<a href="<?php the_permalink(); ?>" class="button-text">
					<?php esc_html_e( 'Read More', 'bizplan' ); ?>
				</a>
				<?php bizplan_edit_link(); ?>
			</div>
		</div>
	</article>
</div>
