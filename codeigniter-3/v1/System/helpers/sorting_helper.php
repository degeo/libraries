<?php

if( !function_exists('priority_values') ):
	function priority_values( $a, $b ) {
		return ( $a['priority'] < $b['priority'] ) ? 0 : 1 ;
	} // function
endif;

if( !function_exists( 'priority_strlen' ) ):
	function priority_strlen($a,$b){
	    return strlen($b)-strlen($a);
	}
endif;