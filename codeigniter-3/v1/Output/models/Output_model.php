<?php

class Output_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load_models();
	} // function

	public function load_models() {
		# Load Hosts Model
		$this->load->model('Output/Hosts_model', 'Hosts');

		# Load Assets Model
		$this->load->model('Output/Assets_model', 'Assets');

		# Load Layout Model
		$this->load->model('Output/Layout_model', 'Layout');

		# Load Metatags Model
		$this->load->model('Output/Metatags_model', 'Metatags');
	} // function

} // class