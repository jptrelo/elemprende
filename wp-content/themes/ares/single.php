<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Ares
 */

$ares_options = ares_get_options();

get_header(); ?>

<div id="primary" class="content-area">

    <main id="main" class="site-main">

        <div class="container">
    
            <?php while ( have_posts() ) : the_post(); ?>
    
                <div class="page-content row ">
                    
                    <div class="col-md-<?php echo is_active_sidebar(1) && $ares_options['ares_single_layout'] == 'col2r' ? '8' : '12'; ?>">
                    
                        <?php if ( isset( $ares_options['single_post_layout_style'] ) && $ares_options['single_post_layout_style'] == 'alternate' ) : ?>
                        
                            <?php get_template_part( 'template-parts/content', 'single-alt' ); ?>
                        
                        <?php else : ?>
                        
                            <article class="item-page">

                                <h2 class="post-title">
                                    <?php the_title(); ?>
                                </h2>

                                <div class="avenue-underline"></div>

                                <div class="entry-content">

                                    <?php $ares_options['ares_single_featured'] == 'on' ? the_post_thumbnail( 'medium' ) : ''; ?>

                                    <?php the_content(); ?>

                                    <?php echo $ares_options['ares_single_date'] == 'on' ? __( 'Posted on: ', 'ares' ) . esc_html( get_the_date() ) : ''; ?><?php echo $ares_options['ares_single_author'] == 'on' && $ares_options['ares_single_date'] == 'on' ? __( ', ', 'ares' ) : ''; ?>

                                    <?php echo $ares_options['ares_single_author'] == 'on' ? __( 'by : ', 'ares' ) . get_the_author_posts_link() : ''; ?>

                                    <?php 

                                    wp_link_pages(array(
                                        'before' => '<div class="page-links">' . __( 'Pages:', 'ares' ),
                                        'after' => '</div>',
                                    ));

                                    if (comments_open() || '0' != get_comments_number()) :
                                        comments_template();
                                    endif;

                                    ?>

                                </div>
                                
                            </article>
                        
                        <?php endif; ?>
                        
                    </div>

                    <?php if ( is_active_sidebar(1) && $ares_options['ares_single_layout'] == 'col2r' ) : ?>

                        <div class="col-md-4 avenue-sidebar">
                            <?php get_sidebar(); ?>
                        </div>

                    <?php endif; ?>

                </div>

            <?php endwhile; // end of the loop. ?>

        </div>
        
    </main><!-- #primary -->
    
</div><!-- #primary -->

<?php get_footer();
