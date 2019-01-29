<?php

class Application_model extends CI_Model {

	public $data = array();

	public $app_info = array();
	public $page_info = array();

	public function __construct() {
		parent::__construct();

		$this->app_info = array(
			'name' => $this->config->item('site_title'),
			'version' => $this->config->item('site_version'),
			'description' => $this->config->item('site_description'),
			'keywords' => $this->config->item('site_keywords')
		);

		$this->page_info = array(
			'title' => $this->config->item('site_title'),
			'description' => $this->config->item('site_description'),
			'keywords' => $this->config->item('site_keywords')
		);

		$this->load->model('System/Autoloader_model', 'Autoloader');
	} // function

	public function get_data( $key = '' ) {
		if( !empty( $key ) )
			return $this->data[$key];
		else
			return $this->data;
	} // function

	public function add_data( $key, $data ) {
		$this->data[$key] = $data;
	} // function

	public function start() {
		# Add Degeo Assets Host
		$host_config = array(
			'host_domain' => 'assets.degeo.net',
			'host_path' => ''
		);

		$this->Hosts->add_host( 'degeo_assets', $host_config );

		$this->Metatags->set_meta_title( $this->page_info['title'] );
		$this->Metatags->set_meta_description( $this->page_info['description'] );
		$this->Metatags->set_meta_keywords( $this->page_info['keywords'] );

		# Register header and footer layouts
		$this->Layout->add_section( 'html_header', 'Output/templates/html_header', 0 );
		$this->Layout->add_section( 'header', 'Output/templates/header', 10 );
		$this->Layout->add_section( 'body', 'Output/layouts/1-column', 50 );
		$this->Layout->add_section( 'footer', 'Output/templates/footer', 90 );
		$this->Layout->add_section( 'html_footer', 'Output/templates/html_footer', 100 );

		# Add Breadcrumbs
		$this->load->model('Breadcrumbs/Breadcrumbs_model', 'Breadcrumbs');
		$this->Breadcrumbs->add_breadcrumb( '', $this->page_info['title'], 0 );

		# Load Bootstrap
		#$this->load->model('Bootstrap/Bootstrap_model', 'Bootstrap');
		$this->load->model('Frameworks/Frameworks_model', 'Frameworks');

		return $this->app_info;
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

		# Set Meta Title
		$this->Metatags->set_meta_title( $this->page_info['title'] );
	} // function

} // class