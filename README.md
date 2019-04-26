![Fatal Error Notify](https://fatalerrornotify.com/wp-content/uploads/2017/12/icon_color-150x150.png)
# Fatal Error Notify #

Receive email notifications whenever a fatal error occurs on your WordPress site.

This plugin hooks into PHP's shutdown functions to send you a notification whenever a fatal error (or other error level, configurably) is detected on your site.

Unlike traditional uptime monitoring services, which will only notify you if your entire site is down, this plugin can notify you when an error is detected on any page or process on your site.

Automatic plugin and theme updates often introduce problems that you aren't aware of until they're reported by your visitors. Fatal Error Notify lets you address these issues as they occur and before they cause significant problems.


### What's in the Pro version?

[Fatal Error Notify Pro](https://fatalerrornotify.com/) includes several additional features, like Slack notifications, the ability to hide the plugin settings, logging of recorded errors, out of memory handling, and more.

### Can I exclude specific errors?

Yes, you can use the filter `fen_ingore_error`, like so:

```php
function fen_ignore_error( $ignore, $error ) {

	if( $error['file'] == '/home/username/public_html/wp-includes/class-phpass.php' ) {
		$ignore = true;
	}

	return $ignore;

}

add_filter( 'fen_ignore_error', 'fen_ignore_error', 10, 2 );
```

The `$error` variable is an array containing:

* `$error['type']`: (int) The PHP [error code](http://php.net/manual/en/errorfunc.constants.php)
* `$error['message']`: (string) The error message
* `$error['file']`: (string) The path to the file that triggered the error
* `$error['line']`: (int) The line number that triggered the error

## Installation ##

1. You can clone the GitHub repository: `https://github.com/verygoodplugins/fatal-error-notify.git`
2. Or download it directly as a ZIP file: `https://github.com/verygoodplugins/fatal-error-notify/archive/master.zip`

This will download the latest copy of Fatal Error Notify.

## Bugs ##
If you find an issue, let us know [here](https://github.com/verygoodplugins/fatal-error-notify/issues?state=open)!

## Support ##
This is a developer's portal for Fatal Error Notify and should _not_ be used for support. Please visit the [support page](https://fatalerrornotify.com/support/contact/) if you need to submit a support request.

## Changelog ##

#### 1.4 - 4/26/2019
* Added request URI, HTTP Referrer, and current user ID to notifications

#### 1.3 - 4/21/2018
* Added "Send Test" button
* Rate limiting so notifications are only sent once per hour

#### 1.2 - 2/8/2018
* Added filter to ignore errors

#### 1.1
* Updated branding
* Added link to Pro version

#### 1.0
* Initial release