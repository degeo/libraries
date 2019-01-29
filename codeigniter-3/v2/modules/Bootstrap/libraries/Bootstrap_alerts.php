<?php
/**
 * Bootstrap Alerts
 * @author Jay Fortner <jay@degeo.net>
 */
class Bootstrap_alerts {

	private $session_key = 'alerts';

	/* @var Array $types */
	protected static $types = array(
		'info',
		'success',
		'error',
		'warning'
	);

	private function __construct( $params ) {
		// If messages exist in the session
		if( $this->session->has_userdata( $this->session_key ) ):
			// Set the messages array
			$this->messages = $this->session->userdata( $this->session_key );
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
			return false;
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
		$this->session->set_userdata( $this->session_key, $this->messages );

		return;
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
		// Make sure the session is destroyed
		unset( $_SESSION[$this->session_key] );
		// Reset the messages array
		$this->messages = array();

		return;
	} // function

} // class