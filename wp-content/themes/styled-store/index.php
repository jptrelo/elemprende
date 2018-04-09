<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package styledstore
 */
get_header();

$bloglayout = get_theme_mod( 'blog_layout', 'blogright' );
		
switch ($bloglayout) {
	case 'blogright': ?>

		<div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-8">
					<div id="primary" class="content-area">
						<?php do_action( 'styledstore_post_loop' ); ?>
					</div>
				</div><!-- #primary .content-area -->
				<div class="col-md-3 col-sm-4">
					<?php get_sidebar(); ?>
				</div>
			</div> <!-- #div coloumn  -->
		</div><!-- .container -->
	<?php break;
	// Left sidebar
	case 'blogleft' : ?>
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-4">
					<?php get_sidebar( 'left' ); ?>
				</div>
				<div class="col-md-9 col-sm-8">
					<div id="primary" class="content-area">
						<?php do_action( 'styledstore_post_loop' ); ?>
					</div>	
				</div><!-- #primary .content-area -->
			</div> <!-- #div coloumn  -->
		</div><!-- .container -->
	<?php break;

	// Full width no sidebars
	case 'blogwide': ?>

		<div class="container">
			<div class="row">
				<div class="col-md-12 ">
					<div id="primary" class="content-area">
						<?php do_action( 'styledstore_post_loop' ); ?>
					</div>
				</div><!-- #primary .content-area -->
			</div> <!-- #div coloumn  -->
		</div><!-- .container -->
	<?php break;
	//this is for default case
	default: ?>

		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<div id="primary" class="content-area">
						<?php do_action( 'styledstore_post_loop' ); ?>
					</div>
				</div><!-- #primary .content-area -->
				<div class="col-md-3">
					<?php get_sidebar(); ?>
				</div>
			</div> <!-- #div coloumn  -->
		</div><!-- .container -->
	<?php break;
}

get_footer();
