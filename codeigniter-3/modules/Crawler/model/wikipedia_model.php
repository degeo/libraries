<?php

/**
 * Source: Wikipedia
 */
class Wikipedia_model extends Crawler_model {

	protected $url = 'http://en.m.wikipedia.org';

	protected $source = 'http://wikipedia.org';

	public function __construct() {
		parent::__construct();
	} // function

	public function search( $search_string ) {
		$search_string = urlencode( $search_string );

		$url = $this->url . '/w/index.php?search=' . $search_string

		$this->set_method( 'file_get_contents' );
		$response = $this->crawl( $url );

		if( !empty( $response ) )
			return $response;

		return false;
	} // function

} // class