<?php
/**
* Sets sections for Bizplan_Customizer
*
* @since  Bizplan 0.1
* @param  array $sections
* @return array Merged array
*/
function bizplan_customizer_sections( $sections ){

	$bizplan_sections = array(
		
		# Section for Fronpage panel
		'slider' => array(
			'title' => esc_html__( 'Slider', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_about' => array(
			'title' => esc_html__( 'About', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_service' => array(
			'title' => esc_html__( 'Service', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_portfolio' => array(
			'title' => esc_html__( 'Portfolio', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_callback' => array(
			'title' => esc_html__( 'Callback', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_testimonial' => array(
			'title' => esc_html__( 'Testimonial', 'bizplan' ),
			'panel' => 'frontpage_options'
		),
		'home_blog' => array(
			'title' => esc_html__( 'Blog', 'bizplan' ),
			'panel' => 'frontpage_options'
		),

		# Section for Theme Options panel
		'general_options' => array(
			'title' => esc_html__( 'General', 'bizplan' ),
			'panel' => 'theme_options'
		),
		'footer_options' => array(
			'title' => esc_html__( 'Footer', 'bizplan' ),
			'panel' => 'theme_options'
		)
	);

	return array_merge( $bizplan_sections, $sections );
}
add_filter( 'bizplan_customizer_sections', 'bizplan_customizer_sections' );