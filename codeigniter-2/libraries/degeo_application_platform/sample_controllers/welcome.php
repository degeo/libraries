<?php

class Welcome extends CI_Controller {
	
	var $data = array();
	
	public function __construct() {
		parent::__construct();
		
		# Load Degeo Application Model
		$this->load->model('Degeo_app_application_model', 'Degeo_application');
		
		# Start Degeo Application
		$this->Degeo_application->start( 'Degeo App Builder for CodeIgniter', '0.0.1' );
		
		# Load Google Analytics Tracking
		$this->Degeo_google_analytics->load( 'UA-xxxxxxxx-x' );
		
		# Set Meta Description
		$this->Degeo_seo->set_meta_description( 'Description of Degeo App Builder Test Suite' );
		# Set Meta Keywords
		$this->Degeo_seo->set_meta_keywords( 'Degeo,App,Builder,Test,Sweet' );
		
		$this->Degeo_layout->add_section( 'header', 'degeo-templates/header', 10 );
		$this->Degeo_layout->add_section( 'footer', 'degeo-templates/footer', 90 );
	} // function
	
	public function index() {
		$this->Degeo_application->set_page_title( 'Deploy A New CodeIgniter App With Foundation CSS/JS In Less Than 5 Minutes' );
		$this->Degeo_layout->add_section( 'body', 'degeo-layouts/1-column', 50 );
		$this->Degeo_layout->add_content( 'page-title', 'degeo-templates/page-title', 0 );
		$this->Degeo_layout->add_content( 'home-page', 'degeo-pages/degeo-home', 10 );
#		$this->Degeo_layout->add_sidebar( 'left', 'home-left-sidebar', 'degeo-sidebars/home-left', 0 );
#		$this->Degeo_layout->add_sidebar( 'right', 'home-right-sidebar', 'degeo-sidebars/home-right', 0 );
		$this->Degeo_layout->view( $this->data );
	} // function
	
	public function singlecol() {
		$this->Degeo_application->set_page_title( 'Single Column Degeo App Builder Test Suite' );
		
		# Add Breadcrumb
		$this->Degeo_breadcrumbs->add_breadcrumb( 'singlecol', 'Single Column', 10 );
		
		# Register body layout
		$this->Degeo_layout->add_section( 'body', 'degeo-layouts/1-column', 50 );
		
		# Register Layout Content
		$this->Degeo_layout->add_content( 'page-title', 'degeo-templates/page-title', 0 );
		$this->Degeo_layout->add_content( 'home-page', 'degeo-pages/degeo-home', 10 );
		
		# Load the Layouts
		$this->Degeo_layout->view( $this->data );
	} // function
	
	public function triplecol() {
		$this->Degeo_application->set_page_title( 'Triple Column Degeo App Builder Test Suite' );
		
		# Add Breadcrumb
		$this->Degeo_breadcrumbs->add_breadcrumb( 'triplecol', 'Triple Column', 10 );
		
		# Register body layout
		$this->Degeo_layout->add_section( 'body', 'degeo-layouts/3-columns', 50 );
		
		# Register Layout Content
		$this->Degeo_layout->add_content( 'page-title', 'degeo-templates/page-title', 0 );
		$this->Degeo_layout->add_content( 'home-page', 'degeo-pages/degeo-home', 10 );
		
		# Register left Layout Sidebar
		$this->Degeo_layout->add_sidebar( 'left', 'home-left-sidebar', 'degeo-sidebars/home-left', 0 );
		
		# Register right Layout Sidebar
		$this->Degeo_layout->add_sidebar( 'right', 'home-right-sidebar', 'degeo-sidebars/home-right', 0 );
		
		# Load the Layout views
		$this->Degeo_layout->view( $this->data );
	} // function

} // class