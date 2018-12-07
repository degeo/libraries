<?php

class Autoloader_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load_models();
	} // function

	public function load_models() {
		# Load Helpers
		$this->load->helper('System/time_helper');

		# Load Query Model
		$this->load->model('System/Query_model', 'Query');
		$this->load->model('System/CRUD_model', 'CRUD');

		# Load Ouput Model
		$this->load->model('Output/Output_model', 'Output');
		$this->load->model('Messages/Messages_model', 'Messages');
	} // function

} // class