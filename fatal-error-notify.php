<?php

/*
Plugin Name: Fatal Error Notify
Description: Receive email notifications when errors occur on your WordPress site
Plugin URI: https://fatalerrornotify.com/
Version: 1.4.2
Author: Very Good Plugins
Author URI: https://verygoodplugins.com/
Text Domain: fatal-error-notify
*/

/**
 * @copyright Copyright (c) 2017. All rights reserved.
 *
 * @license   Released under the GPL license http://www.opensource.org/licenses/gpl-license.php
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// deny direct access
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define( 'FATAL_ERROR_NOTIFY_VERSION', '1.4.2' );

if ( ! class_exists( 'Fatal_Error_Notify' ) ) {

	final class Fatal_Error_Notify {

		/** Singleton *************************************************************/

		/**
		 * @var Fatal_Error_Notify The one true Fatal_Error_Notify
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * @var error_levels Define PHP error levels available for reporting
		 * @since 1.4.2
		 */
		public $error_levels = array(
			E_ERROR,
			E_WARNING,
			E_PARSE,
			E_NOTICE,
			E_CORE_ERROR,
			E_CORE_WARNING,
			// E_COMPILE_ERROR,
			// E_COMPILE_WARNING,
			E_USER_ERROR,
			E_USER_WARNING,
			E_USER_NOTICE,
			E_STRICT,
			// E_RECOVERABLE_ERROR,
			E_DEPRECATED,
			// E_USER_DEPRECATED,
		);


		/**
		 * Main Fatal_Error_Notify Instance
		 *
		 * Insures that only one instance of Fatal_Error_Notify exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @return The one true Fatal_Error_Notify
		 */

		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Fatal_Error_Notify ) ) {

				self::$instance = new Fatal_Error_Notify();
				self::$instance->setup_constants();
				self::$instance->includes();

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access protected
		 * @return void
		 */

		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'fatal-error-notify' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access protected
		 * @return void
		 */

		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'fatal-error-notify' ), '1.0' );
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @return void
		 */

		private function setup_constants() {

			if ( ! defined( 'FATAL_ERROR_NOTIFY_DIR_PATH' ) ) {
				define( 'FATAL_ERROR_NOTIFY_DIR_PATH', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'FATAL_ERROR_NOTIFY_PLUGIN_PATH' ) ) {
				define( 'FATAL_ERROR_NOTIFY_PLUGIN_PATH', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'FATAL_ERROR_NOTIFY_DIR_URL' ) ) {
				define( 'FATAL_ERROR_NOTIFY_DIR_URL', plugin_dir_url( __FILE__ ) );
			}

		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */

		private function includes() {

			require_once FATAL_ERROR_NOTIFY_DIR_PATH . 'includes/admin/class-admin.php';
			require_once FATAL_ERROR_NOTIFY_DIR_PATH . 'includes/class-public.php';

		}

		/**
		 * Map error code to error string
		 *
		 * @return void
		 */

		public function map_error_code_to_type( $code ) {

			switch ( $code ) {
				case E_ERROR: // 1 //
					return 'E_ERROR';
				case E_WARNING: // 2 //
					return 'E_WARNING';
				case E_PARSE: // 4 //
					return 'E_PARSE';
				case E_NOTICE: // 8 //
					return 'E_NOTICE';
				case E_CORE_ERROR: // 16 //
					return 'E_CORE_ERROR';
				case E_CORE_WARNING: // 32 //
					return 'E_CORE_WARNING';
				case E_COMPILE_ERROR: // 64 //
					return 'E_COMPILE_ERROR';
				case E_COMPILE_WARNING: // 128 //
					return 'E_COMPILE_WARNING';
				case E_USER_ERROR: // 256 //
					return 'E_USER_ERROR';
				case E_USER_WARNING: // 512 //
					return 'E_USER_WARNING';
				case E_USER_NOTICE: // 1024 //
					return 'E_USER_NOTICE';
				case E_STRICT: // 2048 //
					return 'E_STRICT';
				case E_RECOVERABLE_ERROR: // 4096 //
					return 'E_RECOVERABLE_ERROR';
				case E_DEPRECATED: // 8192 //
					return 'E_DEPRECATED';
				case E_USER_DEPRECATED: // 16384 //
					return 'E_USER_DEPRECATED';
			}

		}


	}

}

/**
 * The main function responsible for returning the one true Fatal Error Notify
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 */

if ( ! function_exists( 'fatal_error_notify' ) ) {

	function fatal_error_notify() {
		return Fatal_Error_Notify::instance();
	}

	fatal_error_notify();

}
