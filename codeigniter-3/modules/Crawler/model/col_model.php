<?php

/**
 * Source: Catalog of Life
 */
class Col_model extends Crawler_model {

	protected $url = "http://www.catalogueoflife.org";

	protected $source = "http://catalogoflife.org";

	public function __construct() {
		parent::__construct();
	} // function

	public function search( $search_string ) {
		$search_string = urlencode( $search_string );

		$url = $this->url . '/annual-checklist/2013/webservice?format=php&response=full&name=' . $search_string;

		$this->set_method( 'file_get_contents' );
		$response = $this->crawl( $url );

		if( !empty( $response ) )
			return $response;

		return false;
	} // function

} // class