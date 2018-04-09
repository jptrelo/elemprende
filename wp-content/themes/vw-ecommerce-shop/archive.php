<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package VW Ecommerce Shop
 */

get_header(); ?>

<div class="container">
  <div class="middle-align">
    <?php
        $theme_lay = get_theme_mod( 'vw_ecommerce_shop_theme_options','One Column');
        if($theme_lay == 'Left Sidebar'){ ?>
        <div class="col-md-4 col-sm-4" id="sidebar"><?php dynamic_sidebar('sidebar-1');?></div>
        <section id="our-services" class="services col-md-8 col-sm-8">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>         
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
        <div class="clearfix"></div>
    <?php }else if($theme_lay == 'Right Sidebar'){ ?>
        <section id="our-services" class="services col-md-8 col-sm-8">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>          
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
        <div class="col-md-4 col-sm-4" id="sidebar"><?php dynamic_sidebar('sidebar-1');?></div>
    <?php }else if($theme_lay == 'One Column'){ ?>
        <section id="our-services" class="services col-md-12">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>          
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
    <?php }else if($theme_lay == 'Three Columns'){ ?>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-1');?></div>
        <section id="our-services" class="services col-md-6 col-sm-6">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>          
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-2');?></div>
    <?php }else if($theme_lay == 'Four Columns'){ ?>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-1');?></div>
        <section id="our-services" class="services col-md-3 col-sm-3">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>          
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-2');?></div>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-3');?></div>
    <?php }else if($theme_lay == 'Grid Layout'){ ?>
        <section id="our-services" class="services flipInX col-md-9 col-sm-9">
          <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="taxonomy-description">', '</div>' );
          ?>          
          <?php if ( have_posts() ) :
            /* Start the Loop */
              
              while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/grid-layout' ); 
              
              endwhile;

              else :

                get_template_part( 'no-results' ); 

              endif; 
          ?>
          <div class="navigation">
            <?php
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( 'Previous page', 'vw-ecommerce-shop' ),
                    'next_text'          => __( 'Next page', 'vw-ecommerce-shop' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vw-ecommerce-shop' ) . ' </span>',
                ) );
            ?>
              <div class="clearfix"></div>
          </div>
        </section>
        <div class="col-md-3 col-sm-3" id="sidebar"><?php dynamic_sidebar('sidebar-1');?></div>
    <?php } ?>
    <div class="clearfix"></div>
  </div>
</div>
<div class="clearfix"></div>

<?php get_footer(); ?>