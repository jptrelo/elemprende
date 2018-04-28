=== Login Widget With Shortcode ===
Contributors: avimegladon
Donate link: http://www.aviplugins.com/donate/
Tags: login, widget, login widget, widget login, sidebar login, login form, user login, authentication, facebook login, twitter login, google login, google plus, facebook, twitter, social login, social media, facebook comments, fb comment, forgot password, reset password, link
Requires at least: 2.0.2
Tested up to: 4.9.4
Stable tag: 5.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple login form in the widget. This will allow users to login to the site from frontend. 

== Description ==

* This is a simple login form in the widget.
* Compatible with WordPress Multisite Installation.
* Use this shortcode [login_widget] to use login form in your pages/ posts. 
* Just install the plugin and add the login widget in the sidebar. 
* Change some 'optional' settings in `Login Widget Settings` (admin panel left side menu) and you are good to go. 
* Add CSS as you prefer because the form structure is really very simple.
* Use this shortcode [forgot_password] in your page to display the forgot password form. Forgot password link can be added to login widget from plugin settings page.
* Login form is responsive.
* Plugin is compatible with <strong>WPML</strong> plugin. You can check the compatibility at <a href="https://wpml.org/plugin/login-widget-with-shortcode/" target="_blank">wpml.org</a>.

= Other Optional Options =
* Add CAPTCHA security in admin and frontend login forms.
* Login Logs are stored in database ( IP, login status, login time ). PRO version has options to block IPs after certain numbers of wrong login attempts.
* You can choose the redirect page after login. It can be a page or a custom URL.
* Choose redirect page after logout.
* Choose user profile page.
* Easy CSS implementation from admin panel.

= Facebook Login Widget (PRO) =
There is a PRO version of this plugin that supports login with <strong>Facebook</strong>, <strong>Google</strong>, <strong>Twitter</strong>, <strong>LinkedIn</strong>, <strong>Amazon</strong> and <strong>Instagram</strong> accounts. You can get it <a href="http://www.aviplugins.com/fb-login-widget-pro/" target="_blank">here</a> in <strong>USD 4.00</strong>

<a href="http://www.aviplugins.com/demo/login/" target="_blank">Click here for a Live Demo</a>

* The PRO version comes with a <strong>FREE Content Restriction Addon</strong>. Partial contents of Pages/ Posts or the complete Page/Post can be hidden from visitors of your site.
* Compatible with <strong>WooCommerce</strong> plugin.
* Compatible with <strong>WordPress Multisite</strong> Installation.
* Login Logs are stored in database. IPs gets <strong>Blocked</strong> after a certain numbers of wrong login attempts. This ensures site's security.
* IPs can be <strong>Blocked</strong> permanently from admin panel.
* <strong>Captcha</strong> login securiy in Frontend and Admin login Forms.
* <strong>Restrict Admin panel Access</strong> for selected user Roles. For example you can restrict Admin Panel access for "Subscriber" and "Contributor" from your site.
* Use Shortcode to display login form in Post or Page.
* Use only Social Icons for logging in. No need to put the entire login form.
* Change welcome text "Howdy" from plugin settings section.
* Manage Forgot Password Email Body.
* Easy CSS implementation from admin panel.
* And with other useful settings. <a href="http://www.aviplugins.com/fb-login-widget-pro/" target="_blank">Click here for details</a>


> Check out <strong>Social Login No Setup</strong> that supports login with <strong>Facebook</strong>, <strong>Google</strong>, <strong>Twitter</strong>, <strong>LinkedIn</strong>, <strong>WordPress</strong> and <strong>Microsoft</strong> accounts and the most important part is that it requires no Setups, no Maintanance, no need to create any APPs, APIs, Client Ids, Client Secrets or anything. <a href="http://www.aviplugins.com/social-login-no-setup/" target="_blank">Click here for details</a> | <a href="http://aviplugins.com/demo/social-login/" target="_blank">Click here for Live Demo</a>

= Facebook Comments Extension =
This Add-on can be used to replace the default Wordpress Comments and insert Facebook or Disqus or Google+ Comments system in your site. You can get it <a href="http://www.aviplugins.com/fb-comments-afo-addon" target="_blank">here</a> in <strong>USD 1.00</strong>

* Facebook Comments.
* Disqus Comments.
* Google+ Comments.
* Globally turn off comments.

> Post your plugin related queries at <a href="http://www.aviplugins.com/support.php">http://www.aviplugins.com/support.php</a>

== Installation ==

