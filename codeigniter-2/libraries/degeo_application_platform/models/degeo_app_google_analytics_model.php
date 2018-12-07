<?php

class Degeo_app_google_analytics_model extends CI_Model {
	
	private $analytics_id;
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function load( $analytics_id ) {
		$this->set_analytics_id( $analytics_id );
		$this->add_tracking_code();
	} // function
	
	public function set_analytics_id( $analytics_id ) {
		$this->analytics_id = $analytics_id;
	} // function
	
	public function add_tracking_code() {
		if( empty( $this->analytics_id ) )
			return false;
		
		if( defined('ENVIRONMENT') ):
			if( ENVIRONMENT == 'development' )
				return false;
		endif;
		
		$data = array(
			'analytics_id' => $this->analytics_id
		);
		
		$tracking_script = $this->load->view( 'degeo-google/analytics-tracking', $data, true );
		
		# Depends on Degeo_app_resources_model
		$this->load->model('Degeo_app_resources_model', 'Degeo_resources');
		
		# Add GA Tracking Code to Footer as the very last resource loaded
		$this->Degeo_resources->add_resource( 'footer', 'js-googleanalytics', $tracking_script, 1000 );
	} // function
	
} // class