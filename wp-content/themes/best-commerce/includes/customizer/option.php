<?php
/**
 * Theme Options.
 *
 * @package Best_Commerce
 */

$default = best_commerce_get_default_theme_options();

// Setting show_title.
$wp_customize->add_setting( 'theme_options[show_title]',
	array(
	'default'           => $default['show_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[show_title]',
	array(
	'label'    => esc_html__( 'Show Site Title', 'best-commerce' ),
	'section'  => 'title_tagline',
	'type'     => 'checkbox',
	'priority' => 11,
	)
);

// Setting show_tagline.
$wp_customize->add_setting( 'theme_options[show_tagline]',
	array(
	'default'           => $default['show_tagline'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[show_tagline]',
	array(
	'label'    => esc_html__( 'Show Tagline', 'best-commerce' ),
	'section'  => 'title_tagline',
	'type'     => 'checkbox',
	'priority' => 11,
	)
);

// Add theme options panel.
$wp_customize->add_panel( 'theme_option_panel',
	array(
	'title'      => esc_html__( 'Theme Options', 'best-commerce' ),
	'priority'   => 21,
	'capability' => 'edit_theme_options',
	)
);

// Header Section.
$wp_customize->add_section( 'section_header',
	array(
	'title'      => esc_html__( 'Header Options', 'best-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting enable_offer.
$wp_customize->add_setting( 'theme_options[enable_offer]',
	array(
		'default'           => $default['enable_offer'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[enable_offer]',
	array(
		'label'    => esc_html__( 'Enable Offer', 'best-commerce' ),
		'section'  => 'section_header',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting offer_title.
$wp_customize->add_setting( 'theme_options[offer_title]',
	array(
		'default'           => $default['offer_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[offer_title]',
	array(
		'label'           => esc_html__( 'Offer Title', 'best-commerce' ),
		'section'         => 'section_header',
		'type'            => 'text',
		'priority'        => 100,
		'active_callback' => 'best_commerce_is_offer_active',
	)
);

// Setting offer_link_text.
$wp_customize->add_setting( 'theme_options[offer_link_text]',
	array(
		'default'           => $default['offer_link_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[offer_link_text]',
	array(
		'label'           => esc_html__( 'Offer Link Text', 'best-commerce' ),
		'section'         => 'section_header',
		'type'            => 'text',
		'priority'        => 100,
		'active_callback' => 'best_commerce_is_offer_active',
	)
);

// Setting offer_link_url.
$wp_customize->add_setting( 'theme_options[offer_link_url]',
	array(
		'default'           => $default['offer_link_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control( 'theme_options[offer_link_url]',
	array(
		'label'           => esc_html__( 'Offer Link URL', 'best-commerce' ),
		'section'         => 'section_header',
		'type'            => 'url',
		'priority'        => 100,
		'active_callback' => 'best_commerce_is_offer_active',
	)
);

// Setting enable_quick_contact.
$wp_customize->add_setting( 'theme_options[enable_quick_contact]',
	array(
		'default'           => $default['enable_quick_contact'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[enable_quick_contact]',
	array(
		'label'    => esc_html__( 'Enable Quick Contact', 'best-commerce' ),
		'section'  => 'section_header',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting contact_number.
$wp_customize->add_setting( 'theme_options[contact_number_title]',
	array(
	'default'           => $default['contact_number_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[contact_number_title]',
	array(
	'label'           => esc_html__( 'Contact Title', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);
$wp_customize->add_setting( 'theme_options[contact_number]',
	array(
	'default'           => $default['contact_number'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[contact_number]',
	array(
	'label'           => esc_html__( 'Contact Number', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);

// Setting contact_email.
$wp_customize->add_setting( 'theme_options[contact_email_title]',
	array(
	'default'           => $default['contact_email_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[contact_email_title]',
	array(
	'label'           => esc_html__( 'Email Title', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);
$wp_customize->add_setting( 'theme_options[contact_email]',
	array(
	'default'           => $default['contact_email'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_email',
	)
);
$wp_customize->add_control( 'theme_options[contact_email]',
	array(
	'label'           => esc_html__( 'Contact Email', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);

// Setting contact_email.
$wp_customize->add_setting( 'theme_options[contact_address_title]',
	array(
	'default'           => $default['contact_address_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[contact_address_title]',
	array(
	'label'           => esc_html__( 'Address Title', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);

// Setting contact_address.
$wp_customize->add_setting( 'theme_options[contact_address]',
	array(
	'default'           => $default['contact_address'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[contact_address]',
	array(
	'label'           => esc_html__( 'Contact Address', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);

// Setting contact_address_url.
$wp_customize->add_setting( 'theme_options[contact_address_url]',
	array(
	'default'           => $default['contact_address_url'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control( 'theme_options[contact_address_url]',
	array(
	'label'           => esc_html__( 'Address URL', 'best-commerce' ),
	'description'     => esc_html__( 'eg, Google map URL', 'best-commerce' ),
	'section'         => 'section_header',
	'type'            => 'url',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_quick_contact_active',
	)
);

// Setting show_social_in_header.
$wp_customize->add_setting( 'theme_options[show_social_in_header]',
	array(
		'default'           => $default['show_social_in_header'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[show_social_in_header]',
	array(
		'label'       => esc_html__( 'Enable Social Icons', 'best-commerce' ),
		'description' => esc_html__( 'Do not forget to create social menu and assign it to Social menu location.', 'best-commerce' ),
		'section'     => 'section_header',
		'type'        => 'checkbox',
		'priority'    => 100,
	)
);

// Menu Section.
$wp_customize->add_section( 'section_menu',
	array(
	'title'      => esc_html__( 'Menu Options', 'best-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting show_search_in_header.
$wp_customize->add_setting( 'theme_options[show_search_in_header]',
	array(
		'default'           => $default['show_search_in_header'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[show_search_in_header]',
	array(
		'label'    => esc_html__( 'Enable Search Form', 'best-commerce' ),
		'section'  => 'section_menu',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting enable_category_menu.
$wp_customize->add_setting( 'theme_options[enable_category_menu]',
	array(
		'default'           => $default['enable_category_menu'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'best_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[enable_category_menu]',
	array(
		'label'    => esc_html__( 'Enable Category Menu', 'best-commerce' ),
		'section'  => 'section_menu',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting category_menu_label.
$wp_customize->add_setting( 'theme_options[category_menu_label]',
	array(
	'default'           => $default['category_menu_label'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[category_menu_label]',
	array(
	'label'           => esc_html__( 'Category Menu Label', 'best-commerce' ),
	'section'         => 'section_menu',
	'type'            => 'text',
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_category_menu_active',
	)
);

// Setting category_menu_type.
$wp_customize->add_setting( 'theme_options[category_menu_type]',
	array(
	'default'           => $default['category_menu_type'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[category_menu_type]',
	array(
	'label'           => esc_html__( 'Category Menu Type', 'best-commerce' ),
	'section'         => 'section_menu',
	'type'            => 'select',
	'priority'        => 100,
	'choices'         => best_commerce_get_category_menu_type_options(),
	'active_callback' => 'best_commerce_is_category_menu_active',
	)
);

// Setting category_menu_depth.
$wp_customize->add_setting( 'theme_options[category_menu_depth]',
	array(
	'default'           => $default['category_menu_depth'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'theme_options[category_menu_depth]',
	array(
	'label'           => esc_html__( 'Category Menu Depth', 'best-commerce' ),
	'section'         => 'section_menu',
	'type'            => 'number',
	'priority'        => 100,
	'input_attrs'     => array( 'min' => 0, 'max' => 10, 'style' => 'width: 55px;' ),
	'active_callback' => 'best_commerce_is_category_menu_active',
	)
);

// Layout Section.
$wp_customize->add_section( 'section_layout',
	array(
	'title'      => esc_html__( 'Layout Options', 'best-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting global_layout.
$wp_customize->add_setting( 'theme_options[global_layout]',
	array(
	'default'           => $default['global_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[global_layout]',
	array(
	'label'    => esc_html__( 'Global Layout', 'best-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => best_commerce_get_global_layout_options(),
	'priority' => 100,
	)
);

// Setting archive_layout.
$wp_customize->add_setting( 'theme_options[archive_layout]',
	array(
	'default'           => $default['archive_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[archive_layout]',
	array(
	'label'    => esc_html__( 'Archive Layout', 'best-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => best_commerce_get_archive_layout_options(),
	'priority' => 100,
	)
);

// Setting archive_image.
$wp_customize->add_setting( 'theme_options[archive_image]',
	array(
	'default'           => $default['archive_image'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[archive_image]',
	array(
	'label'    => esc_html__( 'Image in Archive', 'best-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => best_commerce_get_image_sizes_options(),
	'priority' => 100,
	)
);

// Setting archive_image_alignment.
$wp_customize->add_setting( 'theme_options[archive_image_alignment]',
	array(
	'default'           => $default['archive_image_alignment'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'theme_options[archive_image_alignment]',
	array(
	'label'           => esc_html__( 'Image Alignment in Archive', 'best-commerce' ),
	'section'         => 'section_layout',
	'type'            => 'select',
	'choices'         => best_commerce_get_image_alignment_options(),
	'priority'        => 100,
	'active_callback' => 'best_commerce_is_image_in_archive_active',
	)
);

// Footer Section.
$wp_customize->add_section( 'section_footer',
	array(
	'title'      => esc_html__( 'Footer Options', 'best-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting copyright_text.
$wp_customize->add_setting( 'theme_options[copyright_text]',
	array(
	'default'           => $default['copyright_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[copyright_text]',
	array(
	'label'    => esc_html__( 'Copyright Text', 'best-commerce' ),
	'section'  => 'section_footer',
	'type'     => 'text',
	'priority' => 100,
	)
);

// Blog Section.
$wp_customize->add_section( 'section_blog',
	array(
	'title'      => esc_html__( 'Blog Options', 'best-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting excerpt_length.
$wp_customize->add_setting( 'theme_options[excerpt_length]',
	array(
	'default'           => $default['excerpt_length'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'best_commerce_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'theme_options[excerpt_length]',
	array(
	'label'       => esc_html__( 'Excerpt Length', 'best-commerce' ),
	'description' => esc_html__( 'in words', 'best-commerce' ),
	'section'     => 'section_blog',
	'type'        => 'number',
	'priority'    => 100,
	'input_attrs' => array( 'min' => 1, 'max' => 200, 'style' => 'width: 55px;' ),
	)
);
// Setting read_more_text.
$wp_customize->add_setting( 'theme_options[read_more_text]',
	array(
	'default'           => $default['read_more_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'theme_options[read_more_text]',
	array(
	'label'    => esc_html__( 'Read More Text', 'best-commerce' ),
	'section'  => 'section_blog',
	'type'     => 'text',
	'priority' => 100,
	)
);
