<?php

class Se_db_collections_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create( $user_id ) {
		$user_id_esc = $this->db->escape( $user_id );
		
		$collection_name = $this->input->post( 'collection_name' );
		$collection_name_esc = $this->db->escape( $collection_name );
		
		$url = format_url( $this->input->post( 'collection_name' ) );
		$url_esc = $this->db->escape( $url );

		$query = "INSERT INTO collections SET account_id = {$user_id_esc}, collection_name = {$collection_name_esc}, collection_url = {$url_esc}";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			$insert_id = $this->db->insert_id();
			return $insert_id;
			/*
			$query = "";
			
			$sql = $this->db->query( $query );

			if(  $this->db->affected_rows() > 0 )
				return true;
			else
				return false;
			*/	
			# @todo add additional crap.
		endif;

		return false;
	} // function
	
	public function get_collections( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM collections JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class