<?php

if( !function_exists( 'set_value_data' ) ):
	function set_value_data( $key, array $data ) {
		$value = '';
		if( array_key_exists( $key, $data ) ):
			$value = $data[$key];
		endif;
		return set_value( $key, $value );
	} // function
endif;