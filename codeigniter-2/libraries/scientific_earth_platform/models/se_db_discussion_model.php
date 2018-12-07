<?php

class Se_db_discussion_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_discussion_children( $discussion_id, $limit = 10, $offset = 0  ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussions WHERE discussions.parent_discussion_id = {$discussion_id_esc} ORDER BY discussion_date DESC ";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_collections( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_collections as ac JOIN collections as c USING(collection_id) WHERE ac.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_images( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_images as ai JOIN images as i USING(image_id) WHERE ai.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_journals( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_journals as aj JOIN journals as j USING(journal_id) WHERE aj.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_locations( $discussion_id, $limit = 10, $offset = 0 ) {
		
	} // function
	
	public function get_discussion_rating( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_ratings as ar JOIN ratings as r USING(rating_id) WHERE ar.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_taxa( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_taxa JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE discussion_taxa.discussion_id = {$discussion_id_esc} GROUP BY taxa.taxon_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_articles( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_articles JOIN articles USING(article_id) WHERE discussion_articles.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussion_categories( $discussion_id, $limit = 10, $offset = 0 ) {
		$discussion_id_esc = $this->db->escape( $discussion_id );
		
		$query = "SELECT * FROM discussion_categories JOIN categories USING(category_id) WHERE discussion_categories.discussion_id = {$discussion_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class