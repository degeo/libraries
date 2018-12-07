<?php

class Se_app_mailer_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('email');
		
		$this->set_default_headers();
	} // function
	
	public function set_default_headers() {
		$this->email->mailtype = 'html';
	} // function
	
	public function send_mail( $to, $email, $name, $subject, $content ) {
		$this->email->from( $email, $name );
		$this->email->to( $to ); 
		
		$this->email->subject( $subject );
		
		$this->email->message( $content );

		$this->email->send();
	} // function
	
	public function send_verification_code( $verification_code ) {
		$email = $this->input->post( 'user_email' );
		$username = $this->input->post( 'user_nickname' );
		
		$this->email->from( $this->config->item('system_email'), $this->config->item('site_title') );
		$this->email->to( $email );
		
		$stamp = date('ymd h:i:s');
		$this->email->subject("{$username}, Please Verify Your {$this->data['site_title']} Account");
		
		$verification_url = site_url( "verify?a={$verification_code}" );
		
		$message = "<p>Welcome {$username}, Thank you for creating a {$this->data['site_title']} account.</p><p>Please visit this url ( <a href='{$verification_url}' target='_blank'>{$verification_url}</a> ) to verify your account.</p>";
		
		$this->email->message($message);

		$this->email->send();
	} // function
	
	public function send_user_feedback() {
		$email = $this->input->post( 'email' );
		$name = $this->input->post( 'name' );
		$feedback = $this->input->post( 'feedback' );
		
		$this->send_mail( $this->config->item('feedback_email'), $email, $name, $subject = 'Scientific Earth Application Feedback', $feedback );
	} // function
	
	public function send_contact_form() {
		$email = $this->input->post( 'email' );
		$name = $this->input->post( 'name' );
		$message = $this->input->post( 'message' );
		
		$this->send_mail( $this->config->item('feedback_email'), $email, $name, $subject = 'Scientific Earth Contact Form', $message );
	} // function
	
} // class