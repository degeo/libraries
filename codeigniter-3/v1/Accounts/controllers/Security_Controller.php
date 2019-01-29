<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_Controller extends Application_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model( 'Accounts/Account_Security_model', 'Account_Security' );
		$this->load->model( 'Accounts/Acl_model', 'ACL' );
		$this->load->model( 'Accounts/User_model', 'User' );
		$this->load->model( 'Accounts/Account_model', 'Account' );
	} // function

} // class
