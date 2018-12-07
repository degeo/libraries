<?php

/**
 * @author Jared "Jay" Fortner
 * @package Degeo_app_builder
 */
class Degeo_app_resources_model extends CI_Model {
	
	private $resources = array(
		'header' => array(),
		'footer' => array()
	);
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function add_resource( $position, $resource_id, $resource_html_tag, $priority = 50 ) {
		$this->resources[$position][] = array(
			'resource_id' => $resource_id,
			'resource_html_tag' => $resource_html_tag,
			'priority' => $priority
		);
		
		return true;
	} // function
	
	public function remove_resource( $position, $resource_id, $priority = 50 ) {
		if( empty( $this->resources[$position] ) )
			return false;
		
		foreach( $this->resources[$position] as $key => $resource ):
			if( $resource[ 'resource_id' ] == $resource_id && $resource['priority'] == $priority )
				unset( $this->resources[$position][$key] );
		endforeach;
		
		return $this->resources[$position];
	} // function
	
	public function get_resources_html( $position ) {
		if( empty( $this->resources[$position] ) )
			return false;
		
		$this->load->helper('degeo_comparison_helper');
		uasort( $this->resources[$position], 'priority_values' );
		
		$html = '';
		
		foreach( $this->resources[$position] as $resource ):
			$html .= "{$resource['resource_html_tag']}\r\n";
		endforeach;
		
		return $html;
	} // function
	
	public function get_script_tag_html( $resource_id, $resource_source_url ) {
		return "<script type='text/javascript' src='{$resource_source_url}'></script>\r\n";
	} // function
	
	public function get_link_tag_html(  $resource_id, $resource_source_url  ) {
		return "<link type='text/css' rel='stylesheet' href='{$resource_source_url}' />\r\n";
	} // function
	
} // class