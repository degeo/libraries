<?php

if( !function_exists('priority_values') ):
	function priority_values( $a, $b ) {
		return ( $a['priority'] < $b['priority'] ) ? 0 : 1 ;
	} // function
endif;