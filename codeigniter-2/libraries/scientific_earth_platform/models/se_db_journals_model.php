<?php

class Se_db_journals_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create( $user_id ) {
		$user_id_esc = $this->db->escape( $user_id );
		/*
		$discuss = $this->input->post( 'discuss' );
		
		if( empty( $discuss ) )
			$discuss = '1';
		
		$discuss_esc = $this->db->escape( $discuss );
		*/
		$journal_name = $this->input->post( 'journal_name' );
		$journal_name_esc = $this->db->escape( $journal_name );
		
		$url = format_url( $journal_name );
		$url_esc = $this->db->escape( $url );

		$query = "INSERT INTO journals VALUES( NULL, {$user_id_esc}, NULL, NULL, {$journal_name_esc}, {$url_esc} )";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			$insert_id = $this->db->insert_id();
			
			if( $insert_id )
				return true;
			else
				return false;
				
			# @todo add additional crap.
		endif;

		return false;
	} // function
	
	public function get_recent( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM journal_entries JOIN journals USING(journal_id) JOIN accounts USING(account_id)";
		else:
			$query = "SELECT * FROM journal_entries JOIN journals USING(journal_id) JOIN accounts USING(account_id)";
			
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
	
	public function get_articles( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM articles JOIN article_journals USING(article_id) JOIN journal_articles USING(article_id)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_categories( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM categories JOIN journal_categories USING(category_id)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussions( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM discussions JOIN journal_discussions USING(discussion_id)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class