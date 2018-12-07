<?php

class Se_db_measurement_systems_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_systems( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM measurement_systems";
		else:
			$query = "SELECT * FROM measurement_systems";
			
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
	
	public function get_units( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM measurement_units";
		else:
			$query = "SELECT * FROM measurement_units LEFT JOIN measurement_physical_quantities USING(measurement_physical_quantity_id) LEFT JOIN measurement_systems USING(measurement_system_id)";
			
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
	
	public function get_physical_quantities( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM measurement_physical_quantities";
		else:
			$query = "SELECT * FROM measurement_physical_quantities";
			
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