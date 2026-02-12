<?php

class Fatal_Error_Notify_Public {

	/**
	 * Reserve some memory for shutdown handler in case of OOM.
	 *
	 * @since 1.5.4
	 * @var string
	 */
	private $memory;

	/**
	 * Get things started
	 *
	 * @return void
	 */
	public function __construct( $register_hooks = true ) {

		$this->memory = '';

		if ( $register_hooks ) {
			// Gives us a little buffer in case we run out of memory.
			$this->memory = str_repeat( '*', 1024 * 256 );

			// 1 so it runs before any plugins potentially generate warnings during shutdown after a fatal error.
			add_action( 'shutdown', array( $this, 'shutdown' ), 1 );
		}
	}

	/**
	 * Send notifications with HTML formatting
	 *
	 * @return string
	 */
	public function wp_mail_content_type() {
		return 'text/html';
	}

	/**
	 * Get the full request URL including protocol and domain.
	 *
	 * @since 1.5.4
	 *
	 * @return string The full request URL.
	 */
	public function get_full_request_url() {

		if ( ! isset( $_SERVER['HTTP_HOST'] ) && ! isset( $_SERVER['SERVER_NAME'] ) ) {

			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				return $_SERVER['REQUEST_URI'];
			}

			return 'unknown';
		}

		$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$host     = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
		$uri      = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';

		return $protocol . $host . $uri;
	}

	/**
	 * Get the referrer URL if available.
	 *
	 * @since 1.5.4
	 *
	 * @return string|false The referrer URL or false if not set.
	 */
	public function get_referrer_url() {

		if ( ! isset( $_SERVER['HTTP_REFERER'] ) ) {
			return false;
		}

		$referrer = $_SERVER['HTTP_REFERER'];

		// Don't show referrer if it's the same as the request.
		if ( $referrer === $this->get_full_request_url() ) {
			return false;
		}

		return $referrer;
	}

	/**
	 * Catch any fatal errors and act on them.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function shutdown() {

		$this->memory = null; // Free reserved memory.

		$error = error_get_last();

		if ( is_null( $error ) ) {
			return;
		}

		// Allow bypassing.
		$ignore = apply_filters( 'fen_ignore_error', false, $error );

		if ( $ignore ) {
			return;
		}

		// A couple types of errors we don't need reported.

		if ( E_WARNING === $error['type'] && (
			false !== strpos( $error['message'], 'unlink' ) ||
			false !== strpos( $error['message'], 'rmdir' ) ||
			false !== strpos( $error['message'], 'mkdir' ) ||
			false !== strpos( $error['message'], 'chmod' )
		) ) {
			// A lot of plugins generate these because it's faster to unlink()
			// without checking if the file exists first, even if it creates a
			// warning.
			return;
		}

		// Normalize out-of-memory messages to prevent duplicate notifications
		// when only the allocation size differs.
		if ( 0 === strpos( $error['message'], 'Allowed memory' ) ) {
			$error['message'] = preg_replace( '/\s*\(.*?\)\s*/', '', $error['message'] );
		}

		// Strip the server path for cleaner notifications.
		$error['file'] = str_replace( ABSPATH, '', $error['file'] );

		$this->handle_error( $error );
	}

	/**
	 * Handle an error: check settings, rate limit, and send notification.
	 *
	 * @since 1.5.4
	 *
	 * @param array $error The error data.
	 */
	public function handle_error( $error ) {

		$settings = get_option( 'vgp_fen_settings', array() );

		if ( empty( $settings ) || empty( $settings['notification_email'] ) || empty( $settings['levels'] ) ) {
			return;
		}

		if ( empty( $settings['levels'][ $error['type'] ] ) ) {
			return;
		}

		// Rate limiting.

		$is_test = ! empty( $error['test'] );

		if ( ! $is_test ) {

			$hash      = md5( $error['message'] . ( isset( $error['file'] ) ? $error['file'] : '' ) );
			$cache_key = 'fen_' . $hash;

			/**
			 * Filter the rate limit time for error notifications.
			 *
			 * @since 1.5.4
			 *
			 * @param int $rate_limit_time The rate limit time in seconds. Default HOUR_IN_SECONDS.
			 */
			$rate_limit_time = apply_filters( 'fen_rate_limit_time', HOUR_IN_SECONDS );

			if ( wp_using_ext_object_cache() ) {
				$last_sent = wp_cache_get( $cache_key, 'fen' );

				if ( $last_sent ) {
					return;
				}

				wp_cache_set( $cache_key, time(), 'fen', $rate_limit_time );
			} else {
				$transient = get_transient( $cache_key );

				if ( ! empty( $transient ) ) {
					return;
				}

				set_transient( $cache_key, time(), $rate_limit_time );
			}
		}

		$this->send_notification( $error, $settings );
	}

	/**
	 * Build and send the email notification.
	 *
	 * @since 1.5.4
	 *
	 * @param array $error    The error data.
	 * @param array $settings The plugin settings.
	 */
	public function send_notification( $error, $settings ) {

		// Build the notification output.

		$message = isset( $error['message'] ) ? $error['message'] : '';

		$output  = '<ul>';
		$output .= '<li><strong>Error Level:</strong> ' . fatal_error_notify()->map_error_code_to_type( $error['type'] ) . '</li>';
		$output .= '<li><strong>Message:</strong> ' . nl2br( esc_html( $message ) ) . '</li>';

		if ( isset( $error['file'] ) ) {
			$output .= '<li><strong>File:</strong> ' . esc_html( $error['file'] ) . '</li>';
			$output .= '<li><strong>Line:</strong> ' . (int) $error['line'] . '</li>';
		}

		$output .= '<li><strong>URL:</strong> ' . esc_html( $this->get_full_request_url() ) . '</li>';

		$referrer = $this->get_referrer_url();

		if ( $referrer ) {
			$output .= '<li><strong>Referrer:</strong> ' . esc_html( $referrer ) . '</li>';
		}

		if ( function_exists( 'wp_get_current_user' ) ) {
			$user = wp_get_current_user();

			if ( ! empty( $user->ID ) ) {
				$output .= '<li><strong>User:</strong> ' . esc_html( $user->user_login ) . ' (#' . $user->ID . ')</li>';
			}
		}

		$output .= '</ul><br />';

		$output = '<h2>Error notification</h2>For site <a href="' . esc_url( get_home_url() ) . '" target="_blank">' . esc_html( get_home_url() ) . '</a><br />' . $output;

		$output .= '<br /><em>(Pause notifications, mute plugins, and more in <a href="https://fatalerrornotify.com/?utm_source=free-plugin&utm_medium=notification">Fatal Error Notify Pro</a>)</em><br />';

			if ( function_exists( 'wp_mail' ) && apply_filters( 'fen_use_wp_mail', true ) ) {

				add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );
				wp_mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output );
				remove_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );

			} else {

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
			mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output, $headers );

		}
	}
}

new Fatal_Error_Notify_Public();
