<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://miplugins.com
 * @since             1.0.2
 * @package           MI_logo_slider
 *
 * @wordpress-plugin
 * Plugin Name:       MI Logo Slider
 * Plugin URI:        https://miplugins.com/plugin/mi-logo-slider
 * Description:       Mi Logo Slider is perfect to showcase clients, partners, teams, sponsors logos.
 * Version:           1.0.2
 * Author:            Mi Plugins
 * Author URI:        https://miplugins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mi-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mi-plugin-activator.php
 */
function activate_mi_plugin() {
    $compare_plugins = array('mi-logo-slider-pro/mi-logo-slider-pro.php','mi-logo-slider-vc/mi-logo-slider-vc.php');

    $target = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );


    if(count(array_intersect($compare_plugins, $target))>0){
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Please make sure "MI Logo Slider Pro" & "MI Logo Slider VC" is deactivated", Before Activate "MI Logo Slider"' );
	}
	
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mi-plugin-activator.php';
	Mi_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mi-plugin-deactivator.php
 */
function deactivate_mi_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mi-plugin-deactivator.php';
	Mi_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mi_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mi_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mi-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mi_plugin() {

	$plugin = new Mi_Plugin();
	$plugin->run();

}
run_mi_plugin();
