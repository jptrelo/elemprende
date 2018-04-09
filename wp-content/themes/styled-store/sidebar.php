<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package styledstore
 */

if ( ! is_active_sidebar( 'styled_store_blog_right_sidebar' ) ) {
	return;
} ?>

<aside id="blog-right-sidebar" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'styled_store_blog_right_sidebar' ); ?>
</aside><!-- #secondary -->
