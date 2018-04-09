<?php
/**
 * The template used for displaying page content in front-page.php
 *
 * @package ares
 */

$ares_options = ares_get_options();

?>

<div class="homepage-content">
        
    <div class="col-md-<?php echo $ares_options['ares_homepage_sidebar'] == 'sidebar-on' && is_active_sidebar(1) ? '8' : '12'; ?>">

        <article id="post-<?php the_ID(); ?>">

            <header class="entry-header">
                <?php the_title( '<h2 class="post-title">', '</h2>' ); ?>
                <div class="avenue-underline"></div>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php the_content(); ?>
                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'ares' ),
                    'after' => '</div>',
                ) );
                ?>
            </div><!-- .entry-content -->
            <footer class="entry-footer">
                <?php edit_post_link( __( 'Edit', 'ares' ), '<span class="edit-link">', '</span>' ); ?>
            </footer><!-- .entry-footer -->

        </article><!-- #post-## -->

    </div>

    <?php if ( $ares_options['ares_homepage_sidebar'] == 'sidebar-on' && is_active_sidebar(1) ) : ?>

        <div class="col-md-4 avenue-sidebar">
            <?php get_sidebar(); ?>
        </div>

    <?php endif; ?>

</div>
