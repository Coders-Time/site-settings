=== Site-settings ===
Contributors: coderstime,lincolndu
Tags: site, settings, site-settings
Requires at least: 4.9 or higher
Tested up to: 5.6
Requires PHP: 5.6
License: GPLv2 (or later)
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
Stable tag: 1.0.0


Site-settings is the ultimate plugin for making all the necessary changes in the custom fields of a website. 

== Description ==
Site-settings is an official plugin maintained by the Coderstime team that adds an extra feature on the "Settings" option on the admin dashboard. It makes it possible to use plugins that extend that screen, add clean meta boxes.

Site Settings is an official Coderstime plugin, and will be fully supported and maintained until at least 2022, or as long as is necessary.

At a glance, this plugin adds the following:

Administrators can make the default changes for all users. The form includes the change in the site's logo, title, tagline, email, phone, address, copyrighted text along with the change in the social media links that are to be posted in the website.
When allowed, the plugin will ask for information to change and once the changes are submitted, there will be a confirmation box to alert the admin. Only admin will have the privilege to make all the necessary changes.
Each post opens in the last editor used regardless of who edited it last. This is important for maintaining a consistent experience when editing content.

By default, this plugin hides all functionality available in the new block editor (“Gutenberg”).

== Installation ==
For installation, it is quite easy. Anyone can install it from the the plugin repository and afterwards can be found in the install new plugin section. Once installed, it will require activation from the admin. Later, the plugin form can be found in the settings option as Site-settings. just to to


== Usage ==

to show site Title just paste this code on your place

`
	do_action('ss_show','blogname');
`

And just replace do_action second parameter with 'site_logo' for Logo (image src),  'blogdescription' for Site Description, 'site_email' for Email, 'site_phone' for Phone number, 'site_address' for Address, 'site_copyright' for Copyright text, 'product_tags' for Tags, 'site_facebook' for Facebook link, 'site_twitter' for Twitter, 'site_instagram' for Instagram and 'site_youtube' for Youtube link. 



== Frequently Asked Questions ==
When activated this plugin will allow the admin to make changes in the custom fields.
These settings can be changed at the Settings => Site-Settings.

== Changelog ==
1.0.0
Initial release.
