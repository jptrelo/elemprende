/*-----------------------------------------------------------------------------------*/
/* VW Ecommerce Shop */
/*-----------------------------------------------------------------------------------*/

Theme Name      :   VW Ecommerce Shop
Theme URI       :   https://www.vwthemes.com/free/wp-ecommerce-wordpress-theme/
Version         :   0.2.6
Tested up to    :   WP 4.9.4
Author          :   VWthemes
Author URI      :   https://www.vwthemes.com/
license         :   GNU General Public License v3.0
License URI     :   http://www.gnu.org/licenses/gpl.html

/*-----------------------------------------------------------------------------------*/
/* About Author - Contact Details */
/*-----------------------------------------------------------------------------------*/

email       	:   support@vwthemes.com

/*-----------------------------------------------------------------------------------*/
/* Description */
/*-----------------------------------------------------------------------------------*/
	VW E-commerce Shop theme is the one that can check all the boxes relating to every need of your store. Our multipurpose E-commerce WordPress theme is social media integrated & highly responsive. It is built on bootstrap 4 with using clean coding standards. It is cross-browser & woo commerce compatible, has Call to action button, its SEO & user-friendly and works at its optimal best across all platforms.  You may be a business owner, informative firm, travel agency, designing firm, artist, restaurant owner, construction agency, healthcare firm, digital marketing agency, blogger, corporate business, freelancers, online bookstore, mobile & tablet store, apparel store, fashion store, sport store, handbags store, cosmetics shop, jewellery store and etc. You can set all kinds of stores up with much ease using our theme, as it is made for people like you.  You could be a freelancer or a corporate entity or a sole proprietor. Our theme will boost your business and improve your revenue with the aid of seamless features and exclusive functionalities. Running an online E-commerce store along with your physical store is a hectic task. Your trouble is doubled, when you are not only supposed to take care of the physical presence of the store but you are also required to bring the online store up to speed. That is much like running two branches of a single business. You cannot possibly put your faith into sub-standard things and expect results. E-commerce store should have a theme that is both impressive and lucrative. This medium attracts customers from so many platforms that it becomes important for the theme and the webpage to perform at its 100% at all times. Check Our Demo: https://www.vwthemes.net/vw-ecommerce-theme/

/*-----------------------------------------------------------------------------------*/
/* Features */
/*-----------------------------------------------------------------------------------*/

Manage Slider, Product and footer from admin customizer vw setting section.

/*-----------------------------------------------------------------------------------*/
/* Theme Resources */
/*-----------------------------------------------------------------------------------*/

Theme is Built using the following resource bundles.

1 - All js that have been used are within folder /js of theme.
- jquery.nivo.slider.js, License: MIT, License Url: https://opensource.org/licenses/MIT
- Bootstrap.js => https://github.com/twbs/bootstrap/releases/download/v3.1.1/bootstrap-3.1.1-dist.zip
  Code and documentation copyright 2011-2016 Twitter, Inc. Code released under the MIT license. Docs released under Creative Commons.

2 - Open Sans font - https://www.google.com/fonts/specimen/Open+Sans
	PT Sans font - https://fonts.google.com/specimen/PT+Sans
	Roboto font - https://fonts.google.com/specimen/Roboto
	License: Distributed under the terms of the Apache License, version 2.0 http://www.apache.org/licenses/LICENSE-2.0.html

3 - Images used from Pixabay.
	Pixabay provides images under CC0 license (https://creativecommons.org/about/cc0)
	Screenshot Image urls
		https://www.pexels.com/photo/black-corded-headset-205926/
		https://www.pexels.com/photo/blur-box-business-checkered-shirt-297933/
		https://www.pexels.com/photo/man-using-laptop-on-table-against-white-background-257897/
		https://www.pexels.com/photo/footwear-leather-shoes-wear-267320/
		https://www.pexels.com/photo/black-and-white-blue-bottles-close-up-339835/
	
	
4 - CSS bootstrap.css
	Code and documentation copyright 2011-2016 Twitter, Inc. Code released under the MIT license. Docs released under Creative Commons.
	-- nivo-slider.css
	Free to use and abuse under the MIT license.
	http://www.opensource.org/licenses/mit-license.php
	-- font-awesome.css and fonts folder
	Font Awesome 4.6.3 by @davegandy - http://fontawesome.io - @fontawesome
 	License - http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)


