<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package styledstore
 */

get_header(); ?>
	
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title">
								<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'styled-store' ); ?>
							</h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'styled-store' ); ?>
							</p>
							<div class="styledstore-404-search">
								<?php get_search_form(); ?>
							</div>
							
						</div><!-- .page-content -->
					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		<div class="col-md-3" id="404notfound-sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>			

<?php
get_footer();
