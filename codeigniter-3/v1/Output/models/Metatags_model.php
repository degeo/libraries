<?php

/**
 * @author Jared "Jay" Fortner
 */
class Metatags_model extends CI_Model {
	
	public $meta_title;
	public $meta_description;
	public $meta_keywords;
	
	public function __construct() {
		parent::__construct();
		
		# Depends on Output/Assets_model
		$this->load->model('Output/Assets_model', 'Assets');
	} // function
	
	public function set_meta_title( $title = '' ) {
		$this->meta_title = $title;
		
		$this->Assets->remove_asset( 'header', 'meta-title', 0 );
		$this->Assets->add_asset( 'header', 'meta-title', "<title>{$this->meta_title}</title>", 0 );
		
		return true;
	} // function
	
	public function get_meta_title() {
		return $this->meta_title;
	} // function
	
	public function set_meta_description( $description = '' ) {
		$this->meta_description = $description;
		
		$this->Assets->remove_asset( 'header', 'meta-description', 1 );
		$this->Assets->add_asset( 'header', 'meta-description', "<meta name='description' content='{$this->meta_description}' />", 1 );
		
		return true;
	} // function
	
	public function get_meta_description() {
		return $this->meta_description;
	} // function
	
	public function set_meta_keywords( $keywords = '' ) {
		$this->meta_keywords = $keywords;
		
		$this->Assets->remove_asset( 'header', 'meta-keywords', 2 );
		$this->Assets->add_asset( 'header', 'meta-keywords', "<meta name='keywords' content='{$this->meta_keywords}' />", 2 );
		
		return true;
	} // function
	
	public function get_meta_keywords() {
		return $this->meta_keywords;
	} // function
	
} // class