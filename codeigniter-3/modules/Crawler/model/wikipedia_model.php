<?php

/**
 * Source: Wikipedia
 */
class Wikipedia_model extends CI_Model {
	
	var $source_url = "http://wikipedia.org";
	var $search_url = "http://en.m.wikipedia.org/w/index.php?search=";
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function search( $search_string ) {
		$search_string = urlencode( $search_string );
		$context = stream_context_create( array( 'http' => array( 'timeout' => 45 ) ) );
		$result = file_get_contents( $this->search_url . $search_string, 0, $context );
		if( $result !== false )
			return $result;
		else
			return false;
	} // function
	
	public function parse_results( $results, $taxon_id ) {
		$this->parse_result( $results, $taxon_id );
		
		log_message( 'info', __METHOD__ . ' --> ' . $taxon_id );
	} // function
	
	
	public function parse_result( $result, $taxon_id ) {
		preg_match( '/<\/[a-z][a-z]+>[?\s](<p>(.*<\/p>\s)+)/i', $result, $matches );
		if( array_key_exists( 1, $matches ) && !empty( $matches[1] ) ):
			$content = strip_tags( $matches[1] );
			$content = preg_replace( '/(\[[a-zA-Z0-9\s]+\])/', '', $content );
			$this->Data_model->edit_taxon_description( $taxon_id, $content );
		endif;
	} // function

} // class