<?php

/**
 * @author Jared "Jay" Fortner
 * @package Degeo_app_builder
 */
class Degeo_app_layout_model extends CI_Model {
	
	private $views = array(
		'sections' => array(),
		'sidebars' => array(
			'left' => array(),
			'right' => array()
		),
		'contents' => array()
	);
	
	private $view_data = array();
	
	public function __construct() {
		parent::__construct();
	} // function
	
	private function _view( $views, $view_data = array() ) {
		if( empty( $views ) )
			return false;
		
		if( empty( $view_data ) )
			$view_data = $this->view_data;
		
		$views = $this->_prioritize( $views );
		
		foreach( $views as $view ):
			if( !empty( $view['view_file_path'] ) )
				$this->load->view( $view['view_file_path'], $view_data );
		endforeach;
		
		return true;
	} // function
	
	private function _add( $view_type, $views, $id, $view_file_path, $priority ) {
		$views[] = array(
			$view_type . '_id' => $id,
			'view_file_path' => $view_file_path,
			'priority' => $priority
		);
		
		return $views;
	} // function
	
	private function _remove( $view_type, $views, $id, $view_file_path, $priority ) {
		foreach( $views as $key => $view ):
			if( $view[ $view_type . '_id' ] == $id && $view['view_file_path'] == $view_file_path && $view['priority'] == $priority )
				unset( $views[$key] );
		endforeach;
		
		return $views;
	} // function
	
	private function _prioritize( $array ) {
		$this->load->helper('degeo_comparison_helper');

		uasort( $array, 'priority_values' );
		
		return $array;
	} // function
	
	public function view_sections( $view_data = array() ) {
		if( empty( $this->views['sections'] ) )
			return false;
		
		if( !empty( $view_data ) )
			$this->view_data = $view_data;
		
		return $this->_view( $this->views['sections'] );
	} // function
	
	public function add_section( $section_id, $view_file_path, $priority = 50 ) {
		$this->views['sections'] = $this->_add( 'section', $this->views['sections'], $section_id, $view_file_path, $priority );
		
		return true;
	} // function
	
	public function remove_section( $section_id, $view_file_path, $priority = 50 ) {
		$this->views['sections'] = $this->_remove( 'section', $this->views['sections'], $section_id, $view_file_path, $priority );

		return true;
	} // function
	
	public function view_contents( $view_data = array() ) {
		if( empty( $this->views['contents'] ) )
			return false;
		
		if( !empty( $view_data ) )
			$this->view_data = $view_data;
		
		return $this->_view( $this->views['contents'] );
	} // function
	
	public function add_content( $content_id, $view_file_path, $priority = 50 ) {
		$this->views['contents'] = $this->_add( 'content', $this->views['contents'], $content_id, $view_file_path, $priority );
		
		return true;
	} // function
	
	public function remove_content( $content_id, $priority = 50 ) {
		$this->views['contents'] = $this->_remove( 'content', $this->views['contents'], $content_id, $priority );
		
		return true;
	} // function
	
	public function view_sidebar( $which_sidebar, $view_data = array() ) {
		if( !array_key_exists( $which_sidebar, $this->views['sidebars'] ) )
			return false;
		
		if( empty( $this->views['sidebars'][$which_sidebar] ) )
			return false;
		
		if( !empty( $view_data ) )
			$this->view_data = $view_data;
		
		return $this->_view( $this->views['sidebars'][$which_sidebar] );
	} // function
	
	public function add_sidebar( $which_sidebar, $sidebar_id, $view_file_path, $priority = 50 ) {
		if( !array_key_exists( $which_sidebar, $this->views['sidebars'] ) )
			return false;
			
		$this->views['sidebars'][$which_sidebar] = $this->_add( 'sidebar', $this->views['sidebars'][$which_sidebar], $sidebar_id, $view_file_path, $priority );
		
		return true;
	} // function
	
	public function remove_sidebar( $which_sidebar, $sidebar_id, $priority = 50 ) {
		if( !array_key_exists( $which_sidebar, $this->views['sidebars'] ) )
			return false;
			
		$this->views['sidebars'] = $this->_remove( 'sidebar', $this->views['sidebars'][$which_sidebar], $sidebar_id, $priority );
		
		return true;
	} // function
	
	public function view( $view_data = array() ) {
		$this->view_data = $view_data;
		
		$this->view_sections();
	} // function
	
} // class