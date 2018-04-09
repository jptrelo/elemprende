<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package styledstore
 */

if ( ! is_active_sidebar( 'styled_store_blog_left_sidebar' ) ) {
	return;
} ?>

<aside id="blog-left-sidebar" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'styled_store_blog_left_sidebar' ); ?>
</aside><!-- #secondary -->
