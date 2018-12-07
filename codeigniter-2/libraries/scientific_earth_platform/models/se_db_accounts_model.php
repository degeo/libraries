<?php

class Se_db_accounts_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_accounts( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count ):
			$query = "SELECT COUNT(*) as total FROM accounts";
		else:
			$query = "SELECT DISTINCT accounts.*, SUM(account_points_history.points) as total_points FROM accounts LEFT JOIN account_points_history USING(account_id) GROUP BY account_id ORDER BY account_id DESC";
		endif;
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			if( $get_count ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;

		return array();
	} // function
	
	public function create_account( $email, $username, $password = '' ) {
		$email_esc = $this->db->escape($email);
		$username_esc = $this->db->escape( $username );
		$password_esc = $this->db->escape( $password );

		$url = format_user_url( $username );
		$url_esc = $this->db->escape( $url );
		
		$roles = array( '1' => '1' );
		
		$account_type_id = 1;
		$account_type_id_esc = $this->db->escape( $account_type_id );
		
		$ip_addr = $this->input->ip_address();
		$ip_addr_esc = $this->db->escape( $ip_addr );
		
		# @todo url_esc
		$query = "INSERT INTO accounts VALUES( NULL, {$account_type_id_esc}, {$email_esc}, {$username_esc}, {$url_esc}, CURRENT_TIMESTAMP(), {$ip_addr_esc}, NULL, NULL, NULL, NULL )";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			$account_id = $this->db->insert_id();
			
			$userdata = array(
				'account_id' => $account_id,
				'account_type_id' => 1,
				'email' => $email,
				'username' => $username,
				'user_url' => $url,
				'registration_date' => '',
				'regisstration_ip' => $ip_addr,
				'last_login_date' => '',
				'last_known_ip' => '',
				'dob' => '',
				'job_title' => '',
				'total_points' => '250'
				);
			
			$this->insert_account_roles( $account_id, $roles );
			
			# @se-points Points Earned - Reason: Created an Account
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '250', 'created a scientific earth network account' );
			
			if( empty( $password ) )
				return $userdata;
			
			$query = "INSERT INTO account_authorization VALUES( '{$account_id}', {$password_esc} )";
			$sql = $this->db->query( $query );
			
			return $userdata;
		endif;
		
		return false;
	} // function
	
	public function insert_account_roles(  $account_id, $roles ) {
		if( empty( $account_id ) || empty( $roles ) )
			return false;
			
		$account_id_esc = $this->db->escape( $account_id );
		
		$values = "";
		foreach( $roles as $role ):
			$role_esc = $this->db->escape( $role );
			$values .= "( NULL, {$account_id_esc}, {$role_esc} ),";
		endforeach;
		
		$values = rtrim( $values, ',' );
		
		$query = "INSERT INTO account_roles VALUES {$values}";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 )
			return true;
		
		return false;
	} // function
	
	public function lookup_account_type_id( $account_type ) {
		$account_type_esc = $this->db->escape( $account_type );
		
		$query = "SELECT account_type_id FROM account_types WHERE account_type = {$account_type_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
} // class