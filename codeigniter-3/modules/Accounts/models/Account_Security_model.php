<?php

class Account_Security_model extends CRUD_model {
	
	protected $session_key = 'user';
	
	protected $user = array();
	
	protected $accounts = array();
	
	protected $account = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->account = $this->read_session();
	} // function
	
	public function create_session( $userdata ) {
		$this->account = array_merge( $this->account, $userdata );
		$this->session->set_userdata( $this->session_key, $userdata );
	} // function
	
	public function read_session() {
		$userdata = $this->session->userdata( $this->session_key );
		
		if( empty( $userdata ) )
			return array();
		
		return array_merge( $this->account, $userdata );
	} // function
	
	public function update_session( $userdata ) {
		$this->account = array_merge( $this->account, $userdata );
		$this->session->set_userdata( $this->session_key, $userdata );
	} // function
	
	public function destroy_session() {
		$this->session->sess_destroy();
		$this->account = array();
	} // function

	public function auth_required( $internal_url = 'authorize/login' ) {
		if( !$this->is_authorized() )
			redirect( site_url( $internal_url ), 'refresh' );
	} // function
	
	/**
	 * User has successfully logged in
	 */
	public function authorize_user( $userdata ) {
		$this->create_session( $userdata );
		
		$message = "Welcome back";
		
		$username = $userdata['account_username'];
		
		$this->Messages->add( 'success', "Welcome back, {$username}!" );
		
		$redirect_url = $this->session->userdata('redirect_url');
		if( !empty( $redirect_url ) ):
			redirect( $redirect_url, 'location', 301 );
		endif;
		
		redirect( site_url(), 'location', 301 );
	} // function
	
	public function is_authorized() {
		if( empty( $this->account ) )
			return false;
			
		if( array_key_exists( 'account_id', $this->account ) && !empty( $this->account['account_id'] ) )
			return true;
	
		return false;
	} // function
	
} // class