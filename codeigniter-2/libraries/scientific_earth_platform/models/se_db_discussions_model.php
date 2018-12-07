<?php

class Se_db_discussions_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create_discussion( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$section = $this->input->post( 'section' );
		
		$primary_id = $this->input->post( 'primary_id' );
		$primary_id_esc = $this->db->escape( $primary_id );
		
		$parent_discussion_id = $this->input->post( 'parent_discussion_id' );
		$parent_discussion_id_esc = $this->db->escape( $parent_discussion_id );
		
		$subject = $this->input->post('subject');
		$subject_esc = $this->db->escape( $subject );
		
		$message = $this->input->post('message');
		$message_esc = $this->db->escape( $message );
		
		if( empty( $message ) )
			return false;
		
		$query = "INSERT INTO discussions VALUES( NULL, {$parent_discussion_id_esc}, {$account_id_esc}, {$subject_esc}, {$message_esc}, CURRENT_TIMESTAMP() );";
		
		$sql = $this->db->query( $query );

		if( $this->db->insert_id() > 0 ):
			$discussion_id = $this->db->insert_id();
			$discussion_id_esc = $this->db->escape( $discussion_id );
			
			if( !empty( $parent_discussion_id ) && $parent_discussion_id > 0 ):
				# @se-points Points Earned - Reason: User contributed to a discussion
				$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user contributed to a discussion' );
			else:
				# @se-points Points Earned - Reason: User started a discussion
				$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '250', 'user started a discussion' );
			endif;
			
			if( empty( $primary_id ) )
				return true;
			
			$query = '';
			
			switch( $section ):
				case 'articles':
					$query = "INSERT INTO article_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				case 'collections':
					$query = "INSERT INTO collection_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				case 'journals':
					$query = "INSERT INTO journal_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				case 'recipes':
					$query = "INSERT INTO recipe_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				case 'taxa':
					$query = "INSERT INTO taxon_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				case 'images':
					$query = "INSERT INTO image_discussions VALUES( NULL, {$primary_id_esc}, {$discussion_id_esc} )";
					break;
				default:
					break;
			endswitch;
			echo $query;
			if( empty( $query ) )
				return false;
			
			$sql = $this->db->query( $query );
			
			if( $this->db->affected_rows() > 0 )
				return true;
			
		endif;

		return false;
	} // function
	
	public function get_discussions( $parent_discussion_id = '', $limit = 10, $offset = 0 ) {
		if( !empty($parent_discussion_id) ):
			$parent_discussion_id_esc = $this->db->escape( $parent_discussion_id );
			$query = "SELECT * FROM discussions JOIN accounts USING(account_id) WHERE parent_discussion_id = {$parent_discussion_id_esc} ORDER BY discussion_date ASC";
		else:
			$query = "SELECT * FROM discussions JOIN accounts USING(account_id) WHERE discussions.parent_discussion_id IS NULL OR discussions.parent_discussion_id = 0 ORDER BY discussion_date DESC";
		endif;
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['discussion_children'] = $this->get_discussions( $row['discussion_id'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
	public function get_taxa( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM taxon_discussions JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) GROUP BY taxa.taxon_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_articles( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM article_discussions JOIN articles USING(article_id) GROUP BY articles.article_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_collections( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM collection_discussions JOIN collections USING(collection_id) GROUP BY collections.collection_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_journals( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM journal_discussions JOIN journals USING(journal_id) GROUP BY journals.journal_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_recipes( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM recipe_discussions JOIN recipes USING(recipe_id) GROUP BY recipes.recipe_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class