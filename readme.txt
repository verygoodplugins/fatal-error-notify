=== Fatal Error Notify ===
Contributors: verygoodplugins
Tags: error, reporting, debugging, fatal
Requires at least: 4.6
Tested up to: 6.5.0
Stable tag: 1.5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Receive email notifications when errors occur on your WordPress site.

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

[Fatal Error Notify Pro](https://fatalerrornotify.com/?utm_campaign=fatal-error-notify-free&utm_source=wp-org) includes several additional features, like Slack notifications, the ability to hide the plugin settings, multisite support, logging of recorded errors, out of memory handling, the ability to pause individual notifications, and more.

Fatal Error Notify Pro also includes integrations with Gravity Forms, WooCommerce, WPForms, [WP Fusion](https://wpfusion.com/?utm_campaign=fatal-error-notify-free&utm_source=wp-org), and WP Mail SMTP and can send notifications when errors are logged in those plugins.

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

= Does it support WP CLI =

Yup, you bet! You can update the plugin settings using WP CLI, for example to set a default list of error levels, or a notification email address.

The two methods are `get_option` and `update_option`. For example:

	wp fatal-error-notify get_option slack_notifications

Will tell you if Slack notifications are enabled on the site.

	wp fatal-error-notify update_option notification_email email@domain.com

Will update the notification email for the site.

You can also update options on multiple sites using xargs:

	wp site list --field=url | xargs -n1 -I {} sh -c 'wp --url={} fatal-error-notify update_option notification_email email@domain.com'

If you are updating options that are stored as arrays (for example `levels` or `plugins`) you can use JSON-formatted values. For an example, use `get_option` first on the field you are trying to update.

== Screenshots ==

1. Admin configuration settings
2. Example email received when an error has been reported

== Changelog ==

= 1.5.3 - 1/12/2024 =
* Tested for WordPress 6.5.0
* Added nonce further secure to admin test error action

= 1.5.2 - 1/5/2024 =
* Improved - Removed some uncommon error types
* Fixed test error endpoint being accessible by non-admins

= 1.5.1 - 8/11/2023 =
* Tested for WordPress 6.3.0
* Improved - "mkdir" warnings will be ignored by default

= 1.5.0 - 3/20/2023 =
* Tested for WordPress 6.2.0
* Added [WP CLI endpoint](https://fatalerrornotify.com/documentation/#wp-cli) for updating plugin settings

= 1.4.7 - 11/14/2022 =
* Fixed typo (misplaced parenthesis) checking `WARNING` level errors in v1.4.6

= 1.4.6 - 11/1/2022 =
* Tested for WordPress 6.1.0
* Improved - Moved actions to `shutdown` action priority 1, to fix cases where other plugins generate notices or warnings during `shutdown` after a fatal error
* Improved - "rmdir" warnings will be ignored by default
* Fixed `unlink` warnings still triggering notifications if `unlink` was the first part of the error string

= 1.4.5 - 2/15/2022 =
* Tested for WordPress 5.9
* Improved - "unlink" warnings will be ignored by default (see https://wordpress.org/support/topic/wordfence-notification-error-wordfenceclass-php/#post-15187940)
* Added upgrade prompt in notification email

= 1.4.4 - 8/3/2021 =
* Tested for WordPress 5.8
* Moved upgrade nag to top of settings page to prevent layout issues on smaller screens

= 1.4.3 - 12/16/2020 =
* Tested for WordPress 5.6
* Fixed PHP notice when HTTP referrer was missing
* Added fen_use_wp_mail filter

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