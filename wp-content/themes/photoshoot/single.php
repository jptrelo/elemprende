<?php
/*
 * Single Post Template File.
 */
get_header(); ?>
<div class="breadcrumb-photoshoot">
    <div class="container photoshoot-container">
        <ul class="breadcrumb">
            <li class="active"><?php photoshoot_title(); ?></li>
        </ul>
    </div>
</div>
<div class="detail-section">
    <div class="container photoshoot-container">
        <div class="row">
            <div class="col-md-9">
                <?php if (have_posts()) : while (have_posts()) : the_post(); 
                photoshoot_setPostViews(get_the_ID()); ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="row photo-details">
                            <div class="col-md-7">
                                <?php $photoshoot_feat_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); 
                                if(empty($photoshoot_feat_image))
                                  $photoshoot_feat_image=  get_template_directory_uri().'/images/no-image.jpg';?>
                                    <img src="<?php echo  esc_url($photoshoot_feat_image); ?>" alt="photo detail" class="img-responsive">
                            </div>
                            <div class="col-md-5">
                                <div class="photoshoot-widget">
                                    <?php the_content();
                                    wp_link_pages(array(
                                        'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'photoshoot') . '</span>',
                                        'after' => '</div>',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                    )); ?>
                                </div>
                                <?php $photoshoot_tags = '<h3>'.__('Tags','photoshoot').''.get_the_tag_list(': ', ', ').'</h3>';
                                if (!empty($photoshoot_tags)): ?>
                                    <div class="photoshoot-widget photo-tags">
                                        <?php echo $photoshoot_tags; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                </div>
               <div class="col-md-12 photoshoot-post-comment no-padding">
                    <?php comments_template('', true); ?>
                </div>
                <?php endwhile;
                endif; ?>
                <div class="col-md-12 photoshoot-default-pagination">
                    <span class="default-previous-link"><?php previous_post_link(); ?></span>
                    <span class="default-next-link"><?php next_post_link(); ?></span>
                </div>
                <?php global $post;
                $photoshoot_related_args = array(
                    "post_type" => 'post',
                    "post_status" => "publish",
                    "post__not_in" => array($post->ID),
                    "order" => "desc",
                    "orderby" => "post_date"
                );
                $photoshoot_related_query = new WP_Query($photoshoot_related_args);
                if ($photoshoot_related_query->have_posts()): ?>
                    <div class="photos-group">
                        <h2 class="photoshoot-title"><span><span class="title-color"><?php _e('Related','photoshoot') ?></span><?php _e('Photos','photoshoot') ?> </span></h2>                    <div class="masonry-container">
                            <?php while ($photoshoot_related_query->have_posts()): $photoshoot_related_query->the_post();
                                $photoshoot_related_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'photoshoot-topphoto-width');
                                if(empty($photoshoot_related_image_array[0]))
                                    $photoshoot_feat_image=  get_template_directory_uri().'/images/no-imageall.jpg';
                              else
                                $photoshoot_feat_image=$photoshoot_related_image_array[0]; ?>
                                    <div class="col-md-4 no-padding box">
                                      <div class="article">
                                        <div class="photos-box">
                                            <a href="<?php echo  esc_url(get_permalink()); ?>"><img src="<?php echo  esc_url($photoshoot_feat_image); ?>"  alt="<?php echo get_the_title(); ?>" class="img-responsive"></a>
                                            <div class="photos-details">
                                                <?php photoshoot_entry_meta(); ?>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>