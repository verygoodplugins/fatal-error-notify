<?php

// deny direct access.
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Adds WP CLI support.
 *
 * @since 1.5.0
 */
class Fatal_Error_Notify_CLI_Command extends WP_CLI_Command {

	/**
	 * Get an option
	 *
	 * @since 1.5.0
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function get_option( $args, $assoc_args ) {

		if ( empty( $args ) ) {
			WP_CLI::error( 'You must provide an option name and value.' );
		}

		$option = $args[0];

		$options = get_option( 'vgp_fen_settings', array() );

		if ( ! isset( $options[ $option ] ) ) {
			WP_CLI::error( 'Unknown option name.' );
		}

		if ( is_array( $options[ $option ] ) ) {
			$options[ $option ] = wp_json_encode( $options[ $option ] );
		}

		WP_CLI::success( $options[ $option ] );
	}

	/**
	 * Update an option
	 *
	 * @since 1.5.0
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function update_option( $args, $assoc_args ) {

		if ( empty( $args ) || 1 === count( $args ) ) {
			WP_CLI::error( 'You must provide an option name and value.' );
		}

		$option = $args[0];
		$value  = $args[1];

		$options = get_option( 'vgp_fen_settings', array() );

		if ( isset( $options[ $option ] ) && is_array( $options[ $option ] ) ) {

			$value = json_decode( $value, true );

			if ( ! is_array( $value ) ) {
				WP_CLI::error( "Invalid value. The value for $option must be a JSON encoded array. For example " . wp_json_encode( $options[ $option ] ) );
			}
		}

		$options[ $option ] = $value;

		update_option( 'vgp_fen_settings', $options );

		WP_CLI::success( "Option $option successfully updated to $value." );
	}
}

WP_CLI::add_command( 'fatal-error-notify', 'Fatal_Error_Notify_CLI_Command' );
