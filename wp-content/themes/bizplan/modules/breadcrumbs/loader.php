<?php
/**
* Bizplan breadcrumb.
*
* @since Bizplan 0.1
* @uses breadcrumb_trail()
*/
require get_parent_theme_file_path( '/modules/breadcrumbs/breadcrumbs.php' );
if ( ! function_exists( 'bizplan_breadcrumb' ) ) :

	function bizplan_breadcrumb() {

		$breadcrumb_args = apply_filters( 'bizplan_breadcrumb_args', array(
			'show_browse' => false,
		) );

		breadcrumb_trail( $breadcrumb_args );
	}

endif;

function bizplan_modify_breadcrumb( $crumb ){

	$i = count( $crumb ) - 1;
	$title = bizplan_remove_pipe( $crumb[ $i ] );

	$crumb[ $i ] = $title;

	return $crumb;
}
add_filter( 'breadcrumb_trail_items', 'bizplan_modify_breadcrumb' );