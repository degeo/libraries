<?php

/**
 * Source: Wikipedia
 */
class Google_model extends Crawler_model {

	protected $url = "https://www.google.com";

	protected $source = "http://google.com";

	public function __construct() {
		parent::__construct();
	} // function

	public function search( $search_string ) {
		$search_string = urlencode( $search_string );

		$url = $this->url . '/search?q=' . $search_string;

		$this->set_method( 'file_get_contents' );
		$response = $this->crawl( $url );

		if( !empty( $response ) )
			return $response;

		return false;
	} // function

} // class
