<?php

class Crawler_model extends CI_Model {

	protected $method = 'file_get_contents';

	protected $url;

	private $soap_client;

	private $curl_handler;

	public function __construct() {
		parent::__construct();
	} // function

	public function set_method( $method ) {
		$this->method = $method;

		return $method;
	} // function

	public function get_method() {
		return $this->method;
	} // function

	public function set_url( $url ) {
		$this->url = $url;

		return $url;
	} // function

	public function get_url() {
		return $this->url;
	} // function

	public function crawl( $url = '' ) {
		if( empty( $url ) )
			$url = $this->url;

		$response = '';
		switch( $this->method ):
			case 'soap':
				$response = $this->soap( $url );
				break;
			case 'curl':
				$response = $this->curl( $url );
				break;
			case 'file_get_contents':
			default:
				$response = $this->file_get_contents( $url );
				break;
		endswitch;
	} // function

	public function curl( $url ) {
		if( empty( $url ) )
			$url = $this->url;

		$this->create_curl_handler();

		curl_setopt( $this->curl_handler, CURLOPT_URL, $url);

		curl_setopt( $this->curl_handler, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec( $this->curl_handler );

		$this->close_curl_handler();

		if( !empty( $response ) )
			return $response;

		return false;
	} // function

	public function create_curl_handler() {
		$this->curl_handler = curl_init();

		return $this->curl_handler();
	} // function

	public function close_curl_handler() {
		curl_close( $this->curl_handler );
		unset( $this->curl_handler );

		return true;
	} // function

	public function file_get_contents( $url ) {
		if( empty( $url ) )
			$url = $this->url;

		$context = stream_context_create( array( 'http' => array( 'timeout' => 45 ) ) );

		$response = file_get_contents( $url, 0, $context );

		if( $response !== false )
			return $response;

		return false;
	} // function

	public function soap( $url ) {
		if( empty( $url ) )
			$url = $this->url;

		$client = $this->create_soap_client( $url );

		if( $client === false )
			return false;

		// @todo Fetch $url contents

		return false;
	} // function

	public function create_soap_client( $url ) {
		if( empty( $url ) )
			$url = $this->url;

		try{
			$client = new SoapClient( $url );
		} catch( SoapFault $e ) {
			return false;
		}

		$this->soap_client = $client;

		return $client;
	} // function

} // class