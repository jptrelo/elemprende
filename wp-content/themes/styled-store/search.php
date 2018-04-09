<?php
/**
 * The template for displaying search results pages. 
 * @package Styledstore
 * @since 1.0.0
 */

get_header(); ?>


<div class="container">
    <div class="row">
        <div class="col-md-12">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'styled-store' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;

		else:

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
        
        </div>
    </div>
</div>


<?php get_footer();
