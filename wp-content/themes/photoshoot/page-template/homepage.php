<?php 
/*
 * Template Name: Home Page
 */
get_header();
global $photoshoot_options; ?>

<div id="photoshootcarousel" class="carousel slide photoshoot-slider" data-interval="3000" data-ride="carousel"> 
  <!-- Carousel indicators -->
  <!-- Carousel items -->
  <div class="carousel-inner">
    <?php $photoshoot_slider_count = 0;
	 for($photoshoot_loop=0 ; $photoshoot_loop <5 ; $photoshoot_loop++):
    if(!empty($photoshoot_options['slider-image-'.$photoshoot_loop])){ 
		$photoshoot_slider_count++;
		if($photoshoot_loop== 0) $photoshoot_class='active';else $photoshoot_class=''; ?>
    <div class="<?php echo $photoshoot_class; ?> item"> <img src="<?php echo esc_url($photoshoot_options['slider-image-'.$photoshoot_loop]); ?>" alt="photoshoot">
      <?php if((!empty($photoshoot_options['slider-title-'.$photoshoot_loop])) || (!empty($photoshoot_options['slider-caption-'.$photoshoot_loop]))): ?>
      <div class="carousel-caption">
        <h3>
          <?php if((!empty($photoshoot_options['slider-title-'.$photoshoot_loop]))) echo esc_attr($photoshoot_options['slider-title-'.$photoshoot_loop]); ?>
        </h3>
        <p>
          <?php if((!empty($photoshoot_options['slider-caption-'.$photoshoot_loop]))) echo esc_attr($photoshoot_options['slider-caption-'.$photoshoot_loop]); ?>
        </p>
      </div>
      <?php endif; ?>
    </div>
    <?php } ?>
    <?php endfor;?>
  </div>
  <!-- Carousel nav --> 
  <?php if($photoshoot_slider_count != 0) { ?>
  <a class="carousel-control left" href="#photoshootcarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left carousel-control-left"></span> </a> <a class="carousel-control right" href="#photoshootcarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right carousel-control-right"></span></a>
  <?php } ?>
  </div>
<div class="detail-section">
  <div class="container photoshoot-container">
    <div class="row">
      <div class="col-md-9">
		<?php $photoshoot_top_args=array(
            'post_type'=>'post',
            'post_status'=>'publish',
            'meta_key'     => 'post_views_count',
            'orderby'      => 'meta_value_num', 
            'order'      => 'DESC',
            'posts_per_page'=> -1,
        ); 
        $photoshoot_top_query=new WP_Query($photoshoot_top_args);
        if($photoshoot_top_query->have_posts()): ?>
        <div class="photos-group">
          <h2 class="photoshoot-title"><span><span class="title-color"><?php _e('Top','photoshoot'); ?></span> <?php _e('Photos','photoshoot'); ?></span></h2>
          <div id="owl-demo" class="owl-carousel photos-slider">
          <?php while($photoshoot_top_query->have_posts()) : $photoshoot_top_query->the_post(); ?>
          <?php  $photoshoot_top_image_array = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'photoshoot-topphoto-width' );
          if(!empty($photoshoot_top_image_array[0])): ?>
            <div class="item"> <a href="<?php echo  esc_url(get_permalink()); ?>"><img class="lazyOwl" data-src="<?php echo $photoshoot_top_image_array[0]; ?>" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>"></a>
              <div class="photos-details">
                <?php photoshoot_entry_meta(); ?>
              </div>
            </div>
	        <?php endif;
          endwhile; ?>            
          </div>
<?php $photoshoot_pid=get_option('page_for_posts');
if(!empty($photoshoot_pid)){ ?>
  <div class="col-md-12 photos-group-btn"> <a href="<?php echo  esc_url(get_permalink($photoshoot_pid)); ?>" class="btn-photoshoot btn-white"><?php _e('View All','photoshoot'); ?></a> </div>
<?php } ?>
        </div>
        <?php endif;
        $photoshoot_latest_args=array(
            'post_type'=>'post',
            'post_status'=>'publish',
            'order'=>'DESC',
            'orderby'=>'post_date',
            'posts_per_page'=> -1,
        ); 
        $photoshoot_latest_query=new WP_Query($photoshoot_latest_args);
        if($photoshoot_latest_query->have_posts()): ?>
        <div class="photos-group">
          <h2 class="photoshoot-title"><span><span class="title-color"><?php _e('Latest','photoshoot') ?></span> <?php _e('Photos','photoshoot') ?> </span></h2>
          <div id="latest-photos" class="owl-carousel photos-slider">
            <?php while($photoshoot_latest_query->have_posts()) : $photoshoot_latest_query->the_post();
            $photoshoot_latest_image_array = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'photoshoot-topphoto-width' ); 
            if(!empty($photoshoot_latest_image_array[0])): ?>
            <div class="item"> <a href="<?php echo  esc_url(get_permalink()); ?>"><img class="lazyOwl" data-src="<?php echo $photoshoot_latest_image_array[0]; ?>" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>"></a>
              <div class="photos-details">
                <?php photoshoot_entry_meta(); ?>
              </div>
            </div>
	        <?php endif;
          endwhile; ?>
          </div>
          <?php $photoshoot_pid=get_option('page_for_posts');
          if(!empty($photoshoot_pid)){ ?>
          <div class="col-md-12 photos-group-btn"> <a href="<?php echo  esc_url(get_permalink($photoshoot_pid)); ?>" class="btn-photoshoot btn-white"><?php _e('View All','photoshoot'); ?></a></div>
          <?php } ?>
        </div>
        <?php endif; ?>
      </div>
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>