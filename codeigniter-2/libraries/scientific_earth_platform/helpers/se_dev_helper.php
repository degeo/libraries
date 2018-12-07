<?php

if( !function_exists( 'dev_print_data' ) ):
	function dev_print_data( $data ) {
		if( ENVIRONMENT == 'development' && !empty( $data ) ):
			echo '<div class="large-12 columns panel">';
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			echo '</div>';
			
			return true;
		endif;
		
		return false;
	} // function
endif;