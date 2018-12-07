<?php

class Se_db_category_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_category_by_url( $category_url ) {
		$category_url_esc = $this->db->escape( $category_url );
		
		$query = "SELECT * FROM categories WHERE category_url = {$category_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_category_discussions( $category_id, $limit = 10, $offset = 0, $get_count = false, $ecosystem_ids = '' ) {
		$category_id_esc = $this->db->escape( $category_id );
		
		if( !empty( $ecosystem_ids ) ):
			$ecosystems_where = '';
			foreach( $ecosystem_ids as $es ):
				$ecosystems_where .= "discussion_ecosystems.ecosystem_id = '{$es['ecosystem_id']}' OR ";
			endforeach;
			$ecosystems_where = rtrim( $ecosystems_where, ' OR ' );
		else:
			$ecosystems_where = "discussion_ecosystems.ecosystem_id = '3' OR discussion_ecosystems.ecosystem_id = '4' OR discussion_ecosystems.ecosystem_id = '5'";
		endif;
		
		if( $get_count === true ):
			$query = "SELECT COUNT(discussions.discussion_id) as total FROM discussion_categories JOIN discussions USING(discussion_id) JOIN discussion_ecosystems USING(discussion_id) JOIN accounts USING(account_id) WHERE discussion_categories.category_id = {$category_id_esc} AND ({$ecosystems_where})";
		else:
			$query = "SELECT *, (SELECT COUNT(*) FROM discussions WHERE parent_discussion_id = discussions.discussion_id ) as total_replies FROM discussion_categories JOIN discussions USING(discussion_id) JOIN discussion_ecosystems USING(discussion_id) JOIN accounts USING(account_id) WHERE discussion_categories.category_id = {$category_id_esc} AND ({$ecosystems_where})";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$result = $sql->row_array();
				return $result['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function

} // class