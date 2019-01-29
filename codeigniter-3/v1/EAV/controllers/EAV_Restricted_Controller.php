<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EAV_Restricted_Controller extends Degeo_Restricted_Controller {

	public function __construct() {
		parent::__construct();
	} // function

	public function _remap( $method )
	{
		$extend_controller = 'EAV_Controller';
		if( class_exists( $extend_controller ) ):
			if( method_exists( $extend_controller, $method ) ):
				$params = func_get_args();
				call_user_func_array( array( $extend_controller, $method ), $params );
			endif;
		endif;
	}

} // class
