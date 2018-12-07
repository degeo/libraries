<?php

class Frameworks_model extends CI_Model {

	private $framework = 'bootstrap';

	public function __construct() {
		parent::__construct();

		# no alias, only used as parent class
		$this->load->model( 'Framework_model' );

		$this->load();
	} // function

	public function load( $framework = '' ) {
		if( empty( $framework ) )
			$framework = $this->framework;
		else
			$this->framework = $framework;

		switch( $framework ):
			case 'foundation':
				$this->load->model( 'Foundation/Foundation_model', 'Framework' );
				break;
			case 'bootstrap':
			default:
				$this->load->model( 'Bootstrap/Bootstrap_model', 'Framework' );
				break;
		endswitch;

		$this->Framework->load();
	} // function

} // class