<?php

class Se_db_collection_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_collection_by_url( $collection_url ) {
		$collection_url_esc = $this->db->escape( $collection_url );
		
		$query = "SELECT * FROM collections JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id) WHERE collection_url = {$collection_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_collection_categories( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT collection_categories.category_id, collection_categories.collection_id, c1.category_name, c1.category_url, c2.category_name as parent_category_name, c2.category_url as parent_category_url FROM collection_categories JOIN categories as c1 USING(category_id) LEFT JOIN categories as c2 ON collection_categories.parent_category_id = c2.category_id WHERE collection_categories.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_articles( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_articles JOIN articles USING(article_id) WHERE collection_articles.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_discussions( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_discussions JOIN discussions USING(discussion_id) JOIN accounts USING(account_id) WHERE collection_discussions.collection_id = {$collection_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['discussion_children'] = $this->Discussions_model->get_discussions( $row['discussion_id'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
	public function get_collection_images( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_images JOIN images USING(image_id) WHERE collection_images.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_journals( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_journals JOIN journals USING(journal_id) WHERE collection_journals.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_locations( $collection_id, $limit = 10, $offset = 0 ) {
		
	} // function
	
	public function get_collection_rating( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_ratings JOIN ratings USING(rating_id) WHERE collection_ratings.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_taxa( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_taxa JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE collection_taxa.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collection_terms( $collection_id, $limit = 10, $offset = 0 ) {
		$collection_id_esc = $this->db->escape( $collection_id );
		
		$query = "SELECT * FROM collection_terms JOIN terms as t USING(term_id) WHERE collection_terms.collection_id = {$collection_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class