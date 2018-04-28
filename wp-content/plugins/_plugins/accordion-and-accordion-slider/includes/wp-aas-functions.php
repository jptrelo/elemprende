<?php
/**
 * Plugin generic functions file
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Escape Tags & Slashes
 *
 * Handles escapping the slashes and tags
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_esc_attr($data) {
    return esc_attr( stripslashes($data) );
}

/**
 * Strip Slashes From Array
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_slashes_deep($data = array(), $flag = false) {
  
    if($flag != true) {
        $data = wp_aas_nohtml_kses($data);
    }
    $data = stripslashes_deep($data);
    return $data;
}

/**
 * Strip Html Tags 
 * 
 * It will sanitize text input (strip html tags, and escape characters)
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

function wp_aas_nohtml_kses($data = array()) {
  
  if ( is_array($data) ) {
    
    $data = array_map('wp_aas_nohtml_kses', $data);
    
  } elseif ( is_string( $data ) ) {
    $data = trim( $data );
    $data = wp_filter_nohtml_kses($data);
  }
  
  return $data;
}

/**
 * Function to unique number value
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_get_unique() {
	static $unique = 0;
	$unique++;

	return $unique;
}

/**
 * Function to add array after specific key
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_add_array(&$array, $value, $index, $from_last = false) {
    
    if( is_array($array) && is_array($value) ) {

        if( $from_last ) {
            $total_count    = count($array);
            $index          = (!empty($total_count) && ($total_count > $index)) ? ($total_count-$index): $index;
        }
        
        $split_arr  = array_splice($array, max(0, $index));
        $array      = array_merge( $array, $value, $split_arr);
    }
    
    return $array;
}

/**
 * Function to get registered post types
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_get_post_types() {
	
	// Getting registered post type
	$post_type_args = array(
		'public' => true
	);
	$custom_post_types = get_post_types($post_type_args);
	$custom_post_types = (!empty($custom_post_types) && is_array($custom_post_types)) ? array_keys($custom_post_types) : array();
	
	// Exclude some post type
	$include_post_types = apply_filters('wp_aas_gallery_support', array(WP_AAS_POST_TYPE));
	$custom_post_types = array_merge($custom_post_types, (array)$include_post_types);
	
	// Exclude some post type
	$exclude_post_types = apply_filters('wp_aas_gallery_support', array('attachment'));
	$custom_post_types = array_diff($custom_post_types, (array)$exclude_post_types);
	
	return $custom_post_types;
}