<?php $ares_options = ares_get_options(); ?>

<div class="blog-roll-item">

    <article data-link="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>" id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class(); ?>>

        <?php if ( isset( $ares_options['ares_blog_featured'] ) && $ares_options['ares_blog_featured'] == 'on' && has_post_thumbnail() ) : ?>
        
            <div class="image">
                <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                    <?php the_post_thumbnail( 'large' ); ?>
                </a>
            </div>

        <?php endif; ?>
        
        <div class="inner">

            <?php if ( isset( $ares_options['alt_blog_show_category'] ) && $ares_options['alt_blog_show_category'] == 'on' ) : ?>  
                <h6 class="post-category">
                    <?php $categories = get_the_category(); ?>
                    <?php if ( ! empty( $categories ) ) : ?>

                        <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                            <?php echo esc_html( $categories[0]->name ); ?>
                        </a>

                    <?php endif; ?>
                </h6>
            <?php endif; ?>
            
            <h3 class="post-title">
                <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                    <?php echo esc_html( get_the_title() ); ?>
                </a>
            </h3>
            
            <hr>
            
            <?php $words = isset( $ares_options['alt_blog_word_trim'] ) ? $ares_options['alt_blog_word_trim'] : 40; ?>
            
            <?php if ( $words > 0 ) : ?>
                <div class="post-content">
                    <?php echo esc_html( wp_trim_words( get_the_excerpt(), $words, '...' ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( isset( $ares_options['alt_blog_show_date'] ) && $ares_options['alt_blog_show_date'] == 'on' || isset( $ares_options['alt_blog_show_author'] ) && $ares_options['alt_blog_show_author'] == 'on' ) : ?>
                <h5 class="post-meta">
                    <?php echo isset( $ares_options['alt_blog_show_date'] ) && $ares_options['alt_blog_show_date'] == 'on' ? esc_html( get_the_date( get_option( 'date_format' ) ) ) : ''; ?>
                    <?php if ( isset( $ares_options['alt_blog_show_author'] ) && $ares_options['alt_blog_show_author'] == 'on' ) : ?>    
                        <?php _e( 'by', 'ares' ); ?> <span class="post-author"><?php the_author_posts_link(); ?></span>
                    <?php endif; ?>
                </h5>
            <?php endif; ?>
            
            <?php if ( isset( $ares_options['alt_blog_hover_icon'] ) ) : ?>
            
                <div class="image-corner"></div>
                <a href="<?php the_permalink() ?>">
                    <i class="<?php echo esc_attr( $ares_options['alt_blog_hover_icon'] ); ?> icon"></i>
                </a>
                
            <?php endif; ?>
            
        </div>

    </article>

</div>