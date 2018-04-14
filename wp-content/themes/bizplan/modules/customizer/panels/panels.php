<?php
/**
* Sets the panels and returns to Bizplan_Customizer
*
* @since  Bizplan 0.1
* @param  array An array of the panels
* @return array
*/
function bizplan_customizer_panels( $panels ){

	$panels = array(
		'frontpage_options' => array(
			'title' => esc_html__( 'Front Page Options', 'bizplan' )
		),
		'theme_options' => array(
			'title' => esc_html__( 'Theme Options', 'bizplan' )
		)
	);

	return $panels;	
}
add_filter( 'bizplan_customizer_panels', 'bizplan_customizer_panels' );