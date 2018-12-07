<?php

class Se_db_recipes_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_recipes( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT DISTINCT COUNT(*) as total FROM recipes JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id)";
		else:
			$query = "SELECT * FROM recipes JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id)";
			
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