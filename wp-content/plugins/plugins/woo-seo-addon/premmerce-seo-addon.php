<?php

use Premmerce\SeoAddon\SeoAddonPlugin;

/**
 * WooCommerce SEO Addon plugin
 *
 * @package           Premmerce\SeoAddon
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce SEO Addon
 * Plugin URI:        https://premmerce.com/woocommerce-seo-addon-yoast/
 * Description:       Premmerce WooCommerce SEO Addon plugin extends the functionality of Yoast SEO for microdata management.
 * Version:           1.1.4
 * Author:            premmerce
 * Author URI:        https://premmerce.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-seo-addon
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.5
 */

// If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}

call_user_func(function(){

	require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

	if(!get_option('premmerce_version')){
		require_once plugin_dir_path(__FILE__) . '/freemius.php';
	}

	$main = new SeoAddonPlugin(__FILE__);

	register_activation_hook(__FILE__, [$main, 'activate']);

	register_uninstall_hook(__FILE__, [SeoAddonPlugin::class, 'uninstall']);

	$main->run();
});
