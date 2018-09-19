=== WooCommerce PayU EU Payment Gateway ===
Contributors: payu.pl
Donate link: https://www.payu.pl/en
Tags: woocommerce, PayU, payment, payment gateway, platnosci, PayU Poland, PayU EU
Requires at least: 3.5.1
Tested up to: 4.9
Stable tag: 1.2.5
License: GPLv2 

== Description ==

PayU EU Payment Gateway supports:

* Card payments in various currencies  
* Polish online transfers and installments
* Czech online transfers

= Features =

The PayU Payment Gateway for WooCommerce adds the PayU payment option and enables you to process the following operations in your shop:

* Creating a payment order
* Updating order status (canceling/completing an order will simultaneously update payment's status)
* Conducting a refund operation (for a whole or partial order)

= Usage =

PayU Payment Gateway is visible for your customers as a single "Buy and Pay" button during checkout. After clicking the button customer is redirected to the Payment Summary page to choose payment method. After successful payment customer is redirected back to your shop.

== Installation ==

If you do not already have PayU merchant account [please register in Production](https://secure.payu.com/boarding/#/form&pk_campaign=WordpressPlugins&pk_kwd=WooCommerce) or [please register in Sandbox](https://secure.snd.payu.com/boarding/#/form&pk_campaign=WordpressPlugins&pk_kwd=WooCommerce).

**Important:** This plugin works only with **REST API (checkout) points of sales (POS)**.

**If you have any questions or issues, feel free to contact our technical support: tech@payu.pl**

In the Wordpress administration panel:

1. Go to **WooCommerce** -> **Settings section**
1. Choose **Checkout** tab and scroll down to the **"Payment Gateways"** section
1. Choose **Settings** option next to the **PayU** name
1. Enable and configure the plugin

== Changelog ==

= 1.2.6 =
* Sandbox added
* Cleanup code
= 1.2.5 =
* PayU SDK update
= 1.2.4 =
* Fix calculate price in products
* Update SDK
= 1.2.3 =
* Fix for WooCommerce 3.x
= 1.2.2 =
* added language to redirect URI
* added e-mail notification
* added stock reduce
= 1.2.1 =
* Fixed extOrderId when other plugin changes WooCommerce order number
= 1.2.0 =
* Add Oauth support
= 1.1.1 =
* fix notifications
= 1.1.0 =
* remove many pos config for currency

