<?php

class Degeo_app_application_model extends CI_Model {
	
	public $app_info = array();
	public $page_info = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->load_models();
		
		$this->app_info = array(
			'name' => '',
			'version' => '',
			'description' => ''
		);
		
		$this->page_info = array(
			'title' => ''
		);
	} // function
	
	public function load_models() {
		# Load Query Model
		$this->load->model('Degeo_app_query_model', 'Degeo_query');
		
		# Load Assets Model
		$this->load->model('Degeo_app_assets_model', 'Degeo_assets');
		
		# Load Resources Model
		$this->load->model('Degeo_app_resources_model', 'Degeo_resources');
		
		# Load Layout Model
		$this->load->model('Degeo_app_layout_model', 'Degeo_layout');
		
		# Load SEO Model
		$this->load->model('Degeo_app_seo_model', 'Degeo_seo');
		
		# Load Breadcrumbs Model
		$this->load->model('Degeo_app_breadcrumbs_model', 'Degeo_breadcrumbs');
	} // function
	
	public function start( $name, $version, $load_extras = true ) {
		$this->set_name( $name );
		$this->set_version( $version );
		
		# Add Degeo Assets Host
		$host_config = array(
			'host_domain' => 'assets.degeotechnologies.com',
			'host_path' => ''
		);
		
		$this->Degeo_assets->add_host( 'degeo_assets', $host_config );
		
		# Register header and footer layouts
		$this->Degeo_layout->add_section( 'html_header', 'degeo-templates/html_header', 0 );
		$this->Degeo_layout->add_section( 'html_footer', 'degeo-templates/html_footer', 100 );
		
		# Add Breadcrumb
		$this->Degeo_breadcrumbs->add_breadcrumb( '', $name, 0 );
		
		if( $load_extras === true ):
			$this->load_extras();
		endif;
		
		return $this->app_info;
	} // function
	
	public function load_extras() {
		$this->Degeo_resources->add_resource( 'header', 'css-application', '<link rel="stylesheet" href="' . base_url('assets/css/application.css') . '" />', 100 );
		
		# Load Google Analytics Model
		$this->load->model('Degeo_app_google_analytics_model', 'Degeo_google_analytics');
		
		# Load Foundation Model
		$this->load->model('Degeo_app_foundation_model', 'Degeo_foundation');
		
		# Load Foundation
		$this->Degeo_foundation->load();
	} // function
	
	public function get_info( $key = '' ) {
		if( !empty( $key ) ):
			return $this->app_info[$key];
		endif;
		
		return $this->app_info;
	}
	
	public function name() {
		echo $this->get_name();
	} // function
	
	public function get_name() {
		return $this->app_info['name'];
	} // function
	
	public function set_name( $name ) {
		$this->app_info['name'] = $name;
		
		# Load SEO Model
		$this->load->model('Degeo_app_seo_model', 'Degeo_seo');
		
		# Set Meta Title
		$this->Degeo_seo->set_meta_title( $this->app_info['name'] );
	} // function
	
	public function version() {
		echo $this->get_version();
	} // function
	
	public function get_version() {
		return $this->app_info['version'];
	} // function
	
	public function set_version( $version ) {
		$this->app_info['version'] = $version;
	} // function
	
	public function description() {
		echo $this->get_description();
	} // function
	
	public function get_description() {
		return $this->app_info['description'];
	} // function
	
	public function set_description( $description ) {
		$this->app_info['description'] = $description;
		
		# Load SEO Model
		$this->load->model('Degeo_app_seo_model', 'Degeo_seo');
		
		# Set Meta Title
		$this->Degeo_seo->set_meta_description( $this->app_info['description'] );
	} // function
	
	public function page_title() {
		echo $this->get_page_title();
	} // function
	
	public function get_page_title() {
		return $this->page_info['title'];
	} // function
	
	public function set_page_title( $title ) {
		$this->page_info['title'] = $title;
		
		# Load SEO Model
		$this->load->model('Degeo_app_seo_model', 'Degeo_seo');
		
		# Set Meta Title
		$this->Degeo_seo->set_meta_title( $this->page_info['title'] );
	} // function
	
} // class