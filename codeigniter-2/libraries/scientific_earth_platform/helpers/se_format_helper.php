<?php

if( !function_exists( 'format_url' ) ):
	function format_url( $string ) {
		$string = str_replace( ' ', '-', strtolower( $string ) );
		return $string;
	} // function
endif;

if( !function_exists( 'format_user_url' ) ):
	function format_user_url( $string ) {
		$string = str_replace( ' ', '-', strtolower( $string ) );
		return $string;
	} // function
endif;

if( !function_exists( 'format_image_name' ) ):
	function format_image_name( $user_id, $filename ) {
		$string = sha1( $user_id . $filename . microtime() );
		return $string;
	} // function
endif;