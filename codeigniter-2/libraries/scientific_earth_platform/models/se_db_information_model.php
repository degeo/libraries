<?php

class Se_db_information_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create_article( $user_id ) {
		$user_id_esc = $this->db->escape( $user_id );
		
		$publish = $this->input->post( 'publish' );
		
		if( empty( $publish ) )
			$publish = '1';
		
		$publish_esc = $this->db->escape( $publish );
		
		$discuss = $this->input->post( 'discuss' );
		
		if( empty( $discuss ) )
			$discuss = '1';
		
		$discuss_esc = $this->db->escape( $discuss );
		
		$title = $this->input->post( 'title' );
		$title_esc = $this->db->escape( $title );
		
		$url = format_url( $this->input->post( 'title' ) );
		$url_esc = $this->db->escape( $url );
		
		$source = $this->input->post( 'source' );
		$source_esc = $this->db->escape( $source );
		
		$summary = $this->input->post( 'summary' );
		$summary_esc = $this->db->escape( $summary );

		$content = $this->input->post( 'content' );
		$content_esc = $this->db->escape( $content );

		$query = "INSERT INTO articles SET account_id = {$user_id_esc}, published = {$publish_esc}, discussions = {$discuss_esc}, article_title = {$title_esc}, article_url = {$url_esc}, article_summary = {$summary_esc}, article_date = CURRENT_TIMESTAMP()";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			$insert_id = $this->db->insert_id();
			
			$query = "INSERT INTO article_content SET article_content = {$content_esc}";
			
			$sql = $this->db->query( $query );

			if(  $this->db->affected_rows() > 0 )
				return true;
			else
				return false;
				
			# @todo add additional crap.
		endif;

		return false;
	} // function
	
	public function get_articles( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM information_articles";
		else:
			$query = "SELECT * FROM information_articles ORDER BY article_id DESC";
			
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
	
	public function get_sources( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM information_sources";
		else:
			$query = "SELECT * FROM information_sources ORDER BY source_id DESC";
			
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
	
	public function get_terms( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM information_terms";
		else:
			$query = "SELECT *, ( SELECT COUNT(*) as total FROM information_term_definitions WHERE information_term_definitions.term_id = information_terms.term_id GROUP BY information_term_definitions.term_id ) as total_definitions, ( SELECT COUNT(*) as total FROM information_term_synonyms WHERE information_term_synonyms.term_id = information_terms.term_id GROUP BY information_term_synonyms.term_id ) as total_synonyms FROM information_terms ORDER BY term_name ASC";
			
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
	
	public function get_definitions( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM information_definitions";
		else:
			$query = "SELECT * FROM information_definitions ORDER BY definition_id DESC";
			
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