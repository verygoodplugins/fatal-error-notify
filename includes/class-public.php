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

	public function map_error_code_to_type( $code ) {

		switch($code) { 
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

	/**
	 * Catch any fatal errors and act on them
	 *
	 * @return void
	*/

	public function shutdown() {

		$error = error_get_last();

		if ($error['type'] === E_ERROR) {
		    error_log('FATAL ERROR:');
		} else {
			error_log('Non-fatal error of type: ' . $this->map_error_code_to_type( $error['type'] ));
		}

		error_log(print_r($error, true));

	}


}

new Fatal_Error_Notify_Public;