All the slider images taken from pixabay under Creative Commons Deed CC0 - https://pixabay.com/en/service/terms/

All the icons taken from genericons licensed under GPL License.
http://genericons.com/

/*-----------------------------------------------------------------------------------*/
/* Steps to Setup Theme */
/*-----------------------------------------------------------------------------------*/

Below are the following step to setup theme static page.
=========================================================

Step 1. Add new page named as "home page" and select the template "Custom Home Page".

Step 2. Go to customizer >> static front page >> check Static page, then select the page which you have added example "home page".

For SLider
==============

Step 1. Add new page, add title, content and featured image and then publish it.

Step 2. Go to customizer >> VW Settings >> Slider settings >> here you can select the page which you have add for slider.

For Category
===============

Step 1. You have to add the categories in woocommerce then it will display below "ALL CATEGORIES". 

For Trending Products
======================

Step 1. Add the category in woocommerce product.

Step 2. Add the product in this category.

Step 3. Add new page, In this page add the woocommerce category sortcode "[product_category category="category-name" columns="4"]"

Step 4. Go to customizer >> VW Settings >> Trending Product >> here you can select the page which you have add for Product.

For Top Bar
=============

Step 1. Go to customizer >> VW Settings >> Topbar Section >> here you can add contact details and offer details example free shipping, cash on delivery etc.

For Social icon
=================

Step 1. Go to customizer >> VW Settings >> Social Icon Section >> here you can add social links.

For General Settings
======================

Step 1. Go to customizer >> VW Settings >> General Settings >> here you can change the layout of the blog post.

/*-----------------------------------------------------------------------------------*/
/* Changelog */
/*-----------------------------------------------------------------------------------*/

Version 0.1
	i) Intial version Released

Version 0.2
	i)   Console Error Removed
	ii)  Screenshot Change
	iii) Styling done

Version 0.2.1
	i) Improper use of esc_url function, url parameter should not be empty
		echo '<a href="';
			echo esc_url();
		echo '">';
	ii) No need to escape url when using on if conditionLicense Missing for header image( headphone ) of screenshot
	iii) License Missing for header image( headphone ) of screenshot
	iv) Use esc_url to escape url instead of esc_html
	v) Could you please tell me the reason to add this css on all admin pages. Add on specific admin pages only.
	vi) use wp_reset_postdata() to reset global $post variable. custom-home-page.php
	vii) post id will never be negative integer, so please use absint to esape postID.
		$mod = intval( get_theme_mod( 'vw_ecommerce_shop_page' . $count ));
	viii) There is no use of wp_reset_postdata() here
		$vw_ecommerce_shop_k = 0;
	ix) always follow late escaping. here you are escaping twice, just escpae in the point where you want to display the data
	x) get_posts() does not modify query post, so no need to use wp_reset_postdata()
	
Version 0.2.2
	i) Removed the default data represent content creation.
	ii) Changed the content creation to dynamic product category.

Version 0.2.3
	i) Added the woocommerce theme support.
	ii) Remove the unwanted code.
	iii) Did some customization.

Version 0.2.4
	i) Did the css changes in shop page.

Version 0.2.5
	i)   Set the logo title and description properly.
	ii) Removed the email code.
	iii)  Removed the template_part called to does not exist i.e. get_template_part( 'no-results', 'archive' );

Version 0.2.6
	i)   Update font url code in function.php file.
	ii)  Update fontawesome file.
	iii) Done the customization in footer.
	iv)  Change "text" to "url" in customizer.php file.

VW Ecommerce Shop Free version
==========================================================
VW Ecommerce Shop Free version is compatible with GPL licensed.

For any help you can mail us at support[at]vwthemes.com