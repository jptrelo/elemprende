<?php namespace Premmerce\SeoAddon\Frontend;

use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class Frontend
 * @package Premmerce\SeoAddon\Frontend
 */
class Frontend
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * Admin constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct($fileManager)
    {
        $this->fileManager = $fileManager;
        $this->registerHooks();
    }

    /**
     * Register frontend hooks
     */
    private function registerHooks()
    {
        add_filter('wpseo_json_ld_output', array($this, 'filterJsonLdOutput'), 10, 2);
        add_filter('woocommerce_structured_data_product', array($this, 'filterProductJsonLdOutput'), 10, 2);
        add_action('wpseo_opengraph', array($this, 'addOpenGraph'), 40);
        add_filter('get_post_metadata', array($this, 'changeImageAlt'), 10, 4);
    }

    /**
     * Generate schema json in main page
     *
     * @param array $returnArray
     * @param string $context
     *
     * @return array
     */
    public function filterJsonLdOutput($returnArray, $context)
    {
        $companyOptions = array('address', 'email', 'telephone', 'openingHours', 'paymentAccepted');

        if ($context == 'company') {
            foreach ($companyOptions as $option) {
                if (get_option('premmerce_seo_' . $option)) {
                    if ($option == 'email') {
                        $returnArray[ $option ] = 'mailto:' . get_option('premmerce_seo_' . $option);
                    } else {
                        $returnArray[ $option ] = get_option('premmerce_seo_' . $option);
                    }
                }
            }
        }

        return $returnArray;
    }

    /**
     * Generate schema json in product page
     *
     * @param array $markup
     * @param \WC_Product $product
     *
     * @return array
     */
    public function filterProductJsonLdOutput($markup, \WC_Product $product)
    {
        if ($product->get_price()) {
            $markup['offers'] = array(
                '@type'         => 'Offer',
                'price'         => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
            );

            if ($product->get_stock_status() == 'instock') {
                $markup['offers']['availability'] = 'http://schema.org/InStock';
            }
        }

        if ($product->get_image_id()) {
            $image           = wp_get_attachment_image_src($product->get_image_id());
            $markup['image'] = $image[0];
        }

        if (taxonomy_exists('product_brand')) {
            $brands = get_the_terms(get_the_ID(), 'product_brand');

            if (!empty($brands) && !is_wp_error($brands)) {
                $markup['brand'] = $brands[0]->name;
            }
        } elseif (get_option('premmerce_seo_brand_field')) {
            $brand = $product->get_attribute(get_option('premmerce_seo_brand_field'));

            if ($brand) {
                $markup['brand'] = $brand;
            }
        }

        if ($product->get_height()) {
            $markup['height'] = $product->get_height();
        }

        if ($product->get_width()) {
            $markup['width'] = $product->get_width();
        }

        if ($product->get_weight()) {
            $markup['weight'] = $product->get_weight();
        }

        return $markup;
    }

    /**
     * Generate opengraph meta
     */
    public function addOpenGraph()
    {
        if (get_post_type() == 'product') {
            global $product;

            if ($product->get_price()) {
                echo '<meta property="product:price:amount" content="' . $product->get_price() . '" />';
                echo '<meta property="product:price:currency" content="' . get_woocommerce_currency() . '" />';
            }

            if ($product->get_stock_status() == 'instock') {
                echo '<meta property="product:availability" content="instock" />';
            } else {
                echo '<meta property="product:availability" content="pending" />';
            }
        }
    }

    /**
     * Change image alt to product name in product
     *
     * @param string $value
     * @param int $objectId
     * @param string $metaKey
     * @param bool $single
     *
     * @return null|string
     */
    public function changeImageAlt($value, $objectId, $metaKey, $single)
    {
        if ($metaKey == '_wp_attachment_image_alt') {
            if (get_post_type() == 'product') {
                return get_the_title();
            }
        }

        return null;
    }
}
