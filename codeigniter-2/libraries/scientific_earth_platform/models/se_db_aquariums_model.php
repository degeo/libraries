<?php

class Se_db_aquariums_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create_aquarium( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$aquarium_name = $this->input->post('aquarium_name');
		$aquarium_name_esc = $this->db->escape( $aquarium_name );
		
		$aquarium_url = format_user_url( $aquarium_name );
		$aquarium_url_esc = $this->db->escape( $aquarium_url );

		$water_type = $this->input->post('water_type');
		$water_type_esc = $this->db->escape( $water_type );
		
		$gallons = $this->input->post('gallons');
		$gallons_esc = $this->db->escape( $gallons );
		
		
		$query = "INSERT INTO aquariums VALUES( NULL, {$account_id_esc}, {$aquarium_name_esc}, {$aquarium_url_esc}, {$gallons_esc}, {$water_type_esc} )";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			$insert_id = $this->db->insert_id();
		
			# @se-points Points Earned - Reason: User added an aquarium
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user added an aquarium' );
		
			return $insert_id;
		endif;

		return false;
	} // function
	
	public function remove_aquarium( $account_id, $aquarium_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "DELETE FROM aquariums WHERE account_id = {$account_id_esc} AND aquarium_id = {$aquarium_id_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
} // class