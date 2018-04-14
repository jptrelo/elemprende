<?php
/**
* Sets setting field for homepage
* 
* @since  Bizplan 0.1
* @param  array $settings
* @return array Merged array of settings
*
*/
function bizplan_frontpage_settings( $settings ){

	$home_settings = array(
		# Settings for slider
		'slider_page' => array(
			'label'       => esc_html__( 'Slider Page', 'bizplan' ),
			'section'     => 'slider',
			'type'        => 'text',
			'description' => esc_html__( 'Input page id. Separate with comma. for eg. 2,9,23. Supports Maximum 3 sliders.', 'bizplan' )
		),
		'slider_control' => array(
			'label'     => esc_html__( 'Show Slider Control', 'bizplan' ),
			'section'   => 'slider',
			'type'      => 'checkbox',
			'transport' => 'postMessage',
		),
		'slider_autoplay' => array(
			'label'   => esc_html__( 'Slider Auto Play', 'bizplan' ),
			'section' => 'slider',
			'type'    => 'checkbox'
		),
		'slider_timeout' => array(
			'label'    => esc_html__( 'Slider Timeout ( in sec )', 'bizplan' ),
			'section'  => 'slider',
			'type'     => 'number'
		),

		# Settings for service section
		'service_main_page' => array(
			'label'   => esc_html__( 'Select Main Page for Services', 'bizplan' ),
			'section' => 'home_service',
			'type'    => 'dropdown-pages',
		),
		'service_page' => array(
			'label'   => esc_html__( 'Service Page', 'bizplan' ),
			'section' => 'home_service',
			'type'    => 'text',
			'description' => esc_html__( 'Input page id. Separate with comma. for eg. 2,9,23', 'bizplan' )
		),
		'disable_service' => array(
			'label'   => esc_html__( 'Disable Service Section', 'bizplan' ),
			'section' => 'home_service',
			'type'    => 'checkbox'
		),

		# Settings for about page
		'about_page' => array(
			'label'   => esc_html__( 'Select About Page', 'bizplan' ),
			'section' => 'home_about',
			'type'    => 'dropdown-pages',
		),
		
		# Settings for portfolio
		'portfolio_main_page' => array(
			'label'   => esc_html__( 'Select Main Page for Portfolio', 'bizplan' ),
			'section' => 'home_portfolio',
			'type'    => 'dropdown-pages',
		),
		'portfolio_page' => array(
			'label'   => esc_html__( 'Portfolio Pages', 'bizplan' ),
			'section' => 'home_portfolio',
			'type'    => 'text',
			'description' => esc_html__( 'Input page id. Separate with comma. for eg. 2,9,23', 'bizplan' )
		),
		'disable_portfolio' => array(
			'label'   => esc_html__( 'Disable Service Section', 'bizplan' ),
			'section' => 'home_portfolio',
			'type'    => 'checkbox'
		),

		# Settings for callback section
		'callback_page' => array(
			'label'   => esc_html__( 'Select a Callback Page', 'bizplan' ),
			'section' => 'home_callback',
			'type'    => 'dropdown-pages'
		),
		'callback_link_page' => array(
			'label'   => esc_html__( 'Select a Link Page', 'bizplan' ),
			'section' => 'home_callback',
			'type'    => 'dropdown-pages'
		),
		'disable_callback' => array(
			'label'   => esc_html__( 'Disable Callback Section', 'bizplan' ),
			'section' => 'home_callback',
			'type'    => 'checkbox'
		),

		# Settings for Testimonials
		'testimonial_main_page' => array(
			'label'   => esc_html__( 'Select a main page for Testimonial', 'bizplan' ),
			'section' => 'home_testimonial',
			'type'    => 'dropdown-pages'
		),
		'testimonial_page' => array(
			'label'   => esc_html__( 'Testimonial Pages', 'bizplan' ),
			'section' => 'home_testimonial',
			'type'    => 'text',
			'description' => esc_html__( 'Input page id. Separate with comma. for eg. 2,9,23', 'bizplan' )
		),
		'disable_testimonial' => array(
			'label'   => esc_html__( 'Disable Testimonial Section', 'bizplan' ),
			'section' => 'home_testimonial',
			'type'    => 'checkbox'
		),

		# Settings for Blog section
		'blog_main_page' => array(
			'label'   => esc_html__( 'Select a main page for Blog', 'bizplan' ),
			'section' => 'home_blog',
			'type'    => 'dropdown-pages'
		),
		'blog_category' => array(
			'label'   => esc_html__( 'Choose Blog Category', 'bizplan' ),
			'section' => 'home_blog',
			'type'    => 'dropdown-categories'
		),
		'blog_number' => array(
			'label' => esc_html__( 'Number of Posts', 'bizplan' ),
			'section' => 'home_blog',
			'type'    => 'number',
			'input_attrs' => array(
				'max' => 3,
				'min' => 1
			)
		),
		'disable_blog' => array(
			'label'   => esc_html__( 'Disable Blog Section', 'bizplan' ),
			'section' => 'home_blog',
			'type'    => 'checkbox'
		),
	);

	return array_merge( $home_settings, $settings );
}
add_filter( 'bizplan_customizer_fields', 'bizplan_frontpage_settings' );