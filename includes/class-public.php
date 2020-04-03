<?php

class Fatal_Error_Notify_Public {

	/**
	 * Get things started
	 *
	 * @return void
	 */

	public function __construct() {

		register_shutdown_function( array( $this, 'shutdown' ) );

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
	 * Catch any fatal errors and act on them
	 *
	 * @return void
	 */

	public function shutdown() {

		$error = error_get_last();

		if ( is_null( $error ) ) {
			return;
		}

		// Allow bypassing
		$ignore = apply_filters( 'fen_ignore_error', false, $error );

		if ( $ignore ) {
			return;
		}

		$settings = get_option( 'vgp_fen_settings', array() );

		if ( empty( $settings ) || empty( $settings['notification_email'] ) || empty( $settings['levels'] ) ) {
			return;
		}

		$output = '';

		foreach ( $settings['levels'] as $level_id => $enabled ) {

			if ( $error['type'] == $level_id ) {
				$output .= '<ul>';
				$output .= '<li><strong>Error Level:</strong> ' . fatal_error_notify()->map_error_code_to_type( $error['type'] ) . '</li>';
				$output .= '<li><strong>Message:</strong> ' . nl2br( $error['message'] ) . '</li>';
				$output .= '<li><strong>File:</strong> ' . $error['file'] . '</li>';
				$output .= '<li><strong>Line:</strong> ' . $error['line'] . '</li>';
				$output .= '<li><strong>Request:</strong> ' . $_SERVER['REQUEST_URI'] . '</li>';
				$output .= '<li><strong>Referrer:</strong> ' . $_SERVER['HTTP_REFERER'] . '</li>';

				$user_id = get_current_user_id();

				if ( ! empty( $user_id ) ) {
					$output .= '<li><strong>User ID</strong>: ' . $user_id . '</li>';
				}

				$output .= '</ul><br /><br />';
			}
		}

		if ( ! empty( $output ) ) {

			$hash      = md5( $error['message'] );
			$transient = get_transient( 'fen_' . $hash );

			if ( strpos( $error['message'], 'function_that_does_not_exist' ) !== false ) {
				$bypass = true;
			} else {
				$bypass = false;
			}

			if ( ! empty( $transient ) && false === $bypass ) {

				return;

			} else {

				set_transient( 'fen_' . $hash, true, HOUR_IN_SECONDS );

			}

			$output = '<h2>Error notification</h2>For site <a href="' . get_home_url() . '" target="_blank">' . get_home_url() . '</a><br />' . $output;

			if ( function_exists( 'wp_mail' ) ) {

				add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );
				wp_mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output );

			} else {

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
				mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output, $headers );

			}
		}

	}


}

new Fatal_Error_Notify_Public();
