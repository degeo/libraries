<?php

class Se_db_soils_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_soil_textures( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM soil_textures";
		else:
			$query = "SELECT * FROM soil_textures";
			
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
	
} // class