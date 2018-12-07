<?php

/**
 * @author Jared "Jay" Fortner
 * @package Degeo_app_builder
 * @see Degeo_app_resources_model
 */
class Degeo_app_seo_model extends CI_Model {
	
	public $meta_title;
	public $meta_description;
	public $meta_keywords;
	
	public function __construct() {
		parent::__construct();
		
		# Depends on Degeo_app_resources_model
		$this->load->model('Degeo_app_resources_model', 'Degeo_resources');
	} // function
	
	public function set_meta_title( $title = '' ) {
		$this->meta_title = $title;
		
		$this->Degeo_resources->remove_resource( 'header', 'meta-title', 10 );
		$this->Degeo_resources->add_resource( 'header', 'meta-title', "<title>{$this->meta_title}</title>", 10 );
		
		return true;
	} // function
	
	public function get_meta_title() {
		return $this->meta_title;
	} // function
	
	public function set_meta_description( $description = '' ) {
		$this->meta_description = $description;
		
		$this->Degeo_resources->remove_resource( 'header', 'meta-description', 11 );
		$this->Degeo_resources->add_resource( 'header', 'meta-description', "<meta name='description' content='{$this->meta_description}' />", 11 );
		
		return true;
	} // function
	
	public function get_meta_description() {
		return $this->meta_description;
	} // function
	
	public function set_meta_keywords( $keywords = '' ) {
		$this->meta_keywords = $keywords;
		
		$this->Degeo_resources->remove_resource( 'header', 'meta-keywords', 12 );
		$this->Degeo_resources->add_resource( 'header', 'meta-keywords', "<meta name='keywords' content='{$this->meta_keywords}' />", 12 );
		
		return true;
	} // function
	
	public function get_meta_keywords() {
		return $this->meta_keywords;
	} // function
	
} // class