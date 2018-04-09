<?php
/**
* Template Name: Front Page
*
* This is the static homepage of this theme. Will be rendered when user select such a page whose 
* page template is home in static front page setting. 
* @since Bizplan 0.1
*/ 
get_header(); 
$slider_ids = bizplan_get_ids( 'slider_page' );
if( count( $slider_ids ) > 0 ): 
?>
	<section class="wrapper block-slider">
		<div class="mouse-holder">
			<div class="mouse-hover">
			  	<a href="#after-slider" class="mouse scroll-to">
					<span><?php esc_html_e( 'Scroll Down' ,'bizplan' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="29.083px" height="44px" viewBox="0 0 29.083 44" enable-background="new 0 0 29.083 44" xml:space="preserve">
					<path id="mouse" fill="transparent" stroke="#919191" stroke-width="2" stroke-miterlimit="10" d="M27.083,29.962c0,6.627-5.373,12-12,12h-1  c-6.627,0-12-5.373-12-12v-16c0-6.627,5.373-12,12-12h1c6.627,0,12,5.373,12,12V29.962z"/>
					<path id="scroll" fill="#D9D9D9" d="M15.417,15.504c0,0.553-0.448,1-1,1l0,0c-0.552,0-1-0.447-1-1v-4.167c0-0.553,0.448-1,1-1l0,0  c0.552,0,1,0.447,1,1V15.504z"/>
					</svg>
				</a>
			</div>
		</div>

		<div class="controls">
		</div>
		<div class="owl-pager" id="kt-slide-pager"></div>

		<div class="home-slider owl-carousel">
			<?php
				$query = new WP_Query( apply_filters( 'bizplan_slider_args', array(
					'posts_per_page' => 3,
					'post_type'      => 'page',
					'orderby'        => 'post__in',
					'post__in'       => $slider_ids,
				)));
				
				while ( $query->have_posts() ) :  $query->the_post();
					$image = bizplan_get_thumbnail_url( array( 'size' => 'bizplan-kt-1142-500' ) );
			?>
					<div class="slide-item" style="background-image: url( <?php echo esc_url( $image ); ?> );">
						<div class="banner-overlay">
					    	<div class="container">
					    		<div class="row">
					    			<?php
					    				$class = 'col-xs-12 col-sm-10 col-md-8';
					    				if( is_rtl() ){
					    					$class .= ' col-sm-offset-2 col-md-offset-4';
					    				}
					    			?>
					    			<div class="<?php echo esc_attr( $class ); ?>">
					    				<div class="slide-inner">
					    					<div class="post-content-inner-wrap">
						    					<header class="post-title">
						    						<?php
						    							$title = get_the_title();
						    						?>
						    						<h2><?php echo bizplan_remove_pipe( $title, true ); ?></h2>
						    					</header>
						    					<?php  
						    						bizplan_excerpt( 16, true );
						    						bizplan_edit_link(); 
						    					?>
							    				<div class="button-container">
							    					<a href="<?php the_permalink(); ?>" class="button-light">
							    						<?php esc_html_e( 'Learn More', 'bizplan' ); ?>
							    					</a>
							    				</div>
						    				</div>
					    				</div>
					    			</div>
					    		</div>
					    	</div>
						</div>
					</div>
			<?php
				endwhile; 
				wp_reset_postdata(); 	
			?>
		</div>
		<div id="after-slider"></div>
	</section>
<?php 
endif;

if( !bizplan_get_option( 'disable_service' ) ):

	$srvc_ids = bizplan_get_ids( 'service_page' );
	if( count( $srvc_ids ) > 0 ):

		$query = new WP_Query( apply_filters( 'bizplan_services_args',  array( 
			'post_type'      => 'page',
			'post__in'       => $srvc_ids, 
			'posts_per_page' => 4,
			'orderby'        => 'post__in'
		)));

		if( $query->have_posts() ):
?>
			<!-- Service Section -->
			<section id="block-service" class="wrapper block-service">
				<?php 
					bizplan_section_heading( array( 
						'id' => 'service_main_page'
					));
				?>
				<div class="container">
					<div class="icon-block-outer">
						<div class="row">
			    		<?php
			    			$count = $query->post_count;
				    		while( $query->have_posts() ): 
				    			$query->the_post();
				    			$title = bizplan_get_piped_title();
				    			if( 1 == $count ){
				    				$class = 'col-xs-12 col-sm-12 col-md-12';
				    				$excerpt_length = 35;
				    			}elseif( 2 == $count ){
				    				$class = 'col-xs-12 col-sm-6 col-md-6';
				    				$excerpt_length = 30;
				    			}elseif( 3 == $count ){
				    				$class = 'col-xs-12 col-sm-6 col-md-4';
				    				$excerpt_length = 25;
				    			}else{
				    				$class = 'col-xs-12 col-sm-6 col-md-3';
				    				$excerpt_length = 20;
				    			}
				    	?>
								<div class="<?php echo esc_attr( $class ); ?> text-center">
									<div class="list-inner">
										<?php if( $title[ 'sub_title' ] ): ?>
											<div class="icon-area">
												<span class="icon-outer">
													<a href="<?php the_permalink(); ?>">
														<span class="kfi <?php echo esc_attr( $title[ 'sub_title' ] ); ?>"></span>
													</a>
												</span>
											</div>
										<?php endif; ?>
										<h3>
											<a href="<?php the_permalink(); ?>">
												<?php echo esc_html( $title[ 'title' ] ); ?>
											</a>
										</h3>
										<div class="text">
											<?php 
												bizplan_excerpt( $excerpt_length, true );
												bizplan_edit_link(); 
											?>
										</div>
										<div class="button-container">
											<a href="<?php the_permalink(); ?>" class="button-text">
												<?php esc_html_e( 'Know More', 'bizplan' ); ?>
											</a>
										</div>
									</div>
								</div>
						<?php  
							endwhile;
							wp_reset_postdata();
						?>
						</div>
					</div>
				</div>
			</section> <!-- End Service Section -->
<?php
		endif; 
	endif; 
endif;

$id = bizplan_get_option( 'about_page' );
if( $id ):
	$query = new WP_Query( apply_filters( 'bizplan_about_page_args',  array( 
		'post_type'      => 'page', 
		'p'       => $id, 
	)));
	while( $query->have_posts() ): 
		$query->the_post();
		$image = bizplan_get_thumbnail_url( array(
			'size' => 'bizplan-kt-670-500'
		));
?>
<!-- About Section -->
<section class="wrapper block-about">
	<div class="thumb-block-outer">
		<div class="container-full">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 pad0lr">
					<div class="area-div content-outer">
						<div class="section-title-group">
							<h2 class="section-title">
								<?php the_title(); bizplan_edit_link(); ?>
							</h2>
							<div class="maintitle-divider"></div>
						</div>
						<?php bizplan_excerpt(40); ?>
						<div class="button-container">
							<a href="<?php the_permalink(); ?>" class="button">
								<?php esc_html_e( 'Explore More', 'bizplan' ); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 pad0lr">
					<div class="area-div thumb-outer">
						<img src="<?php echo esc_url( $image );?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</section> <!-- End About Section -->
<?php 
	endwhile;
	wp_reset_postdata();
endif;

if( !bizplan_get_option( 'disable_portfolio' ) ):

	$port_ids = bizplan_get_ids( 'portfolio_page' );
	if( count( $port_ids ) > 0 ):

		$query = new WP_Query( apply_filters( 'bizplan_portfolio_args', array( 
			'post_type'      => 'page', 
			'post__in'       => $port_ids, 
			'posts_per_page' => 6,
			'orderby'        => 'post__in'
		)));

		if( $query->have_posts() ):
?>
			<!-- Portfolio Section -->
			<section class="wrapper block-portfolio">
				<?php 
					bizplan_section_heading( array(
						'id' => 'portfolio_main_page'
					)); 
				?>
			    <div class="gallery-item-inner">
			    	<div class="grid">
			    		<?php
			    			while( $query->have_posts() ):
			    				$query->the_post();
			    				$image = bizplan_get_thumbnail_url( array(
			    					'size' => 'bizplan-kt-670-500'
			    				));
			    		?>
					            <div class="col-xs-6 col-sm-6 col-md-4 pad0lr grid-list">
					                <div class="gallery-list">
					                    <div class="gallery-thumb">
					                        <a href="<?php the_permalink(); ?>" class="gallery-link">
					                            <img src="<?php echo esc_url( $image ); ?>" >
					                        </a>
					                    	<div class="icon-area">
					                            <a href="<?php the_permalink(); ?>">
					                            	<span class="kfi kfi-arrow-expand"></span>
					                            </a>
					                        </div>
					                        <div class="hidden-content">
					                            <div class="head">
					                            	<h2 class="heading">
					                            		<a href="<?php the_permalink(); ?>">
					                            			<?php the_title(); ?>
					                            		</a>
					                            		<?php bizplan_edit_link(); ?>
					                            	</h2>
					                            </div>
					                        </div>
					                    </div>
					                </div>
					            </div>
					    <?php
					    	endwhile;
					    ?>
			        </div>
			    </div>
			</section> 
			<!-- End Portfolio Section -->
<?php
		endif; 
	endif; 
endif;
 
if( ! bizplan_get_option( 'disable_testimonial' ) ):
	$testi_ids = bizplan_get_ids( 'testimonial_page' );

	if( count( $testi_ids ) > 0 ):

		$query = new WP_Query( apply_filters( 'bizplan_testimonial_args', array( 
			'post_type'      => 'page', 
			'post__in'       => $testi_ids, 
			'posts_per_page' => 4,
			'orderby'        => 'post__in'
		)));

		if( $query->have_posts() ):
?>
			<section class="wrapper block-testimonial">
				<?php
					bizplan_section_heading( array(
						'id' => 'testimonial_main_page'
					));
				?>
				<div class="content-inner">
					<div class="controls"></div>
					<div class="container">
						<div class="testimonial-carousel owl-carousel">
							<?php 
								while ( $query->have_posts() ):
									$query->the_post(); 
									$image = bizplan_get_thumbnail_url( array(
										'size' => 'thumbnail'
									));
							?>
								    <div class="slide-item">
										<article class="post-content">
											<div class="post-thumb-outer">
												<div class="big-icon">
													<span class="kfi kfi-quotations-alt2"></span>
												</div>
												<div class="post-thumb">
							    					<img src="<?php echo esc_url( $image ); ?>">
												</div>
											</div>
											<div class="post-content-inner">
												<blockquote>
							    					<?php the_content(); bizplan_edit_link(); ?> 
								    				<footer class="post-title">
								    					<cite>
								    						<?php bizplan_testimonial_title(); ?>
								    					</cite>
								    				</footer>
												</blockquote>
											</div>
										</article>
									</div>
							<?php
								endwhile; 
								wp_reset_postdata();
							?>
						</div>
					</div>
					<div class="container">
						<div class="owl-pager" id="testimonial-pager"></div>
					</div>
				</div>
			</section><!-- End Testimonial Section -->
<?php
		endif;
	endif;
endif;

if( ! bizplan_get_option( 'disable_callback' ) ):

	$callback_page = bizplan_get_option( 'callback_page' );

	if( !empty( $callback_page ) ):

		$query = new WP_Query( apply_filters( 'bizplan_callback_args', array(
			'p'         => $callback_page,
			'post_type' => 'page'
		)));

		while( $query->have_posts() ):
			$query->the_post();
			$image = bizplan_get_callback_banner_url();
		?>
			<style type="text/css">
				.block-callback{
					background-image: url(<?php echo esc_url( $image ); ?> );
				}
			</style>
			<!-- Callback Section -->
			<section class="wrapper block-callback banner-content">
				<div class="banner-overlay">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-md-10 col-sm-offset-1 col-md-offset-1">
								<?php
									bizplan_section_heading( array(
										'divider' => false,
										'query'   => false
									));
								?>
								<div class="button-container">
									<?php
										$callback_link_page = bizplan_get_option( 'callback_link_page' );
										if( !empty( $callback_link_page ) ):

											$query_link = new WP_Query( apply_filters( 'bizplan_callback_link_args', array(
												'p'         => $callback_link_page,
												'post_type' => 'page'
											)));

											while( $query_link->have_posts() ):
												$query_link->the_post();
									?>
												<a href="<?php the_permalink(); ?>" class="button-light">
													<?php the_title(); ?>
												</a>
									<?php
												bizplan_edit_link();
											endwhile;
											wp_reset_postdata();
										endif;
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section><!-- End Callback Section -->
<?php
		endwhile;
		wp_reset_postdata();
	endif;
endif; 

if( !bizplan_get_option( 'disable_blog' ) ):

	$cat_id = absint( bizplan_get_option( 'blog_category' ) );

	$args = array(
		'posts_per_page' => absint( bizplan_get_option( 'blog_number' ) ),
		'ignore_sticky_posts' => 1
	);

	if( $cat_id > 0 ){
		$args[ 'cat' ] = $cat_id;
	}
	$query = new WP_Query( apply_filters( 'bizplan_blog_args', $args ) );

	if( $query->have_posts() ): 
?>
		<!-- Blog Section -->
		<section class="wrapper block-grid">
			<?php
				bizplan_section_heading( array(
					'id' => 'blog_main_page'
				));
			?>
			<div class="container">
				<div class="row">
					<?php
						while( $query->have_posts() ):
							$query->the_post();
							get_template_part( 'template-parts/archive/content', '' );
						endwhile; 
						wp_reset_postdata(); 
					?>
				</div>
			</div>
		</section> <!-- End Blog Section -->
<?php 
	endif; 
endif; 

get_footer();