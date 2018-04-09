<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Sparkle_Store
 */
get_header(); ?>

<?php do_action('sparklestore-breadcrumbs'); ?>

<div class="inner_page">
    <div class="container">
        <div class="row">

            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <?php
                    while (have_posts()) : the_post();

                        get_template_part('template-parts/content', 'single');

                        the_post_navigation();

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </main>
            </div>

            <?php get_sidebar(); ?>

        </div>
    </div>
</div>

<?php
get_footer();
