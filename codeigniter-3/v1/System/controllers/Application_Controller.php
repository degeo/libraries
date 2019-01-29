<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();

		# Load Application
		$this->load->model( 'System/Application_model', 'Application' );
		$this->Application->start();

		$this->Layout->add_section( 'messages', "Messages/alert", 15 );

		$messages = $this->Messages->get_messages();
		$this->Application->add_data( 'messages', $messages );
	} // function

} // class
