<?php namespace Premmerce\SeoAddon;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\SDK\V2\Plugin\PluginInterface;
use Premmerce\SeoAddon\Admin\Admin;
use Premmerce\SeoAddon\Frontend\Frontend;

/**
 * Class SeoAddonPlugin
 * @package Premmerce\SeoAddon
 */
class SeoAddonPlugin implements PluginInterface
{
    /**
     * @var FileManager FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * PremmerceSeoPlugin constructor.
     *
     * @param $mainFile
     *
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile);
        $this->notifier    = new AdminNotifier();
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $this->registerHooks();
        if (is_admin()) {
            new Admin($this->fileManager);
        } else {
            new Frontend($this->fileManager);
        }
    }

    /**
     * Register plugin hooks
     */
    private function registerHooks()
    {
        add_action('init', array($this, 'loadTextDomain'));
        add_action('admin_init', array($this, 'checkRequirePlugins'));
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('woo-seo-addon', false, $name . '/languages/');
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', 'woo-seo-addon');

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'WooCommerce SEO Addon', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = array();
        if (!defined('WPSEO_VERSION')) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/wordpress-seo/">Yoast Seo</a>';
        }

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (!(is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php'))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
    }

    /**
     * Fired when the plugin is uninstalled
     */
    public static function uninstall()
    {
        delete_option('premmerce_seo_brand_field');
        delete_option('premmerce_seo_address');
        delete_option('premmerce_seo_email');
        delete_option('premmerce_seo_telephone');
        delete_option('premmerce_seo_openingHours');
        delete_option('premmerce_seo_paymentAccepted');
    }
}
