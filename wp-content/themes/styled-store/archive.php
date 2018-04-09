<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Styled Store
 * @since StyledStore 1.0
 */
get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

       <header class="page-header">
            <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </header><!-- .page-header -->
    </div>        
    	<div class="col-md-9 col-sm-8">
    		<div id="primary" class="content-area">
    			<main id="main" class="site-main" role="main">
    		        <?php if ( have_posts() ) :
    					while ( have_posts() ) : the_post();

    						/*
    						 * Include the Post-Format-specific template for the content.
    						 * If you want to override this in a child theme, then include a file
    						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
    						 */
    						get_template_part( 'template-parts/content', 'archive' );

    				    // End the loop.
    					endwhile;
                        //creat pag navigation
                        styledstore_paging_nav();
    				// If no content, include the "No posts found" template.
    				else :
    					get_template_part( 'template-parts/content', 'none' );
    				endif;	?>
    			</main><!-- #main .site-main -->
    		</div>
    	</div><!-- #primary .content-area -->
    	<div class="col-md-3 col-sm-4">
    		<?php get_sidebar(); ?>
    	</div>
	</div> <!-- #div coloumn  -->	
</div>	
<?php get_footer();