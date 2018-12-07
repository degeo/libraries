<?php

class Se_app_user_model extends CI_Model {
	
	private $session_key = 'user';
	
	private $user_id;
	
	private $user = array();
	
	private $roles = array();
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function start_session( $user_id = '' ) {
		if( empty( $user_id ) || $user_id == 0 ):
			$this->user = $this->session->userdata( $this->session_key );
			
			if( !empty( $this->user ) ):
				$this->user_id = $this->user['account_id'];
				$this->roles = $this->Se_db_account->get_account_roles( $this->user['account_id'] );
			endif;
			
		else:
			$this->user_id = $user_id;
			$this->user = $this->Se_db_account->get_account( $user_id );
			$this->roles = $this->Se_db_account->get_account_roles( $user_id );
		endif;
			
		if( !empty( $this->user_id ) ):
			$this->Se_app_network_sessions->update_session( $this->user_id );
		endif;
		
		$this->create_session();
	} // function
	
	public function create_session() {
		$this->session->set_userdata( $this->session_key, $this->user );
	} // function
	
	public function update_session() {
		$this->session->set_userdata( $this->session_key, $this->user );
	} // function
	
	public function destroy_session() {
		$this->Se_app_network_sessions->delete_session( $this->user_id );
		
		$this->session->sess_destroy();
	} // function
	
	public function refresh_session() {
		$this->destroy_session();
		$this->create_session();
	} // function
	
	public function set_id( $user_id ) {
		if( empty( $user_id ) )
			return false;
		
		$this->user_id = $user_id;
		
		return true;
	} // function
	
	public function get_id() {
		if( empty( $this->user_id ) )
			return false;
		
		return $this->user_id;
	} // function
	
	public function get_info( $key = '' ) {
		if( !empty( $key ) && array_key_exists( $key, $this->user ) )
			return $this->user[$key];
		
		return '';
	} // function
	
	public function get_points() {
		$key = 'total_points';
		
		if( !empty( $key ) && array_key_exists( $key, $this->user ) && $this->user[$key] != '' )
			return $this->user[$key];
		
		return '0';
	} // function
	
	public function is_loggedin() {
		if( empty( $this->user ) )
			return false;
		
		if( array_key_exists( 'account_id', $this->user ) && !empty( $this->user['account_id'] ) )
			return true;
	
		return false;
	} // function
	
	public function is_disabled() {
		if( !$this->is_loggedin() )
			return false;
		
		if( array_key_exists( 'disabled', $this->roles ) )
			return true;
		
		return false;
	} // function

	public function auth_required() {
		if( !$this->is_loggedin() ):
			if( ENVIRONMENT == 'development' ):
				redirect( 'http://localhost/dgt/accounts.scientificearth.net/?goto=' . base_url(), 'refresh' );
			else:
				redirect( 'http://accounts.scientificearth.net/?goto=' . base_url(), 'refresh' );
			endif;
		endif;
	} // function
	
	public function has_role( $role = '' ) {
		if( !$this->is_loggedin() )
			return false;
		
		if( !empty( $role ) && !empty( $this->roles ) ):
			
			if( $this->is_disabled() )
				return false;

			if( array_key_exists( 'admin', $this->roles ) || array_key_exists( $role, $this->roles ) )
				return true;

		endif;

		return false;
	} // function
	
} // class