<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ares
 */

get_header(); 

$ares_options = ares_get_options();
$alternate_blog = isset( $ares_options['blog_layout_style'] ) && $ares_options['blog_layout_style'] == 'masonry' ? true : false;

?>

<div id="primary" class="content-area">

    <main id="main" class="site-main">

        <header class="page-header">

            <div class="container">

                <div class="row">

                    <div class="col-sm-12">

                        <?php
                            the_archive_title( '<h1 class="page-title">', '</h1>' );
                            the_archive_description( '<div class="archive-description">', '</div>' );
                        ?>
                        
                    </div>

                </div>

            </div>

        </header><!-- .page-header -->

        <div class="container">

            <div class="frontpage row">

                <div class="col-sm-12">
                    
                    <?php if ( have_posts() ) :
                        
                        if ( $alternate_blog ) : ?>

                            <div id="ares-alt-blog-wrap">

                                <div id="masonry-blog-wrapper">

                                    <div class="grid-sizer"></div>
                                    <div class="gutter-sizer"></div>

                        <?php endif;
                        
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();

                            if ( $alternate_blog ) { 
                                get_template_part('template-parts/content', 'posts-alt' );
                            } else {
                                get_template_part('template-parts/content', 'posts' );
                            }

                        endwhile;
                        
                        if ( $alternate_blog ) : ?>

                                </div>
                            
                            </div>
                            
                        <?php endif;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif; ?>
                
                </div>
                
            </div>
        
        </div>

    </main><!-- #main -->

</div><!-- #primary -->

<?php
get_footer();
