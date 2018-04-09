<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ares
 */
?>

<div class="page-content nothing-found-index">

    <article>

        <div class="widget widget_categories">

            <h2 class="widgettitle center">
                <h3 class="center"><?php _e( 'Nothing Found', 'ares' ); ?></h3>
                <div class="center mt20">
                    <?php get_search_form(); ?>
                </div>
            </h2>

        </div><!-- .widget -->

    </article>

</div><!-- .page-content -->
