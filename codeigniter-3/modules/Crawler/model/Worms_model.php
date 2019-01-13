<?php

/**
 * Source: WoRMS
 */
class Worms_model extends Crawler_model {

	protected $url = "http://www.marinespecies.org/aphia.php?p=soap&wsdl=1";

	protected $source = "http://marinespecies.org";

	public function __construct() {
		parent::__construct();
	} // function

	public function search( $search_string ) {
		$search_string = urlencode( $search_string );

		$this->set_method( 'soap' );
		$response = $this->crawl( $this->url );

		if( !empty( $response ) )
			return $response;

		return false;
	} // function

} // class
