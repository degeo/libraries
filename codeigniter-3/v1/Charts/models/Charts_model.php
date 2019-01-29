<?php

class Charts_model extends CI_Model {

	protected $charts = array();

	public function __construct() {
		parent::__construct();

#		$chart_js_url = $this->Hosts->get_url( '/chart-js/Chart.min.js' );
		$this->Assets->add_asset( 'footer', 'js-charts-js', '<script src="' . $this->Hosts->get_url( '/chart-js/Chart.min.js', 'degeo_assets' ) . '" crossorigin="anonymous"></script>', 30 );

		$this->Layout->add_section( 'charts', 'Charts/charts', 101 );
	} // function

	public function create( $id, $type = 'default', $data ) {
		$this->charts[$id] = array(
			'id' => $id,
			'type' => $type,
			'data' => $data
		);

		$this->Application->add_data( 'charts', $this->charts );

		return $this->get( $id );
	} // function

	public function get( $id ) {
		return $this->charts[$id];
	} // function

	public function remove( $id ) {
		unset( $this->charts[$id] );
	} // function

} // class