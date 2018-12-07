<?php

class Jquery_model extends CI_Model {

	private $version = '3.2.1';

	private $position = 'footer';

	protected $priority = 5;

	protected $asset_id = 'js-jquery';

	public function __construct() {
		parent::__construct();

		$this->load_version( $this->version );
	} // function

	public function load_version( $version = '3.2.1' ){
		$this->Assets->remove_asset( 'header', $this->asset_id );
		$this->Assets->remove_asset( 'footer', $this->asset_id );

		switch( $version ):
			case '1.12.4':
				$this->Assets->add_asset( $this->position, $this->asset_id, '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" crossorigin="anonymous"></script>', $this->priority );
				break;
			case '3.2.1':
				$this->Assets->add_asset( $this->position, $this->asset_id, '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>', $this->priority );
				break;
			case '3.1.1':
			default:
				$this->Assets->add_asset( $this->position, $this->asset_id, '<script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>', $this->priority );
				break;
		endswitch;

		return true;
	} // function

	public function set_position( $position = 'footer' ) {
		$this->position = $position;

		$this->load_version( $this->version );
	} // function

	public function set_priority( $priority = 5 ) {
		$this->priority = $priority;

		$this->load_version( $this->version );
	} // function

} // class