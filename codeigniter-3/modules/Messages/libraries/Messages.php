<?php

class Messages {

	/* @var Array $types */
	protected static $types = array(
		'info',
		'success',
		'error',
		'warning'
	);

	/* @var Array $messages */
	protected $messages = array();

	private function __construct() {
		// If messages exist in the session
		if( array_key_exists( 'messages', $_SESSION) ):
			// Set the messages array
			$this->messages = $_SESSION['messages'];
		endif;

		// Return Void
		return;
	} // function

	public function create( $type, $message ) {
		// Remove whitespace surrounding the message
		$message = trim( $message );

		// If $type isn't an expected value or $message is empty
		if( !in_array( $type, $this->types ) || empty( $message ) ):
			// Return Boolean False
			return FALSE;
		endif;

		// Create the message structure with $type and $message
		$message_array = array(
			'type' => $type,
			// Remove HTML and PHP tags from $message
			'content' => strip_tags( $message )
		);

		// Add the new message to messages array
		$this->messages[] = $message_array;
		// Add the new message to the session
		$_SESSION['messages'] = $this->messages;

		// Return Boolean True
		return true;
	} // function

	public function read() {
		// Load the messages array into a variable
		$messages = $this->messages;
		// Destroy the messages array and session
		$this->delete();

		// Return the messages
		return $messages;
	} // function

	public function delete() {
		// Destroy the session
		session_destroy();
		// Make sure the session is destroyed
		unset( $_SESSION['messages'] );
		// Reset the messages array
		$this->messages = array();

		// Return Boolean True
		return true;
	} // function

} // class