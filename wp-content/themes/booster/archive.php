<?php get_header(); ?>
<div class="page-title">
  <div class="container">
    <div class="row">
      <div class="col-md-6  col-sm-6 no-padding">
        <?php if ( have_posts() ) : ?>      
	 		 <p class="booster-post-title"><?php if ( have_posts() ) : _e('Archives','booster'); echo " : ". get_the_date('M-Y'); endif;?></p>
		<?php endif; ?>
      </div>
      <div class="col-md-6 col-sm-6 no-padding">
        <ol class="archive-breadcrumb  pull-right">
          <?php if (function_exists('booster_custom_breadcrumbs')) booster_custom_breadcrumbs(); ?>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container no-padding-left"> 
    <div class="row">
      <div class="col-md-8 booster-post main">
        <?php if (have_posts() ) :
        while (have_posts()) : the_post(); ?>
        <article class="post clearfix">
          <div class="post-meta">
            <div class="post-meta-author">
              <div class="post-author-name blog-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               </div>
              <?php booster_entry_meta(); ?>
              <div class="clear-fix"></div>
              <?php the_tags(); ?>
            </div>
          </div>
           <?php $booster_feature_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
		   		 if($booster_feature_img != '') { ?>
                	<img src="<?php echo $booster_feature_img; ?>" class="img-responsive blog-page-image" />
           <?php } ?>
          <div class="post-content">
            <?php the_excerpt(); ?>
          </div>
        </article>
          <div class="blog-hr-archive"></div> 
        <?php endwhile;
          endif; ?>
	<!--Pagination Start-->
		<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
          if(is_plugin_active('faster-pagination/ft-pagination.php')) {
            faster_pagination();
          }else {
          if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
        <nav class="booster-nav">
          <span class="booster-nav-previous"><?php previous_posts_link(); ?></span>
          <span class="booster-nav-next"><?php next_posts_link(); ?></span>
        </nav>
        <?php }
        }//is plugin active ?>
		<!--Pagination End-->
      </div>
      <div class="col-md-4  blog-col-2 main-sidebar">
      	<?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>