<?php 
/*
 * Author Template File.
 */
get_header();
if (function_exists('photoshoot_custom_breadcrumbs')) photoshoot_custom_breadcrumbs(); ?>
<div class="detail-section">
	<div class="container photoshoot-container">
    	<div class="row">
		 	<div class="col-md-9">
            	<div class="photos-group">
                <h2 class="photoshoot-title"><span class="title-color"><?php echo get_the_author(); ?><span></h2>
					<!--Pagination Start-->
					<?php if(function_exists('faster_pagination')) {?>
            <div class="col-md-12 pagination-photoshoot-align no-padding">
						<?php faster_pagination();?>
            </div>
					<?php }else {
            if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                              <div class="col-md-12 pagination-photoshoot-align no-padding">
                                <ul class="pagination pagination-photoshoot">
                                <li><?php previous_posts_link(); ?></li>
                                <li><?php next_posts_link(); ?></span></li>
                              </ul>
                              </div>
                              <?php }
                            }// ?>
					<!--Pagination End-->
                    <div class="masonry-container">
                      <?php while ( have_posts() ) : the_post(); ?>
                      <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php $photoshoot_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()),'photoshoot-topphoto-width' );
                              if(empty($photoshoot_image[0]))
                                $photoshoot_feat_image=  get_template_directory_uri().'/images/no-imageall.jpg';
                              else
                                $photoshoot_feat_image=$photoshoot_image[0]; ?>
                    <div class="col-md-4 no-padding box">
                      <div class="article">
                    	<div class="photos-box ">
						<a href="<?php echo  esc_url(get_permalink()); ?>"><img src="<?php echo  esc_url($photoshoot_feat_image); ?>" alt="<?php echo get_the_title(); ?>" class="img-responsive"></a>
                        <div class="photos-details">
                        <?php photoshoot_entry_meta(); ?>
                    	</div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <?php endwhile; ?>
                    </div>
                    <!--Pagination Start-->
                   <?php if(function_exists('faster_pagination')) { ?>
                    <div class="col-md-12 pagination-photoshoot-align no-padding">
                      <?php faster_pagination();?>
                    </div>
                   <?php }else { ?>
                   <?php if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                   <div class="col-md-12 pagination-photoshoot-align no-padding">
                     <ul class="pagination pagination-photoshoot">
                     <li><?php previous_posts_link(); ?></li>
                     <li><?php next_posts_link(); ?></span></li>
                   </ul>
                   </div>
                   <?php }
                 }// ?>
                   <!--Pagination End-->
                </div>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>			
<?php get_footer(); ?>