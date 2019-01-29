<?php

class Autoloader_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load_models();
	} // function

	public function load_models() {
		# Load Helpers
		$this->load->helper('System/time_helper');
		$this->load->helper('System/value_helper');

		# Load Query Model
		$this->load->model('System/Query_model', 'Query');
		$this->load->model('System/CRUD_model', 'CRUD');

		# Load Ouput Model
		$this->load->model('Output/Output_model', 'Output');

		# Load Messages Model
		$this->load->model('Messages/Messages_model', 'Messages');
	} // function

} // class