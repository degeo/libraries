<?php

/**
 * Breadcrumbs
 * Formats Breadcrumbs for use with Foundation CSS
 */
class Breadcrumbs_model extends CI_Model {
	
	public $breadcrumbs = array();
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function add_breadcrumb( $url, $label, $priority = 50 ) {
		$this->load->helper('url');
		
		$this->breadcrumbs[] = array(
			'label' => $label,
			'url' => site_url($url),
			'priority' => $priority
		);
		
		return true;
	} // function
	
	public function breadcrumbs_html() {
		echo $this->get_breadcrumbs_html();
	} // function
	
	public function get_breadcrumbs_html() {
		$this->load->helper('System/Sorting_helper');
		uasort( $this->breadcrumbs, 'priority_values' );
		
		$data = array(
			'breadcrumbs' => $this->breadcrumbs
		);
		
		$html = $this->load->view( 'Breadcrumbs/breadcrumbs', $data, true );
		
		return $html;
	} // function
	
} // class