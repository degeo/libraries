<?php

class Jqueryui_model extends CI_Model {

	private $version = '1.12.4';

	private $position = 'header';

	protected $priority = 6;

	protected $asset_id = 'js-jqueryui';

	public function __construct() {
		parent::__construct();

		$this->load_version( $this->version );
	} // function

	public function load_version( $version = '1.12.4' ){
		$this->Assets->remove_asset( 'header', $this->asset_id );
		$this->Assets->remove_asset( 'footer', $this->asset_id );

		switch( $version ):
			default:
			case '1.12.4':
				#$this->Assets->add_asset( 'header', 'css-jqueryui', '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" crossorigin="anonymous"/>', 10 );
				$this->Assets->add_asset( $this->position, $this->asset_id, '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>', $this->priority );
				break;
		endswitch;

		return true;
	} // function

	public function set_position( $position = 'footer' ) {
		$this->position = $position;

		$this->load_version( $this->version );
	} // function

	public function set_priority( $priority = 6 ) {
		$this->priority = $priority;

		$this->load_version( $this->version );
	} // function

} // class