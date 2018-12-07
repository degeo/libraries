<?php

if( !function_exists( 'is_user' ) ):
	function is_user() {
		$CI =& get_instance();
		return $CI->Se_app_user->is_loggedin();
	} // function
endif;

if( !function_exists( 'user_has_role' ) ):
	function user_has_role( $role ) {
		$CI =& get_instance();
		return $CI->Se_app_user->has_role( $role );
	} // function
endif;