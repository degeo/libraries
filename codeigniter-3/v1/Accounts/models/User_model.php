<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_model {

	protected $user = array(
		'type' => 'visitor',
		'is_logged_in' => false
	);
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_userdata() {
		$this->current_user = $this->session->userdata('user');
		
#		$this->data['is_admin'] = $this->user_has_role( 'admin' );
		$this->data['is_user'] = $this->is_user();
		$this->data['is_user_disabled'] = $this->is_user_disabled();
		
		if( $this->is_user() )
			$this->data['current_user'] = $this->current_user;
	} // function
	
	public function get_user_id() {
		if( !$this->is_user() )
			return false;
		
		return $this->current_user['user_id'];
	} // function
	
} // class
