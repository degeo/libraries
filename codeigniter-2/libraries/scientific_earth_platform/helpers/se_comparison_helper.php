<?

if( !function_exists('cmp_strlen') ):
	function cmp_strlen_terms( $a, $b ) {
		return (strlen($a['term_name']) > strlen($b['term_name'])) ? 0 : 1 ;
	} // function
endif;