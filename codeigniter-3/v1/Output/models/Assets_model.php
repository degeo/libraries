<?php

/**
 * @author Jared "Jay" Fortner
 */
class Assets_model extends CI_Model {

	private $assets = array(
		'header' => array(),
		'footer' => array()
	);

	public function __construct() {
		parent::__construct();
	} // function

	public function add_asset( $position, $asset_id, $asset_html_tag, $priority = 50 ) {
		$this->assets[$position][ $asset_id ] = array(
			'asset_html_tag' => $asset_html_tag,
			'priority' => $priority
		);

		return true;
	} // function

	public function remove_asset( $position, $asset_id, $priority = 50 ) {
		if( empty( $this->assets[$position] ) )
			return false;

		foreach( $this->assets[$position] as $key => $asset ):
			if( $key == $asset_id )
				unset( $this->assets[$position][$key] );
		endforeach;

		return $this->assets[$position];
	} // function

	public function remove_all() {
		$this->assets = array(
			'header' => array(),
			'footer' => array()
		);
	}

	public function get_assets_html( $position ) {
		if( empty( $this->assets[$position] ) )
			return false;

		$this->load->helper('System/Sorting_helper');
		uasort( $this->assets[$position], 'priority_values' );

		$html = '';

		foreach( $this->assets[$position] as $asset ):
			$html .= "{$asset['asset_html_tag']}\r\n";
		endforeach;

		return $html;
	} // function

	public function get_script_tag_html( $asset_id, $asset_source_url ) {
		return "<script type='text/javascript' src='{$asset_source_url}'></script>\r\n";
	} // function

	public function get_link_tag_html(  $asset_id, $asset_source_url  ) {
		return "<link type='text/css' rel='stylesheet' href='{$asset_source_url}' />\r\n";
	} // function

} // class