1. Upload `login-sidebar-widget.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to `Login Widget Settings` and set some optional options. It's really easy.
4. Go to `Login Widget Settings -> Login Logs` to check the user login log.
4. Go to `Appearance->Widgets` in available widgets you will find `Login Widget AFO` widget, drag it to chosen widget area where you want it to appear.
5. You can also use shortcodes to insert login form in post or pages. [login_widget title="Login Here"]
5. Now visit your site and you will see the user login form.

= Redirection After Login =
To restrict a page from non logged in users and redirect users to requested URL after successful login, add this code in the top of the page template.
 
> if ( !is_user_logged_in() ) { <br>
wp_redirect('http://www.example.com/login/?redirect='.login_wid::curPageURL());<br>
exit;<br>
}<br> Change "http://www.example.com/login/" to your login page url.
 
= Translations =

* The Serbo-Croatian Language translation file is provided by <a href="http://www.webhostinghub.com" target="_blank">Web Hosting Hub</a>  
* Chinese translation is provided by Tianming Wu 
* Portuguese (European) translation is provided by David Costa 
* Spanish translation is provided by Javier   
* Finnish translation is provided by Tomi Yl&auml;-Soininm&auml;ki, Katja Lampela
* Persian translation is provided by Salman Amini
* Dutch translation is provided by Baree van Vugt
* Italian translation is provided by Filippo Antonacci
* Brazilian Portuguese translation is provided by Edu Musa
* Polish translation is provided by Mateusz W&oacute;jcik
* German translation is provided by Benjamin Hartwich 
* Hungarian translation is provided by Attila Kiss


== Frequently Asked Questions ==

= For any kind of queries =

1. Please email me demoforafo@gmail.com. Contact me at http://www.aviplugins.com/support.php
2. Or you can write comments directly to my plugins page. Please visit here http://avifoujdar.wordpress.com/2014/02/13/login-widget/

* If you want to translate the plugin in your language please translate the sample .PO file and mail me the the file at demoforafo@gmail.com and I will include that in the language file. Sample .PO file can be downloaded from <a href="http://www.aviplugins.com/language-sample/login-sidebar-widget-es_ES.po">here</a>

== Screenshots ==

1. Login widget
2. Login widget
3. Admin login with captcha security
4. General Settings
5. Security Settings
6. Error Message Settings
7. Style Settings
8. Email Settings
9. Users Login Log
10. Forgot Password form
11. Facebook Comments Addon
12. WPML Plugin compatibility Certificate.


== Changelog ==

= 5.7.1 = 
* Default plugin style updated.

= 5.6.8 = 
* Bug Fixed. 

= 5.6.7 = 
* More filters and hooks are added in the plugin. 

= 5.6.6 = 
* pagination class updated. 

= 5.6.5 = 
* loopback related bug fixed. 

= 5.6.4 = 
* support for redirect_to parameter in login form added.

= 5.6.3 = 
* Error message display updated.

= 5.6.2 = 
* Confliction with WP Register Profile PRO plugin bug fixed.

= 5.6.1 = 
* Validation functionality updated. Captcha image updated. Login form design updated.

= 5.6.0 = 
* Password code updated.

= 5.5.8 = 
* Password field bug fixed.

= 5.5.7 = 
* Plugin is now compatible with <strong>WPML</strong> Plugin.

= 5.5.6 =
* Plugin is now compatible with <a href="https://wordpress.org/plugins/google-authenticator/">Google Authenticator</a> plugin.

= 5.5.5 =
* Plugin settings panel design updated.

= 5.5.4 =
* Option to redirect users to requested URL after successful login.

= 5.5.3 =
* Bug fixed.

= 5.5.2 =
* Notice message bug fixed.

= 5.5.1 =
* Bug fixed.

= 5.5.0 =
* Forgot Password functionality updated.

= 5.4.1 =
* Option to change Error Login Message from plugin settings section.

= 5.4.0 =
* Now Compatible with WordPress Multisite.

= 5.3.0 =
* Code updated for compatibility.

= 5.2.6 =
* Styling/ CSS updated in login form.

= 5.2.5 =
* Login Log section updated in admin panel. Option added to Clear / Empty Login Log data.

= 5.2.4 =
* Plugin message display updated.

= 5.2.3 =
* User Login Log feature implemented.

= 5.2.2 =
* Hooks are added for compatibility.

= 5.2.1 =
* plugin code modifications.

= 5.2.0 =
* Code updated with some security modifications.

= 5.1.5 =
* Captcha security added in admin and frontend login forms.

= 5.1.4 =
* plugin code optimized.

= 5.1.3 =
* news dashboard widget optimized.

= 5.1.2 =
* Settings saved message in admin panel.

= 5.1.1 =
* Option to add after login redirect URL with redirect to page option.

= 5.1.0 =
* Forgot password form email address added. aviplugins.com dashboard news widget added.

= 5.0.0 =
* forms structure is updated, Now with fully responsive login form. Make sure to reload the default styling of the plugin from plugin settings page.

= 4.2.4 =
* forms structure is updated.

= 4.2.3 =
* Language selection bug fixed for <a href="http://www.aviplugins.com/fb-comments-afo-addon/">Facebook Comments Add On</a>

= 4.2.2 =
* Login and Logout page redirection modified.

= 4.2.1 =
* Remember me issue fixed.

= 4.2.0 =
* Help and Support link added.

= 4.1.0 =
* Plugin notice message bug fixed.

= 4.0.0 =
* forgot password functionality added.

= 3.2.1 =
* Security related bug fixed. Advisory https://security.dxw.com/advisories/csrfxss-vulnerablity-in-login-widget-with-shortcode-allows-unauthenticated-attackers-to-do-anything-an-admin-can-do/

= 3.1.1 =
* Localization is added.

= 2.2.4 =
* admin menu related bug fixed.

= 2.2.3 =
* Added support for css.

= 2.1.3 =
* Modified error message display.

= 2.0.2 =
* CSS file bug issue is solved.

= 2.0.1 =
* Shortcode functionality is added.

= 1.0.1 =
* this is the first release.


== Upgrade Notice ==

= 1.0 =
I will update this plugin when ever it is required.
