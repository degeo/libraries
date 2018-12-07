<?php

class Se_db_roles_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function

	public function get_signupcode_roles( $signupcode ) {
		$signupcode_esc = $this->db->escape( $signupcode );
		
		$query = "SELECT account_authorization_role_id as role_id FROM account_signupcodes as acctsc JOIN account_signupcode_roles as acctscr USING(account_signupcode_id) WHERE acctsc.signupcode = UPPER({$signupcode_esc})";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			$data = array();
			foreach( $result as $row ):
				$data[$row['role_id']] = $row['role_id'];
			endforeach;
			
			return $data;
		endif;
		
		return array();
	} // function
	
	public function get_account_type_roles( $account_type ) {
		$account_type_esc = $this->db->escape( $account_type );
		
		$query = "SELECT account_authorization_role_id as role_id FROM account_type as at JOIN account_type_roles as accttr USING(account_type_id) WHERE at.account_type = {$account_type_esc}";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			$data = array();
			foreach( $result as $row ):
				$data[$row['role_id']] = $row['role_id'];
			endforeach;
			
			return $data;
		endif;
		
		return array();
	} // function

} // class