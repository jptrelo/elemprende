<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @since Bizplan 0.1
 */

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
	return;
}
?>

<div class="col-xs-12 col-sm-5 col-md-4" >
	<sidebar class="sidebar clearfix" id="primary-sidebar">
	<?php dynamic_sidebar( 'right-sidebar' ); ?>
	</sidebar>
</div>