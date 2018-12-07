<?php

class Se_app_loader_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function load_helpers() {
		# Load CodeIgniter Helpers
		$this->load->helper( 'url' );
		
		# Load Scientific Earth Helpers
		$this->load->helper( 'se_dev' );
		$this->load->helper( 'se_role' );
		$this->load->helper( 'se_format' );
		$this->load->helper( 'se_model' );
		$this->load->helper( 'se_loop' );
		$this->load->helper( 'se_breadcrumbs' );
		$this->load->helper( 'se_discussions' );
		
		return true;
	} // function
	
	public function load_libraries() {
		# Load CodeIgniter Libraries
#		$this->load->library( 'database' );
		$this->load->library( 'session' );
		$this->load->library( 'user_agent' );
		$this->load->library( 'form_validation' );
		
		return true;
	} // function
	
	public function load_models() {
		# Load Scientific Earth Models
		$this->load->model( 'Se_app_network_sessions_model', 'Se_app_network_sessions' );
		$this->load->model( 'Se_app_user_model', 'Se_app_user' );
		
		$this->load->model('Se_db_account_points_model', 'Se_db_account_points');
		
		$this->load->model( 'Se_db_roles_model', 'Se_db_roles' );
		$this->load->model( 'Se_db_accounts_model', 'Se_db_accounts' );
		$this->load->model( 'Se_db_account_model', 'Se_db_account' );
		$this->load->model( 'Se_db_account_favorites_model', 'Se_db_account_favorites' );
		
		$this->load->model( 'Se_db_life_taxa_model', 'Se_db_life_taxa' );
		$this->load->model( 'Se_db_life_taxon_model', 'Se_db_life_taxon' );
		
		$this->load->model( 'Se_db_categories_model', 'Se_db_categories' );
		$this->load->model( 'Se_db_category_model', 'Se_db_category' );
		
		$this->load->model( 'Se_db_discussions_model', 'Se_db_discussions' );
		$this->load->model( 'Se_db_discussion_model', 'Se_db_discussion' );
		
		$this->load->model( 'Se_db_images_model', 'Se_db_images' );
		$this->load->model( 'Se_db_image_model', 'Se_db_image' );
		
		return true;
	} // function
	
} // class