<?php

class Se_db_chemicals_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_elements( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM chemical_elements";
		else:
			$query = "SELECT * FROM chemical_elements LEFT JOIN chemical_names USING(chemical_name_id)";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_bonds( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM chemical_bonds";
		else:
			$query = "SELECT * FROM chemical_bonds";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_formulas( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM chemical_formulas";
		else:
			$query = "SELECT *, (SELECT COUNT(*) as total FROM chemical_formula_elements WHERE chemical_formula_elements.chemical_formula_id = chemical_formulas.chemical_formula_id GROUP BY chemical_formula_elements.chemical_formula_id) as total_unique_elements FROM chemical_formulas";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_compounds( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM chemical_compounds";
		else:
			$query = "SELECT *, (SELECT COUNT(*) as total FROM chemical_compound_synonyms WHERE chemical_compound_synonyms.chemical_compound_id = chemical_compounds.chemical_compound_id GROUP BY chemical_compound_synonyms.chemical_compound_id) as total_synonyms, (SELECT COUNT(*) as total FROM chemical_compound_interactions WHERE chemical_compound_interactions.x_chemical_compound_id = chemical_compounds.chemical_compound_id OR chemical_compound_interactions.y_chemical_compound_id = chemical_compounds.chemical_compound_id) as total_chemical_interactions FROM chemical_compounds LEFT JOIN chemical_names USING(chemical_name_id) LEFT JOIN chemical_formulas USING(chemical_formula_id)";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_substances( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM chemical_substances";
		else:
			$query = "SELECT * FROM chemical_substances";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
} // class