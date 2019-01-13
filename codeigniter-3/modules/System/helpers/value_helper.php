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

if( !function_exists( 'set_values_from_fields' ) ):
	function set_values_from_fields( array $data ) {
		$input_data = array();

		if( !empty( $data ) ):
			foreach( $data as $field ):
				$input_data[$field] = set_value( $field );
			endforeach;
		endif;

		return $input_data;
	} // function
endif;