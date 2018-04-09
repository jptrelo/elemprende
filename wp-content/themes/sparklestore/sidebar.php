<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sparkle_Store
 */
$post_sidebar = esc_attr( get_post_meta( $post->ID, 'sparklestore_page_layouts', true ) );

if(!$post_sidebar){
	$post_sidebar = 'rightsidebar';
}

if ( $post_sidebar ==  'nosidebar' ) {
	return;
}


if( $post_sidebar == 'rightsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebarone')){
	?>
		<aside id="secondaryright" class="widget-area right" role="complementary">
			<?php dynamic_sidebar( 'sparklesidebarone' ); ?>
		</aside><!-- #secondary -->
	<?php
}

if( $post_sidebar == 'leftsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sparklesidebartwo')){
	?>
		<aside id="secondaryleft" class="widget-area left" role="complementary">
			<?php dynamic_sidebar( 'sparklesidebartwo' ); ?>
		</aside><!-- #secondary -->
	<?php
}