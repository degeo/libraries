<?php

class Se_db_interactions_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_interaction_types( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM interaction_types";
		else:
			$query = "SELECT * FROM interaction_types JOIN information_terms USING(term_id)";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_interactions( $limit = 100, $offset = 0, $get_count = false ) {
		$query = "SELECT * FROM taxon_interactions as ti JOIN interaction_types USING(interaction_type_id)";
		
#		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['x_taxon'] = $this->Se_db_taxon->get_taxon_by_id( $row['x_taxon'] );
				$result[$k]['y_taxon'] = $this->Se_db_taxon->get_taxon_by_id( $row['y_taxon'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
} // class