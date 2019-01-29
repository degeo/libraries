<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorized_Controller extends Security_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->Account_Security->is_authorized();
		
#		$this->Layout->add_section( 'navigation', 'main.navi.php', 20 );
		
		$this->Layout->add_section( 'page-title', 'Output/templates/page-title', 30 );
	} // function
	
} // class
