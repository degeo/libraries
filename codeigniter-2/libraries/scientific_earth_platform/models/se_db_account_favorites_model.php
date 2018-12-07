<?php

class Se_db_account_favorites_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function make_favorite( $account_id, $taxon_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "INSERT IGNORE INTO account_favorite_taxa VALUES( NULL, {$account_id_esc}, {$taxon_id_esc} )";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 )
			return true;
		
		return false;
	} // function
	
	public function remove_favorite( $account_id, $taxon_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "DELETE FROM account_favorite_taxa WHERE account_id = {$account_id_esc} AND taxon_id = {$taxon_id_esc}";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 )
			return true;
		
		return false;
	} // function
	
	public function get_taxon_favorites( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM account_favorite_taxa WHERE account_id = {$account_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function is_taxon_favorite( $account_id, $taxon_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT TRUE as is_favorite FROM account_favorite_taxa WHERE account_id = {$account_id_esc} AND taxon_id = {$taxon_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$result = $sql->row_array();
			return $result['is_favorite'];
		endif;

		return false;
	} // function

} // class