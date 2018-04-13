<?php

class Fatal_Error_Notify_Admin {

	// Define available error levels for reporting

	private $error_levels = array(
		E_ERROR,
		E_WARNING,
		E_PARSE,
		E_NOTICE,
		E_CORE_ERROR,
		E_CORE_WARNING,
		E_COMPILE_ERROR,
		E_COMPILE_WARNING,
		E_USER_ERROR,
		E_USER_WARNING,
		E_USER_NOTICE,
		E_STRICT,
		E_RECOVERABLE_ERROR,
		E_DEPRECATED,
		E_USER_DEPRECATED
	);

	/**
	 * Get things started
	 *
	 * @since 1.0
	 * @return void
	*/

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_action('wp_ajax_test_error', array( $this, 'test_error' ) );
		add_action('wp_ajax_create_error', array( $this, 'create_error' ) );
		//add_action('wp_ajax_nopriv_test_error', 'test_error');
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

	public function enqueue_scripts(){

    	wp_enqueue_style( 'fatal-error-notify', FATAL_ERROR_NOTIFY_DIR_URL . 'assets/admin.css' );
    	wp_enqueue_script( 'test_error', FATAL_ERROR_NOTIFY_DIR_URL . "assets/admin.js", array('jquery'), time());
		wp_localize_script( 'test_error', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

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

		if( empty( $settings ) ) {

			$settings = array(
				'notification_email'	=> get_option('admin_email'),
				'levels'				=> array()
			);

			foreach( $this->error_levels as $level_id ) {

				// Enable fatal error by default
				if( $level_id == 1 ) {
					$settings['levels'][$level_id] = true;
				} else {
					$settings['levels'][$level_id] = false;
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
									<img src="<?php echo FATAL_ERROR_NOTIFY_DIR_URL ?>assets/pro-promo.png" />
								</div>

								<ul>
									<li>Slack notifications</li>
									<li>Stealth mode</li>
									<li>Auto-deactivate</li>
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
							<fieldset>
								<?php foreach( $this->error_levels as $i => $level_id ) : ?>

									<?php $level_string = fatal_error_notify()->map_error_code_to_type( $level_id ); ?>
									<label for="level_<?php echo $level_string ?>">
										<input type="checkbox" name="fen_settings[levels][<?php echo $level_id; ?>]" id="level_<?php echo $level_string ?>" value="1" <?php checked( $settings['levels'][$level_id] ); ?> />
										<?php echo $level_string; ?>
									</label>
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

	public function test_error() {

		wp_remote_post( 'http://vgp.local/wp-admin/admin-ajax.php?action=create_error' );

		wp_send_json_success();

	}

	public function create_error() {

		function_that_does_not_exist();

	}
	



}

new Fatal_Error_Notify_Admin;