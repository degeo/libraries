<?php

class Messages_model extends CI_model {

	protected $messages = array();

	public function __construct() {
		parent::__construct();

		$this->Layout->add_section( 'messages', "Messages/alert", 15 );
	} // function

	public function add( $type, $message ) {
		$message = trim( $message );

		if( empty( $message ) )
			return false;

		$this->messages[] = array(
			'type' => $type,
			'content' => strip_tags( $message )
		);

		$this->Application->add_data( 'messages', $this->messages );
	} // function

	public function get_messages() {
		return $this->messages;
	} // function

} // class