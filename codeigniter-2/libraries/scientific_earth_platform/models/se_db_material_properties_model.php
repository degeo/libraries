<?php

class Se_db_material_properties_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_properties( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM material_properties";
		else:
			$query = "SELECT * FROM material_properties LEFT JOIN material_property_types USING(material_property_type_id) LEFT JOIN information_terms USING(term_id) ORDER BY term_name ASC";
			
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
	
	public function get_property_by_term( $term_id ) {
		$term_id_esc = $this->db->escape( $term_id );
		
		$query = "SELECT * FROM material_properties WHERE term_id = {$term_id_esc}";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;
		
		return array();
	} // function
	
	public function get_property_types( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM material_property_types";
		else:
			$query = "SELECT * FROM material_property_types ORDER BY material_property_type_name ASC";
			
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
	
	public function insert_property( $term_id, $material_property_type_id = '' ) {
		$term_id_esc = $this->db->escape( $term_id );
		$material_property_type_id_esc = $this->db->escape( $material_property_type_id );
		
		$query = "INSERT IGNORE INTO material_properties VALUES( NULL, {$material_property_type_id_esc}, {$term_id} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return false;
	} // functions
	
	public function insert_property_value( $material_property_value_type = 'string', $value ) {
		if( empty( $value ) )
			return false;
		
		$material_property_value_type_esc = $this->db->escape( $material_property_value_type );
		
		$query = "INSERT INTO material_property_values VALUES( NULL, {$material_property_value_type_esc}, NULL )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			$material_property_value_id = $this->db->insert_id();
			
			switch( $material_property_value_type ):
				case 'binary':
					$this->insert_property_value_binary( $material_property_value_id, $value );
					break;
				case 'decimal':
					$this->insert_property_value_decimal( $material_property_value_id, $value );
					break;
				case 'range':
					$value = array_merge( array( array('minimum'=>NULL), array('maximum'=>NULL), array('average'=>NULL) ), $value );
				
					$this->insert_property_value_range( $material_property_value_id, $value['minimum'], $value['maximum'], $value['average'] );
					break;
				case 'term':
					$this->insert_property_value_term( $material_property_value_id, $value );
					break;
				case 'string':
				default:
					$this->insert_property_value_string( $material_property_value_id, $value );
					break;
			endswitch;
			
			return $material_property_value_id;
		endif;

		return false;
	} // functions
	
	public function insert_property_value_binary( $material_property_value_id, $value ) {
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		$value_esc = $this->db->escape( $value );
		
		$query = "INSERT INTO material_property_value_binaries VALUES( {$material_property_value_id_esc}, {$value_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
	public function insert_property_value_decimal( $material_property_value_id, $value ) {
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		$value_esc = $this->db->escape( $value );
		
		$query = "INSERT INTO material_property_value_decimals VALUES( {$material_property_value_id_esc}, {$value_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
	public function insert_property_value_range( $material_property_value_id, $min_value = NULL, $max_value = NULL, $avg_value = NULL ) {
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		$min_value_esc = $this->db->escape( $min_value );
		$max_value_esc = $this->db->escape( $max_value );
		$avg_value_esc = $this->db->escape( $avg_value );
		
		$query = "INSERT INTO material_property_value_ranges VALUES( {$material_property_value_id_esc}, {$min_value_esc}, {$max_value_esc}, {$avg_value} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
	public function insert_property_value_string( $material_property_value_id, $value ) {
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		$value_esc = $this->db->escape( $value );
		
		$query = "INSERT INTO material_property_value_strings VALUES( {$material_property_value_id_esc}, {$value_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
	public function insert_property_value_term( $material_property_value_id, $value ) {
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		$value_esc = $this->db->escape( $value );
		
		$query = "INSERT INTO material_property_value_terms VALUES( {$material_property_value_id_esc}, {$value_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
} // class