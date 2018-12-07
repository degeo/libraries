<?php

class Se_db_gardens_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function create_garden( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$garden_name = $this->input->post('garden_name');
		$garden_name_esc = $this->db->escape( $garden_name );
		
		$garden_url = format_user_url( $garden_name );
		$garden_url_esc = $this->db->escape( $garden_url );

		$soil_type = $this->input->post('soil_type');
		$soil_type_esc = $this->db->escape( $soil_type );
		
		$soil_depth = $this->input->post('soil_depth');
		$soil_depth_esc = $this->db->escape( $soil_depth );
		
		$temperature = $this->input->post('temperature');
		$temperature_esc = $this->db->escape( $temperature );
		
		$precipitation_level = $this->input->post('precipitation_level');
		$precipitation_level_esc = $this->db->escape( $precipitation_level );
		
		$ph_level = $this->input->post('ph_level');
		$ph_level_esc = $this->db->escape( $ph_level );
		
		$nitrogen_level = $this->input->post('nitrogen_level');
		$nitrogen_level_esc = $this->db->escape( $nitrogen_level );
		
		$phosphorous_level = $this->input->post('phosphorous_level');
		$phosphorous_level_esc = $this->db->escape( $phosphorous_level );
		
		$potash_level = $this->input->post('potash_level');
		$potash_level_esc = $this->db->escape( $potash_level );
		
		
		$query = "INSERT INTO gardens VALUES( NULL, {$account_id_esc}, {$garden_name_esc}, {$garden_url_esc}, {$soil_depth_esc}, {$soil_type_esc} )";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			$insert_id = $this->db->insert_id();
			$insert_id_esc = $this->db->escape( $insert_id );
			
#			$query = "INSERT INTO garden_readings VALUES( NULL, {$insert_id_esc}, CURRENT_TIMESTAMP(), {$temperature_esc}, {$precipitation_level_esc}, {$ph_level_esc}, {$nitrogen_level_esc}, {$phosphorous_level_esc}, {$potash_level_esc} )";
			$query = "INSERT INTO garden_readings VALUES( NULL, {$insert_id_esc}, CURRENT_TIMESTAMP(), {$temperature_esc}, NULL, {$ph_level_esc}, NULL, NULL, NULL )";
			$sql = $this->db->query( $query );
			
			return $insert_id;
		endif;

		return false;
	} // function
	
	public function remove_garden( $account_id, $garden_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "DELETE FROM gardens WHERE account_id = {$account_id_esc} AND garden_id = {$garden_id_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if(  $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
} // class