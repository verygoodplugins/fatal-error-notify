=== Fatal Error Notify ===
Contributors: verygoodplugins
Tags: error, reporting, debugging
Requires at least: 4.6
Tested up to: 4.9.1
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Receive email notifications whenever a fatal error occurs on your site.

== Description ==

This plugin hooks into PHP's shutdown functions to send you a notification whenever a fatal error (or other error level, configurably) is detected on your site.

Unlike traditional uptime monitoring services, which will only notify you if your entire site is down, this plugin can notify you when an error is detected on any page or process on your site.

Automatic plugin and theme updates often introduce problems that you aren't aware of until they're reported by your visitors. Fatal Error Notify lets you address these issues as they occur and before they cause significant problems.

== Installation ==

Install it just like any other WordPress plugin:

Either: Upload the plugin files to the `/wp-content/plugins/fatal-error-notify` directory.
Or: Install the plugin through the WordPress plugins screen directly.

Then:
1. Activate the plugin through the 'Plugins' screen in WordPress
2. Use the Settings->Fatal Error Notify screen to configure notification settings


== Frequently Asked Questions ==

= How does the plugin send error notifications if my site is down? =

Even the dreaded "500 - Internal Server Error" still triggers PHP's shutdown actions. Even if your site is completely offline, in most cases this plugin will be able to detect the error and notify you.

= What's in the Pro version =

[Fatal Error Notify Pro](https://fatalerrornotify.com/) includes several additional features, like Slack notifications, the ability to hide the plugin settings, logging of recorded errors, and more.

== Screenshots ==

1. Admin configuration settings
2. Example email received when an error has been reported

== Changelog ==

= 1.0 =
* Initial release

= 1.1 =
* Updated branding
* Added link to Pro version