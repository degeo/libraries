<?php

class Task_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function reset_verification_flags() {
		$this->Data_model->reset_col_verified();
	} // function
	
	public function clean_db( $limit = 10 ) {
		log_message( 'info', __method__ . ' started' );
		
		$this->Data_model->remove_one_word_species( $limit );
		
		# @todo remove "species" that are actually synonyms
		
		$this->Data_model->remove_short_long_descriptions( $limit );
		
		$this->Data_model->clean_brief_descriptions( $limit );
		$this->Data_model->clean_long_descriptions( $limit );
		
		log_message( 'info', __method__ . ' completed' );
		
		return true;
	} // function
	
	public function crawl_internal_data( $taxa ) {
		if( empty( $taxa ) )
			return false;
		
		$this->load->model('Col_model');
		$this->load->model('Wikipedia_model');
		
		foreach( $taxa as $taxon ):
			$taxon_id = $taxon['taxon_id'];
			$search_string = $taxon['taxon_name'];
			
			$results = $this->Col_model->search( $search_string );
			if( $results !== false )
				$this->Col_model->parse_results( $results, $taxon_id );
			
			$results = $this->Wikipedia_model->search( $search_string );
			if( $results !== false )
				$this->Wikipedia_model->parse_results( $results, $taxon_id );
			
			$this->Data_model->set_col_verified( $taxon_id );
			
			sleep(2);
		endforeach;
		
		return true;
	} // function
	
	public function crawl_internal_species_data( $limit = 10 ) {
		log_message( 'info', __method__ . ' started' );
		
		$taxa = $this->Data_model->get_lower_taxa( $limit, 0, $rand = true );
		
		$this->crawl_internal_data( $taxa );
		
		log_message( 'info', __method__ . ' completed' );
		
		return true;
	} // function
	
	public function crawl_internal_rank_data( $limit = 10 ) {
		log_message( 'info', __method__ . ' started' );
		
		$taxa = $this->Data_model->get_higher_taxa( $limit, 0, $rand = true );
		
		$this->crawl_internal_data( $taxa );
		
		log_message( 'info', __method__ . ' completed' );
		
		return true;
	} // function

} // class