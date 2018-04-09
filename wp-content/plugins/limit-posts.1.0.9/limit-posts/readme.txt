=== Plugin Name ===
Contributors: PluginCentral
Donate link: www.limitposts.com/donate/
Tags: limit posts, limit number of posts, limit publish, limit post creation, limit post type, limit user, posts per user, restrict posts, restrict number of posts
Requires at least: 4.0.0
Tested up to: 4.3
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to allow administrators to limit the number of posts a user can publish or submit for review in a given time period.

== Description ==

This plugin allows you to limit the number of posts (any post type, including custom post types) that a user can publish or submit for review within a specified time period.

You can set up as many limits as you like, for different post types, different user roles, whatever you like. When a user tries to create a new post, the limits are checked, if the user has exceeded any of the limits, they wont be able to publish.

= Main Features =

*	Limit the creation of any post type.
*	Limit the number of posts by user or by user role.
*	Specify 0 to block a post type completely.
*	Time period can be in seconds, minutes, hours, days, weeks, months or years
*	Make a limit last forever by specifying 9999 years
*	A shortcode can be added to your forms and pages to use this functionality

== Installation ==

= Automatic Installation =

1. In your WordPress dashboard go to Plugins > Add New > search for limit posts.
2. Click Install Now on the Limit Posts plugin. Make sure you install the correct plugin, Limit Posts by PluginCentral

= Manual Installation =

1.	Click the Download Version button above, save the file to a handy location.
2.	Extract the zip file to path_to_your_site/wp-content/plugins/, this should create a limit-posts folder.

= Enable the plugin =

In your WordPress dashboard go to Plugins > Installed Plugins > click Activate just under Limit Posts.

= Using this plugin =

In your WordPress dashboard go to Settings > Limit Posts. Here you'll be able to set up limits.

NB When you add a new limit, edit a limit or delete a limit, you must click Save Changes. If you don't click Save Changes, your changes will be lost.

== Frequently Asked Questions ==

= I've found a bug, what do I do? =

Click on the View support forum button on the right.

= What happens when a limit is reached? =

A message will be displayed, informing the user that the limit has been reached. The publish section will not be displayed, so the user will not be able to attempt to publish a post.

= How can I use the shortcode? = 

To use the limit rules functionality on your own forms you'll need to add a shortcode to your page:

`[limit_posts] your form code [/limit_posts]`

The following parameters can be used with the shortcode:

`type - this is the post type that the form is dealing with.
	Values for this can be any post type or custom post type, that you currently have.
	Default is post.
action - this is the action which the form submit will take.
	Values for this can be publish (where submit will publish) and submit for review (where submit will submit for review).
	Default is publish`
	
Example:

'[limit_posts type="post" action="publish"] your form code [/limit_posts]'

= Are there any filter hooks? =

`'limit_posts_shortcode_ok' - when a user can see the form.`
`'limit_posts_shortcode_limit_exceeded' - when the user limit is reached.`
`'limit_posts_shortcode_not_logged_in' - when user is not logged in.`

= Can I limit total publications for a particular post type? =

Yes, simply create a rule as you normally would, but set the Category to ALL. So all users, whatever category they are in, will be checked against this rule.


== Screenshots ==

1.	Settings > Limit Posts admin panel, showing rules.
2. 	Adding or editing a new rule.
3.	publication blocked message.

== Changelog ==

= 1.0.0 = 
Initial release

= 1.0.1 =
Minor bug fixes

= 1.0.2 = 
Minor bug fixes. 
Rules can now be created for individual users.

= 1.0.3 = 
Publish or Submit for review can now be chosen for a rule.

= 1.0.5 = 
Bug fix - undefined index publish_action limit-posts 205.

= 1.0.6 = 
Bug fix - undefined index publish_action limit-posts 332.

= 1.0.7 = 
Bug fix - Disable post css not always being enqueued correctly.
Bug fix - Post type not being shown correctly when editing a rule.

= 1.0.8 = 
Sorted out some issues with the text domain, for translation purposes.

= 1.0.9 = 
Add All user category so admins can limit total publications for a post type.

== Upgrade Notice ==

= 1.0 = 
Initial release

= 1.0.3 = 
Publish or Submit For Review can now be chosen for a rule.