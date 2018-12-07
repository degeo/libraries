<?php

class Degeo_app_foundation_model extends CI_Model {
	
	private $foundation_version = '5.5.3';
	
	public function __construct() {
		parent::__construct();
		
		# Depends on Degeo_app_resources_model
		$this->load->model('Degeo_app_resources_model', 'Degeo_resources');
	} // function
	
	public function load( $version = '' ) {
		if( !empty( $version ) )
			$this->foundation_version = $version;
		
		$this->add_css_resources();
		$this->add_javascript_resources();
	}
	
	public function add_css_resources() {
		# Foundation Normalize CSS
		$this->Degeo_resources->add_resource( 'header', 'css-normalize', '<link type="text/css" rel="stylesheet" href="//assets.degeotechnologies.com/foundation-' . $this->foundation_version . '/css/normalize.css" />', 20 );
		# Foundation Minimized CSS
		$this->Degeo_resources->add_resource( 'header', 'css-foundation', '<link type="text/css" rel="stylesheet" href="//assets.degeotechnologies.com/foundation-' . $this->foundation_version . '/css/foundation.min.css" />', 21 );
		# Foundation Icons CSS
		$this->Degeo_resources->add_resource( 'header', 'css-foundation-icons', '<link type="text/css" rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" />', 22 );
	} // function
	
	public function add_javascript_resources() {
		# Foundation depends on jQuery
		$this->Degeo_resources->add_resource( 'header', 'js-jquery', '<script type="text/javascript" src="//assets.degeotechnologies.com/foundation-' . $this->foundation_version . '/js/vendor/jquery.js"></script>', 80 );
		# Foundation Minimized JS
		$this->Degeo_resources->add_resource( 'header', 'js-foundation', '<script type="text/javascript" src="//assets.degeotechnologies.com/foundation-' . $this->foundation_version . '/js/foundation.min.js"></script>', 81 );
		# Add code to run Foundation JS
		$this->Degeo_resources->add_resource( 'footer', 'js-foundation-run', '<script type="text/javascript">$(document).foundation();</script>', 82 );
	} // function
	
} // class