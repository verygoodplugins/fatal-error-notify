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
		return "text/html";
	}

	/**
	 * Catch any fatal errors and act on them
	 *
	 * @return void
	*/

	public function shutdown() {

		$error = error_get_last();

		if( is_null( $error ) ) {
			return;
		}

		$settings = get_option( 'vgp_fen_settings', array() );

		if( empty( $settings ) || empty( $settings['notification_email'] ) || empty( $settings['levels'] ) ) {
			return;
		}

		$output = '';

		foreach( $settings['levels'] as $level_id => $enabled ) {

			if ( $error['type'] == $level_id ) {
				$output .= '<ul>';
				$output .= '<li><strong>Error Level:</strong> ' . fatal_error_notify()->map_error_code_to_type( $error['type'] ) . '</li>';
				$output .= '<li><strong>Message:</strong> ' . $error['message'] . '</li>';
				$output .= '<li><strong>File:</strong> ' . $error['file'] . '</li>';
				$output .= '<li><strong>Line:</strong> ' . $error['line'] . '</li>';
				$output .= '</ul><br /><br />';
			}

		}

		if( !empty( $output ) ) {

			$output = '<h2>Error notification</h2><br/>For site <a href="' . get_home_url() . '" target="_blank">' . get_home_url() . '</a><br /><br />' . $output;

			if( function_exists( 'wp_mail' ) ) {

				add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );
				wp_mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output );

			} else {

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				mail( $settings['notification_email'], 'Error notification for ' . get_home_url(), $output, $headers );

			}

		}

	}


}

new Fatal_Error_Notify_Public;