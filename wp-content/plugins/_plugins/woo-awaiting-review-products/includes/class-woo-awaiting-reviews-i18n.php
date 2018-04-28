<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.nw2web.com.br
 * @since      1.0.0
 *
 * @package    Woo_Awaiting_Reviews
 * @subpackage Woo_Awaiting_Reviews/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Awaiting_Reviews
 * @subpackage Woo_Awaiting_Reviews/includes
 * @author     Fausto Rodrigo Toloi <fausto@nw2web.com.br>
 */
class Woo_Awaiting_Reviews_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-awaiting-reviews',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
