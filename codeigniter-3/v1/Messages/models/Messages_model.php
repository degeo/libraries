<?php

class Messages_model extends Application_model {

	protected $messages = array();

	public function __construct() {
		parent::__construct();

		if( $this->session->has_userdata('messages') ):
			$session_messages = $this->session->userdata( 'messages' );
			log_message( 'debug', __CLASS__ . ' Session: ' . print_r( $session_messages, true ) );
			$this->messages = $session_messages;
		endif;
	} // function

	public function add( $type, $message ) {
		$message = trim( $message );

		if( empty( $message ) )
			return false;

		$message_array = array(
			'type' => $type,
			'content' => strip_tags( $message )
		);

		$this->messages[] = $message_array;

		$this->session->set_userdata( 'messages', $this->messages );
	} // function

	public function get_messages() {
		$messages = $this->messages;

		$this->empty_messages();

		return $messages;
	} // function

	public function empty_messages() {
		$this->session->unset_userdata('messages');
		$this->messages = array();
	} // function

} // class