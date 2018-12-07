<?php

class Se_db_categories_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_category_by_url( $category_url ) {
		
	} // function
	
	public function get_categories( $categories = '', $limit = 0, $offset = 0 ) {
		if( !empty( $categories ) ):
			$categories_where = " AND ( ";
			foreach( $categories as $category_id ):
				$category_id_esc = $this->db->escape( $category_id );
				$categories_where .= "category_id = {$category_id_esc} OR ";
			endforeach;
			$categories_where = rtrim( $categories_where, ' OR ' );
			$categories_where .= " )";
			
			$query = "SELECT * FROM categories WHERE 1=1{$categories_where} ORDER BY category_sort_weight DESC, category_name ASC";
		else:
			$query = "SELECT * FROM categories ORDER BY category_sort_weight DESC, category_name ASC";
		endif;
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_category_content( $category_id ) {
		
	} // function
	
	public function get_category_categories( $category_id ) {
		
	} // function
	
	public function get_category_collections( $category_id ) {
		
	} // function
	
	public function get_category_discussions( $category_id ) {
		
	} // function
	
	public function get_category_images( $category_id ) {
		
	} // function
	
	public function get_category_journals( $category_id ) {
		
	} // function
	
	public function get_category_locations( $category_id ) {
		
	} // function
	
	public function get_category_rating( $category_id ) {
		
	} // function
	
	public function get_category_taxa( $category_id ) {
		
	} // function
	
	public function get_category_terms( $category_id ) {
		
	} // function
	
} // class