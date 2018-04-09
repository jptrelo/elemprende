<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package SweetHeat
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="sidebar" class="large-3 medium-4 columns show-for-medium-up" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
