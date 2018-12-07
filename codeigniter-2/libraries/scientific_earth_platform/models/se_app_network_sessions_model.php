<?php

class Se_app_network_sessions_model extends CI_Model {
	
	private $network_accounts_table = 'accounts';
	private $network_sessions_table = 'account_sessions';
	
	private $encryption_key;
	
	private $ip_addr;
	private $user_agent;
	
	private $session_id;
	
	private $network_session;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('user_agent');
		
		$this->encryption_key = $this->config->item('encryption_key');
		
		$this->ip_addr = $this->input->ip_address();
		$this->user_agent = $this->agent->agent_string();
		
		$this->session_id = sha1( '<' . $this->encryption_key . '>' . $this->ip_addr . $this->user_agent );
	} // function
	
	public function start_session() {
		#$this->session->sess_destroy();
		$this->session->unset_userdata('user');
		
		$this->network_session = $this->select_session();
		
		if( empty( $this->network_session ) ):
			$this->insert_session();
		endif;
		
		$this->load->model( 'Se_app_user_model', 'Se_app_user' );
		
		if( !empty( $this->network_session['account_id'] ) && $this->network_session['account_id'] != 0 ):
			$this->Se_app_user->start_session( $this->network_session['account_id'] );
		else:
			$this->Se_app_user->start_session();
		endif;
		
		return true;
	} // function
	
	public function select_session() {
		$query = "SELECT {$this->network_accounts_table}.* FROM {$this->network_sessions_table} JOIN {$this->network_accounts_table} USING(account_id) WHERE network_session_id = '{$this->session_id}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;
		
		return false;
	} // function
	
	public function insert_session( $account_id = 0 ) {
		$query = "INSERT IGNORE INTO {$this->network_sessions_table} VALUES( '{$account_id}', '{$this->session_id}', CURRENT_TIMESTAMP() )";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;
		
		return false;
	} // function
	
	public function update_session( $account_id = 0 ) {
		$query = "UPDATE {$this->network_sessions_table} SET account_id = '{$account_id}' WHERE network_session_id = '{$this->session_id}'";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;
		
		return false;
	} // function
	
	public function delete_session( $account_id = 0 ) {
		$query = "DELETE FROM {$this->network_sessions_table} WHERE account_id = '{$account_id}' AND network_session_id = '{$this->session_id}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		$this->delete_application_sessions();
		
		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;
		
		return false;
	} // function
	
	public function delete_application_sessions() {
		$query = "DELETE FROM visitor_sessions WHERE ip_address = '{$this->ip_addr}' AND user_agent = '{$this->user_agent}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;
		
		return false;
	} // function
	
} // class