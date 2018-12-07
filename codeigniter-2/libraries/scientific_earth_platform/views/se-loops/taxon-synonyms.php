<? 
if( !empty( $dataset ) ):
	$list = '';
	
	foreach( $dataset as $synonym ):
		if( !empty( $synonym['taxon_name'] ) )
			$list .= "{$synonym['taxon_name']}, ";
	endforeach;
	
	$list = rtrim( $list, ", " );
	
	echo ucwords( $list );
endif;
?>