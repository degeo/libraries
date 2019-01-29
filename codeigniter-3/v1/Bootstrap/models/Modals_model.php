<?php

class Modals_model extends CI_Model {

	protected $modals = array();

	public function __construct() {
		parent::__construct();

		$this->Layout->add_section( 'modals', 'Bootstrap/modals', 100 );
	} // function

	public function create( $id, $title, $view ) {
		$this->modals[$id] = array(
			'id' => $id,
			'title' => $title,
			'view' => $view,
		);

		$this->Application->add_data( 'modals', $this->modals );

		return $this->get( $id );
	} // function

	public function get( $id ) {
		return $this->modals[$id];
	} // function

	public function remove( $id ) {
		unset( $this->modals[$id] );
	} // function

} // class