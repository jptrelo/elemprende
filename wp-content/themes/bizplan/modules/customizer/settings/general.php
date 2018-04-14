<?php
/**
* Sets settings for general fields
*
* @since  Bizplan 0.1
* @param  array $settings
* @return array Merged array
*/

function bizplan_customizer_general_settings( $settings ){

	$general = array(
		'site_title_color' => array(
			'label'     => esc_html__( 'Site Title', 'bizplan' ),
			'transport' => 'postMessage',
			'section'   => 'colors',
			'type'      => 'colors',
		),
		'site_tagline_color' => array(
			'label'     => esc_html__( 'Site Tagline', 'bizplan' ),
			'transport' => 'postMessage',
			'section'   => 'colors',
			'type'      => 'colors',
		),
		'site_primary_color' => array(
			'label'     => esc_html__( 'Primary', 'bizplan' ),
			'section'   => 'colors',
			'type'      => 'colors',
		),

		# Theme Options Section
		'footer_text' =>  array(
			'label'     => esc_html__( 'Footer Text', 'bizplan' ),
			'section'   => 'footer_options',
			'type'      => 'textarea'
		),
		'disable_footer_widget' => array(
			'label'   => esc_html__( 'Disable Footer Widget', 'bizplan' ),
			'section' => 'footer_options',
			'type'    => 'checkbox' 
		),

		'menu_padding_top' => array(
			'label'     => esc_html__( 'Additional Space on top of Menu.', 'bizplan' ),
			'section'   => 'general_options',
			'type'      => 'number',
			'transport' => 'postMessage'
		),
		'enable_scroll_top_in_mobile' => array(
			'label'     => esc_html__( 'Enable Scroll top in mobile', 'bizplan' ),
			'section'   => 'general_options',
			'transport' => 'postMessage',
			'type'      => 'checkbox' ,
		)
	);

	return array_merge( $settings, $general );
}
add_filter( 'bizplan_customizer_fields', 'bizplan_customizer_general_settings' );