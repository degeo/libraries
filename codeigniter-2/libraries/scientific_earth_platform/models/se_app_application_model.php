<?php

class Se_app_application_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		
		$application_info = array();
	} // function
	
	public function start( $config = array() ) {
		if( empty( $config ) ):
			$config = array(
				'site_version' => $this->config->item('site_version'),
				'site_title' => $this->config->item('site_title'),
				'site_description' => $this->config->item('site_description'),
				'site_keywords' => $this->config->item('site_keywords'),
				'google_analytics_id' => $this->config->item('google_analytics_id')
			);
			
			if( empty( $config['site_version'] ) ):
				show_error( 'No site version defined.' );
				return false;
			endif;
			
		endif;
		
		# Load Degeo Application Model
		$this->load->model('Degeo_app_application_model', 'Degeo_application');
		
		# Start Degeo Application
		$this->Degeo_application->start( $config['site_title'], $config['site_version'] );
		$this->Degeo_application->set_description( $config['site_description'] );
		
		# Load Google Analytics Tracking
		$this->Degeo_google_analytics->load( $config['google_analytics_id'] );
		
		# Set Meta Description
		$this->Degeo_seo->set_meta_description( $config['site_description'] );
		
		# Set Meta Keywords
		$this->Degeo_seo->set_meta_keywords( $config['site_keywords'] );
		
		$this->Degeo_resources->add_resource( 'header', 'css-se-master', '<link rel="stylesheet" href="//assets.scientificearth.net/css/master.css" />', 90 );
		
		# Set Default Layout Views
		$this->Degeo_layout->add_section( 'header', 'se-templates/network-toolbar', 2 );
		$this->Degeo_layout->add_section( 'footer', 'se-templates/footer', 92 );
		
		$this->Degeo_layout->add_sidebar( 'left', 'network-sidebar', 'se-sidebars/network-sidebar', 90 );
		
		$this->autoload();
	} // function
	
	public function autoload() {
		$this->load->model( 'Se_app_loader_model', 'Se_loader' );
		
		$this->Se_loader->load_helpers();
		$this->Se_loader->load_libraries();
		$this->Se_loader->load_models();
		
		$this->application_info = $this->get_se_application_info();
		
		if( empty( $this->application_info ) ):
			log_message( 'error', 'Application information failed to load.' );
			$this->config->set_item( 'se_application_id', 0 );
		else:
			$this->config->set_item( 'se_application_id', $this->application_info['se_application_id'] );
		endif;
	} // function
	
	public function get_se_application_info() {
		$website_url = str_replace( 'localhost/dgt/', 'www.', $this->config->item('base_url') );
		
		$website_url_esc = $this->db->escape( $website_url );
		
		$query = "SELECT * FROM se_applications WHERE se_application_website = {$website_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
} // class