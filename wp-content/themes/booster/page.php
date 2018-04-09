<?php
/*
 * Main Page Template. 
 */
get_header(); ?>
<section class="section-main back-img-aboutus">
  <div class="container">
    <div class="col-md-12 no-padding-left img-banner-aboutus">
    	 <p class="font-34 color-fff conter-text"> <?php the_title(); ?> </p>
         <p class="font-14 color-fff conter-text">  <?php if (function_exists('booster_custom_breadcrumbs')) booster_custom_breadcrumbs(); ?> </p>
    </div>
  </div>
</section>
<div class="container blog-background booster-page no-padding">
<?php while ( have_posts() ) : the_post(); ?>
	<div class="col-md-8 no-padding-left">
 <?php $booster_feature_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_id())); ?>
    	<div class="blog">
        	<?php if(!empty($booster_feature_img)){ ?>
            <img src="<?php echo $booster_feature_img; ?>" alt="" class="img-responsive blog-img" />
            <?php } ?>
            <h1 class="blog-title"><?php echo get_the_title(); ?></h1>
            <p class="blog-text">
              <?php the_content(); ?>
            </p>
            <div class="blog-hr"></div>
        </div>
<?php comments_template( '', true ); ?>
    </div> 
<?php endwhile; ?> 
<!-- right sidebar -->
       <div class="col-md-4 blog-col-2 main-sidebar">
		   <?php get_sidebar(); ?>
  	   </div>
       </div>
<?php get_footer(); ?>