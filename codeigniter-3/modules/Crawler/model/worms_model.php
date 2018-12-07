<?php

/**
 * Source: WoRMS
 */
class Worms_model extends CI_Model {
	
	var $source_url = "http://marinespecies.org";
	var $soap_url = "http://www.marinespecies.org/aphia.php?p=soap&wsdl=1";
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function search( $search_string ) {
		$client = new SoapClient( $this->soap_url );

		$AphiaID = $client->getAphiaID( $search_string = 'Ocimum Basilicum' );
		$result = $client->getAphiaRecordByID( $AphiaID );
		print_r($client);
		print_r($result);
		die();
		
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