<?php
/*
 * Posts Template
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */

$ares_options = ares_get_options();

?>
<div class="col-md-3 col-sm-3 col-xs-6">
    <a href="<?php the_permalink(); ?>">
        <div class="item-post <?php echo has_post_thumbnail() && $ares_options['ares_blog_featured'] == 'on' ? '' : 'text-left'; ?>">
            
            <?php if ( has_post_thumbnail() && $ares_options['ares_blog_featured'] == 'on' ) : ?>
            
                <div class="post-thumb col-md-12">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('large'); ?>
                    </a>
                </div>
            
            <?php endif; ?>
            
            <!-- <div class="col-md-<?php echo has_post_thumbnail() && $ares_options['ares_blog_featured'] == 'on' ? '8' : '12'; ?>"> -->
            <div class="col-md-12">
                <h2 class="post-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <div class="post-content">
                    <?php echo wp_trim_words( $post->post_content, 20); ?>
                </div>
                <div class="text-right">
                    <a class="ares-button button-primary" href="<?php the_permalink(); ?>"><?php echo esc_html_e( 'Read More', 'ares' ); ?></a>
                </div>                        
            </div>
            
        </div>
    </a>
</div>