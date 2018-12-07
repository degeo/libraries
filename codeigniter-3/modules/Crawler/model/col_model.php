<?php

/**
 * Source: Catalog of Life
 */
class Col_model extends CI_Model {
	
	var $source_url = "http://catalogoflife.org";
	var $search_url = "http://www.catalogueoflife.org/annual-checklist/2013/webservice?format=php&response=full&name=";
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function search( $search_string ) {
		$search_string = str_replace( '-', ' ', $search_string );
		$search_string = urlencode( $search_string );
		
		$context = stream_context_create( array( 'http' => array( 'timeout' => 45 ) ) );
		$result = file_get_contents( $this->search_url . $search_string, 0, $context );
		if( $result !== false )
			return $result;
		else
			return false;
	} // function
	
	public function parse_results( $results, $taxon_id ) {
		$results = unserialize( $results );
		
		if( $results['number_of_results_returned'] == 0 || !array_key_exists( 'results', $results ) || empty( $results['results'] ) ):
			
		else:
		
			foreach( $results['results'] as $col_taxon_result ):
				$this->parse_result( $col_taxon_result, $taxon_id );
			endforeach;
			
		endif;
		
		log_message( 'info', __METHOD__ . ' --> ' . $taxon_id );
	} // function
	
	public function parse_result( $result, $taxon_id = false ) {
		if( empty( $result ) )
			return false;
		
		$name = $result['name'];

		if( empty( $name ) || $name == 'Not assigned' )
			return false;
		
		$rank = $result['rank'];
		
		if( empty( $rank ) )
			return false;
		
		if( empty( $taxon_id ) ):
			$taxon_rank_id = $this->Data_model->get_taxon_rank_id( $rank );
			$taxon_name_id = $this->Data_model->get_taxon_name_id( $name );
		
			if( $taxon_rank_id === false || $taxon_name_id == false )
				return false;
		
			$taxon_id = $this->Data_model->get_taxon_id( $taxon_rank_id, $taxon_name_id );
		endif;
		
		if( array_key_exists( 'synonyms', $result ) && !empty( $result['synonyms'] ) )
			$this->Data_model->edit_taxon_synonyms( $taxon_id, $result['synonyms'] );
		
		if( array_key_exists( 'common_names', $result ) && !empty( $result['common_names'] ) )
			$this->Data_model->edit_taxon_synonyms( $taxon_id, $result['common_names'] );
		
		if( $result['rank'] == 'Species' ):
			if( array_key_exists( 'classification', $result ) && !empty( $result['classification'] ) ):
				$this->Data_model->add_classification( $result['rank'], $taxon_id, $result['classification'] );
				
				foreach( $result['classification'] as $child_taxon ):
					$this->parse_result( $child_taxon );
				endforeach;
			endif;
		endif;
		
		if( array_key_exists( 'child_taxa', $result ) && !empty( $result['child_taxa'] ) ):
			foreach( $result['child_taxa'] as $child_item ):
				$this->parse_result( $child_item );
			endforeach;
		endif;
	} // function

} // class