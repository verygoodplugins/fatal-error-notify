<?php

class Fatal_Error_Notify_Admin {

	/**
	 * Get things started
	 *
	 * @since 1.0
	 * @return void
	 */

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_test_error', array( $this, 'test_error' ) );
	}

	/**
	 * Register admin settings menu
	 *
	 * @since 1.0
	 * @return void
	 */

	public function admin_menu() {

		$id = add_options_page(
			'Fatal Error Notification Settings',
			'Fatal Error Notify',
			'manage_options',
			'fatal_error_notify',
			array( $this, 'settings_page' )
		);

		add_action( 'load-' . $id, array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Register CSS files
	 *
	 * @since 1.0
	 * @return void
	 */

	public function enqueue_scripts() {

		wp_enqueue_style( 'fatal-error-notify', FATAL_ERROR_NOTIFY_DIR_URL . 'assets/admin.css' );
		wp_enqueue_script( 'test_error', FATAL_ERROR_NOTIFY_DIR_URL . 'assets/admin.js', array( 'jquery' ), time() );
		wp_localize_script( 'test_error', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

	}

	/**
	 * Creates test error button
	 *
	 * @access public
	 * @return void
	 */

	public function test_error() {

		function_that_does_not_exist();

	}

	/**
	 * Renders Settings page
	 *
	 * @access public
	 * @return mixed
	 */

	public function settings_page() {

		// Save settings
		if ( isset( $_POST['fen_settings_nonce'] ) && wp_verify_nonce( $_POST['fen_settings_nonce'], 'fen_settings' ) ) {
			update_option( 'vgp_fen_settings', $_POST['fen_settings'] );
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		}

		$settings = get_option( 'vgp_fen_settings', array() );

		if ( empty( $settings ) ) {

			$settings = array(
				'notification_email' => get_option( 'admin_email' ),
				'levels'             => array(),
			);

			foreach ( fatal_error_notify()->error_levels as $level_id ) {

				// Enable fatal error by default
				if ( $level_id == 1 ) {
					$settings['levels'][ $level_id ] = true;
				} else {
					$settings['levels'][ $level_id ] = false;
				}
			}
		}

		?>

		<div class="wrap">


			<h2>Error Notification Settings</h2>

			<form id="fen-settings" action="" method="post">
				<?php wp_nonce_field( 'fen_settings', 'fen_settings_nonce' ); ?>
				<input type="hidden" name="action" value="update">

				<table class="form-table">
					<tr valign="top">
						<th scope="row">Notification Email</th>
						<td valign="top">
							<input class="regular-text" type="email" name="fen_settings[notification_email]" value="<?php echo esc_attr( $settings['notification_email'] ); ?>" />
							<p class="description">Configured error notifications will be sent to this address.</p>
						</td>

						<td>
							<div id="fen-pro">
								<div id="fen-pro-top">
									<img src="<?php echo FATAL_ERROR_NOTIFY_DIR_URL; ?>assets/pro-promo.png" />
								</div>

								<ul>
									<li>Slack notifications</li>
									<li>Pause notifications</li>
									<li>Stealth mode</li>
									<li>Out-of-memory handling</li>
									<li>Logging</li>
								</ul>

								<a class="button-primary" href="https://fatalerrornotify.com/?utm_source=free-plugin" target="_blank">Learn More</a>

							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Test Crash Notification</th>
						<td>
						<a id="test-button" class="button-primary" href="#">Send Test</a>
						<p class="description">Creates a test fatal error to generate error e-mail message.</p>
					</tr>

					<tr valign="top">
						<th scope="row">Error Levels To Notify</th>
						<td>
							<fieldset class="error-levels">

								<?php foreach ( fatal_error_notify()->error_levels as $i => $level_id ) : ?>

									<?php $level_string = fatal_error_notify()->map_error_code_to_type( $level_id ); ?>

									<?php
									if ( empty( $settings['levels'][ $level_id ] ) ) {
										$settings['levels'][ $level_id ] = false;}
										?>

									<label for="level_<?php echo $level_string; ?>">
										<input type="checkbox" name="fen_settings[levels][<?php echo $level_id; ?>]" id="level_<?php echo $level_string; ?>" value="1" <?php checked( $settings['levels'][ $level_id ] ); ?> />
										<?php echo $level_string; ?>
									</label>

									<?php
									switch ( $level_string ) {
										case 'E_ERROR':
											echo '<span class="description"><strong>Recommended:</strong> A fatal run-time error that can\'t be recovered from.</span>';
											break;

										case 'E_WARNING':
											echo '<span class="description">Warnings indicate that something unexpected happened, but the site didn\'t crash.</span>';
											break;

										case 'E_PARSE':
											echo '<span class="description">A Parse error is uncommon but could be caused by an incomplete plugin update.</span>';
											break;

										case 'E_NOTICE':
											echo '<span class="description">Many plugins generate Notice-level errors, and these can usually be ignored.</span>';
											break;

										default:
											break;
									}
									?>

									<br />

								<?php endforeach; ?>
							</fieldset>

						</td>

				</table>

				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="Save Changes"/>
				</p>

			</form>

		</div>

		<?php
	}


}

new Fatal_Error_Notify_Admin();
