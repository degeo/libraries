<?php

class Se_db_image_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_image_species( $image_id, $limit = 0, $offset = 0 ) {
		$image_id_esc = $this->db->escape( $image_id );
		
		$query = "SELECT * FROM taxon_images JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE taxon_images.image_id = {$image_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;
		
		return array();
	} // function
	
	public function get_image_discussions( $image_id, $limit = 10, $offset = 0, $get_count = false ) {
		$image_id_esc = $this->db->escape( $image_id );
		
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM image_discussions JOIN discussions USING(discussion_id) JOIN accounts USING(account_id) WHERE image_discussions.image_id = {$image_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		else:
			$query = "SELECT * FROM image_discussions JOIN discussions USING(discussion_id) JOIN accounts USING(account_id) WHERE image_discussions.image_id = {$image_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
		
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				$result = $sql->result_array();
			
				foreach( $result as $k => $row ):
					$result[$k]['discussion_children'] = $this->Se_db_discussions->get_discussions( $row['discussion_id'] );
				endforeach;
			
				return $result;
			endif;
			
		endif;
		
		return array();
	} // function
	
} // class