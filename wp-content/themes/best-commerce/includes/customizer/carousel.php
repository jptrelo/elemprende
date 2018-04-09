<?php
/**
 * Theme Options related to featured carousel.
 *
 * @package Best_Commerce
 */

$default = best_commerce_get_default_theme_options();

// Featured Carousel Section.
$wp_customize->add_section( 'section_theme_carousel_type',
	array(
	'title'      => esc_html__( 'Featured Carousel', 'best-commerce' ),
	'priority'   => 22,
	'capability' => 'edit_theme_options',
	)
);

// Setting featured_carousel_status.
$wp_customize->add_setting( 'theme_options[featured_carousel_status]',
	array(
	'default'           => $default['featured_carousel_status'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_checkbox_multiple',
	)
);
$wp_customize->add_control(
	new Best_Commerce_Checkbox_Multiple_Control( $wp_customize, 'theme_options[featured_carousel_status]',
		array(
			'label'    => esc_html__( 'Enable Carousel On', 'best-commerce' ),
			'section'  => 'section_theme_carousel_type',
			'settings' => 'theme_options[featured_carousel_status]',
			'priority' => 100,
			'choices'  => best_commerce_get_carousel_status_options(),
		)
	)
);

// Setting featured_carousel_type.
$wp_customize->add_setting( 'theme_options[featured_carousel_type]',
	array(
	'default'           => $default['featured_carousel_type'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[featured_carousel_type]',
	array(
	'label'           => esc_html__( 'Select Carousel Type', 'best-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'select',
	'priority'        => 100,
	'choices'         => best_commerce_get_featured_carousel_type(),
	'active_callback' => 'best_commerce_is_featured_carousel_active',
	)
);

// Setting featured_carousel_number.
$wp_customize->add_setting( 'theme_options[featured_carousel_number]',
	array(
	'default'           => $default['featured_carousel_number'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_number_range',
	)
);
$wp_customize->add_control( 'theme_options[featured_carousel_number]',
	array(
	'label'           => esc_html__( 'No of Items', 'best-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'number',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_featured_carousel_active',
	'input_attrs'     => array( 'min' => 2, 'max' => 20, 'step' => 1, 'style' => 'width: 55px;' ),
	)
);

// Setting featured_carousel_category.
$wp_customize->add_setting( 'theme_options[featured_carousel_category]',
	array(
		'default'           => $default['featured_carousel_category'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new Best_Commerce_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[featured_carousel_category]',
		array(
			'label'           => esc_html__( 'Select Category', 'best-commerce' ),
			'section'         => 'section_theme_carousel_type',
			'settings'        => 'theme_options[featured_carousel_category]',
			'priority'        => 100,
			'active_callback' => 'best_commerce_is_featured_category_carousel_active',
		)
	)
);

// Setting featured_carousel_product_category.
$wp_customize->add_setting( 'theme_options[featured_carousel_product_category]',
	array(
		'default'           => $default['featured_carousel_product_category'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new Best_Commerce_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[featured_carousel_product_category]',
		array(
			'label'           => esc_html__( 'Select Product Category', 'best-commerce' ),
			'section'         => 'section_theme_carousel_type',
			'settings'        => 'theme_options[featured_carousel_product_category]',
			'priority'        => 100,
			'taxonomy'        => 'product_cat',
			'active_callback' => 'best_commerce_is_featured_product_category_carousel_active',
		)
	)
);

// Setting featured_carousel_enable_autoplay.
$wp_customize->add_setting( 'theme_options[featured_carousel_enable_autoplay]', array(
	'default'           => $default['featured_carousel_enable_autoplay'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_checkbox',
) );
$wp_customize->add_control( 'theme_options[featured_carousel_enable_autoplay]', array(
	'label'           => esc_html__( 'Enable Autoplay', 'best-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'checkbox',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_featured_carousel_active',
) );

// Setting featured_carousel_transition_delay.
$wp_customize->add_setting( 'theme_options[featured_carousel_transition_delay]', array(
	'default'           => $default['featured_carousel_transition_delay'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_number_range',
) );
$wp_customize->add_control( 'theme_options[featured_carousel_transition_delay]', array(
	'label'           => esc_html__( 'Transition Delay', 'best-commerce' ),
	'description'     => esc_html__( 'in seconds', 'best-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'number',
	'priority'        => 100,
	'input_attrs'     => array( 'min' => 1, 'max' => 10, 'step' => 1, 'style' => 'width: 55px;' ),
	'active_callback' => 'best_commerce_is_featured_carousel_active',
) );

// Setting featured_carousel_widget_heading.
$wp_customize->add_setting( 'theme_options[featured_carousel_widget_heading]', array(
	'default'           => '',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control(
	new Best_Commerce_Heading_Control( $wp_customize, 'theme_options[featured_carousel_widget_heading]',
		array(
			'label'           => esc_html__( 'Widgets Section', 'best-commerce' ),
			'section'         => 'section_theme_carousel_type',
			'settings'        => 'theme_options[featured_carousel_widget_heading]',
			'priority'        => 100,
			'active_callback' => 'best_commerce_is_featured_carousel_active',
		)
	)
);

// Setting featured_carousel_widget_alignment.
$wp_customize->add_setting( 'theme_options[featured_carousel_widget_alignment]', array(
	'default'           => $default['featured_carousel_widget_alignment'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
) );
$wp_customize->add_control( 'theme_options[featured_carousel_widget_alignment]', array(
	'label'           => esc_html__( 'Widget Section Alignment', 'best-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'select',
	'priority'        => 100,
	'choices'         => best_commerce_get_featured_carousel_widget_alignment(),
	'active_callback' => 'best_commerce_is_featured_carousel_active',
) );
