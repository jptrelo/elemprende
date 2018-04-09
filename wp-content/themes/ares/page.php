<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ares
 */

get_header(); ?>

<div id="primary" class="content-area">

    <main id="main" class="site-main">

        <div class="container">

            <div class="frontpage">

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php 
                    
                    get_template_part( 'template-parts/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template
                    if (comments_open() || '0' != get_comments_number()) :
                        comments_template();
                    endif;

                    ?>

                <?php endwhile; // end of the loop.   ?>

            </div>

        </div>

    </main><!-- #main -->
    
</div><!-- #primary -->

<?php
get_footer();
