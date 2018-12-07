<?php

if( !function_exists( 'set_role_id_as_key' ) ):
	function set_role_id_as_key( $roles ) {
		$data = array();
		foreach( $roles as $row ):
			$data[$row['account_authorization_role_id']] = $row['role_name'];
		endforeach;

		return $data;
	} // function
endif;

if( !function_exists( 'set_role_as_key' ) ):
	function set_role_as_key( $roles ) {
		$data = array();
		foreach( $roles as $row ):
			$data[$row['role_name']] = $row;
		endforeach;

		return $data;
	} // function
endif;

if( !function_exists( 'group_interaction_taxon_ids' ) ):
	function group_interaction_taxon_ids( $interactions ) {
		$taxa = array();
		
		foreach( $interactions as $key => $interaction ):
			$taxa[$interaction['x_taxon']['taxon_id']] = $interaction['x_taxon'];
			$taxa[$interaction['y_taxon']['taxon_id']] = $interaction['y_taxon'];
		endforeach;
		
		return $taxa;
	} // function
endif;

if( !function_exists( 'limit_query' ) ):
	function limit_query( $query, $limit = '', $offset = '' ) {
		if( !empty($limit) && !empty( $offset ) ):
			$query .= " LIMIT {$offset}, {$limit}";
		elseif( !empty( $limit ) ):
			$query .= " LIMIT {$limit}";
		endif;
		
		return $query;
	} // function
endif;