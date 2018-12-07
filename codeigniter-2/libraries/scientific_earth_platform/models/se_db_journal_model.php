<?php

class Se_db_journal_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_journal_by_url( $journal_url ) {
		$journal_url_esc = $this->db->escape( $journal_url );
		
		$query = "SELECT * FROM journals JOIN journal_entries USING(journal_id) JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id) WHERE journal_url = {$journal_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_journal_entry( $journal_id ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT journal_entry FROM journal_entries WHERE journal_id = {$journal_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_journal_articles( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_articles as ja JOIN articles as a USING(article_id) WHERE ja.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_categories( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT ac.category_id, ac.journal_id, c1.category_name, c1.category_url, c2.category_name as parent_category_name, c2.category_url as parent_category_url FROM journal_categories as ac JOIN categories as c1 USING(category_id) LEFT JOIN categories as c2 ON ac.parent_category_id = c2.category_id WHERE ac.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_collections( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_collections as ac JOIN collections as c USING(collection_id) WHERE ac.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_discussions( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_discussions as ad JOIN discussions as d USING(discussion_id) WHERE ad.journal_id = {$journal_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		
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
	
	public function get_journal_images( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_images as ai JOIN images as i USING(image_id) WHERE ai.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_locations( $journal_id, $limit = 10, $offset = 0 ) {
		
	} // function
	
	public function get_journal_rating( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_ratings as ar JOIN ratings as r USING(rating_id) WHERE ar.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_taxa( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_taxa as at JOIN taxa as t USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE at.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journal_terms( $journal_id, $limit = 10, $offset = 0 ) {
		$journal_id_esc = $this->db->escape( $journal_id );
		
		$query = "SELECT * FROM journal_terms as at JOIN terms as t USING(term_id) WHERE at.journal_id = {$journal_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class