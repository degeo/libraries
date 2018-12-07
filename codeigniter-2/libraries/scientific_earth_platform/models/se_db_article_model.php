<?php

class Se_db_article_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_article_by_url( $article_url ) {
		$article_url_esc = $this->db->escape( $article_url );
		
		$query = "SELECT * FROM articles JOIN article_content USING(article_id) JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id) LEFT JOIN resources USING(resource_id) WHERE article_url = {$article_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_article_content( $article_id ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT article_content FROM article_content WHERE article_id = {$article_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_article_categories( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT article_categories.category_id, article_categories.article_id, c1.category_name, c1.category_url, c2.category_name as parent_category_name, c2.category_url as parent_category_url FROM article_categories JOIN categories as c1 USING(category_id) LEFT JOIN categories as c2 ON article_categories.parent_category_id = c2.category_id WHERE article_categories.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_resources( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_resources JOIN resources USING(resource_id) WHERE article_resources.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_collections( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_collections JOIN collections USING(collection_id) WHERE article_collections.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_discussions( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_discussions JOIN discussions USING(discussion_id) JOIN accounts USING(account_id) WHERE article_discussions.article_id = {$article_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		
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
	
	public function get_article_images( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_images JOIN images USING(image_id) WHERE article_images.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_journals( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_journals JOIN journals USING(journal_id) WHERE article_journals.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_locations( $article_id, $limit = 10, $offset = 0 ) {
		
	} // function
	
	public function get_article_rating( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_ratings JOIN ratings USING(rating_id) WHERE article_ratings.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_taxa( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_taxa JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE article_taxa.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_article_terms( $article_id, $limit = 10, $offset = 0 ) {
		$article_id_esc = $this->db->escape( $article_id );
		
		$query = "SELECT * FROM article_terms JOIN terms USING(term_id) WHERE article_terms.article_id = {$article_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class