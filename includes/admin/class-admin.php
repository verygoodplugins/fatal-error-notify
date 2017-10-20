<?php

class Fatal_Error_Notify_Admin {

	/**
	 * Get things started
	 *
	 * @since 1.0
	 * @return void
	*/

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_action( 'admin_init', array( $this, 'register_settings') );

	}

	public function register_settings() {

		add_settings_section( 'reading_speed_options_fields', 'All Settings', null, 'reading_speed_options');

		// Default WPM
		add_settings_field( 'reading_speed_default', 'Default', array($this, 'display_reading_speed_default'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_default');

	    // Secton 1 header
		add_settings_field( 'reading_speed_header_one', 'Heading 1', array($this, 'display_reading_speed_header_one'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_header_one');

	    // Secton 2 header
		add_settings_field( 'reading_speed_header_two', 'Heading 2', array($this, 'display_reading_speed_header_two'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_header_two');

	    // Placeholder
		add_settings_field( 'reading_speed_placeholder', 'Placeholder', array($this, 'display_reading_speed_placeholder'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_placeholder');

	    // Secton 3 header
		add_settings_field( 'reading_speed_header_three', 'Heading 3', array($this, 'display_reading_speed_header_three'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_header_three');

	    // Secton 4 header
		add_settings_field( 'reading_speed_header_four', 'Heading 4', array($this, 'display_reading_speed_header_four'), 'reading_speed_options', 'reading_speed_options_fields');
	    register_setting( 'reading_speed_options_fields', 'reading_speed_header_four');

	}

	public function admin_menu() {

		add_options_page(
			'Reading Speed Settings',
			'Reading Speed',
			'manage_options',
			'reading_speed',
			array( $this, 'settings_page' )
		);

	}

	public function display_reading_speed_default() { ?>
        <p>
            <input type="number" name="reading_speed_default" size="45" value="<?php echo get_option('reading_speed_default'); ?>" />
        </p>
	   <?php
	}

	public function display_reading_speed_header_one() { ?>
        <p>
        	<textarea style="width: 400px; height: 100px;" name="reading_speed_header_one"><?php echo get_option('reading_speed_header_one'); ?></textarea>
        </p>
	   <?php
	}

	public function display_reading_speed_header_two() { ?>
        <p>
        	<textarea style="width: 400px; height: 100px;" name="reading_speed_header_two"><?php echo get_option('reading_speed_header_two'); ?></textarea>
        </p>
	   <?php
	}

	public function display_reading_speed_placeholder() { ?>
        <p>
        	<textarea style="width: 400px; height: 100px;" name="reading_speed_placeholder"><?php echo get_option('reading_speed_placeholder'); ?></textarea>
        </p>
	   <?php
	}

	public function display_reading_speed_header_three() { ?>
        <p>
        	<textarea style="width: 400px; height: 100px;" name="reading_speed_header_three"><?php echo get_option('reading_speed_header_three'); ?></textarea>
        </p>
	   <?php
	}

	public function display_reading_speed_header_four() { ?>
        <p>
        	<textarea style="width: 400px; height: 100px;" name="reading_speed_header_four"><?php echo get_option('reading_speed_header_four'); ?></textarea>
        </p>
	   <?php
	}


	public function settings_page() { 

		?>

		<div class="wrap">
	        <h2>Reading Speed Shortcode Options</h2>

	        <form method="post" action="options.php">

				<?php settings_fields('reading_speed_options_fields'); ?>
	            <?php do_settings_sections('reading_speed_options'); ?>
	            <?php submit_button(); ?>
	            
	        </form>
	    </div>

	    <?php 
	}



}

new Fatal_Error_Notify_Admin;