=== Fatal Error Notify ===
Contributors: verygoodplugins
Tags: error, reporting, debugging, fatal
Requires at least: 4.6
Tested up to: 5.4
Stable tag: 1.4.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Receive email notifications whenever a fatal error occurs on your site.

== Description ==

This plugin sends you an email notification whenever a fatal error (or other error level, configurably) is detected on your site.

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

Fatal Error Notify hooks into PHP's "shutdown" function to send notifications right before the page stops loading. Even the dreaded "500 - Internal Server Error" still triggers PHP's shutdown actions. Even if your site is completely offline, in most cases this plugin will be able to detect the error and notify you.

= What's in the Pro version =

[Fatal Error Notify Pro](https://fatalerrornotify.com/?utm_campaign=fatal-error-notify-free&utm_source=wp-org) includes several additional features, like Slack notifications, the ability to hide the plugin settings, logging of recorded errors, out of memory handling, the ability to pause individual notifications, and more.

Fatal Error Notify Pro also includes integrations with [WP Fusion](https://wpfusion.com/?utm_campaign=fatal-error-notify-free&utm_source=wp-org) and Gravity Forms and can send notifications when errors are logged in those plugins.

= Can I exclude specific errors? =

Yes, you can use the filter `fen_ingore_error`, like so:

	function fen_ignore_error( $ignore, $error ) {

		if( $error['file'] == '/home/username/public_html/wp-includes/class-phpass.php' ) {
			$ignore = true;
		}

		return $ignore;

	}

	add_filter( 'fen_ignore_error', 'fen_ignore_error', 10, 2 );

The `$error` variable is an array containing:

* `$error['type']`: (int) The PHP [error code](http://php.net/manual/en/errorfunc.constants.php)
* `$error['message']`: (string) The error message
* `$error['file']`: (string) The path to the file that triggered the error
* `$error['line']`: (int) The line number that triggered the error

== Screenshots ==

1. Admin configuration settings
2. Example email received when an error has been reported

== Changelog ==

= 1.4.2 - 4/3/2020 =
* Tested for WordPress 5.4
* Added error level descriptions

= 1.4.1 - 11/27/2019 =
* Tested for WordPress 5.3

= 1.4 - 4/26/2019 =
* Added request URI, HTTP Referrer, and current user ID to notifications

= 1.3 - 4/21/2018 =
* Added "Send Test" button
* Rate limiting so notifications are only sent once per hour

= 1.2 - 2/8/2018 =
* Added filter to ignore errors

= 1.1 =
* Updated branding
* Added link to Pro version

= 1.0 =
* Initial release